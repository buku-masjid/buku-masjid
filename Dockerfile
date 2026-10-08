# ================
# Base Stage
# ================
FROM serversideup/php:8.1-fpm-nginx AS base
ENV AUTORUN_ENABLED=false
ENV SSL_MODE=off

# ================
# Frontend Stage
# ================
FROM node:18-bullseye-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json vite.config.js ./
RUN npm ci

COPY resources resources
RUN npm run build

# ================
# Production Stage
# ================
FROM base AS production

ENV APP_ENV=production
ENV APP_DEBUG=false

# Required Modules
USER root:root
RUN apt-get update && \
    apt-get install -y libpng-dev libicu-dev && \
    docker-php-ext-configure intl && \
    docker-php-ext-install pdo_mysql gd intl && \
    docker-php-ext-enable intl && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

USER $PUID:$PGID

# Copy contents.
# - To ignore files or folders, use .dockerignore
COPY --chown=$PUID:$PGID . .
COPY --from=frontend --chown=$PUID:$PGID /app/public ./public

RUN composer install --optimize-autoloader --no-dev --no-interaction --no-progress --ansi
COPY .env.example .env.tmp
RUN sed 's/DB_HOST=127.0.0.1/DB_HOST=mysql_host/' .env.tmp > .env && rm .env.tmp

# artisan commands
RUN php ./artisan key:generate && \
    php ./artisan passport:keys && \
    php ./artisan view:cache && \
    php ./artisan storage:link

# Laravel writes logs, cached views, and framework cache files at runtime.
# Keep ownership aligned with the unprivileged runtime user (PUID:PGID).
USER root:root
RUN chown -R $PUID:$PGID storage bootstrap/cache && \
    chmod -R ug+rwX storage bootstrap/cache

USER $PUID:$PGID
