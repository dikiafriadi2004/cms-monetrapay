# Testing Checklist - CMS Monetrapay

## ✅ Pre-Testing Setup
- [ ] Jalankan `php artisan migrate` untuk memastikan semua tabel ter-update
- [ ] Jalankan `php artisan db:seed --class=ThemeSeeder` (jika belum)
- [ ] Jalankan `php artisan optimize:clear`
- [ ] Restart server: `herd restart` atau `php artisan serve`

## 🎨 Theme System Testing

### 1. Theme Management (Admin)
- [ ] Buka `/admin/themes` - harus muncul 10 tema
- [ ] Klik "Preview" pada salah satu tema
  - Preview harus muncul dalam iframe
  - Tampilan harus real (bukan mockup HTML)
  - Navigasi, footer, konten harus terlihat
- [ ] Klik "Customize" pada tema aktif
  - Form customization harus muncul
  - Ubah warna primary/secondary
  - Klik "Save Changes"
  - Pergi ke homepage, cek apakah warna berubah
- [ ] Klik "Activate" pada tema lain
  - Status tema harus berubah
  - Homepage harus berubah tampilannya

### 2. Theme Preview Quality
- [ ] Preview harus menampilkan:
  - Navbar dengan logo dan menu
  - Hero section
  - Content area
  - Footer
- [ ] Kualitas tampilan harus jelas (tidak blur/pixelated)
- [ ] Scrollable jika konten panjang

### 3. Logo Size (Frontend)
- [ ] Buka homepage (frontend)
- [ ] Logo navbar harus:
  - Tinggi: ~40px (mobile), ~48px (desktop)
  - Maksimal lebar: 200px
  - Tidak terlalu kecil/besar
  - Ada hover effect (opacity 80%)

## 🧱 Page Builder Testing

### 1. Create Page with Builder
- [ ] Buka `/admin/pages/create`
- [ ] Toggle "Use Page Builder" (jika ada toggle button)
- [ ] Atau edit field `use_page_builder` = 1 manual di database
- [ ] Klik "Save"

### 2. Add Components
- [ ] Buka `/admin/page-builder/{page_id}`
- [ ] Test menambah komponen:
  - [ ] Hero Component (title, subtitle, image, button)
  - [ ] Text Component (rich content)
  - [ ] Contact Form Component
  - [ ] Features Component (3-4 features)
  - [ ] CTA Component (call-to-action)
  - [ ] Testimonials Component (3+ testimonials)
  - [ ] Team Component (team members)
  - [ ] Gallery Component (4+ images)
  - [ ] Video Component (YouTube/embed)
  - [ ] Pricing Component (pricing plans)
  - [ ] Social Media Component (social links)
  - [ ] Newsletter Component (email signup)
  - [ ] FAQ Component (questions & answers)
  - [ ] Map Component (Google Maps)
  - [ ] Custom HTML Component (raw HTML)

### 3. View Built Page
- [ ] Buka page di frontend (e.g., `/page/slug-page`)
- [ ] Semua komponen harus muncul sesuai urutan
- [ ] Styling harus konsisten dengan tema aktif
- [ ] Responsif di mobile/tablet/desktop

## 📋 Menu System Testing

### 1. Menu Management (Admin)
- [ ] Buka `/admin/menus`
- [ ] Cek apakah menu sudah ada (Primary, Footer, Social)
- [ ] Klik "Edit" pada menu
- [ ] Tambah/edit menu items
- [ ] Simpan perubahan

### 2. Frontend Menu Display
- [ ] Buka homepage
- [ ] Navbar harus menampilkan menu dari database (Primary Navigation)
- [ ] Footer harus menampilkan menu dari database (Footer Menu)
- [ ] Social links harus muncul (jika ada Social Menu)
- [ ] Klik menu items - harus navigate dengan benar

### 3. Nested Menu (Dropdown)
- [ ] Buat nested menu (menu dengan parent_id)
- [ ] Di frontend, hover pada parent menu
- [ ] Dropdown submenu harus muncul
- [ ] Klik submenu harus navigate dengan benar

## 🔧 Additional Features Testing

### 1. Export/Import System
- [ ] Buka `/admin/export-import`
- [ ] Export blogs ke JSON
- [ ] Export pages ke JSON
- [ ] Import kembali JSON (test restore)

### 2. Notification System
- [ ] Simpan data di admin (blog/page/category)
- [ ] Toast notification harus muncul (success/error)
- [ ] Animasi smooth (fade in/out)

### 3. API Rate Limiting
- [ ] Test endpoint API (jika ada)
- [ ] Coba request berulang kali cepat
- [ ] Harus kena rate limit (429 error) setelah batas tercapai

### 4. Error Handling
- [ ] Test form validation errors
- [ ] Error message harus jelas dan user-friendly
- [ ] Menggunakan RespondsWithError trait

## 🚀 Performance Testing

- [ ] Page load time < 3 detik
- [ ] No console errors di browser (F12)
- [ ] No PHP errors di log (`storage/logs/laravel.log`)
- [ ] Database queries optimized (gunakan Laravel Debugbar jika perlu)

## 📱 Responsive Testing

Test di berbagai device:
- [ ] Mobile (375px - 767px)
- [ ] Tablet (768px - 1023px)
- [ ] Desktop (1024px+)
- [ ] Large Desktop (1920px+)

Cek:
- [ ] Layout tidak pecah
- [ ] Menu mobile (hamburger) berfungsi
- [ ] Images responsive
- [ ] Text readable

## 🔒 Security Testing

- [ ] Login sebagai user biasa (bukan admin)
- [ ] Coba akses `/admin/*` - harus redirect atau 403
- [ ] Test permission system (roles & permissions)
- [ ] CSRF token berfungsi pada forms
- [ ] File upload validation (hanya image untuk gallery)

## ✨ Final Checks

- [ ] Semua migration berhasil tanpa error
- [ ] Seeder berhasil (10 themes ter-insert)
- [ ] Cache cleared
- [ ] Server restarted
- [ ] Git branch `fix/cms-issues` ter-update
- [ ] Dokumentasi lengkap:
  - [ ] ENHANCEMENT_REPORT.md
  - [ ] THEME_AND_BUILDER_GUIDE.md
  - [ ] TESTING_CHECKLIST.md (file ini)

---

## 🐛 Bug Report Template

Jika menemukan bug saat testing, catat dengan format:

```
**Bug**: [Judul singkat]
**Steps to Reproduce**:
1. ...
2. ...
3. ...

**Expected Result**: [Apa yang seharusnya terjadi]
**Actual Result**: [Apa yang benar-benar terjadi]
**Error Message**: [Copy paste error jika ada]
**Screenshot**: [Jika perlu]
```

---

## 📞 Contact untuk Support

Jika menemukan masalah:
1. Cek file log: `storage/logs/laravel.log`
2. Cek browser console (F12)
3. Jalankan `php artisan optimize:clear`
4. Restart server: `herd restart`

Jika masih error, laporkan dengan format Bug Report di atas.

---

**Status Branch**: `fix/cms-issues`
**Last Updated**: 2026-10-17
**Total Commits**: 10
**Features**: Theme System (10 themes) + Page Builder (15 components) + Dynamic Menus
