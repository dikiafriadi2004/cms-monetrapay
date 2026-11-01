# Pending Check — CMS MonetraPay
*Diaudit: 2026-11-01 | Branch: `fix/cms-issues` | Last commit: `0e0bc71`*

---

## Ringkasan Eksekutif

CMS secara keseluruhan **sudah dalam kondisi baik**. Dua workflow besar sudah selesai dan APPROVED: `fix-audit-bugs` dan `grapesjs-integration`. GrapesJS visual editor sudah terintegrasi penuh ke admin dan frontend. **Masalah utama yang masih pending**: ada **22 file yang sudah dimodifikasi tapi belum di-commit**, dan beberapa item penting yang belum diselesaikan yakni:

1. **Google Maps di halaman Kontak masih hardcoded** — koordinat `-6.2088,106.8456` tertanam langsung di HTML seeder, tidak mengacu ke setting `maps_lat`/`maps_lng` di DB.
2. **`TentangKamiSeeder.php` ada tapi tidak terdaftar** di `DatabaseSeeder.php` — seeder orphan tanpa dipanggil.
3. **`DefaultPagesSeeder` sudah di-update** untuk pakai raw HTML (GrapesJS content), tapi perubahan ini **belum di-commit**.
4. **Tidak ada file temp `*.php`** selain `artisan` di root project — bersih.
5. **Error log terbaru**: hanya error tinker (`ParseErrorException`) dari sesi development, bukan error produksi aktif.

---

## ✅ Sudah Selesai dan Berjalan

### Workflow yang Sudah APPROVED
- **`fix-audit-bugs`** — `review.json` verdict: `APPROVED`. Semua 8 bug kritis (makeColumns, SortableJS drag, validasi PageBuilderController, RedirectResponse import, preview view, migrasi flat→row, duplicate contact slug, double MenuService) sudah diperbaiki di commit `8cab352`.
- **`grapesjs-integration`** — `review.json` verdict: `APPROVED`. GrapesJS visual editor sudah terintegrasi: migrasi kolom `gjs_css`/`gjs_components`/`gjs_styles`, controller (grapesjsEdit/Load/Save), routes, blade views admin dan frontend, `PageFrontController` routing logic.

### Integrasi GrapesJS → Halaman
- `admin/pages/{id}/edit` sudah punya tombol "🎨 Visual Editor" (menggantikan toggle page builder lama) yang link ke `admin.pages.grapesjs`.
- `admin/pages/create` sudah ada info panel Visual Editor.
- Toggle `use_page_builder` sudah **dihapus** dari form edit — sesuai permintaan user (opsi 1: tidak pakai page builder lagi).
- `PageFrontController` memeriksa `!empty($page->gjs_css)` → render `frontend.page.grapesjs` — logic sudah benar.

### Seeder yang Terdaftar di DatabaseSeeder
`DatabaseSeeder.php` sudah memanggil (urutan benar):
1. `RolePermissionSeeder`
2. `SettingsSeeder` — termasuk `maps_lat`, `maps_lng`, `maps_zoom`, `office_address`
3. `CategorySeeder`
4. `MenuSeeder`
5. `PageSeeder`
6. `DefaultPagesSeeder` — berisi halaman kontak, tentang-kami, layanan dengan HTML GrapesJS
7. `BlogSeeder`
8. `AdsSeeder`

### Routes
- Semua route GrapesJS terdaftar: `admin.pages.grapesjs`, `admin.pages.grapesjs.load`, `admin.pages.grapesjs.save`
- Catch-all frontend route `/{slug}` ada di paling bawah — urutan benar

### Error Log
- Error terakhir di `laravel.log`: tinker `ParseErrorException` (bukan error produksi) dan `Not enough arguments (missing: "config")` — keduanya dari sesi development manual, **tidak ada error runtime produksi baru**.

---

## ⚠️ Masih Pending / Bermasalah

### 1. Google Maps Kontak — Koordinat Hardcoded di Seeder ❌
**File:** `database/seeders/DefaultPagesSeeder.php`, method `kontakHtml()`, baris ~159  
**Masalah:** iframe Google Maps menggunakan koordinat hardcoded:
```html
<iframe src="https://maps.google.com/maps?q=-6.2088,106.8456&z=15&output=embed" ...>
```
Padahal `SettingsSeeder.php` sudah mendefinisikan `maps_lat`, `maps_lng`, `maps_zoom` sebagai setting di DB. Seeder tidak mengacu ke nilai setting tersebut — sehingga jika admin mengubah koordinat di `admin/settings/general`, perubahan itu **tidak akan tercermin** di halaman kontak (karena konten halaman kontak adalah static HTML yang tersimpan di kolom `content`, bukan dynamic template).

