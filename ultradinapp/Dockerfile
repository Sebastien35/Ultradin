# Use official PHP image with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libonig-dev libzip-dev libxml2-dev libcurl4-openssl-dev \
    && docker-php-ext-install pdo pdo_mysql zip opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader 

# Set up proper permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 777 /var/www/html/var /var/www/html/public /var/www/html/config

# Copy Apache virtual host configuration
COPY ./vhosts/vhosts.conf /etc/apache2/sites-available/000-default.conf

# Enable the new site configuration
RUN a2ensite 000-default.conf
RUN a2enmod rewrite

# Expose the port used by Apache
EXPOSE 80

# Clear Symfony cache
RUN php bin/console cache:clear

# Start Apache
CMD ["apache2-foreground"]
