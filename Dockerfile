# Используем базовый образ PHP 8.4 FPM
FROM php:8.4-fpm

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
    && rm -rf /var/lib/apt/lists/*

# Установка и конфигурация GD
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/
RUN docker-php-ext-install -j$(nproc) gd

# Установка остальных PHP-расширений
RUN docker-php-ext-install pdo pdo_pgsql mbstring intl zip exif sockets opcache pcntl

# Установка и включение APCu
RUN pecl install apcu && docker-php-ext-enable apcu

# Установка и включение Redis
RUN pecl install redis && docker-php-ext-enable redis

# Установка Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm composer-setup.php

# Выполнение команды от имени root
RUN if [ -e /usr/bin/php ]; then rm -f /usr/bin/php; fi && ln -s /usr/local/bin/php /usr/bin/php

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

# Копирование файлов проекта
COPY . /var/www/html

# Копирование файла php.ini
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Создание нового пользователя
RUN useradd -ms /bin/bash appuser

# Переключение на нового пользователя
USER appuser

# Открываем порт
EXPOSE 9000

# Запуск Cron в foreground-режиме вместе с PHP-FPM от имени root
CMD ["sh", "-c", "service cron start && php-fpm"]
