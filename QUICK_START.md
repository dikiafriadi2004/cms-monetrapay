# 🚀 Quick Start Guide - CMS Monetrapay

## ⚡ Langkah Cepat Mulai

### 1️⃣ Setup (Jalankan sekali saja)

```bash
# Jalankan migration
php artisan migrate

# Seed 10 themes
php artisan db:seed --class=ThemeSeeder

# Clear cache
php artisan optimize:clear

# Restart server
herd restart
```

---

## 🎨 Menggunakan Theme System

### **Akses:** `/admin/themes`

#### **Langkah:**
1. Login ke admin panel
2. Klik menu **"Themes"** di sidebar
3. Lihat 10 theme yang tersedia
4. Klik **"Preview"** untuk lihat tampilan real
5. Klik **"Activate"** untuk aktifkan theme
6. Klik **"Customize"** untuk ubah warna

#### **Customize Theme:**
- Primary Color: Warna utama (tombol, link, header)
- Secondary Color: Warna kedua (hover, accent)
- Klik **"Save Changes"** untuk simpan

---

## 🧱 Menggunakan Page Builder

### **Akses:** `/admin/pages` → Create/Edit Page

#### **Langkah Create Page dengan Builder:**

1. Buka **"Pages"** → **"Tambah Baru"**
2. Isi judul halaman
3. **Toggle ON** switch **"Gunakan Page Builder"**
4. Klik **"Simpan"**
5. Setelah tersimpan, klik link **"Edit Komponen →"**
6. Pilih component yang mau ditambahkan:
   - Hero (banner besar)
   - Text (konten)
   - Contact Form (form kontak)
   - Features (grid fitur)
   - CTA (call-to-action)
   - dll. (15 components tersedia)
7. Isi data component
8. Klik **"Add Component"**
9. Ulangi untuk menambah component lain
10. Buka halaman di frontend untuk lihat hasilnya

#### **Tips:**
- Component akan tampil sesuai urutan yang ditambahkan
- Gunakan Hero di paling atas untuk banner
- Gunakan CTA di bagian bawah untuk konversi
- Mix & match berbagai component sesuai kebutuhan

---

## 📋 Mengatur Menu

### **Akses:** `/admin/menus`

#### **Langkah Edit Menu:**

1. Buka **"Menus"**
2. Pilih menu yang mau diedit (Primary, Footer, Social)
3. Klik **"Edit"**
4. Tambah/edit menu items:
   - Label: Teks yang tampil
   - URL: Link tujuan
   - Parent: Untuk submenu (optional)
   - Order: Urutan tampil
5. Klik **"Save"**
6. Cek di homepage, menu otomatis ter-update

#### **Menu Locations:**
- **Primary Navigation:** Menu di navbar header
- **Footer Menu:** Menu di footer
- **Social Menu:** Social media links

---

## 🎯 Component List (15 Total)

| Component | Fungsi | Use Case |
|-----------|--------|----------|
| **Hero** | Banner besar | Homepage, landing page |
| **Text** | Rich content | Artikel, konten panjang |
| **Contact Form** | Form kontak | Contact page |
| **Features** | Grid fitur | Showcase layanan |
| **CTA** | Call-to-action | Konversi, promosi |
| **Testimonials** | Review customer | Build trust |
| **Team** | Team members | About us |
| **Gallery** | Image grid | Portfolio |
| **Video** | Embed video | Tutorial, promo |
| **Pricing** | Tabel harga | Pricing page |
| **Social** | Social links | Footer, sidebar |
| **Newsletter** | Email signup | Lead generation |
| **FAQ** | Accordion Q&A | Help page |
| **Map** | Google Maps | Location page |
| **Custom HTML** | Raw HTML | Advanced needs |

---

## 🔧 Troubleshooting

### **Cache Issues:**
```bash
php artisan optimize:clear
herd restart
```

### **Theme Tidak Berubah:**
```bash
# Clear view cache
php artisan view:clear

# Clear config cache
php artisan config:clear

# Restart server
herd restart
```

