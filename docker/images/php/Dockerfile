FROM php:8.3.14-apache

RUN apt-get update && apt-get install -y curl zip libicu-dev \
    && docker-php-ext-configure intl && docker-php-ext-install intl pdo pdo_mysql \
    && a2enmod rewrite
#xdebug
RUN pecl install xdebug && docker-php-ext-enable xdebug
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/bin --filename=composer
RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
USER "1000:1000"

WORKDIR /var/www/app