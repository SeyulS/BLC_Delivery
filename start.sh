#!/bin/bash

# Set ownership for the Laravel project
chown -R $USER:www-data /var/www/BLC_Delivery

# Set specific ownership for storage and bootstrap/cache
chown -R www-data:www-data /var/www/BLC_Delivery/storage

chown -R www-data:www-data /var/www/BLC_Delivery/bootstrap/cache

# Set base directory permissions
find /var/www/BLC_Delivery -type d -exec chmod 755 {} \;
find /var/www/BLC_Delivery -type f -exec chmod 644 {} \;

# Set storage and bootstrap/cache permissions
chmod -R 775 /var/www/BLC_Delivery/storage
chmod -R 775 /var/www/BLC_Delivery/bootstrap/cache

# Create storage directories
-u www-data mkdir -p /var/www/BLC_Delivery/storage/framework/{sessions,views,cache}
-u www-data mkdir -p /var/www/BLC_Delivery/storage/logs

# Set specific permissions for log file
-u www-data touch /var/www/BLC_Delivery/storage/logs/laravel.log
chmod 664 /var/www/BLC_Delivery/storage/logs/laravel.log

# Add user to www-data group
usermod -a -G www-data $USER

# Reload groups (or log out and back in)
newgrp www-data

# Navigate to project directory
cd /var/www/BLC_Delivery

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Run migrations
php artisan migrate:fresh --seed
