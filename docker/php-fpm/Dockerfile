FROM php:7.2-fpm-alpine

RUN apk --update --no-cache add postgresql-dev
RUN docker-php-ext-install pdo pdo_pgsql

WORKDIR /var/www/bookexchange