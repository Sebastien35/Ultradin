FROM php:8.2-fpm

RUN apt update && apt install -y \
    nano \
    unzip \
    build-essential \
    git \
    nginx \
    libssl-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \

RUN docker-php-ext-install  pdo_mysql \
    && docker-php-ext-enable pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer


COPY . .

ENV COMPOSER_ALLOW_SUPERUSER 1

RUN composer install

EXPOSE 9000
CMD ["php-fpm"]