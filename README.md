<div align="center">

# 🌐 MonetraCMS

**Content Management System berbasis Laravel untuk bisnis payment digital**

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-v4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?style=flat-square&logo=docker&logoColor=white)](https://docker.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

[Demo](#) · [Dokumentasi](#dokumentasi) · [Lapor Bug](issues)

</div>

---

## 📋 Tentang Project

MonetraCMS adalah platform manajemen konten berbasis Laravel yang dirancang khusus untuk bisnis **pulsa, PPOB, top-up game, dan e-money**. Dilengkapi visual editor drag & drop, sistem email terintegrasi, manajemen role & permission granular, dan siap deploy ke VPS dengan Docker.

---

## ✨ Fitur

<table>
<tr>
<td width="50%">

### 📝 Manajemen Konten
- **Blog** — CRUD, publish/draft/schedule, soft delete, bulk action
- **Halaman** — GrapesJS visual editor drag & drop
- **Media Manager** — upload, folder, crop, auto WebP
- **Menu Builder** — multi-level drag & drop
- **Kategori & Tag**

### 🎨 Visual Editor (GrapesJS)
- Desain halaman pixel-perfect
- 15+ komponen siap pakai (Hero, Features, FAQ, Pricing, dll)
- Preview responsive — Desktop / Tablet / Mobile
- Simpan langsung ke database

### 📧 Email System
- 5 template email branded
- **Reply langsung dari CMS** tanpa buka email client
- Newsletter blast ke subscriber
- SMTP dikonfigurasi dari Admin Panel
- Auto-reply konfirmasi kontak

</td>
<td width="50%">

### 🔍 SEO
- Meta tags, Open Graph, Twitter Cards per halaman
- Sitemap XML auto-generate
- Schema.org JSON-LD structured data
- RSS Feed otomatis
- Google Analytics GA4 terintegrasi
- Robots.txt dapat diedit dari admin

### 🔐 Keamanan
- RBAC 44 permission granular (Super Admin, Admin, Editor, Author)
- Two-Factor Authentication (2FA) TOTP
- Audit log semua perubahan
- CSP, HSTS, X-Frame-Options otomatis
- Honeypot + rate limiting semua form
- Password SMTP dienkripsi di database

### ⚙️ Lainnya
- Contact form + reply dari CMS
- Newsletter subscriber management
- Ads/banner management
- Maintenance mode toggle
- Analytics dashboard (lokal + GA4)
- Export/Import konten JSON

</td>
</tr>
</table>



## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 12, PHP 8.2 |
| Frontend | Tailwind CSS v4, Alpine.js, Vite |
| Database | MySQL 8.0 |
| Cache/Queue | Redis (production), File (development) |
| Visual Editor | GrapesJS 0.21 |
| Rich Text Editor | TinyMCE 6 |
| Auth & RBAC | Spatie Laravel Permission |
| 2FA | pragmarx/google2fa-laravel |
| Image Processing | Intervention Image v3 |
| Container | Docker + Nginx Proxy Manager |

---

## 📦 Requirements

| Kebutuhan | Versi Minimum |
|-----------|--------------|
| PHP | 8.2+ |
| MySQL / MariaDB | 8.0+ / 10.6+ |
| Node.js | 20+ |
| Composer | 2+ |
| PHP Extensions | `pdo_mysql`, `gd`, `intl`, `zip`, `bcmath`, `mbstring` |

---

## 🚀 Quick Start

### Local Development

```bash
# 1. Clone repository
git clone https://github.com/username/cms-monetrapay.git
cd cms-monetrapay

# 2. Install dependencies
composer install
npm install && npm run build

# 3. Setup environment
cp .env.example .env
# Edit .env — isi DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD, APP_URL

# 4. Generate app key
php artisan key:generate

# 5. Install (interaktif — tanya nama/email/password admin)
php artisan cms:install

# 6. Jalankan server
php artisan serve
```

Akses: `http://localhost:8000` | Admin: `http://localhost:8000/admin`

---

### VPS dengan Docker

```bash
# 1. Clone di VPS
git clone https://github.com/username/cms-monetrapay.git /var/www/cms
cd /var/www/cms

# 2. Setup environment
cp .env.docker .env
nano .env  # Isi APP_URL, DB_PASSWORD, MAIL_*

# 3. Generate app key
docker run --rm -v $(pwd):/app -w /app php:8.2-cli php artisan key:generate --show
# Paste hasilnya ke APP_KEY= di .env

# 4. Build dan start
docker compose up -d --build

# 5. Install admin (satu kali)
docker compose exec app php artisan cms:install \
  --admin-name="Super Admin" \
  --admin-email="admin@monetrapay.co.id" \
  --admin-password="Admin@2024!"
```

Akses: `https://yourdomain.com` | Admin: `https://yourdomain.com/admin`

> 📖 Lihat [DEPLOY.md](DEPLOY.md) untuk panduan lengkap termasuk SSL dan multi-app setup.

---

## 👤 Role & Permission

| Role | Akses |
|------|-------|
| **Super Admin** | Semua fitur (44 permission) |
| **Admin** | Semua kecuali Settings sistem & Role management |
| **Editor** | Blog, Halaman, Kategori, File, Newsletter (view) |
| **Author** | Blog (view & create) |

---

## 📁 Struktur Project

```
cms-monetrapay/
├── app/
│   ├── Console/Commands/     # cms:install, PublishScheduledBlogs, dll
│   ├── Http/Controllers/
│   │   ├── Admin/            # 20 controller admin
│   │   └── Frontend/         # Controller halaman publik
│   ├── Models/               # Eloquent models
│   └── Services/             # MailConfigService, AnalyticsService, dll
├── database/
│   ├── migrations/           # 40+ migration
│   └── seeders/              # RolePermission, Settings, Pages, dll
├── docker/                   # Nginx, PHP, MySQL, Supervisor config
├── resources/
│   ├── views/
│   │   ├── admin/            # Admin panel views
│   │   ├── emails/           # 5 template email
│   │   └── frontend/         # Halaman publik
│   └── js/ css/              # Assets
├── routes/web.php
├── Dockerfile
├── docker-compose.yml
├── INSTALL.md
└── DEPLOY.md
```

---

## 📚 Dokumentasi

| File | Isi |
|------|-----|
| [INSTALL.md](INSTALL.md) | Panduan install lengkap (local) |
| [DEPLOY.md](DEPLOY.md) | Panduan deploy ke VPS dengan Docker |

---

## 🤝 Kontribusi

1. Fork repository
2. Buat branch fitur: `git checkout -b feature/nama-fitur`
3. Commit: `git commit -m 'feat: tambah fitur X'`
4. Push: `git push origin feature/nama-fitur`
5. Buat Pull Request

---

## 📄 Lisensi

Project ini dilisensikan di bawah [MIT License](LICENSE).

---

<div align="center">

**Dibuat dengan ❤️ menggunakan Laravel**

</div>
