# STATUS AUDIT CMS MONETRAPAY
*Audit dilakukan: 2026-11-01 | Laravel 12.62.0 | PHP 8.2 | DB: MySQL `comonetra`*

---

## RINGKASAN EKSEKUTIF

CMS secara keseluruhan **sudah berfungsi** — login, routing, database, migrations semua OK. Ada **dua bug kritis** yang perlu diperbaiki sebelum Page Builder dapat dipakai dengan aman: (1) `RedirectResponse` tidak di-import di `PageBuilderController`, dan (2) validasi `save()` menolak komponen dengan konten kosong. Auto-refresh yang dilaporkan sebelumnya **bukan loop tak terbatas** — itu bawaan Laravel Boost/Vite HMR di environment Herd yang sudah teratasi.

---

## ✅ Sudah Selesai & Berfungsi

### Database & Migrasi
- Semua **34 migration** sudah berjalan (batch 1–4), tidak ada yang pending.
- Tabel utama lengkap: `users`, `blogs`, `pages`, `categories`, `menus`, `menu_items`, `media`, `ads`, `settings`, `contact_submissions`, `audit_logs`, `newsletter_subscribers`, `comments`, `newsletter_campaigns`, `page_components`.
- `drop_themes_table` (batch 4) sudah berjalan — refactor dari themes ke page builder selesai.

### Konten & Settings Database
- Super Admin tersedia: `admin@monetrapay.com` dengan role `Super Admin` dan **32 permissions**.
- Settings general sudah ada: `site_name`, `tagline`, `contact_email`, `contact_phone`, `navbar_cta_text`, `navbar_cta_url`, `whatsapp_number`.
- Settings SEO lengkap: `meta_title_default`, `meta_description_default`, `robots_txt`.
- Logo & favicon: **null** (belum diupload, ada fallback ke `/assets/images/logo.png`).
- **3 menu** aktif: `header` (5 items), `footer` (5 items), `social`.

### Routes & Admin Panel
- **118 admin routes** terdaftar, semua controller ada.
- Semua modul teregister: Dashboard, Blogs, Pages, Categories, Comments, Contacts, Newsletter, Ads, Menu Builder, File Manager, SEO, Settings, Users, Roles, Analytics, Audit Log, Export/Import, Page Builder.
- `php artisan route:list` berhasil tanpa error.

### Frontend Halaman
- `/` → `HomeController@index` → `frontend.home` — view ada, konten lengkap.
- `/blog` → `BlogFrontController@index` — ada 3+ blog published.
- `/blog/{slug}` → `BlogFrontController@show` — route ada.
- `/tentang-kami` → `PageFrontController@show` → template `tentang-kami.blade.php` — ada, page builder aktif dengan 7 komponen (about-hero, features, visi-misi, values, timeline, team, cta).
- `/layanan` → template `layanan.blade.php` — ada, page builder aktif.
- `/kontak` → template `kontak.blade.php` — ada, page builder aktif.
- `/faq` → `PageFrontController@show` → `frontend.page.show` — published, fallback template.
- `/kebijakan-privasi` → published, non-builder template.
- `/syarat-ketentuan` → published, non-builder template.

### Assets & Build
- `public/build/manifest.json` valid, berisi `app.css`, `admin.js`, `app.js`.
- Vite build output tersedia di `public/build/assets/`.

### Page Builder — Arsitektur
- Edit view (`admin/page-builder/edit.blade.php`, 838 baris) sudah ada, Row→Column→Widget format sudah diimplementasikan.
- Delete modal confirmation sudah ada (terpasang `deleteModal` di Alpine.js state, modal di view).
- SortableJS sudah terintegrasi (`cdn.jsdelivr.net/npm/sortablejs@1.15.2`) — drag dari sidebar ke canvas pakai clone mode.
- Frontend render (`frontend/page/builder.blade.php`) sudah support format Row baru dengan `grid-cols` per layout.
- **19 builder component views** tersedia di `resources/views/components/builder/`.
- Semua page yang pakai builder (kontak, tentang-kami, layanan) **sudah ada komponen** di DB (format lama flat, bukan row).

