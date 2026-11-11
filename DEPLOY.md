# Deploy ke VPS dengan Docker

## Yang Dibutuhkan
- VPS Linux (Ubuntu 22.04+ direkomendasikan)
- Docker + Docker Compose terinstall di VPS
- Domain yang sudah pointing ke IP VPS (via Cloudflare direkomendasikan)

---

## Setup Domain (Wajib Dilakukan Dulu)

### Gunakan Cloudflare (Rekomendasi)
Cloudflare gratis dan otomatis handle SSL/HTTPS.

1. Daftar di [cloudflare.com](https://cloudflare.com)
2. Tambah domain kamu
3. Di menu **DNS** → tambah record:
   ```
   Type: A | Name: @   | Content: IP-VPS | Proxy: ON
   Type: A | Name: www | Content: IP-VPS | Proxy: ON
   ```
4. Ganti nameserver domain ke nameserver Cloudflare
5. Selesai — SSL otomatis aktif dalam beberapa menit

---

## Install di VPS

### Step 1: Install Docker
```bash
curl -fsSL https://get.docker.com | sh
apt install docker-compose-plugin -y
```

### Step 2: Clone Project
```bash
git clone <repo-url> /var/www/cms
cd /var/www/cms
```

### Step 3: Setup Environment
```bash
cp .env.docker .env
nano .env
```

**Yang WAJIB diisi di `.env`:**
```
APP_URL=https://yourdomain.com    ← DOMAIN kamu di sini
APP_KEY=                          ← generate di step berikutnya

DB_DATABASE=cms_db
DB_USERNAME=cms_user
DB_PASSWORD=GantiPasswordKuat!
DB_ROOT_PASSWORD=GantiRootPassword!

MAIL_USERNAME=email@gmail.com
MAIL_PASSWORD=apppassword16char
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

> **Ganti domain?** Cukup ubah `APP_URL` di `.env` lalu restart:
> ```bash
> docker compose restart app
> ```

### Step 4: Generate App Key
```bash
docker run --rm -v $(pwd):/app -w /app php:8.2-cli php artisan key:generate --show
# Copy hasilnya ke APP_KEY= di .env
```

### Step 5: Build dan Start
```bash
docker compose up -d --build

# Pantau progress
docker compose logs -f app
# Tunggu sampai muncul: "CMS is ready!"
```

### Step 6: Install Admin (Satu Kali)
```bash
docker compose exec app php artisan cms:install \
  --admin-name="Super Admin" \
  --admin-email="admin@yourdomain.com" \
  --admin-password="Admin@2024!"
```

Selesai! Akses `https://yourdomain.com/admin`

---

## Update Project

```bash
git pull
docker compose up -d --build
```

---

## Perintah Berguna

```bash
# Cek status
docker compose ps

# Lihat log
docker compose logs -f app

# Restart
docker compose restart app

# Masuk ke container
docker compose exec app sh

# Backup database
docker compose exec db mysqldump \
  -u root -p${DB_ROOT_PASSWORD} ${DB_DATABASE} > backup_$(date +%Y%m%d).sql
```

---

## Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Site tidak bisa diakses | Cek firewall VPS: port 80 harus terbuka |
| Database error | Pastikan `DB_PASSWORD` di `.env` sama dengan `MYSQL_PASSWORD` |
| Storage tidak bisa write | `docker compose exec app chmod -R 775 storage` |
| Email tidak terkirim | Admin → Settings → Email → isi SMTP → Test |
| Ganti domain | Edit `APP_URL` di `.env` → `docker compose restart app` |
