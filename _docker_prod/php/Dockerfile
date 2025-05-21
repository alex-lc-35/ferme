FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip git curl libzip-dev zip libicu-dev libonig-dev libpq-dev \
 && docker-php-ext-install pdo pdo_mysql intl opcache \
 && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy app code
COPY . .

# Initial permissions (ça ne suffit pas seul)
RUN mkdir -p var/cache var/log

# Install PHP deps (may write in var/)
RUN composer install --no-dev --optimize-autoloader

# Refaire les droits après install
RUN chown -R www-data:www-data .

# Exécuter cache:clear avec les bons droits
USER www-data
RUN php bin/console cache:clear --env=prod --no-debug && \
    php bin/console cache:warmup --env=prod --no-debug

USER root

# Optional: custom PHP config
COPY _docker_prod/php/php.ini /usr/local/etc/php/php.ini
