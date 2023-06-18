# Указываем базовый образ
FROM php:8.0-fpm

# Установка необходимых зависимостей
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Установка Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Установка рабочей директории и копирование файлов проекта
WORKDIR /var/www/html
COPY . /var/www/html

# Назначение прав доступа
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Установка MySQL-клиента
RUN apt-get install -y default-mysql-client

# Запуск сервера PHP
CMD php artisan serve --host=0.0.0.0 --port=8000
