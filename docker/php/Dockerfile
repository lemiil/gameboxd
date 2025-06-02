FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    libxml2-dev \
    libpq-dev \
    sqlite3 \
    libsqlite3-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql pgsql mbstring zip exif pcntl pdo_sqlite \
    && pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
