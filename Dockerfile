FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm \
    nginx

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy existing application directory
COPY . .

# Set npm permissions and install dependencies
RUN mkdir -p /var/www/.npm && \
    chown -R www-data:www-data /var/www/.npm && \
    npm install -g vite && \
    npm install

# Install PHP dependencies
RUN composer install

# Generate key
RUN php artisan key:generate

# Create nginx logs directory
RUN mkdir -p /var/log/nginx

# Set permissions for Laravel and Node
RUN chown -R www-data:www-data /var/www && \
    find /var/www -type f -exec chmod 644 {} \; && \
    find /var/www -type d -exec chmod 755 {} \; && \
    chmod -R 777 /var/www/storage && \
    chmod -R 777 /var/www/bootstrap/cache && \
    chmod -R 777 /var/www/node_modules && \
    chown -R www-data:www-data /var/www/storage && \
    chown -R www-data:www-data /var/www/bootstrap/cache && \
    chown -R www-data:www-data /var/www/node_modules

COPY start.sh /var/www/start.sh
RUN chmod +x /var/www/start.sh

COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 8000 5173

CMD ["sh", "/var/www/start.sh"]
