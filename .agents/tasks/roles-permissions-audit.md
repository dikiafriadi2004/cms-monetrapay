# Audit Roles & Permissions — CMS Monetrapay

**Tanggal audit:** investigasi statis (read-only)
**Package:** `spatie/laravel-permission ^6.25`
**Middleware kustom:** `app/Http/Middleware/CheckPermission.php` (`check.permission:{slug}`)

---

## Ringkasan Eksekutif

Sistem menggunakan **32 permission** terdefinisi (8 modul × 4 operasi) yang di-seed lewat `RolePermissionSeeder`. Dari audit route `routes/web.php` ditemukan **8 fitur/module baru yang tidak memiliki permission sendiri** — mereka dipinjam ke `setting.*` padahal secara semantik bukan bagian dari "setting". Tidak ada fitur yang sama sekali tanpa perlindungan permission; masalahnya adalah *pengelompokan yang tidak tepat*, bukan celah keamanan terbuka.

**Temuan kritis:**
- Newsletter, Newsletter Campaigns, Contacts, Audit Log, SEO Tools, Maintenance, Export/Import, dan Page Builder semuanya dijaga dengan permission `setting.*` — padahal itu permission untuk pengaturan sistem, bukan modul konten tersebut.
- Empat role yang ada tidak mencerminkan pembedaan akses yang cukup granular untuk modul-modul tersebut.

---

## 1. Daftar Semua Fitur/Module Admin (dari `routes/web.php`)

| No | Module | Route Prefix | Route Names | Permission Dipakai |
|----|--------|-------------|-------------|-------------------|
| 1 | Dashboard | `/admin/dashboard` | `admin.dashboard` | *(tanpa permission — auth saja)* |
| 2 | Profile | `/admin/profile` | `admin.profile.*` | *(tanpa permission — auth saja)* |
| 3 | 2FA Setup | `/admin/two-factor` | `admin.two-factor.*` | *(tanpa permission — auth saja)* |
| 4 | Blog | `/admin/blogs` | `admin.blogs.*` | `blog.view/create/edit/delete` ✅ |
| 5 | Halaman (Page) | `/admin/pages` | `admin.pages.*` | `page.view/create/edit/delete` ✅ |
| 6 | Kategori | `/admin/categories` | `admin.categories.*` | `category.view/create/edit/delete` ✅ |
| 7 | Menu Builder | `/admin/menus` | `admin.menus.*` | `menu.view/create/edit/delete` ✅ |
| 8 | File Manager | `/admin/files` | `admin.files.*` | `file.view/create/edit/delete` ✅ |
| 9 | Iklan (Ads) | `/admin/ads` | `admin.ads.*` | `ad.view/create/edit/delete` ✅ |
| 10 | Settings | `/admin/settings` | `admin.settings.*` | `setting.view/edit` ✅ |
| 11 | Analytics | `/admin/analytics` | `admin.analytics.*` | `analytics.view` ✅ |
| 12 | Users | `/admin/users` | `admin.users.*` | **`setting.view/create/edit/delete`** ⚠️ |
| 13 | Roles | `/admin/roles` | `admin.roles.*` | **`setting.view/create/edit/delete`** ⚠️ |
| 14 | SEO Tools | `/admin/seo` | `admin.seo.*` | **`setting.view/edit`** ⚠️ |
| 15 | Maintenance | `/admin/maintenance` | `admin.maintenance.*` | **`setting.edit`** ⚠️ |
| 16 | Contacts | `/admin/contacts` | `admin.contacts.*` | **`setting.view/edit/delete`** ⚠️ |
| 17 | Audit Log | `/admin/audit-logs` | `admin.audit-logs.*` | **`setting.view`** ⚠️ |
| 18 | Newsletter Subscribers | `/admin/newsletter` | `admin.newsletter.*` | **`setting.view/delete`** ⚠️ |
| 19 | Newsletter Campaigns | `/admin/newsletter/campaigns` | `admin.newsletter.campaigns.*` | **`setting.view`** ⚠️ |
| 20 | Komentar | `/admin/comments` | `admin.comments.*` | `blog.view/edit/delete` ✅ (wajar, bagian konten blog) |
| 21 | Export/Import Tools | `/admin/tools` | `admin.tools.*` | **`setting.edit`** ⚠️ |
| 22 | Page Builder | `/admin/page-builder` | `admin.page-builder.*` | `page.edit` ✅ (wajar, bagian page) |
| 23 | Notifications | `/admin/notifications` | `admin.notifications.index` | *(tanpa permission — auth saja)* |

---

## 2. Daftar Permission yang Sudah Terdaftar

**Sumber:** `database/seeders/RolePermissionSeeder.php`

Total: **32 permission** (8 modul × 4 operasi: `view`, `create`, `edit`, `delete`)