### Middleware & Security
- `CheckPermission`, `SecurityHeaders`, `SentryContext`, `FrontendCacheHeaders`, `CheckMaintenanceMode` semua ada.
- Rate limiting di login, contact form, newsletter.
- CSRF protection aktif.

---

## ⚠️ Ada tapi Perlu Diperbaiki

### 1. `PageBuilderController` — `RedirectResponse` Tidak Di-Import
**File:** `app/Http/Controllers/Admin/PageBuilderController.php`, baris 90  
**Masalah:** Return type `JsonResponse|RedirectResponse` tapi `use Illuminate\Http\RedirectResponse;` tidak ada di use statements. Meski PHP modern bisa toleran karena class ada di autoload, ini non-standar dan bisa menyebabkan PHP error di strict mode atau saat reflection.  
**Perbaikan:** Tambahkan `use Illuminate\Http\RedirectResponse;` di bagian use statements.

### 2. `PageBuilderController::save()` — Validasi Terlalu Ketat untuk Widget Baru
**File:** `app/Http/Controllers/Admin/PageBuilderController.php`, baris ~100  
**Masalah:** Validasi `'components.*.content' => 'required|array'` akan menolak jika ada widget dengan `content: {}` (objek kosong). Log error `The components.7.content field is required` tanggal 2026-10-31 15:23:31 mengkonfirmasi ini terjadi. Widget baru yang baru ditambahkan dari sidebar memiliki `content: {}` kosong.  
**Perbaikan:** Ubah validasi menjadi `'components.*.content' => 'nullable|array'` atau `'required|array|min:0'`.

### 3. Page Builder — Komponen Lama (Flat Format) Tidak Render Sebagai Row
**File:** `resources/views/frontend/page/builder.blade.php`  
**Masalah:** Semua halaman yang sudah menggunakan page builder (tentang-kami, layanan, kontak) menyimpan komponen dalam **format flat** (bukan row). Frontend `builder.blade.php` punya fallback `@else` untuk format lama, tapi editor (`edit.blade.php`) load ulang dan tampilkan sebagai row baru saat edit. Artinya jika user mengedit dan simpan dari editor baru, format akan berubah dan legacy data bisa tumpang tindih.  
**Perbaikan:** Jalankan migrasi data untuk convert existing flat components ke format row, ATAU pastikan save endpoint tidak hapus komponen lama sebelum ada validasi data.

### 4. Logo & Favicon Belum Diupload
**Evidence:** `Setting::get('logo', 'general')` → `null`, `Setting::get('favicon', 'general')` → `null`  
**Dampak:** Navbar, footer, dan login page semua pakai fallback ke `/assets/images/logo.png` (file statis). Jika file itu tidak ada, tampilan logo akan kosong.  
**Perbaikan:** Upload logo dan favicon melalui `admin/settings/general`.

### 5. FrontendComposer vs. app.blade.php — Double MenuService Call
**File:** `resources/views/frontend/layouts/app.blade.php` baris ~106, `app/View/Composers/FrontendComposer.php`  
**Masalah:** `app.blade.php` langsung memanggil `app(\App\Services\MenuService::class)->getMenuByLocation('header')` secara manual di `@php` block, **padahal** `FrontendComposer` sudah di-register sebagai view composer untuk menyediakan `$headerMenu`. Ini menyebabkan double query (atau double cache read), dan variabel yang disediakan composer (`$headerMenu`) tidak digunakan — navbar menggunakan `$primaryMenu` lokal.  
**Dampak:** Tidak crash, tapi redundant dan bisa menimbulkan kebingungan.

### 6. Vite HMR / Auto-Refresh di Herd (Masalah Asal)
**Evidence:** Log `[2026-10-31 14:57:15] Access is denied` saat rename `bootstrap/cache/services.php` dan error `Target class [view] does not exist` yang muncul bersamaan.  
**Root cause:** Saat `php artisan config:cache` atau `optimize` dijalankan di Windows/Herd, ada **Windows file lock** yang mencegah rename cache file sementara Herd/PHP sedang merespons request. Ini menyebabkan container Laravel corrupt sementara, tampil blank/redirect loop sampai cache beres.  
**Perbaikan:** Jangan jalankan `artisan optimize` atau `config:cache` saat server sedang aktif melayani request di Herd. Gunakan `php artisan config:clear` terlebih dahulu, lalu `optimize:clear` sebelum `optimize`.

