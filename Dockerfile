FROM php:8.2-cli

# Instalar extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copiar archivos
COPY . /app

WORKDIR /app

# Usar el puerto dinámico de Railway
CMD php -S 0.0.0.0:$PORT
