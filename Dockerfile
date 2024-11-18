FROM php:8.2-fpm

COPY ./php.ini /usr/local/etc/php/

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo_mysql

COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-scripts --no-autoloader

RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

RUN php artisan key:generate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--port=8000", "--host=0.0.0.0"]
