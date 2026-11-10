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
USER_COUNT=$(php -r "
require '/var/www/html/vendor/autoload.php';
\$app = require '/var/www/html/bootstrap/app.php';
\$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
echo App\Models\User::count();
" 2>/dev/null || echo "0")

if [ "$USER_COUNT" = "0" ]; then
    echo "  First install detected - running seeders..."
    php artisan db:seed --force --no-interaction
    echo "  Seeding complete!"
else
    echo "  Existing install detected ($USER_COUNT users) - skipping seed."
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
