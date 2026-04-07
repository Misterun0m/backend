FROM php:8.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN docker-php-ext-enable mysqli

RUN apk add --no-cache caddy curl libcurl curl-dev
RUN docker-php-ext-install curl

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app
WORKDIR /app

RUN COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader

EXPOSE 8080

CMD ["sh", "-c", "php-fpm -D && caddy run --config /app/Caddyfile --adapter caddyfile"]