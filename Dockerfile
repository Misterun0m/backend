FROM php:8.2-apache

# 🔥 LIMPIAR CONFIGURACIÓN MPM
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf

RUN a2enmod mpm_prefork

# Extensiones necesarias
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activar rewrite
RUN a2enmod rewrite

# Copiar archivos (ajusta si usas /backend)
COPY . /var/www/html/

# Permisos
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
