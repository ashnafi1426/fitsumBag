# Multi-stage build for Laravel
FROM php:8.4-fpm-alpine AS base

# Install system dependencies
RUN apk add --no-cache \
    postgresql-client \
    postgresql-libs \
    postgresql-dev \
    curl \
    git \
    nginx \
    supervisor \
    && docker-php-ext-install pdo pdo_pgsql \
    && apk del postgresql-dev

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Copy FULL application code FIRST (before composer install)
COPY . .

# Create .env file if it doesn't exist
RUN if [ ! -f .env ]; then cp .env.example .env; fi

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction 2>&1 | grep -v "post-autoload-dump\|artisan package:discover" || true

# Set permissions
RUN chown -R www-data:www-data /app

# Generate Laravel key if needed
RUN php artisan key:generate --force || true

# Clear all caches (ignore database errors during build)
RUN php artisan config:clear || true && \
    php artisan route:clear || true && \
    php artisan cache:clear || true && \
    php artisan view:clear || true

# Production stage
FROM base AS production

# Copy nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf

# Copy supervisor config
COPY docker/supervisord.conf /etc/supervisord.conf

# Expose port
EXPOSE 8080

# Clear caches, run migrations, and start services (NO route caching - let Laravel handle routing)
CMD ["sh", "-c", "php artisan config:clear || true ; php artisan route:clear || true ; php artisan cache:clear || true ; php artisan view:clear || true ; php artisan config:cache ; php artisan migrate --force && supervisord -c /etc/supervisord.conf"]
