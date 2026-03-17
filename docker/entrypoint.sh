#!/bin/sh
set -e

# Install dependencies if vendor is empty
if [ ! -f /var/www/html/vendor/autoload.php ]; then
    composer install --no-interaction --optimize-autoloader
fi

php bin/console doctrine:migrations:migrate --no-interaction

# Ensure var/ directories exist with correct permissions
mkdir -p var/log var/cache
chown -R www-data:www-data var

exec apache2-foreground