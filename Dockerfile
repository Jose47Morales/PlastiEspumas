FROM php:7.4-apache

# Instalar extensiones PDO para PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql

# Resto de las configuraciones y copia de archivos necesarios
COPY . /var/www/html

# El puerto que escucha Apache
EXPOSE 80

