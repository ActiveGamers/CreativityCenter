#!/usr/bin/env bash
#
# Container entrypoint for the Laravel application.
#
# The container starts as root so that the script can prepare the (bind
# mounted) application tree - ownership, writable runtime directories, the
# public/storage symlink and the SQLite database file - and then drops the
# privileges to www-data before php-fpm is started.
#
# Responsibilities (in order):
#   1. bootstrap the environment file (.env) on a clean checkout
#   2. make sure the runtime directories exist and are writable by www-data
#   3. restore the compiled frontend assets when the bind mount hides them
#   4. create the public/storage symlink - only when it is missing
#   5. create the SQLite database file - only when it is missing
#   6. apply the pending database migrations (only when AUTO_MIGRATE is on)
#   7. hand over to the container command (php-fpm by default) as www-data
#
# IMPORTANT (AGENTS.md section 5): this script must never run an
# optimize/cache command (optimize, config:cache, view:cache, route:cache,
# event:cache, ...). Only the `clear` variants are allowed.

set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/html}"
APP_USER="${APP_USER:-www-data}"
APP_GROUP="${APP_GROUP:-www-data}"
BUILD_SOURCE_DIR="${BUILD_SOURCE_DIR:-/opt/app-build}"

cd "$APP_DIR"

log() {
    printf '[entrypoint] %s\n' "$*"
}

warn() {
    printf '[entrypoint] warning: %s\n' "$*" >&2
}

fail() {
    printf '[entrypoint] error: %s\n' "$*" >&2
}

# True while the script runs as root (only then ownership may be changed).
running_as_root() {
    [ "$(id -u)" -eq 0 ]
}

# Reads a configuration value. The real environment wins over the .env file,
# exactly like Laravel's env() helper does.
read_config_value() {
    local key="$1"
    local default="${2:-}"
    local value=""

    value="$(printenv "$key" 2>/dev/null || true)"

    if [ -z "$value" ] && [ -f .env ]; then
        value="$(grep -E "^${key}=" .env 2>/dev/null | tail -n 1 | cut -d '=' -f 2- || true)"
        value="${value%\"}"
        value="${value#\"}"
        value="${value%\'}"
        value="${value#\'}"
    fi

    if [ -z "$value" ]; then
        value="$default"
    fi

    printf '%s' "$value"
}

# True for 1/true/yes/on (case insensitive).
is_truthy() {
    case "$(printf '%s' "${1:-}" | tr '[:upper:]' '[:lower:]')" in
        1 | true | yes | on) return 0 ;;
        *) return 1 ;;
    esac
}

# ---------------------------------------------------------------------------
# 1) Environment file bootstrap
# ---------------------------------------------------------------------------
# The .env file is not tracked by git. On a clean checkout it does not exist,
# so it is created from the template and the application key is generated.
#
# The app, horizon and scheduler containers run this script at the same time
# and share the bind-mounted .env file. The lock serializes the copy and the
# key generation: without it one container could copy .env.example over the
# .env file in which another container has just written APP_KEY, and a
# container that booted before the key existed would encrypt with an empty
# key.
exec 9>"${APP_DIR}/.env.lock"
flock -x 9

if [ ! -f .env ]; then
    if [ -f .env.example ]; then
        log "no .env file found - creating one from .env.example"

        if ! cp .env.example .env; then
            warn "could not create the .env file - copy .env.example manually"
        fi
    else
        warn "neither .env nor .env.example is available"
    fi
fi

if [ -f .env ] && ! grep -qE '^APP_KEY=.+' .env; then
    log "no application key found - generating one"

    if ! php artisan key:generate --force --no-interaction; then
        warn "key:generate failed - set APP_KEY in the .env file manually"
    fi
fi

flock -u 9
exec 9>&-

# ---------------------------------------------------------------------------
# 2) Runtime directories
# ---------------------------------------------------------------------------
for directory in \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/testing \
    storage/logs \
    bootstrap/cache
do
    if [ ! -d "$directory" ]; then
        mkdir -p "$directory" 2>/dev/null || warn "could not create $directory"
    fi

    chmod -R ug+rwX "$directory" 2>/dev/null || true
done

if running_as_root; then
    chown -R "${APP_USER}:${APP_GROUP}" storage bootstrap/cache 2>/dev/null || true
fi

