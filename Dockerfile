FROM php:7.4-apache

# Instalar extensiones PDO para PostgreSQL
RUN apt-get update && \
    apt-get install -y libpq-dev && \
    docker-php-ext-install pdo pdo_pgsql \
    libmcrypt-dev \
    libmagickwand-dev \
    && pecl install imagick \
    && docker-php-ext-enable imagick

# Resto de las configuraciones y copia de archivos necesarios
COPY . /var/www/html

RUN a2enmod rewrite

# El puerto que escucha Apache
EXPOSE 80

CMD ["apache2-foreground"]
