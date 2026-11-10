# ============================================================
# Stage 1: Build frontend assets (Node.js)
# ============================================================
FROM node:20-alpine AS frontend-builder
WORKDIR /app
COPY package*.json ./
RUN npm ci --no-audit --no-fund
COPY . .
RUN npm run build

# ============================================================
# Stage 2: Install PHP dependencies (Composer)
# ============================================================
FROM composer:2 AS composer-builder
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-scripts \
    --no-interaction \
    --optimize-autoloader \
    --prefer-dist
COPY . .
RUN composer dump-autoload --optimize --no-dev

# ============================================================
# Stage 3: Production image
# ============================================================
FROM php:8.2-fpm-alpine AS production

LABEL maintainer="CMS"
LABEL description="Laravel CMS - Production Image"

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    git \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    libwebp-dev \
    freetype-dev \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    && docker-php-ext-configure gd \
        --with-freetype \
        --with-jpeg \
        --with-webp \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        gd \
        intl \
        zip \
        bcmath \
        opcache \
        exif \
        mbstring \
    && apk del --no-cache $PHPIZE_DEPS

# Install Redis extension
RUN apk add --no-cache $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del --no-cache $PHPIZE_DEPS

# Configure PHP
COPY docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Configure Nginx
COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# Configure Supervisor
COPY docker/supervisor/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Copy application from builders
COPY --from=composer-builder --chown=www-data:www-data /app /var/www/html
COPY --from=frontend-builder --chown=www-data:www-data /app/public/build /var/www/html/public/build

# Setup storage directories
RUN mkdir -p storage/app/public \
              storage/framework/cache \
              storage/framework/sessions \
              storage/framework/views \
              storage/logs \
              bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copy entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD curl -f http://localhost/up || exit 1

EXPOSE 80

ENTRYPOINT ["/entrypoint.sh"]
