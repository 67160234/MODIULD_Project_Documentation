FROM composer:2 AS composer

FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

COPY --from=composer /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html/backend

# Copy source
COPY composer.json /var/www/html/backend/
ENV COMPOSER_VENDOR_DIR=/var/www/html/vendor
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
COPY src/ /var/www/html/backend/

EXPOSE 9000

CMD ["php-fpm"]
