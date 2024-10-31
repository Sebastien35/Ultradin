# Utilise l'image officielle PHP avec Apache pour Symfony 7.1 et PHP 8.2
FROM php:8.2-apache

# Installe les extensions PHP nécessaires pour Symfony et Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Active le module Apache Rewrite (indispensable pour Symfony)
RUN a2enmod rewrite

# Installe Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Définit le répertoire de travail
WORKDIR /var/www/html

# Copie les fichiers (sauf ceux montés en volume) et installe les dépendances
COPY . /var/www/html
RUN composer install

# Définit les permissions pour permettre l’écriture dans le dossier de cache et de logs
RUN chown -R www-data:www-data /var/www/html/var

# Définit les variables d'environnement pour le mode développement
ENV APP_ENV=dev
ENV APP_DEBUG=1

# Expose le port 80 pour accéder à l'application Symfony via Apache
EXPOSE 80
