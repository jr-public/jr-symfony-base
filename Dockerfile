FROM php:8.4-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libzip-dev \
    libpq-dev \
    && docker-php-ext-install \
    intl \
    pdo \
    pdo_pgsql \
    zip \
    opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# PHP configuration
RUN echo "short_open_tag = Off" > /usr/local/etc/php/conf.d/symfony.ini

# Enable mod_rewrite for Symfony
RUN a2enmod rewrite

# Configure DocumentRoot to /public (Symfony)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf

WORKDIR /var/www/html
RUN mkdir -p var/cache var/log && chmod -R 777 var/
EXPOSE 80