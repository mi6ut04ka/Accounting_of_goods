# Используем официальный образ PHP с установленными зависимостями
FROM php:8.1-fpm

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev git zip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Устанавливаем Composer (менеджер зависимостей для PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Устанавливаем рабочую директорию
WORKDIR /var/www

# Копируем файлы из директории проекта в контейнер
COPY . .

# Устанавливаем зависимости Laravel
RUN composer install

# Разрешаем доступ к папке storage и bootstrap/cache
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache

# Экспонируем порт
EXPOSE 9000

# Запускаем PHP-FPM
CMD ["php-fpm"]
