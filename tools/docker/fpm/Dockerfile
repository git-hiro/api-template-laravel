FROM php:7.2-fpm-alpine

RUN apk add --update --no-cache \
  curl \
  php-curl \
  php-openssl \
  php-json \
  php-phar \
  php-dom

RUN curl -sS https://getcomposer.org/installer | php \
  && mv composer.phar /usr/local/bin/composer

WORKDIR /var/www
