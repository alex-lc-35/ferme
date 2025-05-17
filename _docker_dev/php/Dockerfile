FROM php:8.3-fpm

# Install utils and dev-friendly tools
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    zip \
    libonig-dev \
    libicu-dev \
    vim less nano \
  && docker-php-ext-install pdo pdo_mysql intl opcache \
  && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Install Symfony CLI (optional but useful for development)
RUN curl -sS https://get.symfony.com/cli/installer | bash \
    && mv /root/.symfony*/bin/symfony /usr/local/bin/symfony

# Copy custom PHP config
COPY php.ini /usr/local/etc/php/php.ini

# Define working directory
WORKDIR /var/www/html
