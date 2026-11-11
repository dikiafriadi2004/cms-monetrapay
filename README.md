# Laravel CMS

CMS (Content Management System) berbasis Laravel yang siap digunakan untuk berbagai jenis bisnis digital. Dilengkapi visual editor, manajemen konten lengkap, sistem email, dan siap deploy ke VPS dengan Docker.

---

## Fitur Unggulan

### Content Management
- **Blog** — CRUD, publish/draft/schedule, soft delete, bulk action, autosave
- **Pages** — dengan GrapesJS visual editor drag & drop
- **Media Manager** — upload, folder, crop, WebP otomatis
- **Menu Builder** — multi-level dengan drag & drop
- **Categories & Tags**

### Visual Editor
- **GrapesJS** — desain halaman visual pixel-perfect
- **15+ komponen siap pakai** — hero, features, FAQ, testimonial, pricing, gallery, dll
- **Responsive preview** — desktop, tablet, mobile

### Email System
- **5 template email branded** — konfirmasi kontak, notifikasi admin, reply, newsletter, reset password
- **Reply langsung dari CMS** — tanpa perlu buka email client
- **Newsletter blast** — ke semua subscriber dengan template
- **SMTP dikonfigurasi dari Admin Panel** — tidak perlu edit `.env`

### SEO
- Meta tags, Open Graph, Twitter Cards per halaman
- Sitemap XML otomatis
- Robots.txt dapat diedit dari admin
- Schema.org JSON-LD
- RSS Feed otomatis
- Google Analytics GA4 integration

### Keamanan
- RBAC dengan 44 permission granular (4 role: Super Admin, Admin, Editor, Author)
- Two-Factor Authentication (2FA) per user
- Audit log semua perubahan
- CSP, HSTS, security headers otomatis
- Honeypot + rate limiting di semua form publik
- Enkripsi password SMTP at rest

### Lainnya
- Contact form + reply dari CMS
- Newsletter dengan subscriber management
- Ads/banner management
- Maintenance mode
- Analytics dashboard
- Export/Import konten

---

## Requirements

- PHP 8.2+
- MySQL 8.0+ / MariaDB 10.6+
- Node.js 20+
- Composer 2+
- Extension PHP: `pdo_mysql`, `gd`, `intl`, `zip`, `bcmath`, `mbstring`

---

## Install (Local)

```bash
# 1. Clone
git clone <repo> cms
cd cms

# 2. Install dependencies
composer install
npm install
npm run build

# 3. Setup environment
cp .env.example .env
# Edit .env: isi DB_*, APP_URL

# 4. Generate app key
php artisan key:generate

# 5. Install CMS (interaktif)
php artisan cms:install
# Akan tanya nama, email, password admin

# 6. Jalankan server
php artisan serve
```

---

## Install (VPS dengan Docker)

Lihat **[DEPLOY.md](DEPLOY.md)** untuk panduan lengkap.

```bash
# Quick start
cp .env.docker .env
# Edit .env: isi APP_URL, DB_PASSWORD, MAIL_*

docker compose up -d --build

# Install admin (satu kali)
docker compose exec app php artisan cms:install \
  --admin-name="Super Admin" \
  --admin-email="admin@domain.com" \
  --admin-password="Admin@2024!"
```

---

## Dokumentasi

- [INSTALL.md](INSTALL.md) — panduan install lengkap
- [DEPLOY.md](DEPLOY.md) — panduan deploy ke VPS

---

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2
- **Frontend:** Tailwind CSS v4, Alpine.js, Vite
- **Database:** MySQL 8
- **Cache/Queue:** Redis (production) / File (development)
- **Visual Editor:** GrapesJS
- **Rich Text Editor:** TinyMCE
- **Auth:** Spatie Laravel Permission, pragmarx/google2fa
