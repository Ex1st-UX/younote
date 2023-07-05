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
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Назначение рабочей директории и копирование файлов проекта
WORKDIR /var/www/html
COPY . /var/www/html

# Установка зависимостей с помощью Composer
RUN composer install --no-interaction --no-dev --optimize-autoloader

# Назначение прав доступа
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Создание символической ссылки на хранилище
RUN php artisan storage:link

# Генерация ключа приложения Laravel
RUN php artisan key:generate

# Копирование файла конфигурации NGINX в контейнер
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Установка и настройка Nginx
RUN apt-get update && apt-get install -y nginx

# Открытие портов 80 и 8080
EXPOSE 80
EXPOSE 8080

RUN php artisan serve

