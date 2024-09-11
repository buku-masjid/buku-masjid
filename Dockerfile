# ================
# Base Stage
# ================
FROM serversideup/php:8.1-fpm-nginx as base
ENV AUTORUN_ENABLED=false
ENV SSL_MODE=off

# ================
# Production Stage
# ================
FROM base as production

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

RUN composer install --optimize-autoloader --no-dev --no-interaction --no-progress --ansi
COPY .env.example .env.tmp
RUN sed 's/DB_HOST=127.0.0.1/DB_HOST=mysql_host/' .env.tmp > .env && rm .env.tmp

# artisan commands
RUN php ./artisan key:generate && \
    php ./artisan passport:keys && \
    php ./artisan view:cache && \
    php ./artisan route:cache && \
    php ./artisan config:cache && \
    php ./artisan storage:link
