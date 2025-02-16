#!/bin/bash

# Ensure proper permissions
chmod -R 777 /var/www/storage
chmod -R 777 /var/www/bootstrap/cache
chown -R www-data:www-data /var/www/storage
chown -R www-data:www-data /var/www/bootstrap/cache

# Start PHP-FPM
php-fpm &

# Generate application key if not set
php artisan key:generate --no-interaction --force

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Run migrations
php artisan migrate --force

# Start Vite
npm run build && npm run dev -- --host &

# Start Nginx (keep this last as it's not backgrounded)
nginx -g 'daemon off;'
