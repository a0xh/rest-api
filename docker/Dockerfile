FROM php:8.4-fpm

# Проверка файлов в /etc/apt/
RUN ls -l /etc/apt/

# Замена репозитория на Yandex Mirror
RUN if [ -d /etc/apt/sources.list.d/ ]; then \
    sed -i 's/deb.debian.org/mirror.yandex.ru/g' /etc/apt/sources.list.d/*; \
fi

# Устанавливаем системные зависимости
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpq-dev \
    libicu-dev \
    libzip-dev \
    libonig-dev \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    zlib1g-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Установка и конфигурация GD
RUN apt-get update && apt-get install -y libfreetype6-dev libjpeg-dev libpng-dev
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

# Установка Node.js и Yarn
RUN curl -fsSL https://deb.nodesource.com/setup_lts.x | bash -E -
RUN apt-get update && apt-get install -y nodejs
RUN npm install -g yarn

# Установка переменных окружения
ENV TZ=Europe/Moscow

# Передача ID пользователя и группы через ARG
ARG USER_ID=1000
ARG GROUP_ID=1000

# Создание группы и пользователя user
RUN addgroup --gid $GROUP_ID user
RUN adduser --disabled-password --gecos '' --uid $USER_ID --gid $GROUP_ID user

# Настройка рабочей директории
WORKDIR /var/www/html

# Создание директорий для Yarn
RUN mkdir -p /var/www/.yarn /var/www/.cache/yarn

# Создание файла .yarnrc
RUN touch /var/www/.yarnrc
RUN chown user:user /var/www/.yarnrc
RUN chmod 644 /var/www/.yarnrc

# Установка прав доступа для директорий Yarn
RUN chown -R user:user /var/www/.yarn /var/www/.cache/yarn
RUN chmod -R 775 /var/www/.yarn /var/www/.cache/yarn

# Установка прав доступа для директории /var/www
RUN chown -R user:user /var/www
RUN chmod -R 775 /var/www

# Настройка рабочей директории
WORKDIR /var/www/html

# Создание директорий и установка прав
RUN mkdir -p storage/logs storage/cache storage/sessions storage/views && \
    chown -R user:user /var/www/html && \
    chmod -R 775 storage/logs storage/cache storage/sessions storage/views

# Создание директории для Composer и настройка прав доступа
RUN mkdir -p /var/www/.composer && \
    chmod +w /var/www/.composer -R && \
    chown user:user /var/www/.composer -R

# Переключение пользователя
USER user

# Открываем порт
EXPOSE 9000

# Команда для запуска PHP-FPM
CMD ["php-fpm"]