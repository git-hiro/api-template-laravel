FROM php:7.3-fpm-alpine

RUN apk add --update --no-cache \
  curl \
  php-curl \
  php-openssl \
  php-json \
  php-phar \
  php-dom \
  postgresql-dev

RUN docker-php-ext-install \
  pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/laravel
