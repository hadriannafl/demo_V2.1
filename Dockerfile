FROM php:8.3-apache

# Install system dependencies + PHP extensions
RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libxml2-dev libzip-dev libonig-dev \
    nodejs npm \
    && docker-php-ext-install pdo pdo_mysql mbstring xml zip gd bcmath ctype fileinfo opcache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_ALLOW_SUPERUSER=1

# Configure Apache: document root = public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite headers

WORKDIR /var/www/html

# Copy semua file dulu baru install (artisan harus ada sebelum composer post-scripts)
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction
RUN npm ci && npm run build

# Fix permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY start.sh /start.sh
RUN chmod +x /start.sh

EXPOSE 80

CMD ["/bin/bash", "/start.sh"]
