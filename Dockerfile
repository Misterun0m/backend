FROM php:8.2-apache

# 🔥 SOLUCIÓN AL ERROR MPM
RUN a2dismod mpm_event
RUN a2enmod mpm_prefork

# Extensiones PHP
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activar rewrite
RUN a2enmod rewrite

# Copiar archivos (ajusta si usas /backend)
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