---

## ❌ Belum Ada / Error

### 1. Admin Panel — Route `preview` di PageBuilderController Tidak Ada View
**File:** `app/Http/Controllers/Admin/PageBuilderController.php`, method `preview()`  
**Masalah:** Method `preview()` me-return `view('admin.page-builder.preview', ...)` tapi file `resources/views/admin/page-builder/preview.blade.php` **tidak ada** (hanya `edit.blade.php` dan `index.blade.php`).  
**Dampak:** Mengakses `admin/pages/{page}/builder/preview` akan throw `InvalidArgumentException: View [admin.page-builder.preview] not found`.

### 2. Page Builder — `_makeColumns()` Bug: Layout Object Tidak Punya `.cols` Property
**File:** `resources/views/admin/page-builder/edit.blade.php`, fungsi `_makeColumns()` (~baris 630)  
**Masalah kritis:**
```js
_makeColumns(layoutId) {
    const layout = this.layouts.find(l => l.id === layoutId) || {cols:[100]};
    return layout.cols.map(() => ({ id: uid('col'), widgets: [] }));
}
```
Objek `layouts` dalam `pageBuilder()` didefinisikan sebagai:
```js
layouts: [
    {id: '100',       label: '1 Kol'},
    {id: '50/50',     label: '50/50'},
    ...
]
```
**Tidak ada property `cols`!** Sehingga `layout.cols` selalu `undefined`, `undefined.map()` akan throw `TypeError`. Ini berarti **`addRow()` dan `setRowLayout()` selalu crash** saat mencoba membuat kolom.  
**Dampak:** Canvas tidak bisa menambahkan row baru (tombol "Tambah Row Pertama" tidak berfungsi karena crash JS).

### 3. Sidebar SortableJS — Widget Sidebar Items Bukan Direct Children
**File:** `resources/views/admin/page-builder/edit.blade.php`  
**Masalah:** Sortable sidebar dikonfigurasi dengan `pull: 'clone'` pada container `#sidebar-widgets`, tapi widget items dibungkus dalam `@foreach` category divs — mereka **bukan direct children** dari `#sidebar-widgets`. SortableJS clone mode hanya bekerja pada direct children, sehingga drag dari sidebar ke canvas tidak menghasilkan event `onAdd` yang benar.  
**Dampak:** Drag dari sidebar ke canvas tidak menghasilkan widget baru (drop zone tidak menerima clone dengan `data-widget-type`).

### 4. Halaman `kontak` — Terdapat Dua Slug Overlap
**Evidence DB:**
```
{"slug": "contact", "use_page_builder": true, "status": "published"}
{"slug": "kontak",  "use_page_builder": true, "status": "published"}
```
Ada dua halaman terpisah: `contact` dan `kontak`. Route `POST /kontak` menuju `ContactController@submit` (form submission), sedangkan `GET /kontak` meload halaman page. Route `GET /contact` akan meload halaman lain yang mungkin duplikat.  
**Rekomendasi:** Hapus atau gabungkan halaman `contact` (slug lama), gunakan `kontak` saja.

### 5. Newsletter & Comments — Tidak Ada Data
**Evidence:** `App\Models\Comment::count()` = 0, `App\Models\NewsletterSubscriber::count()` = 0  
**Ini bukan error** tapi perlu dicatat sebagai "belum ada aktivitas user".

---

## 📋 Rekomendasi Prioritas

### PRIORITAS 1 — Fix Bug Kritis (Blocker)

