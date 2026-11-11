#!/usr/bin/env bash
#
# One-time preparation of the production host. Idempotent: running it again
# reuses the existing environment file, data directories and docker network.
#
#   provision.sh --install-nginx [--public-port 5517] [--server-ip 2.184.239.31]
#
# What it does, in order:
#   1. moves a pre-existing, unrelated checkout aside (never deletes it)
#   2. creates shared/ (environment, storage, nginx upstream) and data/
#   3. creates the external docker network the colours and the data tier share
#   4. renders shared/.env with freshly generated secrets - only if absent
#   5. starts the MySQL + Redis tier and waits for it to be healthy
#   6. with --install-nginx: installs the host vhost and reloads nginx
#
# It deliberately does NOT touch ufw and does NOT deploy a release. The chosen
# public port must already be open in the firewall; the script only verifies it.
#
# IMPORTANT (AGENTS.md section 5): no optimize/cache command is ever run here.

set -euo pipefail

APP_ROOT="${APP_ROOT:-/srv/apps/CreativityCenter}"
SERVER_IP="${SERVER_IP:-2.184.239.31}"
PUBLIC_PORT="${PUBLIC_PORT:-5517}"
DATA_NETWORK="${DATA_NETWORK:-creativitycenter-data}"
INSTALL_NGINX=0

while [ $# -gt 0 ]; do
    case "$1" in
        --install-nginx) INSTALL_NGINX=1; shift ;;
        --public-port) PUBLIC_PORT="$2"; shift 2 ;;
        --server-ip) SERVER_IP="$2"; shift 2 ;;
        *) printf 'unknown argument: %s\n' "$1" >&2; exit 1 ;;
    esac
done

DEPLOY_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
SHARED_DIR="${APP_ROOT}/shared"
DATA_DIR="${APP_ROOT}/data"
ENV_FILE="${SHARED_DIR}/.env"
UPSTREAM_FILE="${SHARED_DIR}/nginx/upstream.conf"
VHOST_SOURCE="${DEPLOY_DIR}/nginx/CreativityCenter.conf"
VHOST_TARGET="/etc/nginx/conf.d/CreativityCenter.conf"

log() { printf '[provision] %s\n' "$*"; }
warn() { printf '[provision] warning: %s\n' "$*" >&2; }
die() { printf '[provision] error: %s\n' "$*" >&2; exit 1; }

[ "$(id -u)" -eq 0 ] || die "run as root: the data directories and nginx need it"
command -v docker >/dev/null 2>&1 || die "docker is not installed"
command -v openssl >/dev/null 2>&1 || die "openssl is not installed"

# ---------------------------------------------------------------------------
# 1) An unrelated checkout is moved aside, never removed
# ---------------------------------------------------------------------------
if [ -d "$APP_ROOT" ] && [ ! -d "$SHARED_DIR" ]; then
    aside="${APP_ROOT}.old-$(date +%Y%m%d-%H%M%S)"
    log "moving the existing ${APP_ROOT} to ${aside}"
    mv "$APP_ROOT" "$aside"
fi

# ---------------------------------------------------------------------------
# 2) Directory layout
# ---------------------------------------------------------------------------
mkdir -p "${SHARED_DIR}/storage/app/public" \
         "${SHARED_DIR}/storage/framework/cache/data" \
         "${SHARED_DIR}/storage/framework/sessions" \
         "${SHARED_DIR}/storage/framework/views" \
         "${SHARED_DIR}/storage/logs" \
         "${SHARED_DIR}/nginx" \
         "${DATA_DIR}/mysql" \
         "${DATA_DIR}/redis" \
         "${APP_ROOT}/deploy"

# The application containers drop to www-data (33:33) before they write.
chown -R 33:33 "${SHARED_DIR}/storage"
chmod -R ug+rwX "${SHARED_DIR}/storage"

# ---------------------------------------------------------------------------
# 3) Shared docker network
# ---------------------------------------------------------------------------
if docker network inspect "$DATA_NETWORK" >/dev/null 2>&1; then
    log "the ${DATA_NETWORK} network already exists"
else
    docker network create --driver bridge "$DATA_NETWORK" >/dev/null
    log "created the ${DATA_NETWORK} network"
fi