**Dampak:** Fitur "input koordinat di admin panel → map langsung tampil" yang diminta user belum berfungsi end-to-end. Admin bisa update setting `maps_lat`/`maps_lng`, tapi halaman `/kontak` tidak membacanya.

**Rekomendasi:** Dua opsi —  
- **(A, lebih sederhana):** Di `DefaultPagesSeeder::kontakHtml()`, ganti iframe statis dengan placeholder `{{ $mapsEmbed }}` dan inject nilai dari Setting saat seeder berjalan — tapi ini tetap static karena tersimpan di kolom `content`.
- **(B, lebih tepat):** Pindahkan section Google Maps ke sebuah Blade partial yang membaca `Setting::get('maps_lat')` dan `Setting::get('maps_lng')` secara dinamis, lalu `PageFrontController` inject nilai itu saat render.

### 2. TentangKamiSeeder — Orphan, Tidak Dipanggil ❌
**File:** `database/seeders/TentangKamiSeeder.php` — ada (untracked di git)  
**File:** `database/seeders/DatabaseSeeder.php` — tidak memanggil `TentangKamiSeeder::class`  
**Masalah:** `TentangKamiSeeder` membuat 7 komponen page builder (format lama `PageComponent`) untuk halaman tentang-kami. Tapi `DatabaseSeeder` tidak mendaftarkannya. Kalau dijalankan `php artisan db:seed`, seeder ini tidak akan dipanggil.  
**Catatan penting:** `DefaultPagesSeeder` sudah menyimpan konten tentang-kami sebagai raw HTML GrapesJS (di kolom `content`), sehingga halaman tentang-kami **sudah ada isinya dari DefaultPagesSeeder**. `TentangKamiSeeder` yang pakai format `PageComponent` lama sudah tidak relevan dengan arsitektur GrapesJS saat ini.  
**Rekomendasi:** Hapus file `TentangKamiSeeder.php` — fungsinya sudah digantikan oleh HTML yang digenerate di `DefaultPagesSeeder::tentangHtml()`.

### 3. 22 File Dimodifikasi, Belum Di-commit ❌
**Evidence:** `git status` menunjukkan 21 file `modified` + 7 untracked files, tidak ada yang staged.  
**File modifikasi terbesar (berdasarkan `git diff --stat`):**
- `resources/views/admin/menus/edit.blade.php` (960 baris berubah)
- `database/seeders/DefaultPagesSeeder.php` (553 baris berubah — ini update konten GrapesJS)
- `resources/views/frontend/page/tentang-kami.blade.php` (317 baris berubah)
- `resources/views/frontend/home.blade.php` (103 baris berubah)
- `resources/views/admin/page-builder/grapesjs.blade.php` (41 baris berubah)
- `resources/views/admin/pages/edit.blade.php` (70 baris berubah — sudah benar: toggle dihapus, Visual Editor button ditambah)
- Dan 16 file lainnya

**Dampak:** Semua pekerjaan yang dilakukan sejak commit `0e0bc71` belum tersimpan ke git. Jika terjadi masalah, rollback tidak bisa dilakukan dengan bersih.

### 4. `create.blade.php` — Masih Ada `togglePageBuilder()` JS yang Tidak Dipakai ⚠️
**File:** `resources/views/admin/pages/create.blade.php`, sekitar baris 238  
**Masalah:** JS function `togglePageBuilder()` dan listener untuk `use_page_builder` checkbox masih ada di file, padahal checkbox sudah dihapus dari `edit.blade.php` dan page builder toggle tidak lagi dipakai. Ini dead code di `create.blade.php`.  
**Dampak:** Tidak crash, tapi membingungkan dan inconsistent.

### 5. Logo & Favicon Belum Diupload ⚠️
**Evidence:** Setting `logo` = null, `favicon` = null di DB (dari audit sebelumnya).  
**Dampak:** Navbar, footer, tab browser semua pakai fallback `/assets/images/logo.png`.

### 6. Karakter Rusak di edit.blade.php ⚠️
**Evidence dari git diff:** Di unstaged changes `edit.blade.php`, emoji 🎨 di heading "Visual Editor" tampil sebagai `≡ƒÄ¿` (encoding garbled). Di file aktual (dibaca dengan read tool) tampil sebagai `🎨` — kemungkinan issue encoding CRLF/UTF-8 saat git diff di Windows. Perlu diverifikasi di browser bahwa tampil benar.

