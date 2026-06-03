ARG PHP_VERSION=7.2
FROM php:${PHP_VERSION}-cli

RUN apt update && apt install -y git libzip-dev zip && docker-php-ext-install zip

ARG COVERAGE
RUN if [ "$COVERAGE" = "pcov" ]; then pecl install pcov && docker-php-ext-enable pcov; fi

RUN apt update && apt install -y git zip
COPY --from=composer /usr/bin/composer /usr/bin/composer

WORKDIR /app
