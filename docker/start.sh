#!/bin/bash
set -e

echo "Starting deployment setup..."

# Give files correct permissions
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

# Generate symlink if it doesn't exist
echo "Linking storage..."
su -s /bin/bash -c "php artisan storage:link" www-data || true

# Cache Laravel configurations
echo "Caching configurations..."
su -s /bin/bash -c "php artisan config:cache" www-data || true
su -s /bin/bash -c "php artisan route:cache" www-data || true
su -s /bin/bash -c "php artisan view:cache" www-data || true

# Run explicit migrations if instructed
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Processing database migrations..."
    su -s /bin/bash -c "php artisan migrate --force" www-data || true
fi

echo "Starting Apache foreground..."
exec apache2-foreground
