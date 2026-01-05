FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip \
    supervisor nodejs npm && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www

# Copy files
COPY . /var/www

# Create required directories BEFORE installing dependencies
RUN mkdir -p /var/www/storage/framework/{sessions,views,cache} && \
    mkdir -p /var/www/storage/logs && \
    mkdir -p /var/www/bootstrap/cache

# Install dependencies
RUN composer install --no-interaction --no-dev --optimize-autoloader && \
    npm install && npm run build

# Set permissions AFTER everything is created
RUN chown -R www-data:www-data /var/www && \
    chmod -R 775 /var/www/storage && \
    chmod -R 775 /var/www/bootstrap/cache

# Create supervisor config directory
RUN mkdir -p /var/log/supervisor

# Copy supervisor config
COPY docker/supervisor/reverb.conf /etc/supervisor/conf.d/reverb.conf
EXPOSE 9000 8084
CMD supervisord -n -c /etc/supervisor/supervisord.conf