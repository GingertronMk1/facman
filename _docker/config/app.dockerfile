FROM php:8.3.0-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

RUN apk add --no-cache \
    bash \
    icu-dev \
    postgresql-dev \
    $PHPIZE_DEPS \
    linux-headers

RUN pecl install -f xdebug pcov && \
    docker-php-ext-enable xdebug

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl opcache pdo_pgsql
