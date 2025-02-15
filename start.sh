#!/bin/bash

# Start PHP-FPM
php-fpm &

# Generate application key if not set
php artisan key:generate --no-interaction --force

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Run migrations
php artisan migrate --force

# Start Nginx
nginx

# Build and start Vite
npm run build && npm run dev -- --host &

# Start Reverb
php artisan reverb:start --host=0.0.0.0
