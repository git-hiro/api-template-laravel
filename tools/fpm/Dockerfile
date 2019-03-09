FROM php:7.3-fpm-alpine

RUN apk add --update --no-cache \
  curl \
  php-bcmath \
  php-curl \
  php-dom \
  php-json \
  php-openssl \
  php-phar \
  postgresql-dev

RUN docker-php-ext-install \
  pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www/laravel
