# Audit: Fitur & Kode Tidak Terpakai — CMS MonetraPay

**Tanggal Audit:** 2025  
**Auditor:** Kiro Investigation Agent (read-only)  
**Scope:** Seluruh kode Laravel di `e:\MUDK Project\project\cms-monetrapay`

---

## Ringkasan Eksekutif

Ditemukan **19 item** yang tidak dipakai atau redundant, dikelompokkan ke dalam 7 kategori. Item paling signifikan adalah **18 themed navbar/footer partial views** yang tidak pernah di-include oleh layout apapun (fitur multi-tema sudah dihapus), middleware `ApiRateLimit` yang terdaftar tapi tidak digunakan di route manapun, dan `AnalyticsService` yang sepenuhnya belum diimplementasikan (setiap method-nya throw `RuntimeException`).

---

## 1. Dead Code / Tidak Dipakai (KONFIRMASI TIDAK TERPAKAI)

### 1.1. Orphan Views — Themed Navbar & Footer Partials (18 file)

**Lokasi:** `resources/views/frontend/layouts/partials/`

File-file berikut **tidak pernah di-include** dari view manapun. Tidak ada satu pun referensi ke nama-nama ini di seluruh codebase (dicari dengan grep menyeluruh):

| File | Status |
|---|---|
| `navbar-classic-business.blade.php` | Tidak dipakai |
| `navbar-creative-agency.blade.php` | Tidak dipakai |
| `navbar-dark-mode-elegant.blade.php` | Tidak dipakai |
| `navbar-ecommerce-style.blade.php` | Tidak dipakai |
| `navbar-landing-page.blade.php` | Tidak dipakai |
| `navbar-magazine-layout.blade.php` | Tidak dipakai |
| `navbar-minimalist-blog.blade.php` | Tidak dipakai |
| `navbar-portfolio-showcase.blade.php` | Tidak dipakai |
| `navbar-tech-startup.blade.php` | Tidak dipakai |
| `footer-classic-business.blade.php` | Tidak dipakai |
| `footer-creative-agency.blade.php` | Tidak dipakai |
| `footer-dark-mode-elegant.blade.php` | Tidak dipakai |
| `footer-ecommerce-style.blade.php` | Tidak dipakai |
| `footer-landing-page.blade.php` | Tidak dipakai |
| `footer-magazine-layout.blade.php` | Tidak dipakai |
| `footer-minimalist-blog.blade.php` | Tidak dipakai |
| `footer-portfolio-showcase.blade.php` | Tidak dipakai |
| `footer-tech-startup.blade.php` | Tidak dipakai |

**Bukti:** `resources/views/frontend/layouts/app.blade.php` hanya melakukan:
```blade
@include('frontend.layouts.partials.navbar')
@include('frontend.layouts.partials.footer')
```
Tidak ada switch/conditional yang memuat varian lainnya. File `resources/views/frontend/theme-layout.blade.php` juga secara eksplisit mencatat bahwa **"Themes feature removed — always use the default layout."**

**Prioritas:** HIGH — aman dihapus.

---

### 1.2. Orphan View — `resources/views/welcome.blade.php`

View bawaan Laravel (`welcome.blade.php`) tidak dirender oleh controller atau route apapun. Route `/` sudah dihandle oleh `HomeController::index()` yang merender `frontend.home`. Tidak ada `view('welcome')` di seluruh codebase.

**Prioritas:** HIGH — aman dihapus.

---

### 1.3. Orphan Views — `resources/views/frontend/errors/` (2 file)

**Lokasi:** `resources/views/frontend/errors/403.blade.php` dan `404.blade.php`

Tidak ada satu pun referensi ke `frontend.errors.403` atau `frontend.errors.404` di seluruh codebase (controller, route, maupun view). Error handling sudah ditangani oleh `resources/views/errors/` (yang di-extend dari `errors.layout`).

**Prioritas:** HIGH — aman dihapus.

---

### 1.4. Orphan View — `resources/views/frontend/partials/menu.blade.php`

Tidak ada referensi ke `frontend.partials.menu` di seluruh codebase. Menu frontend sudah dihandle langsung di dalam `frontend.layouts.partials.navbar`.

**Prioritas:** HIGH — aman dihapus.

---

### 1.5. Orphan View — `resources/views/admin/page-builder/index.blade.php`

Route `admin.page-builder.index` (`GET /admin/page-builder`) terdaftar di `routes/web.php` dan view-nya ada, tetapi **tidak ada link ke halaman ini** dari navigasi admin, dashboard, atau view apapun. Halaman ini tidak dapat diakses melalui UI kecuali mengetik URL secara manual.

