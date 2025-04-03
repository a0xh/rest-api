# Используем базовый образ PHP 8.4 FPM
FROM php:8.4-fpm AS builder

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    cron \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    zlib1g-dev \
    procps \
    supervisor

# Установка расширений PHP
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd pdo pdo_pgsql mbstring intl zip exif sockets opcache pcntl

# Установка и включение APCu и Redis
RUN pecl install apcu redis
RUN docker-php-ext-enable apcu redis

# Удаление ненужных пакетов
RUN rm -rf /var/lib/apt/lists/*

# Установка Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

# Копирование файла crontab
ADD ./docker/cron/cronjob /etc/cron.d/cronjob

# Добавление новой строки в конец файла crontab
RUN sed -i -e '$a\' /etc/cron.d/cronjob

# Установка прав на файл crontab и его активация
RUN chmod 0644 /etc/cron.d/cronjob && crontab /etc/cron.d/cronjob

# Создание директории для Cron PID
RUN mkdir -p /var/run && chmod 777 /var/run

# Установка переменных окружения
ENV TZ=Europe/Moscow

# Настройка рабочей директории
WORKDIR /var/www/html

# Копирование файла php.ini без дубликатов расширений
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Передача ID пользователя и группы через ARG
ARG USER_ID=1000
ARG GROUP_ID=1000

# Создание нового пользователя
RUN useradd -ms /bin/bash appuser

# Копирование файлов проекта
COPY --chown=appuser:appuser . /var/www/html

# Открываем порт
EXPOSE 9000

# Копирование конфигурации supervisord
COPY ./docker/supervisord/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Запуск supervisord для управления службами cron и php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
