# 🚀 Panduan Instalasi MonetraCMS

Pilih metode instalasi sesuai lingkungan kamu:

- [A. Laragon (Windows) — Paling mudah](#a-laragon-windows--paling-mudah)
- [B. XAMPP (Windows/Mac)](#b-xampp-windowsmac)
- [C. php artisan serve (semua OS)](#c-php-artisan-serve-semua-os)
- [D. VPS dengan Docker](#d-vps-dengan-docker)

---

## ⚠️ Peringatan Paling Penting: `APP_URL` harus sesuai

**Ini penyebab nomor satu tampilan hancur (CSS tidak ter-load).**

CMS otomatis menggunakan `https://` untuk semua asset (CSS, JS) **hanya jika `APP_URL` dimulai dengan `https://`**. Kalau tidak cocok, browser memblokir asset dan tampilan hancur.

| Situasi | APP_URL yang benar |
|---------|-------------------|
| Local `php artisan serve` | `http://localhost:8000` |
| Laragon / Herd (HTTP) | `http://namaproject.test` |
| Laragon / Herd (HTTPS) | `https://namaproject.test` |
| VPS — SSL **belum** aktif | `http://namadomain.com` |
| VPS — SSL **sudah** aktif | `https://namadomain.com` |

**Cara ganti setelah SSL aktif di VPS:**
```bash
# Edit .env
APP_URL=https://namadomain.com

# Lalu clear cache
php artisan config:clear && php artisan cache:clear
```

---

| Kebutuhan | Versi Minimum |
|-----------|--------------|
| PHP | 8.2+ |
| MySQL / MariaDB | 8.0+ / 10.6+ |
| Composer | 2.x |
| Node.js | 18+ |
| NPM | 9+ |

**PHP Extensions yang dibutuhkan:**
`pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`, `gd`, `zip`, `fileinfo`

> Di Laragon dan XAMPP semua extension ini sudah aktif secara default.

---

## A. Laragon (Windows) — Paling mudah

Laragon adalah cara tercepat install project Laravel di Windows.
Download di [laragon.org](https://laragon.org/download/) (pilih Full).

### 1. Letakkan project

Salin folder project ke `C:\laragon\www\cms-monetrapay`
atau clone langsung:

```bash
cd C:\laragon\www
git clone <repository-url> cms-monetrapay
cd cms-monetrapay
```

### 2. Buat database

Buka Laragon → klik **Database** → buat database baru bernama `cms_monetrapay`.

### 3. Install dependencies PHP

Buka terminal Laragon (klik kanan di tray → Terminal):
```bash
cd C:\laragon\www\cms-monetrapay
composer install
```

### 4. Setup environment

```bash
copy .env.example .env
php artisan key:generate
```

Buka `.env`, ubah bagian database:
```env
APP_URL=http://cms-monetrapay.test

DB_DATABASE=cms_monetrapay
DB_USERNAME=root
DB_PASSWORD=
```

> Laragon otomatis membuat virtual host `http://cms-monetrapay.test`.
> Jika nama folder berbeda, sesuaikan `APP_URL`.

### 5. Install CMS

```bash
php artisan cms:install
```

Ikuti pertanyaan yang muncul (nama admin, email, password).
Command ini otomatis **build asset CSS/JS**, migrate, seed, dan optimasi.

### 6. Akses

Buka browser: `http://cms-monetrapay.test`
Admin panel: `http://cms-monetrapay.test/admin`

---

## B. XAMPP (Windows/Mac)

### 1. Letakkan project

Salin folder project ke:
- **Windows:** `C:\xampp\htdocs\cms-monetrapay`
- **Mac:** `/Applications/XAMPP/xamppfiles/htdocs/cms-monetrapay`

### 2. Buat database

Buka `http://localhost/phpmyadmin` → buat database baru: `cms_monetrapay`.

### 3. Install dependencies PHP

Buka terminal / command prompt:
```bash
# Windows
cd C:\xampp\htdocs\cms-monetrapay
composer install

# Mac
cd /Applications/XAMPP/xamppfiles/htdocs/cms-monetrapay
composer install
```

### 4. Setup environment

```bash
# Windows
copy .env.example .env

# Mac/Linux
cp .env.example .env
```

Lalu generate app key:
```bash
php artisan key:generate
```

Buka `.env`, ubah bagian ini:
```env
APP_URL=http://localhost/cms-monetrapay/public

DB_DATABASE=cms_monetrapay
DB_USERNAME=root
DB_PASSWORD=
```

> ⚠️ **Penting untuk XAMPP:** `APP_URL` harus menyertakan path `/cms-monetrapay/public`
> karena XAMPP tidak menggunakan virtual host.

### 5. Install CMS

```bash
php artisan cms:install
```

### 6. Akses

Buka browser: `http://localhost/cms-monetrapay/public`
Admin panel: `http://localhost/cms-monetrapay/public/admin`

---

## C. php artisan serve (semua OS)

Ini cara paling universal — tidak butuh Laragon, XAMPP, atau web server apapun.
Cocok untuk development di Mac, Linux, maupun Windows.

### 1. Clone project

```bash
git clone <repository-url> cms-monetrapay
cd cms-monetrapay
```

### 2. Install dependencies PHP

```bash
composer install
```

### 3. Setup environment

```bash
cp .env.example .env
php artisan key:generate
```

Buka `.env`, ubah bagian database saja:
```env
# APP_URL sudah default ke http://localhost:8000, tidak perlu diubah

DB_DATABASE=cms_monetrapay
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

> Pastikan MySQL sudah berjalan dan database `cms_monetrapay` sudah dibuat.

### 4. Install CMS

```bash
php artisan cms:install
```

Command ini akan secara otomatis:
- ✅ Build asset CSS/JS (npm install + npm run build)
- ✅ Jalankan migrasi database
- ✅ Jalankan seeder (data awal)
- ✅ Buat storage link
- ✅ Optimasi cache

### 5. Jalankan server

```bash
php artisan serve
```

### 6. Akses

Buka browser: `http://localhost:8000`
Admin panel: `http://localhost:8000/admin`

---

## D. VPS dengan Docker

### Yang dibutuhkan
- VPS Linux (Ubuntu 22.04+ direkomendasikan)
- Docker + Docker Compose terinstall
- Domain yang sudah pointing ke IP VPS

### 1. Clone project

```bash
git clone <repository-url> /var/www/cms
cd /var/www/cms
```

### 2. Setup environment

```bash
cp .env.docker .env
nano .env
```

**Wajib diisi:**
```env
APP_URL=https://yourdomain.com
APP_KEY=                          # generate di langkah berikutnya

DB_PASSWORD=GantiPasswordKuat!
DB_ROOT_PASSWORD=GantiRootPassword!

MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=apppassword16char
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### 3. Generate App Key

```bash
docker run --rm -v $(pwd):/app -w /app php:8.2-cli php artisan key:generate --show
# Copy hasilnya ke APP_KEY= di .env
```

### 4. Build dan start

```bash
docker compose up -d --build

# Pantau log
docker compose logs -f app
# Tunggu sampai muncul: "CMS is ready!"
```

### 5. Install admin

```bash
docker compose exec app php artisan cms:install \
  --admin-name="Super Admin" \
  --admin-email="admin@yourdomain.com" \
  --admin-password="Admin@2024!"
```

Selesai! Akses `http://yourdomain.com/admin`

### 6. Aktifkan HTTPS (setelah SSL siap)

Setelah domain dan SSL sudah aktif (via Cloudflare, Let's Encrypt, dll):

```bash
# Edit .env — ganti http ke https
APP_URL=https://yourdomain.com

# Restart container agar perubahan terbaca
docker compose restart app
```

Sekarang akses `https://yourdomain.com/admin`.

> 📖 Lihat [DEPLOY.md](DEPLOY.md) untuk panduan lengkap termasuk SSL, Nginx, dan multi-app setup.

---

## Konfigurasi Setelah Instalasi

Login ke admin panel lalu lakukan setup berikut:

1. **Settings → Umum** — nama website, tagline, logo, favicon
2. **Settings → Social Media** — link Facebook, Instagram, dll
3. **Settings → SEO** — meta title default, Google Analytics ID
4. **Settings → Mail** — konfigurasi SMTP untuk email & newsletter
5. **Menu** — edit navigasi header dan footer
6. **Pages** — sesuaikan konten Beranda, Layanan, Tentang Kami

---

## Akun Default

| Role | Email | Password |
|------|-------|----------|
| Super Admin | Sesuai yang diisi saat `cms:install` | Sesuai yang diisi saat `cms:install` |

**Segera ganti password setelah login pertama!**

---

## Troubleshooting

### Tampilan/CSS hancur (tidak ada style sama sekali)

Ini terjadi karena asset frontend belum di-build. Jalankan:

```bash
npm install
npm run build
```

Lalu refresh browser. Jika masih hancur, cek juga:
- `APP_URL` di `.env` sudah sesuai dengan URL yang kamu buka di browser
- Tidak ada error di console browser (tekan F12 → tab Console)

### Error 500 setelah instalasi

```bash
php artisan config:clear
php artisan cache:clear
chmod -R 775 storage bootstrap/cache
```

### Gambar tidak muncul

```bash
php artisan storage:link
```

### Halaman tidak ditemukan (404) di XAMPP

XAMPP membutuhkan `mod_rewrite` aktif. Pastikan `.htaccess` di folder `public/` ada.
Jika tidak, aktifkan `mod_rewrite` di `httpd.conf` XAMPP.

### Menu tidak tampil

```bash
php artisan db:seed --class=MenuSeeder
php artisan cache:clear
```

### Mau install ulang dari awal

```bash
php artisan migrate:fresh --seed --force
```

⚠️ **Ini akan menghapus SEMUA data.** Gunakan hanya di instalasi baru.
