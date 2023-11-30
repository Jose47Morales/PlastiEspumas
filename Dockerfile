FROM php:7.4-apache

# Instalar extensiones PDO para PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev libmcrypt-dev libmagickwand-dev && \
    docker-php-ext-install pdo pdo_pgsql && \
    pecl install imagick && \
    docker-php-ext-enable imagick

# Resto de las configuraciones y copia de archivos necesarios
COPY . /var/www/html

# Habilitar el módulo de reescritura de Apache
RUN a2enmod rewrite

# Exponer el puerto que escucha Apache
EXPOSE 80

# Comando para iniciar Apache
CMD ["apache2-foreground"]

