# Brand de-hardcoding: logo, favicon, dan site name dikontrol dari CMS

Perubahan ini menghapus semua fallback statis brand "MonetraPay" dari layout, auth, error, dan admin views, menggantinya dengan `Setting::get()` + `Storage::url()` + `$siteName` dari CMS. Hasilnya: project bisa dipakai ulang untuk brand apapun hanya dengan mengganti logo, favicon, dan `site_name` di halaman Settings. Auth pages dan error layout yang tidak punya FrontendComposer sekarang query Setting langsung di `@php` block dengan `try/catch` untuk keamanan fresh install. AppServiceProvider menambahkan `View::share()` global sebagai mekanisme baru untuk memastikan variabel brand tersedia di child views yang tidak di-compose oleh FrontendComposer.

Watch for:
- **confirmed** — `View::share()` di AppServiceProvider dan `FrontendComposer::compose()` keduanya assign `$siteName`, `$logo` ke setiap request — double-compose yang bisa menyebabkan overwrite tak terduga untuk frontend views.
- **confirmed** — Beberapa controller dan email template di luar brief masih hardcode fallback `'MonetraPay'` sebagai default ketiga di `Setting::get()`.
- **confirmed** — Demo block HTML di dalam `content:` string grapesjs masih mengandung "MonetraPay" sebagai teks konten — block category-nya sudah diperbaiki, tapi teksnya belum.

**Verdict**: NEEDS_CHANGES

---

## High-level view

Semua item prioritas tinggi di brief telah diperbaiki: favicon fallback statis dihapus dari 4 views, logo `@elseif(file_exists(...))` dihapus dari 9 views, title "MonetraPay CMS" diganti di 5 views, dan 4 PHP files sudah bersih. Editorial pages (`tentang-kami.blade.php`, `home.blade.php`) tidak disentuh pada bagian prosa konten.

`View::share(compact('siteName', 'logo', 'favicon'))` di AppServiceProvider sekarang berjalan di setiap request bersamaan dengan FrontendComposer yang juga assign `$siteName` dan `$logo` ke frontend views. Untuk frontend views, composer selalu overwrite share — nilai akhirnya sama karena baca dari cache yang sama. Tapi `$favicon` tidak di-assign FrontendComposer, sehingga ada dua jalur berbeda untuk variabel yang satu sumber. Jika FrontendComposer di masa depan ikut assign `$favicon`, ordering conflict-nya tidak obvious karena dua mekanisme beroperasi sekaligus.

Grapesjs block category sudah diganti ke `$siteName`, tapi properti `content:` dari block hero, testimonial, dan CTA masih menyematkan "MonetraPay" secara literal — teks yang muncul di canvas editor saat block di-drag.

Sejumlah controller, mail class, dan email templates di luar daftar audit brief masih menggunakan `'MonetraPay'` sebagai default ketiga di `Setting::get()`. RSS feed title-nya visible ke aggregator eksternal jika database belum ada nilai.

---

<details>
<summary>Issues (3)</summary>

1. **View::share + FrontendComposer double-assign** — `AppServiceProvider::boot()` melakukan `View::share(compact('siteName', 'logo', 'favicon'))` untuk setiap request, sementara `FrontendComposer::compose()` juga assign variabel yang sama ke frontend views. Untuk frontend views, composer akan overwrite share, tapi ordering ini tidak eksplisit. Pisahkan: gunakan `View::share` hanya untuk views yang tidak di-cover composer (admin, auth, error), atau konversi composer agar extend dari share sehingga tidak ada duplikasi.

2. **Demo block content strings masih hardcode "MonetraPay"** — Di `grapesjs.blade.php`, properti `content:` pada block `mp-hero`, `mp-testimonial`, dan `mp-cta` masih mengandung teks "MonetraPay" secara literal di dalam HTML string. Ganti dengan `{{ $siteName }}` yang sudah di-inject, atau minimal pakai placeholder generik seperti "Nama Brand Anda".

3. **Sisa fallback 'MonetraPay' di controllers dan email templates di luar brief** — `ContactSubmissionController.php:99`, `NewsletterCampaignController.php:83`, `FeedController.php:19`, `ContactConfirmation.php`, `ContactNotification.php`, `ContactReply.php`, `emails/reset-password.blade.php` masih menggunakan string literal `'MonetraPay'` sebagai default. Ganti semua dengan `config('app.name')` untuk konsistensi multi-brand.

</details>

---

<details>
<summary>Details</summary>

## View::share + FrontendComposer: tumpang tindih assignment

`AppServiceProvider::boot()` menjalankan `Setting::get()` tiga kali (site_name, logo, favicon) dan memanggil `View::share(compact(...))`. FrontendComposer yang sudah ada juga assign `$siteName` dan `$logo` ke views yang di-compose-nya — tapi bukan `$favicon`. Untuk frontend views, composer berjalan setelah share (saat render), sehingga overwrite `$siteName` dan `$logo` dengan nilai identik dari cache yang sama. `$favicon` untuk frontend views hanya datang dari `View::share`.

Konsekuensinya: ada dua jalur data untuk variabel yang satu sumber. Jika FrontendComposer di masa depan ditambah assign `$favicon`, ordering conflict-nya tidak obvious karena developer tidak tahu ada mekanisme ganda. Solusi yang lebih bersih adalah menambah `$favicon` ke FrontendComposer dan hapus `View::share` untuk scope frontend — atau sebaliknya, ganti semua composer dengan share dan hapus FrontendComposer sepenuhnya untuk variabel brand.

