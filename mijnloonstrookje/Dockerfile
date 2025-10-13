FROM php:8.2-fpm

# Systeem packages en extensies
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg-dev libfreetype6-dev zip git unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql gd

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Kopieer code en installeer dependencies
COPY . .
RUN composer install

# Optioneel: cache en key generation (eerste run)
# RUN php artisan key:generate && php artisan config:cache