**1a. Fix `_makeColumns()` di Page Builder** *(Bug paling kritis — membuat semua row creation crash)*  
Di `edit.blade.php`, tambahkan property `cols` ke setiap layout object:
```js
layouts: [
    {id: '100',       label: '1 Kol',  cols: 1},
    {id: '50/50',     label: '50/50',  cols: 2},
    {id: '30/70',     label: '30/70',  cols: 2},
    {id: '70/30',     label: '70/30',  cols: 2},
    {id: '33/33/33',  label: '3 Kol',  cols: 3},
    {id: '25/75',     label: '25/75',  cols: 2},
],
```
Kemudian ubah `_makeColumns()`:
```js
_makeColumns(layoutId) {
    const layout = this.layouts.find(l => l.id === layoutId) || {cols: 1};
    const count = typeof layout.cols === 'number' ? layout.cols : 1;
    return Array.from({length: count}, () => ({ id: uid('col'), widgets: [] }));
}
```

**1b. Fix Sidebar SortableJS Clone** *(Drag dari sidebar ke canvas tidak berfungsi)*  
Ganti Sortable clone-based approach untuk sidebar dengan `dragstart` native HTML5:
- Setiap `.sidebar-widget-item` sudah punya `draggable="true"` — gunakan native dragstart/dragover/drop events.
- Di `initSortable()`, listen `dragstart` pada `.sidebar-widget-item`, set `dataTransfer.setData('widget-type', type)`.
- Di setiap `.widget-dropzone`, listen `dragover` dan `drop`, baca `dataTransfer.getData('widget-type')` dan panggil `addWidget()`.

**1c. Fix Validasi `save()` di `PageBuilderController`**  
Ubah baris validasi:
```php
'components.*.content' => 'required|array',
```
Menjadi:
```php
'components.*.content' => 'present|array',
```
(`present` memastikan key ada tapi tidak mengharuskan isi non-kosong).

**1d. Tambahkan `use Illuminate\Http\RedirectResponse;` ke `PageBuilderController`**

### PRIORITAS 2 — Buat View yang Hilang

**2a. Buat `resources/views/admin/page-builder/preview.blade.php`**  
View ini dibutuhkan oleh `PageBuilderController::preview()`. Buat view sederhana yang render komponen page builder menggunakan `frontend.page.builder` partial atau iframe ke frontend.

### PRIORITAS 3 — Data Migration Page Builder

**3a. Convert existing flat components ke format Row**  
Jalankan script migration data untuk semua `page_components` yang `component_type != 'row'`: bungkus setiap komponen flat dalam satu row 100% layout. Ini memastikan konsistensi antara data lama dan editor baru.

### PRIORITAS 4 — Polish & Settings

**4a. Upload Logo & Favicon** melalui `admin/settings/general`  
**4b. Hapus page `contact`** (slug duplikat), keep `kontak`  
**4c. Refactor `app.blade.php`** — gunakan `$headerMenu` dari FrontendComposer, hapus `@php` block yang memanggil MenuService langsung  
**4d. Atur file/folder permissions** di `bootstrap/cache/` untuk Windows/Herd agar tidak ada "Access is denied" saat cache rebuild

---

## 📊 Inventaris File Penting

| File | Status |
|------|--------|
| `app/Http/Controllers/Admin/PageBuilderController.php` | ⚠️ Missing `use RedirectResponse` |
| `app/Http/Controllers/Admin/ExportImportController.php` | ✅ OK (authorize via base Controller) |
| `resources/views/admin/page-builder/edit.blade.php` | ❌ `_makeColumns()` bug, SortableJS sidebar bug |
| `resources/views/admin/page-builder/preview.blade.php` | ❌ TIDAK ADA |
| `resources/views/frontend/page/builder.blade.php` | ✅ Support Row format & legacy fallback |
| `resources/views/frontend/home.blade.php` | ✅ OK |
| `resources/views/frontend/layouts/partials/navbar.blade.php` | ✅ OK (pakai `$primaryMenu`) |
| `resources/views/frontend/layouts/partials/footer.blade.php` | ✅ OK |
| `resources/views/frontend/page/tentang-kami.blade.php` | ✅ OK |
| `resources/views/components/builder/*.blade.php` | ✅ 19 komponen ada |
| `public/build/manifest.json` | ✅ Valid |
| `storage/logs/laravel.log` | ⚠️ Ada error historis, tidak ada error aktif baru |
