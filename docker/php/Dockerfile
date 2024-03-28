FROM php:8.2-fpm

# Instalacja dodatków linux
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    vim \
    git \
    zlib1g-dev \
    libicu-dev

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-enable intl

# PostgreSQL
RUN apt-get install -y libpq-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# OPCache
RUN docker-php-ext-configure opcache --enable-opcache \
    && docker-php-ext-install opcache

# xDebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

COPY xdebug.ini "${PHP_INI_DIR}/conf.d"

COPY php.ini "${PHP_INI_DIR}/php.ini"

# Ustawienie katalogu roboczego
WORKDIR /var/www