| Module | Permissions |
|--------|------------|
| `blog` | `blog.view`, `blog.create`, `blog.edit`, `blog.delete` |
| `page` | `page.view`, `page.create`, `page.edit`, `page.delete` |
| `category` | `category.view`, `category.create`, `category.edit`, `category.delete` |
| `menu` | `menu.view`, `menu.create`, `menu.edit`, `menu.delete` |
| `file` | `file.view`, `file.create`, `file.edit`, `file.delete` |
| `ad` | `ad.view`, `ad.create`, `ad.edit`, `ad.delete` |
| `setting` | `setting.view`, `setting.create`, `setting.edit`, `setting.delete` |
| `analytics` | `analytics.view`, `analytics.create`, `analytics.edit`, `analytics.delete` |

### Role yang Terdaftar

| Role | Permissions |
|------|------------|
| **Super Admin** | Semua 32 permission |
| **Admin** | Semua kecuali `setting.*` (28 permission) |
| **Editor** | `blog.view`, `blog.edit`, `page.view`, `page.edit`, `category.view`, `category.edit`, `file.view`, `file.edit` |
| **Author** | `blog.view`, `blog.create` |

---

## 3. Gap Analysis — Fitur yang Permission-nya Tidak Tepat/Belum Ada

### 3a. Modul yang Dijaga dengan Permission Modul Lain (Salah Kelompok)

Modul-modul berikut menggunakan `setting.*` padahal bukan bagian dari pengaturan sistem. Ini membuat role **Admin** (yang tidak punya `setting.*`) tidak bisa mengakses fitur-fitur ini meski secara logika seharusnya bisa.

| Module | Permission Saat Ini | Masalah |
|--------|---------------------|---------|
| **User Management** | `setting.view/create/edit/delete` | User management ≠ system setting. Admin seharusnya bisa kelola user tapi tidak bisa. |
| **Role Management** | `setting.view/create/edit/delete` | Role management ≠ system setting. Hanya Super Admin yang bisa kelola role. |
| **SEO Tools** | `setting.view/edit` | SEO adalah fitur konten, bukan setting. Editor tidak bisa akses. |
| **Maintenance** | `setting.edit` | Maintenance (clear cache, regenerate sitemap) logisnya butuh permission terpisah. |
| **Contacts** | `setting.view/edit/delete` | Kontak masuk dari frontend, lebih tepat di modul komunikasi. |
| **Audit Log** | `setting.view` | Audit log adalah fitur admin-level, bukan setting. |
| **Newsletter Subscribers** | `setting.view/delete` | Newsletter adalah modul komunikasi tersendiri. |
| **Newsletter Campaigns** | `setting.view` | Sama — seluruh alur blast campaign dijaga `setting.view`, tidak ada granularitas. |
| **Export/Import Tools** | `setting.edit` | Tools export/import adalah operasi data, bukan pengubahan setting. |

### 3b. Modul yang Sama Sekali Tidak Punya Permission Check

| Module | Status | Catatan |
|--------|--------|---------|
| **Dashboard** | Tidak ada permission check | Wajar — dashboard hanya tampilkan statistik, semua user login boleh lihat |
| **Profile** | Tidak ada permission check | Wajar — setiap user berhak edit profil sendiri |
| **2FA Setup** | Tidak ada permission check | Wajar — setiap user berhak aktifkan 2FA miliknya |
| **Notifications AJAX** | Tidak ada permission check | Perlu dikaji — endpoint mengembalikan jumlah pending comments dan unread contacts, akses harus dibatasi per role |

### 3c. Permission yang Terdaftar tapi Tidak Digunakan

| Permission | Status |
|------------|--------|
| `analytics.create` | Tidak ada route yang menggunakannya |
| `analytics.edit` | Tidak ada route yang menggunakannya |
| `analytics.delete` | Tidak ada route yang menggunakannya |
| `setting.create` | Tidak ada route settings yang menggunakannya (dipakai untuk `users.create` dan `roles.create` — salah modul) |

---

## 4. Rekomendasi Permission yang Perlu Ditambahkan

### Tambahkan 4 modul baru ke seeder (16 permission baru):

```php
$modules = [
    // existing
    'blog', 'page', 'category', 'menu', 'file', 'ad', 'setting', 'analytics',
    // NEW
    'user',        // User management (pisah dari setting)
    'role',        // Role management (pisah dari setting, hanya Super Admin)
    'contact',     // Contact submissions management
    'newsletter',  // Newsletter subscribers + campaigns
];
```

Permission baru yang dihasilkan (16 permission):

| Module | Permissions Baru |
|--------|-----------------|
| `user` | `user.view`, `user.create`, `user.edit`, `user.delete` |
| `role` | `role.view`, `role.create`, `role.edit`, `role.delete` |
| `contact` | `contact.view`, `contact.create`, `contact.edit`, `contact.delete` |
| `newsletter` | `newsletter.view`, `newsletter.create`, `newsletter.edit`, `newsletter.delete` |

### Update route `web.php`:

