# Stage 1: Build Frontend Assets (Vite & Tailwind)
FROM node:20-alpine AS build-frontend
WORKDIR /app
COPY package*.json ./
RUN npm ci
COPY . .
RUN npm run build

# Stage 2: Install Composer Dependencies
FROM composer:2.7 AS build-composer
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --prefer-dist --no-interaction
COPY . .
RUN composer dump-autoload --optimize

# Stage 3: Final Production Image (PHP 8.2 + Apache)
FROM php:8.2-apache
WORKDIR /var/www/html

# Install required system dependencies and extensions for Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    mariadb-client \
    && rm -rf /var/lib/apt/lists/*

# Configure & Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql opcache gd zip bcmath

# Enable Apache mod_rewrite for Laravel routing
RUN a2enmod rewrite

# Setup Port via Environment Variable (Essential for Railway)
ENV PORT=8080
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# Configure Apache listening port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf \
    && sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf \
    && sed -i 's/\/var\/www\/html/${APACHE_DOCUMENT_ROOT}/g' /etc/apache2/sites-available/000-default.conf

# Copy vendor, source code, and frontend build
COPY --from=build-composer /app /var/www/html
COPY --from=build-frontend /app/public/build /var/www/html/public/build

# Setup file permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# Copy custom Apache VHost and Start script
COPY docker/vhost.conf /etc/apache2/sites-available/000-default.conf
COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE ${PORT}

# Run the startup script
ENTRYPOINT ["/usr/local/bin/start.sh"]