---

## ❌ Belum Ada / Tidak Berfungsi

### Google Maps Dinamis (permintaan user message #7)
User meminta map yang membaca koordinat dari input admin, bukan hardcoded. Saat ini:
- Setting `maps_lat`/`maps_lng`/`maps_zoom` **ada di DB** (dari `SettingsSeeder`)
- UI input setting general **sudah ada** (`resources/views/admin/settings/partials/general.blade.php` — dimodifikasi, belum commit)
- Tapi halaman kontak menggunakan HTML statis yang tidak membaca setting → loop tidak tersambung

---

## 📋 Rekomendasi Langkah Selanjutnya

### Prioritas 1 — Commit Perubahan yang Ada
```
git add app/Http/Controllers/Admin/SettingController.php
git add app/Http/Controllers/Controller.php
git add app/Http/Controllers/Frontend/PageFrontController.php
git add app/Http/Middleware/SecurityHeaders.php
git add database/seeders/DefaultPagesSeeder.php
git add database/seeders/SettingsSeeder.php
git add resources/views/admin/layouts/app.blade.php
git add resources/views/admin/menus/edit.blade.php
git add resources/views/admin/page-builder/edit.blade.php
git add resources/views/admin/page-builder/grapesjs.blade.php
git add resources/views/admin/pages/create.blade.php
git add resources/views/admin/pages/edit.blade.php
git add resources/views/admin/pages/index.blade.php
git add resources/views/admin/settings/partials/general.blade.php
git add resources/views/auth/login.blade.php
git add resources/views/frontend/home.blade.php
git add resources/views/frontend/layouts/partials/footer.blade.php
git add resources/views/frontend/page/grapesjs.blade.php
git add resources/views/frontend/page/tentang-kami.blade.php
git add resources/css/app.css
git add public/sitemap.xml
git add resources/views/components/builder/
git commit -m "feat: integrate GrapesJS to pages edit — remove page builder toggle, add visual editor button"
```

### Prioritas 2 — Sambungkan Google Maps ke Setting
Ubah `PageFrontController@show` agar inject `maps_lat`, `maps_lng`, `maps_zoom` ke view, lalu di `frontend.page.grapesjs` atau template kontak, render iframe dengan koordinat dari setting. Atau, lebih sederhana: di `DefaultPagesSeeder::kontakHtml()`, ganti `q=-6.2088,106.8456` dengan `q={{ Setting::get("maps_lat","general") }},{{ Setting::get("maps_lng","general") }}` — tapi ini hanya efektif saat re-seed, bukan live.

**Solusi terbaik:** Buat partial blade `resources/views/frontend/partials/google-map.blade.php` yang membaca `Setting::get('maps_lat')` secara dinamis, dan `PageFrontController` inject ke view kontak.

### Prioritas 3 — Hapus TentangKamiSeeder.php (orphan)
File ini sudah tidak relevan karena `DefaultPagesSeeder` sudah handle konten tentang-kami sebagai GrapesJS HTML. Hapus file `database/seeders/TentangKamiSeeder.php`.

### Prioritas 4 — Bersihkan Dead Code di create.blade.php
Hapus function `togglePageBuilder()` dan referensi `use_page_builder` yang tersisa di `resources/views/admin/pages/create.blade.php`.

### Prioritas 5 — Upload Logo & Favicon
Masuk ke `admin/settings/general` dan upload logo/favicon perusahaan.

---

## 📊 Status File .agents/tasks

| Task | Status | Keterangan |
|------|--------|-----------|
| `fix-cms-issues/` | ✅ APPROVED | Selesai Oktober 2026 |
| `page-builder-rebuild/` | ✅ Selesai | Rebuild page builder row-column |
| `fix-audit-bugs/` | ✅ APPROVED | review.json verdict APPROVED |
| `grapesjs-integration/` | ✅ APPROVED | review.json verdict APPROVED |
| `audit-report.md` | ✅ Referensi | Audit Nov 2026 |
| `pending-check.md` | 📋 Ini | Check terbaru |

---

*Evidence utama: `git status`, `git log --oneline -10`, `git diff --stat HEAD`, `database/seeders/DatabaseSeeder.php`, `database/seeders/TentangKamiSeeder.php`, `database/seeders/DefaultPagesSeeder.php`, `resources/views/admin/pages/edit.blade.php`, `.agents/tasks/fix-audit-bugs/review.json`, `.agents/tasks/grapesjs-integration/review.json`, `storage/logs/laravel.log` (tail 80)*