**Catatan:** Ini berbeda dari `page-builder.edit` yang masih aktif digunakan via halaman Pages.

**Prioritas:** MEDIUM — route dan view bisa dihapus, atau tambahkan link di navigasi admin.

---

### 1.6. Middleware Tidak Dipakai — `ApiRateLimit`

**Lokasi:** `app/Http/Middleware/ApiRateLimit.php`

Middleware ini terdaftar sebagai alias `api.rate` di `bootstrap/app.php`, tetapi **tidak pernah dipakai** di satu route pun (baik di `routes/web.php`, `routes/api.php`, maupun annotation controller). `routes/api.php` sendiri masih kosong.

**Bukti:** Grep `api.rate` hanya menemukan registrasi di `bootstrap/app.php`, bukan penggunaan.

**Prioritas:** MEDIUM — `bootstrap/app.php` dan file middleware bisa dihapus, atau dipakai ketika API diimplementasikan.

---

### 1.7. Middleware Alias Tidak Dipakai — `role`, `permission`, `role_or_permission`

**Lokasi:** `bootstrap/app.php`

Tiga alias Spatie (`role`, `permission`, `role_or_permission`) terdaftar di `bootstrap/app.php`. Namun, proyek ini menggunakan middleware custom `check.permission` (via `CheckPermission.php`) untuk semua access control. Tidak ada satu pun route yang memanggil `->middleware('role:...')` atau `->middleware('permission:...')`.

**Prioritas:** LOW — tidak berbahaya, tetapi registrasi yang tidak digunakan menambah kebingungan.

---

### 1.8. Artisan Command Tidak Dijadwalkan — `SwitchCacheDriver` & `SentryTest` & `FixStoragePermissions`

| Command | Signature | Terjadwal? | Dipakai? |
|---|---|---|---|
| `SwitchCacheDriver` | `cache:check-redis` | ❌ | Tidak ada referensi |
| `SentryTest` | `sentry:test` | ❌ | Tidak ada referensi |
| `FixStoragePermissions` | `storage:fix-permissions` | ❌ | Tidak ada referensi |

**Analisis:**
- `cache:check-redis` — command developer helper untuk setup Redis. Tidak dijadwalkan di `routes/console.php`.
- `sentry:test` — command testing sekali pakai. Tidak dijadwalkan.
- `storage:fix-permissions` — command one-time setup untuk Windows. Tidak dijadwalkan.

**Catatan:** Ketiga command ini masih berguna sebagai alat developer (`php artisan sentry:test`, `php artisan storage:fix-permissions`), tetapi dapat dihapus jika tidak diperlukan lagi.

**Prioritas:**
- `SwitchCacheDriver` & `FixStoragePermissions`: LOW (berguna sekali-waktu)
- `SentryTest`: LOW (berguna untuk debugging Sentry)

---

### 1.9. `AnalyticsService` — Tidak Diimplementasikan

**Lokasi:** `app/Services/AnalyticsService.php`

Semua method yang melakukan fetch data GA (`fetchSummaryFromApi`, `fetchDailyTrendFromApi`, `fetchTopPagesFromApi`) langsung melempar `RuntimeException` dengan pesan *"Google Analytics Data API reporting is not implemented yet."*

Ini berarti halaman `/admin/analytics` selalu menampilkan state "tidak terkonfigurasi" atau error — tidak ada data real yang pernah ditampilkan.

**Dampak:** Route `admin.analytics.index` ada dan terlindungi permission, tapi fiturnya tidak bekerja.

**Prioritas:** MEDIUM — bukan dead code (ada view dan route), tapi fitur yang belum selesai.

---

## 2. Kode Redundant / Duplikat

### 2.1. Duplikat Route `regenerate-sitemap`

Dua route berbeda menjalankan logika yang **identik persis** (sama-sama memanggil `SitemapService::regenerate()`):

| Route Name | Path | Controller |
|---|---|---|
| `admin.seo.regenerate-sitemap` | `POST /admin/seo/regenerate-sitemap` | `SeoToolController::regenerateSitemap()` |
| `admin.maintenance.regenerate-sitemap` | `POST /admin/maintenance/regenerate-sitemap` | `MaintenanceController::regenerateSitemap()` |

**Bukti:** Kedua method ini identik:
```php
// SeoToolController::regenerateSitemap()
$this->sitemapService->regenerate();
return back()->with('success', 'Sitemap berhasil dibuat ulang.');

// MaintenanceController::regenerateSitemap()  
$this->sitemapService->regenerate();
return back()->with('success', 'Sitemap berhasil dibuat ulang.');
```

