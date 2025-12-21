# PHP 8.3 FPM (phù hợp Laravel 11)
FROM php:8.4-fpm

# Cài các package hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    curl \
    && docker-php-ext-install \
    pdo \
    pdo_mysql \
    mbstring \
    zip \
    bcmath \
    exif \
    pcntl

# Cài Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set thư mục làm việc
WORKDIR /var/www/html

# Copy source code vào container
COPY . .

# Cấp quyền cho Laravel
RUN chmod -R 777 storage bootstrap/cache

# Expose port cho PHP-FPM
EXPOSE 9000

CMD ["php-fpm"]
