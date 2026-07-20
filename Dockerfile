# Multi-stage build for Laravel
FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    postgresql-client \
    postgresql-libs \
    curl \
    git \
    nginx \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy composer files from backend
COPY backend/composer.json backend/composer.lock ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copy application code from backend
COPY backend/ .

# Set permissions
RUN chown -R www-data:www-data /app

# Generate Laravel key if needed
RUN php artisan key:generate --force || true

# Production stage
FROM base AS production

# Copy nginx config
COPY backend/docker/nginx.conf /etc/nginx/nginx.conf
COPY backend/docker/default.conf /etc/nginx/conf.d/default.conf

# Copy supervisor config
COPY backend/docker/supervisord.conf /etc/supervisord.conf

# Expose port
EXPOSE 8080

# Run migrations and start services
CMD ["sh", "-c", "php artisan migrate --force && supervisord -c /etc/supervisord.conf"]
