# 🚀 Panduan Instalasi MonetraCMS

## Prasyarat Server (VPS/Hosting)

| Kebutuhan | Versi Minimum |
|-----------|--------------|
| PHP | 8.2+ |
| MySQL / MariaDB | 8.0+ / 10.6+ |
| Composer | 2.x |
| Node.js | 18+ |
| NPM | 9+ |
| Git | 2.x |

Extension PHP yang dibutuhkan: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `gd`, `zip`, `fileinfo`

---

## Langkah Instalasi

### 1. Clone / Upload Project

```bash
git clone <repository-url> /var/www/cms-monetrapay
cd /var/www/cms-monetrapay
```

### 2. Install Dependencies

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

### 3. Konfigurasi Environment

```bash
cp .env.example .env
php artisan key:generate
```

Edit file `.env` dengan konfigurasi Anda:

```env
APP_NAME="Nama Website Anda"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domainanda.com

DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=password_database

# Kredensial admin pertama (WAJIB diisi sebelum seeding)
ADMIN_EMAIL=admin@domainanda.com
ADMIN_PASSWORD=PasswordKuat123!@#
ADMIN_NAME="Nama Admin"

# Email (untuk notifikasi kontak, newsletter, dll)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=app_password_gmail
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@domainanda.com
MAIL_FROM_NAME="Nama Website"
```

### 4. Setup Database

```bash
# Jalankan migrasi
php artisan migrate --force

# Jalankan seeder (mengisi semua data awal)
php artisan db:seed --force
```

Seeder akan membuat:
- ✅ Roles & permissions (Super Admin, Admin, Editor, Author)
- ✅ Akun admin dengan email & password dari `.env`
- ✅ Semua pengaturan site (nama, kontak, social media)
- ✅ 8 kategori blog
- ✅ 10 tema tampilan
- ✅ 3 menu navigasi (header, footer, social)
- ✅ Halaman statis (Kebijakan Privasi, Syarat & Ketentuan, FAQ)
- ✅ Halaman utama dengan Page Builder (Kontak, Layanan, Tentang Kami)
- ✅ 5 artikel blog contoh

### 5. Storage & Permissions

```bash
php artisan storage:link
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 6. Optimasi Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 7. Konfigurasi Web Server

**Nginx:**
```nginx
server {
    listen 80;
    server_name domainanda.com www.domainanda.com;
    root /var/www/cms-monetrapay/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

**Apache (.htaccess sudah ada di folder `public/`).**

### 8. Jadwalkan Task (Cron Job)

Tambahkan ke crontab server:
```bash
* * * * * cd /var/www/cms-monetrapay && php artisan schedule:run >> /dev/null 2>&1
```

---

## Konfigurasi Setelah Instalasi

### Login Admin
- URL: `https://domainanda.com/admin`
- Email: sesuai `ADMIN_EMAIL` di `.env`
- Password: sesuai `ADMIN_PASSWORD` di `.env`

### Yang Perlu Dikonfigurasi di Admin Panel

1. **Settings → Umum**
   - Nama website, tagline, logo, favicon
   - Email kontak, nomor telepon
   - Teks tombol CTA navbar

2. **Settings → Social Media**
   - Link Facebook, Instagram, Twitter, YouTube, TikTok

3. **Settings → SEO**
   - Meta title default, meta description default
   - Google Analytics ID (jika ada)

4. **Settings → Mail**
   - Konfigurasi SMTP untuk email kontak dan newsletter

5. **Themes**
   - Pilih dan aktifkan tema yang diinginkan

6. **Menu**
   - Edit menu navigasi header, footer, dan social

7. **Pages → Edit setiap halaman**
   - Sesuaikan konten Kontak, Layanan, Tentang Kami via Page Builder

---

## Reset & Re-seed (Jika Perlu Mulai Ulang)

```bash
php artisan migrate:fresh --seed --force
```

⚠️ **PERHATIAN:** Perintah ini akan menghapus SEMUA data. Gunakan hanya di instalasi baru.

---

## Troubleshooting

### Error 500 setelah instalasi
```bash
php artisan config:clear
php artisan cache:clear
chmod -R 775 storage
```

### Gambar tidak muncul
```bash
php artisan storage:link
```

### Menu tidak tampil
```bash
php artisan db:seed --class=MenuSeeder
php artisan cache:clear
```

### Tema tidak berubah
```bash
php artisan cache:clear
php artisan view:clear
```

---

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Super Admin | `ADMIN_EMAIL` dari .env | `ADMIN_PASSWORD` dari .env |

**Segera ganti password setelah login pertama!**
