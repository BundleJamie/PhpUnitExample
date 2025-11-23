FROM php:8.4-cli

########################################
# System dependencies
########################################
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    libzip-dev \
    libicu-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    libsqlite3-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libssl-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install \
        intl \
        pdo \
        pdo_mysql \
        pdo_pgsql \
        zip \
        mbstring \
        xml \
        opcache \
    && rm -rf /var/lib/apt/lists/*

########################################
# GD extension with JPEG support
########################################
RUN docker-php-ext-configure gd \
        --with-jpeg=/usr \
        --with-freetype=/usr \
    && docker-php-ext-install gd

########################################
# Install Xdebug (optional, for PhpStorm)
########################################
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug

########################################
# Install Redis extension (optional)
########################################
RUN pecl install redis \
    && docker-php-ext-enable redis

########################################
# Install Composer globally
########################################
RUN curl -sS https://getcomposer.org/installer | php -- \
        --install-dir=/usr/local/bin \
        --filename=composer

########################################
# Configure PHP
########################################
COPY php.ini /usr/local/etc/php/php.ini

WORKDIR /var/www/html
