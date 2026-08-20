FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    zlib-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Set working directory
WORKDIR /var/www/html/backend

# Copy source
COPY src/ /var/www/html/backend/

# Set permissions
RUN chown -R www-data:www-data /var/www/html/backend \
    && chmod -R 755 /var/www/html/backend

EXPOSE 9000

CMD ["php-fpm"]