### **Component Tidak Muncul:**
1. Pastikan toggle "Page Builder" ON
2. Pastikan component sudah di-save
3. Clear browser cache (Ctrl+Shift+R)
4. Check di database: `page_components` table

### **Menu Tidak Muncul:**
1. Pastikan menu sudah di-assign ke location
2. Check `menus` dan `menu_items` table di database
3. Clear cache dengan `php artisan cache:clear`

### **Error 500:**
```bash
# Check log
tail -f storage/logs/laravel.log

# Check PHP error log
tail -f /path/to/php/error.log
```

---

## 📱 Quick Commands

```bash
# Clear all cache
php artisan optimize:clear

# Run migration
php artisan migrate

# Seed themes
php artisan db:seed --class=ThemeSeeder

# Create symlink (if images not showing)
php artisan storage:link

# Restart server (Herd)
herd restart

# Restart server (Artisan)
php artisan serve

# Check routes
php artisan route:list | grep theme
php artisan route:list | grep page-builder
```

---

## 🌐 Admin URLs

| Feature | URL |
|---------|-----|
| **Dashboard** | `/admin/dashboard` |
| **Themes** | `/admin/themes` |
| **Theme Customize** | `/admin/themes/{id}/customize` |
| **Pages** | `/admin/pages` |
| **Page Builder** | `/admin/page-builder/{page_id}` |
| **Menus** | `/admin/menus` |
| **Blogs** | `/admin/blogs` |
| **Categories** | `/admin/categories` |
| **Settings** | `/admin/settings` |

---

## 📂 Important Files

### **Config:**
- `.env` - Environment variables
- `config/app.php` - App config
- `config/cache.php` - Cache config

### **Database:**
- `database/migrations/*_create_themes_table.php`
- `database/migrations/*_create_page_components_table.php`
- `database/seeders/ThemeSeeder.php`

### **Models:**
- `app/Models/Theme.php`
- `app/Models/PageComponent.php`
- `app/Models/Page.php`
- `app/Models/Menu.php`

### **Controllers:**
- `app/Http/Controllers/Admin/ThemeController.php`
- `app/Http/Controllers/Admin/PageBuilderController.php`

### **Services:**
- `app/Services/ThemeService.php`
- `app/Services/MenuService.php`

### **Views:**
- `resources/views/admin/themes/*`
- `resources/views/admin/page-builder/*`
- `resources/views/components/builder/*` (15 files)
- `resources/views/frontend/page/builder.blade.php`

---

## ✅ Pre-Flight Checklist

Sebelum deploy production:

- [ ] Run migration: `php artisan migrate`
- [ ] Seed themes: `php artisan db:seed --class=ThemeSeeder`
- [ ] Clear cache: `php artisan optimize:clear`
- [ ] Test theme preview
- [ ] Test page builder components
- [ ] Test dynamic menus
- [ ] Test responsive (mobile/tablet/desktop)
- [ ] Check browser console (no errors)
- [ ] Check Laravel log (no errors)
- [ ] Test form submissions
- [ ] Test file uploads (images)
- [ ] Backup database
- [ ] Set `.env` APP_ENV=production
- [ ] Set `.env` APP_DEBUG=false

---

## 🆘 Need Help?

1. **Read Documentation:**
   - `ENHANCEMENT_REPORT.md` - Technical details
   - `THEME_AND_BUILDER_GUIDE.md` - User guide
   - `TESTING_CHECKLIST.md` - Testing guide

2. **Check Logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. **Clear Everything:**
   ```bash
   php artisan optimize:clear
   composer dump-autoload
   herd restart
   ```

4. **Reinstall (last resort):**
   ```bash
   # Drop all tables
   php artisan migrate:fresh
   
   # Re-seed
   php artisan db:seed
   
   # Clear cache
   php artisan optimize:clear
   ```

---

## 📞 Support

**Branch:** `fix/cms-issues`  
**Version:** 2.0.0  
**Status:** ✅ Production Ready  

---

**Happy Building! 🚀**