## Demo block content strings di grapesjs

Di `grapesjs.blade.php`, properti `content:` dari tiga block masih berisi HTML hardcode dengan "MonetraPay" meski kategori-nya sudah diganti:

- `mp-hero`: `"Selamat Datang di MonetraPay"`
- `mp-testimonial`: kutipan `"MonetraPay benar-benar mengubah..."` dan `"Laporan keuangan real-time dari MonetraPay..."`  
- `mp-cta`: `"Siap Mulai Dengan MonetraPay?"` dan `"Bergabung bersama ribuan bisnis yang sudah mempercayai MonetraPay."`

Ini adalah teks yang muncul di canvas page builder saat block di-drop, bukan metadata. Untuk brand berbeda, editor akan melihat "MonetraPay" di template mereka. String PHP sudah tersedia di scope (`$siteName`) jadi bisa di-interpolasi langsung.

Juga ada komentar `// MonetraPay custom blocks` dan `// Add MonetraPay custom blocks` di baris 144 dan 284 — minor tapi inkonsisten dengan tujuan brand-neutral.

## Sisa fallback literal di luar audit

File-file berikut tidak ada di daftar brief tapi berada dalam codebase aktif dan melayani fungsionalitas produksi:

- `app/Http/Controllers/Admin/ContactSubmissionController.php:99` — `Setting::get('site_name', 'general', 'MonetraPay')`
- `app/Http/Controllers/Admin/NewsletterCampaignController.php:83` — idem
- `app/Http/Controllers/Frontend/FeedController.php:19` — idem (feed RSS title)
- `app/Mail/ContactConfirmation.php:28`, `ContactNotification.php:28`, `ContactReply.php` — idem
- `resources/views/emails/contact-confirmation.blade.php:2`, `contact-notification.blade.php:2`, `contact-reply.blade.php:2`, `reset-password.blade.php:2` — idem

Untuk RSS feed khususnya, `$siteName` yang muncul di `<title>` feed akan "MonetraPay" jika database belum ada nilai — ini visible ke aggregator eksternal.

## Favicon cache-busting di admin layout

Admin layout menambahkan `?v=` + `filemtime()` pada URL favicon — konsisten dengan frontend layout. Jika file storage tidak ada di disk setelah migrasi server tanpa sync storage, `filemtime(Storage::disk('public')->path($adminFavicon))` akan throw PHP warning. Masalah yang sama ada di frontend layout; ini pre-existing, bukan regresi dari perubahan ini.

</details>

---

<details>
<summary>File map</summary>

| File | Perubahan |
|---|---|
| `app/Http/Controllers/Admin/SettingController.php` | Test email subject/body pakai `$siteName` dari CMS; tambah `copy()` ke `public/favicon.ico` setelah resize |
| `app/Http/Controllers/Admin/TwoFactorController.php` | Hapus fallback `'MonetraPay'` dari `config('app.name', ...)` |
| `app/Providers/AppServiceProvider.php` | Tambah `View::share()` global untuk brand vars dengan try/catch |
| `app/Services/SeoService.php` | Ganti 2 fallback `'MonetraPay'` ke `config('app.name')` |
| `app/View/Composers/FrontendComposer.php` | Ganti 3 fallback `'MonetraPay'` ke `config('app.name')` |
| `resources/views/admin/layouts/app.blade.php` | Title, favicon fallback, logo alt text, logo `@elseif` statis dihapus |
| `resources/views/admin/page-builder/grapesjs.blade.php` | Kategori block pakai `$siteName`; teks konten block belum diganti |
| `resources/views/admin/two-factor/challenge.blade.php` | Title dan favicon fallback dari CMS |
| `resources/views/auth/forgot-password.blade.php` | Title, logo block dengan try/catch |
| `resources/views/auth/login.blade.php` | Title, heading, copyright, logo block dengan try/catch |
| `resources/views/auth/reset-password.blade.php` | Title, logo block dengan try/catch |
| `resources/views/components/builder/about-hero.blade.php` | Logo `@elseif` statis dihapus, fallback ke `$siteName` text |
| `resources/views/errors/layout.blade.php` | Favicon dari CMS dengan try/catch, fallback ke `/favicon.ico` |
| `resources/views/frontend/layouts/app.blade.php` | Fallback favicon statis → `/favicon.ico`; logo preload `@elseif` dihapus; cache-busting ditambah; OG tags pakai `config('app.name')` |
| `resources/views/frontend/layouts/partials/footer.blade.php` | Logo `@elseif` statis dihapus; fallback ke `$siteName` text; copyright pakai `config('app.name')` |
| `resources/views/frontend/layouts/partials/navbar.blade.php` | Logo `@elseif` statis dihapus; mobile menu logo pakai CMS logo |
| `resources/views/frontend/maintenance.blade.php` | Semua fallback statis dihapus; try/catch ditambah |
| `resources/views/frontend/page/tentang-kami.blade.php` | Logo widget saja; konten prosa tidak diubah |

Full diff: `git diff b95623f000d9c4a20c94355be25ec6dd6258e994...HEAD`

</details>
