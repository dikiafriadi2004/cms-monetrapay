#!/bin/sh
set -e

echo "============================================"
echo "  CMS - Container Starting"
echo "============================================"

# Wait for MySQL to be ready
echo "[1/6] Waiting for database connection..."
MAX_TRIES=30
COUNT=0
until php artisan db:show > /dev/null 2>&1; do
    COUNT=$((COUNT + 1))
    if [ $COUNT -ge $MAX_TRIES ]; then
        echo "ERROR: Database not reachable after ${MAX_TRIES} attempts. Exiting."
        exit 1
    fi
    echo "  Database not ready, retrying... ($COUNT/$MAX_TRIES)"
    sleep 3
done
echo "  Database connected!"

# Run migrations
echo "[2/6] Running migrations..."
php artisan migrate --force --no-interaction
echo "  Migrations complete!"

# Seed only on fresh install (no users)
echo "[3/6] Checking if first install..."
USER_COUNT=$(php /var/www/html/artisan tinker --execute="echo App\Models\User::count();" 2>/dev/null | tail -1 | tr -d '[:space:]')

if [ "$USER_COUNT" = "0" ] || [ -z "$USER_COUNT" ]; then
    echo "  First install detected - running cms:install..."

    # Validasi credentials tersedia
    if [ -z "$ADMIN_EMAIL" ]; then
        echo "ERROR: ADMIN_EMAIL environment variable tidak diset!"
        echo "Jalankan manual: docker compose exec app php artisan cms:install --admin-name=... --admin-email=... --admin-password=..."
        exit 1
    fi
    if [ -z "$ADMIN_PASSWORD" ]; then
        echo "ERROR: ADMIN_PASSWORD environment variable tidak diset!"
        exit 1
    fi

    php /var/www/html/artisan cms:install \
        --admin-name="${ADMIN_NAME:-Super Admin}" \
        --admin-email="${ADMIN_EMAIL}" \
        --admin-password="${ADMIN_PASSWORD}" \
        --force
    echo "  Install complete!"
else
    echo "  Existing install detected ($USER_COUNT users) - skipping."
fi

# Storage link
echo "[4/6] Setting up storage link..."
php artisan storage:link 2>/dev/null || echo "  Storage link already exists."

# Optimize for production
echo "[5/6] Optimizing for production..."
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction
echo "  Optimization complete!"

# Fix permissions
echo "[6/6] Setting permissions..."
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
echo "  Permissions set!"

echo ""
echo "============================================"
echo "  CMS is ready!"
echo "  Admin: $(php artisan tinker --execute=\"echo config('app.url');\" 2>/dev/null)/admin"
echo "============================================"
echo ""

# Start supervisor (manages nginx + php-fpm + queue + scheduler)
exec supervisord -c /etc/supervisor/conf.d/supervisord.conf
