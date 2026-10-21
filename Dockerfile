# syntax=docker/dockerfile:1
#
# Application image for the Naft Javan High School website.
#
#   PHP 8.4 (FPM)  +  Composer 2  +  Node.js 22 / npm
#
# Node is installed inside the app image on purpose: `npm run build` must be
# runnable inside the container (see AGENTS.md section 5 - Docker & Build
# Workflow) without depending on a host Node installation.

# ---------------------------------------------------------------------------
# Node.js 22 + npm are taken from the official Node image (no third party repo)
# ---------------------------------------------------------------------------
FROM node:22-bookworm-slim AS node

# ---------------------------------------------------------------------------
# Application image
# ---------------------------------------------------------------------------
FROM php:8.4-fpm-bookworm

ENV DEBIAN_FRONTEND=noninteractive \
    APP_DIR=/var/www/html \
    COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_HOME=/var/www/.composer \
    COMPOSER_NO_INTERACTION=1 \
    COMPOSER_MEMORY_LIMIT=-1 \
    npm_config_cache=/var/www/.npm

# ---------------------------------------------------------------------------
# System packages
# ---------------------------------------------------------------------------
# Build/runtime libraries for the PHP extensions, plus git/unzip for Composer.
RUN apt-get update && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        gnupg \
        unzip \
        zip \
        libcurl4-openssl-dev \
        libfreetype6-dev \
        libgmp-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# ---------------------------------------------------------------------------
# Composer 2
# ---------------------------------------------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# ---------------------------------------------------------------------------
# Node.js 22 + npm (copied from the official Node image - no third party repo)
# ---------------------------------------------------------------------------
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
RUN ln -sf /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && ln -sf /usr/local/lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx \
    && node --version \
    && npm --version \
    && composer --version

# ---------------------------------------------------------------------------
# PHP extensions
# ---------------------------------------------------------------------------
# php:8.4-fpm already ships pdo_sqlite, mbstring, xml, curl and opcache, so only
# the missing ones are compiled here. The assertion below proves the full list
# required by the project is available.
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" pdo_mysql zip intl gd bcmath pcntl \
    && rm -rf /tmp/*

RUN php -r '$required = ["pdo_sqlite", "pdo_mysql", "mbstring", "xml", "curl", "zip", "intl", "gd", "bcmath", "pcntl"]; $loaded = get_loaded_extensions(); foreach ($required as $extension) { if (! in_array($extension, $loaded, true)) { fwrite(STDERR, "missing PHP extension: {$extension}\n"); exit(1); } } if (! in_array("Zend OPcache", $loaded, true)) { fwrite(STDERR, "missing PHP extension: opcache\n"); exit(1); } echo "all required PHP extensions are loaded\n";'

# ---------------------------------------------------------------------------
# Application code
# ---------------------------------------------------------------------------
WORKDIR ${APP_DIR}

# Composer dependencies first: this layer is only invalidated when the
# composer manifest/lock change, not on every code change.
COPY composer.json composer.lock ./
COPY artisan artisan
COPY bootstrap/ bootstrap/
COPY config/ config/
COPY app/ app/
COPY routes/ routes/
COPY resources/ resources/
COPY public/ public/
COPY database/ database/

# Framework scripts are allowed to run (post-autoload-dump -> package:discover).
RUN composer install --no-interaction --prefer-dist --no-progress

# Node dependencies (package-lock.json is the source of truth).
COPY package.json package-lock.json .npmrc ./
RUN npm ci --no-audit --no-fund

# Remaining application files.
COPY . .

# ---------------------------------------------------------------------------
# Runtime directories
# ---------------------------------------------------------------------------
# The home directory of www-data (/var/www) must be writable by the non-root
# user, otherwise npm and composer cannot keep their caches.
RUN mkdir -p \
        storage/app/public \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/framework/testing \
        storage/logs \
        bootstrap/cache \
        /var/www/.npm \
        /var/www/.composer \
        /var/www/.cache \
    && chown -R www-data:www-data storage bootstrap/cache vendor node_modules /var/www \
    && chmod -R ug+rwX storage bootstrap/cache \
    && git config --system --add safe.directory /var/www/html

COPY --chmod=0755 docker/entrypoint.sh /usr/local/bin/entrypoint

# ---------------------------------------------------------------------------
# Runtime
# ---------------------------------------------------------------------------
USER www-data
WORKDIR ${APP_DIR}
EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint"]
CMD ["php-fpm"]
