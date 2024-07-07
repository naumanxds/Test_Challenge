FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    librabbitmq-dev \
    pkg-config \
    libssl-dev \
    autoconf \
    build-essential \
    gnupg \
    software-properties-common

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql gd

RUN pecl install amqp && docker-php-ext-enable amqp

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

RUN curl -sS https://get.symfony.com/cli/installer | bash

RUN mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

COPY . /var/www

RUN composer install --no-scripts --no-plugins
RUN composer require symfony/flex
RUN composer install

COPY --chown=www-data:www-data . /var/www

EXPOSE 9000
CMD ["php", "-S", "0.0.0.0:9000", "-t", "public"]
