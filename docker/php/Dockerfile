FROM php:8.3-fpm

# System deps
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev supervisor \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create directory for supervisor configs
RUN mkdir -p /etc/supervisor/conf.d

# Copy supervisor config for queues
COPY docker/php/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/html

# Expose PHP-FPM port
EXPOSE 9000

# Health check for PHP-FPM
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD php-fpm -t && echo "PHP-FPM healthy"
