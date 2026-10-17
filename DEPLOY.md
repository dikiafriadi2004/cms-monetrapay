# Deployment Guide — MonetraPay CMS

## Linux Server Setup

### 1. Requirements
- PHP 8.2+
- MySQL 8.0+
- Composer
- Node.js 18+ & npm
- Web server: Nginx or Apache

### 2. Clone & Install

```bash
git clone <repo-url> /var/www/cms-monetrapay
cd /var/www/cms-monetrapay

composer install --no-dev --optimize-autoloader
npm ci && npm run build

cp .env.example .env
php artisan key:generate
```

### 3. Environment

Edit `.env`:
```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=cms_monetrapay
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

# Mail — configured via Admin Panel (Settings → Email)
# Leave blank, mail config is stored in DB

SENTRY_LARAVEL_DSN=  # optional
```

### 4. Database

```bash
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
```

### 5. Permissions

```bash
sudo chown -R www-data:www-data /var/www/cms-monetrapay
sudo chmod -R 755 /var/www/cms-monetrapay
sudo chmod -R 775 /var/www/cms-monetrapay/storage
sudo chmod -R 775 /var/www/cms-monetrapay/bootstrap/cache
```

### 6. Laravel Scheduler (Cron) — REQUIRED

The scheduler handles:
- `blogs:publish-scheduled` — every minute (auto-publish scheduled articles)
- `ads:deactivate-expired` — daily
- `trash:cleanup` — daily
- `cache:warm` — hourly

**Add to crontab** (`sudo crontab -e -u www-data`):

```cron
* * * * * cd /var/www/cms-monetrapay && php artisan schedule:run >> /dev/null 2>&1
```

Verify it's working:
```bash
php artisan schedule:list
```

### 7. Nginx Config

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/cms-monetrapay/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Block storage direct access (except public symlink)
    location ~* ^/storage/app/ {
        deny all;
    }
}
```

### 8. Optimize for Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 9. Queue Worker (optional, for background jobs)

If you want to run queue jobs in background:

```bash
# Install supervisor
sudo apt install supervisor

# Create config: /etc/supervisor/conf.d/monetrapay-worker.conf
```

```ini
[program:monetrapay-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/cms-monetrapay/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/cms-monetrapay/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start monetrapay-worker:*
```

### 10. SSL (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

---

## Quick Deploy Checklist

- [ ] `composer install --no-dev`
- [ ] `npm run build`
- [ ] `.env` configured (APP_ENV=production, DB credentials)
- [ ] `php artisan migrate --force`
- [ ] `php artisan storage:link`
- [ ] Cron configured (`* * * * * php artisan schedule:run`)
- [ ] `php artisan config:cache && route:cache && view:cache`
- [ ] Storage permissions set (`775` for storage/ and bootstrap/cache/)
- [ ] Nginx virtual host active
- [ ] SSL certificate installed
- [ ] Mail settings configured via Admin Panel → Settings → Email
