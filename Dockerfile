FROM dunglas/frankenphp:php8.3

ENV SERVER_NAME=":80"

WORKDIR /app

COPY . /app

RUN apt update && apt install -y zip libzip-dev && \
    docker-php-ext-install zip && \
    docker-php-ext-enable zip

RUN install-php-extensions \
    pdo_mysql \
    mysqli \
    intl \
    opcache

COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer

RUN composer install



