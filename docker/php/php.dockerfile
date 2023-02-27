FROM php:7.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add --update --no-cache libgd libpng-dev libjpeg-turbo-dev freetype-dev

RUN docker-php-ext-install -j$(nproc) gd exif

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY composer.json composer.lock ./
RUN composer install --no-scripts --no-autoloader

EXPOSE 6001

WORKDIR /var/www/html