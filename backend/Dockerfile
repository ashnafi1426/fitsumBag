# Multi-stage build for Laravel with PostgreSQL support

# Stage 1: Build stage with Composer and Node.js dependencies
FROM php:8.4-fpm-alpine AS builder

# Install system dependencies
RUN apk add --no-cache \
    build-base \
    postgresql-dev \
    git \
    curl \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    gd \
    mbstring

# Enable Zend opcache
RUN docker-php-ext-install opcache

# Copy Composer from official image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install --optimize-autoloader --no-dev --no-scripts

# Copy application files
COPY . .

# Generate Laravel app key
RUN php artisan key:generate || true

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Stage 2: Runtime stage
FROM php:8.4-fpm-alpine

# Install runtime dependencies only
RUN apk add --no-cache \
    postgresql-client \
    libpng \
    libjpeg-turbo \
    freetype \
    supervisor \
    nginx \
    curl

# Install PHP extensions for runtime
RUN docker-php-ext-install -j$(nproc) \
    pdo_pgsql \
    pgsql \
    gd \
    mbstring \
    opcache

# Copy PHP configuration
COPY docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY docker/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

# Copy Nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/default.conf /etc/nginx/conf.d/default.conf

# Copy Supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy entrypoint script
COPY docker/entrypoint.sh /app/docker-entrypoint.sh
RUN chmod +x /app/docker-entrypoint.sh

# Set working directory
WORKDIR /app

# Copy application from builder
COPY --from=builder /app /app

# Create necessary directories
RUN mkdir -p \
    /app/storage/logs \
    /app/bootstrap/cache \
    /var/run/php-fpm \
    /var/run/nginx \
    /var/log/supervisor

# Set permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache /var/log/supervisor

# Health check
HEALTHCHECK --interval=30s --timeout=10s --start-period=5s --retries=3 \
    CMD curl -f http://localhost/health || exit 1

# Expose port
EXPOSE 8000

# Start services via entrypoint script
ENTRYPOINT ["/app/docker-entrypoint.sh"]
