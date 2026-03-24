FROM php:8.2-cli

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar archivos
COPY . /app

WORKDIR /app

CMD sh -c "php -S 0.0.0.0:$PORT -t /app/backend"
