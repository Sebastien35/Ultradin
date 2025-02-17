# Base image with PHP-FPM
FROM php:8.2-fpm

# Install dependencies
RUN apt update && apt install -y \
    nginx \
    nano \
    unzip \
    git \
    libpng-dev \
    libjpeg-dev \
    libzip-dev \
    libfreetype6-dev \
    libonig-dev \
    libxslt1-dev \
    libssl-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo_mysql zip intl opcache soap xsl \
    && pecl install mongodb \
    && docker-php-ext-enable mongodb

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/symfony

# Copy Symfony app
COPY . .

# Ensure cache/logs directories exist and set proper permissions
RUN mkdir -p var/cache var/log var/sessions && \
    chown -R www-data:www-data var && \
    chmod -R 777 var

# Install Symfony dependencies
RUN composer install --no-dev --optimize-autoloader

# Copy Nginx config
COPY ./docker/nginx/default.conf /etc/nginx/sites-available/default
RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/

# Expose necessary ports
EXPOSE 8000

# Start PHP-FPM and Nginx together
CMD service nginx start && php-fpm
