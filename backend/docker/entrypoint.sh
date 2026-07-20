#!/bin/bash
set -e

echo "===================================="
echo "Laravel Render Deployment Starting"
echo "===================================="

# Wait for database to be ready
echo "Checking database connection..."
max_attempts=30
attempt=1
while [ $attempt -le $max_attempts ]; do
    if php /app/artisan db:show > /dev/null 2>&1; then
        echo "✓ Database connection successful"
        break
    fi
    echo "  Attempt $attempt/$max_attempts - Waiting for database..."
    sleep 2
    attempt=$((attempt + 1))
done

if [ $attempt -gt $max_attempts ]; then
    echo "✗ Failed to connect to database after $max_attempts attempts"
    echo "  Continuing anyway - database may come online later"
fi

# Run migrations
echo ""
echo "Running database migrations..."
php /app/artisan migrate --force --no-interaction

# Run seeders (optional - comment out if not needed)
# echo "Running database seeders..."
# php /app/artisan db:seed --force --no-interaction

# Cache configuration
echo "Caching configuration..."
php /app/artisan config:cache --no-interaction
php /app/artisan route:cache --no-interaction
php /app/artisan view:cache --no-interaction

# Clear any existing cache
php /app/artisan cache:clear --no-interaction

echo ""
echo "===================================="
echo "✓ Preparation Complete"
echo "===================================="

# Start supervisor to manage PHP-FPM and Nginx
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
