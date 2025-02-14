# Usar la imagen base de PHP con FPM
FROM php:8.2-fpm

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    libzip-dev

# Configurar e instalar extensiones de PHP
RUN docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) gd pdo_mysql mbstring exif pcntl bcmath intl zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establecer el directorio de trabajo
WORKDIR /var/www/html

# Copiar el archivo de configuración de Nginx
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Copiar el archivo de configuración de FastCGI
COPY ./fastcgi-php.conf /etc/nginx/snippets/fastcgi-php.conf

# Copiar el código fuente de la aplicación
COPY . .

# Copy the composer.json and composer.lock files
COPY composer.json composer.lock ./

# Instalar dependencias de Composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-plugins

# Copy entrypoint script
COPY ./docker-entrypoint.sh /var/www/html/docker-entrypoint.sh

# Make entrypoint script executable
RUN chmod +x /var/www/html/docker-entrypoint.sh

# Instalar la biblioteca de Elasticsearch
RUN composer require elasticsearch/elasticsearch

# Instalar el SDK de AWS
RUN composer require aws/aws-sdk-php

# Instalar Flysystem AWS S3
RUN composer require league/flysystem-aws-s3-v3

# Generar la clave de la aplicación Laravel
RUN php artisan key:generate

# Exponer el puerto 9000 y ejecutar PHP-FPM
EXPOSE 9000
CMD ["php-fpm"]
