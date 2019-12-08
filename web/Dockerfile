FROM php:7.4-apache

RUN     apt-get update --fix-missing \
        && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
        && docker-php-ext-install mysqli

COPY src/ /var/www/html/