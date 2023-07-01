FROM php:8.2-fpm-alpine

COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www

RUN apk add --no-cache \
    bash \
    icu-dev \
    postgresql-dev \
    $PHPIZE_DEPS \
    linux-headers \
    && curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.alpine.sh' | bash \
    && apk add symfony-cli

RUN pecl install -f xdebug-3.2.1 && \
    docker-php-ext-enable xdebug

RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl opcache pdo_pgsql
