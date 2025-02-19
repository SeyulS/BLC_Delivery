#!/bin/bash

# Set ownership for the Laravel project
sudo chown -R $USER:www-data /var/www/BLC_Delivery

# Set specific ownership for storage and bootstrap/cache
sudo chown -R www-data:www-data /var/www/BLC_Delivery/storage

sudo chown -R www-data:www-data /var/www/BLC_Delivery/bootstrap/cache

# Set base directory permissions
sudo find /var/www/BLC_Delivery -type d -exec chmod 755 {} \;
sudo find /var/www/BLC_Delivery -type f -exec chmod 644 {} \;

# Set storage and bootstrap/cache permissions
sudo chmod -R 775 /var/www/BLC_Delivery/storage
sudo chmod -R 775 /var/www/BLC_Delivery/bootstrap/cache

# Create storage directories
sudo -u www-data mkdir -p /var/www/BLC_Delivery/storage/framework/{sessions,views,cache}
sudo -u www-data mkdir -p /var/www/BLC_Delivery/storage/logs

# Set specific permissions for log file
sudo -u www-data touch /var/www/BLC_Delivery/storage/logs/laravel.log
sudo chmod 664 /var/www/BLC_Delivery/storage/logs/laravel.log

# Add user to www-data group
sudo usermod -a -G www-data $USER

# Reload groups (or log out and back in)
newgrp www-data

# Install dependencies
npm install

# Install Vite globally
npm install -g vite
composer install
npm run build

# Clear and cache config
php artisan config:clear
php artisan config:cache

# Run migrations
php artisan migrate:fresh --seed