# ---------------------------------------------------------------------------
# 4) Environment file with generated secrets
# ---------------------------------------------------------------------------
if [ -f "$ENV_FILE" ]; then
    log "keeping the existing ${ENV_FILE}"
else
    [ -f "${DEPLOY_DIR}/production.env.template" ] || die "the environment template is missing"

    app_key="base64:$(openssl rand -base64 32)"
    db_password="$(openssl rand -hex 24)"
    db_root_password="$(openssl rand -hex 24)"
    redis_password="$(openssl rand -hex 24)"
    app_url="http://${SERVER_IP}:${PUBLIC_PORT}"

    content="$(cat "${DEPLOY_DIR}/production.env.template")"
    content="${content//__APP_KEY__/$app_key}"
    content="${content//__DB_PASSWORD__/$db_password}"
    content="${content//__DB_ROOT_PASSWORD__/$db_root_password}"
    content="${content//__REDIS_PASSWORD__/$redis_password}"
    content="${content//__APP_URL__/$app_url}"

    printf '%s\n' "$content" > "$ENV_FILE"
    # Root writes it, www-data (gid 33) reads it inside the container, nobody
    # else on the host does.
    chown root:33 "$ENV_FILE"
    chmod 640 "$ENV_FILE"
    log "generated ${ENV_FILE} (APP_KEY, database and Redis passwords)"
fi

if [ ! -f "$UPSTREAM_FILE" ]; then
    cp "${DEPLOY_DIR}/nginx/upstream.conf" "$UPSTREAM_FILE"
    log "installed the initial upstream at ${UPSTREAM_FILE}"
fi

# ---------------------------------------------------------------------------
# 5) Stateful tier
# ---------------------------------------------------------------------------
log "starting the MySQL and Redis tier"
SHARED_DIR="$SHARED_DIR" DATA_DIR="$DATA_DIR" \
docker compose \
    --project-name cc-data \
    --project-directory "$APP_ROOT" \
    --env-file "$ENV_FILE" \
    --file "${DEPLOY_DIR}/compose.data.yaml" \
    up -d --wait --timeout 180

# ---------------------------------------------------------------------------
# 6) Host nginx vhost - only with the explicit flag
# ---------------------------------------------------------------------------
if [ "$INSTALL_NGINX" -eq 1 ]; then
    command -v nginx >/dev/null 2>&1 || die "nginx is not installed on this host"
    [ -f "$VHOST_SOURCE" ] || die "$VHOST_SOURCE is missing"

    if ! grep -q "listen ${PUBLIC_PORT};" "$VHOST_SOURCE"; then
        die "${VHOST_SOURCE} does not listen on ${PUBLIC_PORT} - edit it first"
    fi

    if [ -f "$VHOST_TARGET" ]; then
        backup="${VHOST_TARGET}.bak-$(date +%Y%m%d-%H%M%S)"
        cp "$VHOST_TARGET" "$backup"
        log "backed the existing vhost up to ${backup}"
    fi

    cp "$VHOST_SOURCE" "$VHOST_TARGET"

    if ! output="$(nginx -t 2>&1)"; then
        printf '%s\n' "$output" >&2
        if [ -n "${backup:-}" ]; then
            cp "$backup" "$VHOST_TARGET"
        else
            rm -f "$VHOST_TARGET"
        fi
        nginx -t >/dev/null 2>&1 || true
        die "nginx rejected the vhost - the previous configuration is restored"
    fi

    nginx -s reload
    log "installed ${VHOST_TARGET} and reloaded nginx"
else
    log "skipping the host nginx vhost (pass --install-nginx to install it)"
fi

# ---------------------------------------------------------------------------
# Firewall: verified, never changed
# ---------------------------------------------------------------------------
if command -v ufw >/dev/null 2>&1 && ufw status 2>/dev/null | grep -q "Status: active"; then
    if ufw status | grep -qE "^${PUBLIC_PORT}([[:space:]]|/)"; then
        log "ufw already allows ${PUBLIC_PORT}"
    else
        warn "ufw does not allow ${PUBLIC_PORT} - open it yourself: ufw allow ${PUBLIC_PORT}"
    fi
fi

log "provisioning complete"
log "next: deploy/deploy.sh <image-tag>"