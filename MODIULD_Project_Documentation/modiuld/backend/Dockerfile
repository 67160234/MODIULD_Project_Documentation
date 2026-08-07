FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html/backend

# Copy all source files
COPY . .

# Install dependencies (no external packages — only autoloader generation)
RUN composer install --no-dev --optimize-autoloader --prefer-dist

RUN chown -R www-data:www-data /var/www/html/backend

EXPOSE 9000
CMD ["php-fpm"]