| Route Group | Ganti Dari | Ganti Ke |
|-------------|-----------|----------|
| `users.*` | `setting.view/create/edit/delete` | `user.view/create/edit/delete` |
| `roles.*` | `setting.view/create/edit/delete` | `role.view/create/edit/delete` |
| `contacts.*` | `setting.view/edit/delete` | `contact.view/edit/delete` |
| `newsletter.*` (subscribers) | `setting.view/delete` | `newsletter.view/delete` |
| `newsletter.campaigns.*` | `setting.view` | `newsletter.view/create/delete` |
| `seo.*` | `setting.view/edit` | `setting.view/edit` *(bisa tetap, atau buat `seo.*`)* |
| `tools.*` (export/import) | `setting.edit` | `setting.edit` *(bisa tetap — tools memang sensitif)* |
| `audit-logs.*` | `setting.view` | `setting.view` *(wajar — audit log untuk admin sistem)* |

### Update Assignment Role:

```php
// Super Admin — semua permission (termasuk 16 baru)
$superAdminRole->syncPermissions($allPermissions);

// Admin — semua kecuali role.* dan setting.* (sebelumnya tidak bisa akses users)
$adminPermissions = tidak termasuk: ['setting.*', 'role.*'];
// Admin sekarang bisa: blog, page, category, menu, file, ad, analytics,
//                      user, contact, newsletter

// Editor — tambah newsletter.view agar bisa lihat subscriber
$editorPermissions = [
    'blog.view', 'blog.edit',
    'page.view', 'page.edit',
    'category.view', 'category.edit',
    'file.view', 'file.edit',
    'newsletter.view',  // baru
];

// Author — tidak berubah
$authorPermissions = ['blog.view', 'blog.create'];
```

### Perbaikan Minor Tambahan:

1. **`admin.notifications.index`** — tambahkan check `$user->can('blog.view') || $user->can('contact.view')` di `NotificationController::index()` agar data yang dikembalikan sesuai role (pending comments untuk yang punya `blog.view`, unread contacts untuk yang punya `contact.view`).

2. **Hapus permission tidak terpakai** (`analytics.create`, `analytics.edit`, `analytics.delete`) dari seeder agar tidak membingungkan di UI Role Management.

3. **Newsletter Campaigns — granularitas operasi**: saat ini semua aksi campaign (create, send, delete) dijaga `setting.view` saja. Setelah perubahan di atas, gunakan `newsletter.create` untuk create/store, `newsletter.view` untuk show/index, dan `newsletter.delete` untuk destroy.

---

## 5. Matriks Akses Setelah Rekomendasi Diterapkan

| Module | Super Admin | Admin | Editor | Author |
|--------|:-----------:|:-----:|:------:|:------:|
| Dashboard | ✅ | ✅ | ✅ | ✅ |
| Blog | ✅ CRUD | ✅ CRUD | ✅ VE | ✅ VC |
| Page | ✅ CRUD | ✅ CRUD | ✅ VE | ❌ |
| Category | ✅ CRUD | ✅ CRUD | ✅ VE | ❌ |
| Menu | ✅ CRUD | ✅ CRUD | ❌ | ❌ |
| File Manager | ✅ CRUD | ✅ CRUD | ✅ VE | ❌ |
| Ads | ✅ CRUD | ✅ CRUD | ❌ | ❌ |
| Analytics | ✅ V | ✅ V | ❌ | ❌ |
| Contacts | ✅ CRUD | ✅ VED | ❌ | ❌ |
| Newsletter | ✅ CRUD | ✅ CRUD | ✅ V | ❌ |
| Settings | ✅ VE | ❌ | ❌ | ❌ |
| Users | ✅ CRUD | ✅ CRUD | ❌ | ❌ |
| Roles | ✅ CRUD | ❌ | ❌ | ❌ |
| Audit Log | ✅ V | ✅ V | ❌ | ❌ |
| SEO Tools | ✅ VE | ✅ VE | ❌ | ❌ |
| Maintenance | ✅ E | ✅ E | ❌ | ❌ |
| Export/Import | ✅ E | ✅ E | ❌ | ❌ |
| Page Builder | ✅ E | ✅ E | ✅ E | ❌ |
| Komentar | ✅ CRUD | ✅ CRUD | ✅ VE | ❌ |

*V=view, C=create, E=edit, D=delete*

---

## 6. File yang Perlu Dimodifikasi (Ringkasan Implementasi)

| File | Perubahan |
|------|-----------|
| `database/seeders/RolePermissionSeeder.php` | Tambah modul `user`, `role`, `contact`, `newsletter` ke `$modules`; update permission per role |
| `routes/web.php` | Ganti `setting.*` → `user.*`/`role.*`/`contact.*`/`newsletter.*` pada route yang tepat |
| `app/Http/Controllers/Admin/NotificationController.php` | Filter data berdasarkan permission user |

> **Catatan:** Setelah mengubah seeder, jalankan `php artisan db:seed --class=RolePermissionSeeder` dan `php artisan permission:cache-reset` agar perubahan efektif di database.
