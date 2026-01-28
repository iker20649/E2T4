FROM php:8.2-fpm

# Laneko direktorioa
WORKDIR /var/www

# Dependentziak instalatu
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    libzip-dev \
    unzip \
    git \
    curl

# PHP luzapenak
RUN docker-php-ext-install pdo_mysql zip exif pcntl gd

# Composer kopiatu
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Fitxategiak kopiatu
COPY . .

# Karpetak sortu (existitzen ez badira) eta baimenak eman
RUN mkdir -p /var/www/storage /var/www/bootstrap/cache && \
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]