# ---------------------------------------------------------------------------
# 3) Compiled frontend assets
# ---------------------------------------------------------------------------
# The image ships the compiled Vite assets (public/build/manifest.json plus the
# hashed files). The compose setup mounts the working copy over /var/www/html,
# which hides the assets that were built into the image - they are restored
# from the pristine copy in /opt/app-build.
if [ ! -f public/build/manifest.json ] && [ -f "${BUILD_SOURCE_DIR}/manifest.json" ]; then
    log "restoring the compiled frontend assets from ${BUILD_SOURCE_DIR}"

    mkdir -p public/build 2>/dev/null || warn "could not create public/build"

    if ! cp -a "${BUILD_SOURCE_DIR}/." public/build/; then
        warn "could not restore the compiled frontend assets - run 'npm run build'"
    fi
fi

# ---------------------------------------------------------------------------
# 4) public/storage symlink (only when missing)
# ---------------------------------------------------------------------------
if [ ! -e public/storage ]; then
    log "creating the public/storage symlink"

    if ! php artisan storage:link --force --no-interaction; then
        warn "storage:link failed - continuing without it"
    fi
fi

# ---------------------------------------------------------------------------
# 5) SQLite database file
# ---------------------------------------------------------------------------
# The database file is not part of the image (and not tracked by git), so a
# fresh container has to create it before the migrations can run.
db_connection="$(read_config_value DB_CONNECTION sqlite)"
db_database="$(read_config_value DB_DATABASE "${APP_DIR}/database/database.sqlite")"
sqlite_path=""

if [ "$db_connection" = "sqlite" ] && [ "$db_database" != ":memory:" ]; then
    case "$db_database" in
        /*) sqlite_path="$db_database" ;;
        *) sqlite_path="${APP_DIR}/${db_database}" ;;
    esac

    mkdir -p "$(dirname "$sqlite_path")" 2>/dev/null || warn "could not create $(dirname "$sqlite_path")"

    if [ ! -f "$sqlite_path" ]; then
        log "creating the SQLite database file ${sqlite_path}"

        touch "$sqlite_path" || warn "could not create ${sqlite_path}"
    fi

    chmod 664 "$sqlite_path" 2>/dev/null || true

    if running_as_root; then
        chown "${APP_USER}:${APP_GROUP}" "$sqlite_path" 2>/dev/null || true
    fi
fi

# ---------------------------------------------------------------------------
# 6) Database migrations
# ---------------------------------------------------------------------------
# The migration is explicit: it only runs when AUTO_MIGRATE is truthy and a
# failure never keeps php-fpm from starting (concurrent containers would race
# on the same schema).
if is_truthy "$(read_config_value AUTO_MIGRATE true)"; then
    log "running the pending database migrations"

    if ! php artisan migrate --force --no-interaction; then
        fail "the database migration failed - the application starts anyway, run 'php artisan migrate --force' manually"
    fi
else
    log "AUTO_MIGRATE is off - skipping the database migrations"
fi

# ---------------------------------------------------------------------------
# 7) Ownership of everything the application writes
# ---------------------------------------------------------------------------
if running_as_root; then
    chown -R "${APP_USER}:${APP_GROUP}" storage bootstrap/cache 2>/dev/null || true

    if [ -n "$sqlite_path" ]; then
        chown "${APP_USER}:${APP_GROUP}" "$sqlite_path" 2>/dev/null || true
    fi
fi

# ---------------------------------------------------------------------------
# 8) Hand over to the container command (as www-data)
# ---------------------------------------------------------------------------
if running_as_root; then
    # php-fpm reopens its error log (/proc/self/fd/2) after the privileges
    # have been dropped. The pipe belongs to root, so it is handed over to
    # www-data first - otherwise the FPM master refuses to start.
    #
    # The original stderr is kept in fd 3: a redirection of fd 2 would make
    # /proc/self/fd/2 point to the redirection target instead of the pipe.
    exec 3>&2
    chown "${APP_USER}:${APP_GROUP}" /proc/self/fd/3 2>/dev/null || true
    chmod 0660 /proc/self/fd/3 2>/dev/null || true
    exec 3>&-

    log "starting: $* (as ${APP_USER})"

    if command -v gosu >/dev/null 2>&1; then
        exec gosu "${APP_USER}" "$@"
    fi

    if command -v setpriv >/dev/null 2>&1; then
        exec setpriv --reuid="${APP_USER}" --regid="${APP_GROUP}" --init-groups "$@"
    fi

    exec su -s /bin/sh "${APP_USER}" -c "exec $*"
fi

exec "$@"
