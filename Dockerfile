FROM php:8.4-fpm-alpine

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN apk add --no-cache caddy

COPY . /app
WORKDIR /app

EXPOSE 8080

CMD ["sh", "-c", "php-fpm -D && caddy run --config /app/Caddyfile --adapter caddyfile"]