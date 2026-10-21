#!/usr/bin/env bash
#
# Container entrypoint for the Laravel application.
#
# Responsibilities (in order):
#   1. make sure the runtime directories exist and are writable by www-data
#   2. create the public/storage symlink - only when it is missing
#   3. apply the pending database migrations
#   4. hand over to the container command (php-fpm by default)
#
# IMPORTANT (AGENTS.md section 5): this script must never run an
# optimize/cache command (optimize, config:cache, view:cache, route:cache,
# event:cache, ...). Only the `clear` variants are allowed.

set -euo pipefail

APP_DIR="${APP_DIR:-/var/www/html}"

cd "$APP_DIR"

log() {
    printf '[entrypoint] %s\n' "$*"
}

# ---------------------------------------------------------------------------
# 1) Runtime directories
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
        mkdir -p "$directory" 2>/dev/null || log "warning: could not create $directory"
    fi

    chmod -R ug+rwX "$directory" 2>/dev/null || true
done

if [ "$(id -u)" -eq 0 ]; then
    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
fi

# ---------------------------------------------------------------------------
# 2) public/storage symlink (only when missing)
# ---------------------------------------------------------------------------
if [ ! -e public/storage ]; then
    log "creating the public/storage symlink"

    if ! php artisan storage:link --force --no-interaction; then
        log "warning: storage:link failed - continuing without it"
    fi
fi

# ---------------------------------------------------------------------------
# 3) Database migrations
# ---------------------------------------------------------------------------
log "running the pending database migrations"

php artisan migrate --force --no-interaction

# ---------------------------------------------------------------------------
# 4) Hand over to the container command
# ---------------------------------------------------------------------------
exec "$@"
