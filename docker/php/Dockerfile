FROM php:8.2-fpm-bullseye

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    # && docker-php-ext-install pdo_mysql mbstring zip gd exif opcache bcmath \
    && docker-php-ext-install pdo_mysql mbstring zip gd exif bcmath \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY ./php.ini /usr/local/etc/php/conf.d/99-php.ini

ENV COMPOSER_HOME=/tmp/composer

RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

WORKDIR /var/www/

USER www