Di view admin, hanya `admin.seo.regenerate-sitemap` yang dipakai (`resources/views/admin/seo/index.blade.php`). Route `admin.maintenance.regenerate-sitemap` **tidak dipakai di view manapun**.

**Prioritas:** HIGH — hapus `MaintenanceController::regenerateSitemap()` dan route-nya.

---

### 2.2. Logika GrapesJS Ganda di `PageFrontController`

**Lokasi:** `app/Http/Controllers/Frontend/PageFrontController.php`

Method `show()` sudah memeriksa `!empty($page->gjs_css)` di baris paling atas dan langsung memanggil `showWithGrapesJs()`. Namun, method `showWithPageBuilder()` yang dipanggil jika `use_page_builder` true juga melakukan pemeriksaan `!empty($page->gjs_css)` lagi dan memanggil view GrapesJS yang sama.

Ini adalah **dead branch** — kondisi di dalam `showWithPageBuilder` untuk GrapesJS tidak akan pernah tercapai karena sudah ditangkap lebih dulu oleh kondisi di `show()`.

```php
// show() — dicek di sini dulu
if (!empty($page->gjs_css)) {
    return $this->showWithGrapesJs($page); // ← sudah return di sini
}

// Tidak akan pernah masuk ke showWithPageBuilder() dengan gjs_css terisi
if ($page->use_page_builder) {
    return $this->showWithPageBuilder($page);
}

// showWithPageBuilder() masih mengecek lagi:
if (!empty($page->gjs_css)) { // ← DEAD BRANCH, tidak pernah true
    return view('frontend.page.grapesjs', ...);
}
```

**Prioritas:** MEDIUM — bersihkan dead branch di `showWithPageBuilder()`.

---

## 3. Mungkin Tidak Dipakai (Perlu Konfirmasi Developer)

### 3.1. `frontend/page/layanan.blade.php` dan `tentang-kami.blade.php`

Kedua view ini **digunakan** via `templateMap` di `PageFrontController`, tetapi hanya aktif jika ada `Page` dengan `template` = `'layanan'` atau `'tentang-kami'` di database. Jika halaman-halaman tersebut sudah dimigrasikan ke GrapesJS atau PageBuilder, kedua view ini mungkin tidak lagi dirender.

**Rekomendasi:** Cek di database apakah ada page dengan `template = 'layanan'` atau `template = 'tentang-kami'` yang masih berstatus `published`.

**Prioritas:** LOW — tergantung data di database.

---

### 3.2. `analytics.create`, `analytics.edit`, `analytics.delete` Permissions

Di `RolePermissionSeeder`, modul `analytics` dibuat lengkap dengan 4 operasi: `view`, `create`, `edit`, `delete`. Namun di routes, hanya ada **satu route analytics** yang dilindungi `analytics.view`. Tidak ada route untuk `analytics.create`, `analytics.edit`, atau `analytics.delete`.

Ketiga permission ini ada di database tapi tidak dipakai oleh route atau controller apapun.

**Prioritas:** LOW — tidak merugikan, tapi membingungkan di halaman Role Management.

---

### 3.3. View Composer `frontend.themes.*`

**Lokasi:** `app/Providers/AppServiceProvider.php`

```php
View::composer('frontend.themes.*', FrontendComposer::class);
```

Tidak ada directory `resources/views/frontend/themes/` yang ada. Registrasi ini tidak error (Laravel skip jika tidak ada views yang cocok), tetapi adalah sisa dari fitur multi-tema yang sudah dihapus.

**Prioritas:** LOW — bersihkan dari `AppServiceProvider`.

---

### 3.4. `PageBuilderController::components()` Endpoint

**Lokasi:** `app/Http/Controllers/Admin/PageBuilderController.php`

Route `GET /admin/page-builder/components` terdaftar dan method `components()` ada, tetapi tidak ada view atau JavaScript yang memanggil endpoint ini via AJAX. Ini kemungkinan sisa dari rencana drag-drop builder yang belum selesai diimplementasi.

**Prioritas:** LOW — tidak berbahaya, tapi dead code di controller.

---

## 4. Packages & Dependencies

### Composer Packages (semua terpakai)
Semua package di `composer.json` `require` block dipakai:
- `intervention/image` → `FileManagerController` (crop, resize)
- `mews/purifier` → dipakai di content sanitization
- `pragmarx/google2fa-laravel` → `TwoFactorController`
- `sentry/sentry-laravel` → `SentryContext` middleware + `SentryTest` command
- `spatie/laravel-permission` → seluruh RBAC system
- `spatie/laravel-sitemap` → `SitemapService`

