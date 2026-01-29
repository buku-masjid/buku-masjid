FROM mcr.microsoft.com/devcontainers/php:8.1-bullseye

# 1. Hapus repository Yarn yang rusak agar tidak menghentikan apt-get update
RUN rm -f /etc/apt/sources.list.d/yarn.list \
    && rm -f /etc/apt/sources.list.d/google-chrome.list

# 2. Jalankan update dan install dependencies
RUN apt-get update && export DEBIAN_FRONTEND=noninteractive \
    && apt-get install -y --no-install-recommends \
       libicu-dev \
       libpng-dev \
       libjpeg-dev \
       libfreetype6-dev \
       libzip-dev \
       mariadb-client \
    # 3. Install & Configure PHP extensions
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) intl gd zip bcmath mysqli pdo_mysql \
    # 4. Bersihkan sisa instalasi
    && apt-get clean && rm -rf /var/lib/apt/lists/*
