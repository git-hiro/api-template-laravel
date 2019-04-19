FROM php:7.3-fpm-alpine

RUN apk add --update --no-cache \
  autoconf \
  curl \
  g++ \
  make \
  php-curl \
  php-dom \
  php-json \
  php-openssl \
  php-phar \
  postgresql-dev \
  zlib

RUN docker-php-ext-install \
  bcmath \
  pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

# xdebug
RUN pecl install xdebug \
  && docker-php-ext-enable xdebug

WORKDIR /var/www/laravel