### NPM Packages (semua terpakai)
- `tailwindcss` + `@tailwindcss/vite` → `app.css`
- `laravel-vite-plugin` + `vite` → build pipeline
- `axios` → `resources/js/bootstrap.js`
- `npm-run-all2` → `package.json` scripts `dev:all`

**Tidak ada dependency yang tidak terpakai ditemukan.**

---

## 5. Migrations

Semua migration memiliki tabel yang terkait dengan model yang aktif dipakai. Satu-satunya anomali:

- `2026_10_18_103935_create_themes_table.php` — Tabel `themes` dibuat lalu **langsung dihapus** oleh `2026_10_30_151434_drop_themes_table.php`. Kedua migration ini bisa dibersihkan menjadi no-op pada fresh install, tapi secara teknis tidak berbahaya karena `drop_themes` menggunakan `dropIfExists`.

---

## 6. Seeders

Semua seeder sudah terdaftar di `DatabaseSeeder::run()`. Tidak ada orphan seeder.

---

## 7. Ringkasan Prioritas & Rekomendasi

| # | Item | Lokasi | Prioritas | Aksi |
|---|---|---|---|---|
| 1 | 18 themed navbar/footer partials | `resources/views/frontend/layouts/partials/navbar-*.blade.php`, `footer-*.blade.php` | **HIGH** | Hapus semua |
| 2 | `welcome.blade.php` | `resources/views/welcome.blade.php` | **HIGH** | Hapus |
| 3 | `frontend/errors/` (2 file) | `resources/views/frontend/errors/` | **HIGH** | Hapus folder |
| 4 | `frontend/partials/menu.blade.php` | `resources/views/frontend/partials/menu.blade.php` | **HIGH** | Hapus |
| 5 | Duplikat `regenerateSitemap` di `MaintenanceController` | `app/Http/Controllers/Admin/MaintenanceController.php`, `routes/web.php` | **HIGH** | Hapus method + route |
| 6 | `ApiRateLimit` middleware tidak dipakai | `app/Http/Middleware/ApiRateLimit.php`, `bootstrap/app.php` | **MEDIUM** | Hapus jika API belum ada |
| 7 | `admin.page-builder.index` — view/route tidak terlink | `resources/views/admin/page-builder/index.blade.php` | **MEDIUM** | Tambahkan link navigasi atau hapus |
| 8 | `AnalyticsService` belum diimplementasikan | `app/Services/AnalyticsService.php` | **MEDIUM** | Implementasikan atau tandai sebagai placeholder |
| 9 | Dead branch GrapesJS di `PageFrontController::showWithPageBuilder()` | `app/Http/Controllers/Frontend/PageFrontController.php` | **MEDIUM** | Hapus kondisi redundant |
| 10 | `role`, `permission`, `role_or_permission` alias tidak dipakai | `bootstrap/app.php` | **LOW** | Hapus alias, pertahankan package |
| 11 | 3 Artisan commands tidak dijadwalkan | `app/Console/Commands/SentryTest.php`, `SwitchCacheDriver.php`, `FixStoragePermissions.php` | **LOW** | Pertahankan sebagai developer tools |
| 12 | `analytics.create/edit/delete` permissions tidak ada route | `database/seeders/RolePermissionSeeder.php` | **LOW** | Hapus 3 permission dari seeder atau buat route |
| 13 | `View::composer('frontend.themes.*', ...)` orphan | `app/Providers/AppServiceProvider.php` | **LOW** | Hapus baris ini |
| 14 | `PageBuilderController::components()` dead endpoint | `app/Http/Controllers/Admin/PageBuilderController.php` | **LOW** | Hapus atau implementasikan di JS |

---

## Catatan Penting

1. **Middleware `check.permission` sudah tepat** — sistem permissions proyek ini menggunakan custom middleware `CheckPermission` yang memproteksi semua admin route. Ini adalah desain yang benar.

2. **`AnalyticsService` perlu perhatian khusus** — halaman analytics sudah ada di admin panel dengan permission yang terdefinisi (`analytics.view`), tetapi kontennya tidak pernah menampilkan data nyata karena semua API call melempar exception. Ini berpotensi membingungkan user admin.

3. **GrapesJS vs Legacy Page Builder** — kedua sistem aktif berjalan. GrapesJS adalah yang baru (cek `gjs_css`), legacy builder adalah yang lama (cek `use_page_builder`). Ini bukan dead code tetapi transisi yang belum selesai.

4. **`resources/js/bootstrap.js`** — hanya berisi `import axios`. File ini terpakai karena di-import dari `app.js`.
