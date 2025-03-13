# Start from the PHP image
FROM php:8.2-fpm

# Install Redis extension
RUN apt-get update && apt-get install -y \
    libssl-dev \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install other PHP extensions (if needed)
RUN docker-php-ext-install pdo pdo_mysql

# Copy the Symfony application into the container
COPY . /var/www/html

EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
