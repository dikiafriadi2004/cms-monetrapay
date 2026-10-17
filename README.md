# CMS MonetraPay

CMS MonetraPay adalah aplikasi Laravel untuk mengelola konten website MonetraPay: blog, halaman statis, kategori, menu, media, iklan, pengaturan SEO, kontak, komentar, newsletter, user, role/permission, audit log, 2FA, sitemap, robots.txt, RSS feed, dan integrasi Sentry.

## Requirement

- PHP 8.2+
- Composer 2+
- Node.js 22+ dan npm
- SQLite untuk development cepat, atau MySQL/MariaDB untuk environment lokal/production
- Web server production yang mengarah ke folder `public/`

## Instalasi lokal

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
```

Untuk SQLite development:

```bash
touch database/database.sqlite
php artisan migrate --seed
```

Untuk MySQL, ubah `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=comonetra
DB_USERNAME=root
DB_PASSWORD=
```

Lalu jalankan:

```bash
php artisan migrate --seed
```

## Menjalankan aplikasi

```bash
composer run dev
```

Perintah tersebut menjalankan Laravel server, queue listener, dan Vite dev server secara bersamaan.

Alternatif manual:

```bash
php artisan serve
php artisan queue:listen --tries=1
npm run dev
```

## Build asset

```bash
npm run build
```

Hasil build dibuat di `public/build`.

## Quality check

```bash
composer format:test
composer analyse
composer test
npm run build
```

Atau jalankan quality check PHP sekaligus:

```bash
composer quality
```

## Fitur utama

- Admin dashboard
- Blog CRUD, kategori, tag, soft delete, restore, force delete, bulk action, autosave
- Page CRUD dan catch-all public page route
- Menu builder
- Media/file manager dengan folder dan upload
- Ads management
- Settings untuk general, homepage, SEO, email, dan maintenance
- Contact form dan admin reply
- Comment moderation dan anti-spam ringan
- Newsletter subscribe, confirm, unsubscribe, export
- User dan role management berbasis Spatie Permission
- 2FA per user
- Audit log
- Sitemap, robots.txt, RSS feed
- Security headers middleware
- Sentry error tracking opsional

## Akun admin default

Seeder membuat akun admin untuk development. Cek dan ubah data di:

`database/seeders/RolePermissionSeeder.php`

Password default development tidak boleh dipakai di production. Segera ganti setelah login pertama.

## Analytics

Measurement ID Google Analytics dapat disimpan dari Admin -> Settings -> SEO untuk tagging/tracking frontend.

Dashboard analytics tidak lagi menampilkan angka dummy/random. Sampai integrasi Google Analytics Data API dan credentials ditambahkan, halaman analytics akan menampilkan pesan error aman daripada data palsu.

## Scheduler dan queue

Jalankan queue worker di production:

```bash
php artisan queue:work --tries=3 --timeout=90
```

Tambahkan cron scheduler di server production:

```cron
* * * * * cd /path/to/cms-monetrapay && php artisan schedule:run >> /dev/null 2>&1
```

Scheduled command yang terdaftar:

- `ads:deactivate-expired` harian
- `trash:cleanup` harian
- `cache:warm` per jam
- `queue:prune-batches --hours=48` harian
- `queue:flush` harian

## Deployment production

Checklist minimal:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
php artisan migrate --force
php artisan storage:link
php artisan optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Pastikan permission folder berikut writable oleh user web server:

- `storage/`
- `bootstrap/cache/`

Environment production wajib:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-production.example
```

## Troubleshooting

### Error rename di storage/framework/views pada Windows

Jika muncul error `rename(...storage/framework/views...): Access is denied`, biasanya file compiled Blade sedang terkunci oleh proses lain atau antivirus. Jalankan:

```bash
php artisan view:clear
php artisan view:cache
```

Jika masih muncul, stop server lokal terlebih dahulu, hapus isi `storage/framework/views` kecuali `.gitignore`, lalu jalankan ulang `php artisan view:cache`.

### `php artisan schedule:list` gagal koneksi DB

Pastikan DB sesuai `.env` sedang aktif. Jika memakai MySQL lokal, nyalakan MySQL/XAMPP/Laragon dan pastikan database sudah dibuat.
