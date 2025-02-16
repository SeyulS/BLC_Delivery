FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

RUN docker-php-ext-configure pcntl --enable-pcntl \
&& docker-php-ext-install \
    pcntl

EXPOSE 8080

CMD ["php", "artisan", "reverb:start", "--host=0.0.0.0"]
