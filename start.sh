#!/bin/bash

# Generate application key if not set
php artisan key:generate --no-interaction

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate:fresh --seed

# Start Nginx
service nginx start

# Start Laravel development server with specific host
php artisan serve --host=0.0.0.0 --port=8000 &

# Build and start Vite
npm run build && npm run dev -- --host &

# Start Reverb (this should be last as it's not backgrounded)
php artisan reverb:start --host=0.0.0.0
