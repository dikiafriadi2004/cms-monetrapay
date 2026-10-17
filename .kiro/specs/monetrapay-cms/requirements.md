# Requirements Document

## Introduction

MonetraPay CMS adalah sistem manajemen konten (Content Management System) berbasis Laravel untuk website company profile yang mempromosikan aplikasi penjualan pulsa, PPOB, top-up game, dan e-money. Sistem ini menyediakan fitur CMS lengkap setara WordPress meliputi manajemen konten (blog & pages), menu builder, file manager, rich text editor, SEO management, analytics dashboard, ad management, serta role & permission system. Frontend menggunakan TailwindCSS dengan tampilan modern dan responsif.

## Glossary

- **CMS**: Content Management System — sistem untuk mengelola konten website
- **Admin_Panel**: Dashboard administrasi backend untuk mengelola seluruh konten dan konfigurasi
- **Frontend**: Halaman publik website yang dapat diakses pengunjung
- **Blog_Manager**: Modul untuk membuat, mengedit, dan mengelola artikel blog
- **Page_Manager**: Modul untuk membuat dan mengelola halaman statis
- **Category_Manager**: Modul untuk mengelola kategori blog
- **Menu_Builder**: Modul drag-and-drop untuk membangun dan mengatur navigasi website
- **File_Manager**: Modul untuk mengunggah, mengorganisir, dan mengelola file media
- **Text_Editor**: Rich text editor (WYSIWYG) untuk menulis konten blog dan pages
- **SEO_Manager**: Modul untuk mengelola meta tags, sitemap, dan optimasi mesin pencari
- **Analytics_Dashboard**: Widget di admin panel yang menampilkan data Google Analytics
- **Ad_Manager**: Modul untuk mengelola penempatan iklan (Google AdSense, Adsera, manual)
- **Auto_Insert_Ad**: Fitur penempatan iklan otomatis yang menyisipkan iklan setiap N paragraf dalam konten blog post di Frontend
- **Role_Permission_System**: Sistem untuk mengelola role dan permission pengguna
- **Setting_Manager**: Modul untuk mengelola konfigurasi umum website
- **Navbar**: Komponen navigasi di frontend yang dirender berdasarkan konfigurasi Menu_Builder
- **Visitor**: Pengguna publik yang mengakses Frontend tanpa login

## Requirements

### Requirement 1: Authentication dan User Management

**User Story:** Sebagai administrator, saya ingin mengelola pengguna dengan role dan permission, sehingga akses ke fitur admin panel dapat dikontrol sesuai tanggung jawab masing-masing.

#### Acceptance Criteria

1. THE Role_Permission_System SHALL menyediakan role default: Super Admin (memiliki semua permission), Admin (semua permission kecuali manajemen role dan setting), Editor (permission view dan edit pada Blog_Manager, Page_Manager, Category_Manager, dan File_Manager), dan Author (permission view dan create pada Blog_Manager)
2. WHEN seorang administrator membuat role baru dengan nama unik (maksimal 50 karakter alfanumerik dan spasi) dan minimal 1 permission dipilih, THE Role_Permission_System SHALL menyimpan role tersebut dengan daftar permission yang dipilih
3. IF seorang administrator membuat role baru dengan nama yang sudah digunakan oleh role lain, THEN THE Role_Permission_System SHALL menolak pembuatan dan menampilkan pesan error yang mengindikasikan nama role sudah digunakan
4. WHEN seorang pengguna login, THE Admin_Panel SHALL menyembunyikan menu dan elemen UI untuk modul yang tidak termasuk dalam permission role pengguna tersebut
5. THE Role_Permission_System SHALL menyediakan permission granular untuk setiap modul (Blog_Manager, Page_Manager, Category_Manager, Menu_Builder, File_Manager, Ad_Manager, Setting_Manager, dan Analytics_Dashboard) dengan tipe operasi: view, create, edit, dan delete
6. IF seorang pengguna mengakses fitur tanpa permission yang sesuai melalui URL langsung, THEN THE Admin_Panel SHALL menampilkan halaman 403 Forbidden dan tidak mengeksekusi operasi yang diminta
7. WHEN seorang administrator mengedit permission sebuah role, THE Role_Permission_System SHALL memperbarui akses semua pengguna dengan role tersebut tanpa memerlukan pengguna tersebut untuk login ulang, efektif pada request berikutnya
8. THE Role_Permission_System SHALL mencegah penghapusan atau pengubahan permission role default Super Admin

### Requirement 2: Blog Management

**User Story:** Sebagai editor, saya ingin membuat dan mengelola artikel blog dengan kategori, sehingga konten promosi MonetraPay dapat dipublikasikan secara terorganisir.

#### Acceptance Criteria

1. WHEN seorang editor membuat artikel baru, THE Blog_Manager SHALL menyediakan form dengan field: judul (maksimal 200 karakter), slug (maksimal 220 karakter, hanya huruf kecil, angka, dan tanda hubung), konten (Text_Editor), featured image (format: JPG/PNG/WebP, maksimal 2MB), kategori, tags (maksimal 10 tags per artikel, masing-masing maksimal 50 karakter), status (draft/published), dan meta SEO (meta title maksimal 60 karakter, meta description maksimal 160 karakter)
2. WHEN seorang editor mengisi atau mengubah judul artikel, THE Blog_Manager SHALL menghasilkan slug otomatis dengan mengonversi judul ke huruf kecil, mengganti spasi dan karakter khusus dengan tanda hubung, dan menghapus karakter non-alfanumerik selain tanda hubung
3. IF slug yang dihasilkan sudah digunakan oleh artikel lain, THEN THE Blog_Manager SHALL menambahkan sufiks numerik berurutan pada slug (contoh: "judul-artikel-2") hingga slug menjadi unik
4. WHEN seorang editor menyimpan artikel dengan status published, THE Blog_Manager SHALL menampilkan artikel tersebut di halaman daftar artikel dan halaman detail artikel pada Frontend dalam waktu maksimal 30 detik setelah penyimpanan
5. THE Category_Manager SHALL menyediakan fitur CRUD untuk kategori blog dengan field: nama (maksimal 100 karakter), slug (maksimal 120 karakter), dan deskripsi (maksimal 500 karakter)
6. WHEN seorang editor memilih kategori untuk artikel, THE Blog_Manager SHALL mendukung pemilihan minimal 1 dan maksimal 5 kategori per artikel
7. THE Blog_Manager SHALL menyediakan fitur pencarian dan filter berdasarkan status, kategori, dan rentang tanggal di Admin_Panel
8. WHEN seorang editor menghapus artikel, THE Blog_Manager SHALL memindahkan artikel ke trash dengan masa retensi 30 hari sebelum penghapusan permanen secara otomatis
9. IF seorang editor menyimpan artikel tanpa mengisi field wajib (judul, konten, atau kategori), THEN THE Blog_Manager SHALL menampilkan pesan error yang mengindikasikan field mana yang belum diisi dan tidak menyimpan artikel tersebut

### Requirement 3: Page Management

**User Story:** Sebagai administrator, saya ingin membuat dan mengelola halaman statis, sehingga informasi tentang MonetraPay dapat ditampilkan di berbagai halaman website.

#### Acceptance Criteria

1. WHEN seorang administrator membuat halaman baru, THE Page_Manager SHALL menyediakan form dengan field: judul (maksimal 200 karakter), slug (maksimal 200 karakter), konten (Text_Editor), featured image, template, parent page, status (draft/published), dan meta SEO (meta title, meta description, meta keywords)
2. THE Page_Manager SHALL menghasilkan slug otomatis dari judul halaman
3. IF seorang administrator menyimpan halaman dengan slug yang sudah digunakan oleh halaman lain, THEN THE Page_Manager SHALL menampilkan pesan error yang menunjukkan bahwa slug harus unik
4. WHEN seorang administrator mempublikasikan halaman, THE Page_Manager SHALL membuat halaman tersebut dapat diakses melalui URL berdasarkan slug
5. IF halaman berstatus draft, THEN THE Page_Manager SHALL tidak menampilkan halaman tersebut di Frontend
6. THE Page_Manager SHALL menyediakan daftar template layout yang tersedia untuk dipilih pada setiap halaman
7. THE Page_Manager SHALL mendukung hierarki halaman (parent-child) hingga maksimal 3 level kedalaman
8. WHEN seorang administrator menghapus halaman, THE Page_Manager SHALL memindahkan halaman ke trash dengan masa retensi 30 hari sebelum penghapusan permanen

### Requirement 4: Menu Builder

**User Story:** Sebagai administrator, saya ingin membangun dan mengatur menu navigasi secara visual dengan drag-and-drop, sehingga struktur navigasi website dapat diubah tanpa coding.

#### Acceptance Criteria

1. THE Menu_Builder SHALL menyediakan antarmuka drag-and-drop yang memungkinkan administrator untuk menambah, menghapus, mengubah urutan, dan mengubah level nesting item menu
2. WHEN seorang administrator menambahkan item menu, THE Menu_Builder SHALL mendukung tipe berikut dengan field wajib masing-masing: link kustom (label maksimal 100 karakter, URL), halaman (pilih dari daftar halaman yang tersedia), kategori blog (pilih dari daftar kategori yang tersedia), dan URL eksternal (label maksimal 100 karakter, URL dengan format valid dimulai http:// atau https://)
3. THE Menu_Builder SHALL mendukung menu bertingkat (nested) hingga 3 level kedalaman dengan maksimal 50 item per menu
4. IF seorang administrator mencoba meletakkan item menu melebihi 3 level kedalaman, THEN THE Menu_Builder SHALL mencegah aksi tersebut dan menampilkan pesan kesalahan yang menginformasikan batas maksimal level telah tercapai
5. WHEN seorang administrator menyimpan konfigurasi menu, THE Navbar SHALL memperbarui tampilan navigasi di Frontend sesuai konfigurasi terbaru dalam waktu maksimal 5 detik setelah penyimpanan berhasil
6. IF penyimpanan konfigurasi menu gagal, THEN THE Menu_Builder SHALL menampilkan pesan kesalahan yang menginformasikan kegagalan penyimpanan dan mempertahankan konfigurasi terakhir yang berhasil disimpan
7. THE Menu_Builder SHALL mendukung pembuatan multiple menu untuk lokasi berbeda (header, footer, sidebar) dengan maksimal 10 menu aktif secara bersamaan
8. WHEN seorang administrator mengubah urutan item menu melalui drag-and-drop, THE Menu_Builder SHALL menyimpan urutan baru ke database setelah administrator menekan tombol simpan
9. IF seorang administrator menambahkan item menu dengan field wajib yang kosong atau URL dengan format tidak valid, THEN THE Menu_Builder SHALL menampilkan pesan validasi pada field yang bermasalah dan mencegah penyimpanan item tersebut

### Requirement 5: File Manager

**User Story:** Sebagai editor, saya ingin mengunggah dan mengelola file media dalam antarmuka visual, sehingga gambar dan dokumen dapat diorganisir dan digunakan di konten.

#### Acceptance Criteria

1. THE File_Manager SHALL menampilkan daftar file yang telah diunggah dalam tampilan grid dengan thumbnail, mencakup informasi nama file, ukuran file, dan tanggal unggah
2. WHEN seorang editor mengunggah file, THE File_Manager SHALL memvalidasi tipe file (gambar: jpg, png, gif, webp, svg; dokumen: pdf, doc, docx) dan ukuran maksimal 10MB sebelum menyimpan file
3. IF file yang diunggah memiliki tipe yang tidak diizinkan atau ukuran melebihi 10MB, THEN THE File_Manager SHALL menolak unggahan dan menampilkan pesan error yang menunjukkan alasan penolakan (tipe tidak valid atau ukuran melebihi batas)
4. THE File_Manager SHALL mendukung pembuatan folder dengan nama maksimal 100 karakter (alfanumerik, spasi, strip, dan underscore) dan kedalaman maksimal 5 level untuk organisasi file
5. THE File_Manager SHALL menampilkan preview thumbnail untuk file gambar dengan dimensi maksimal 150x150 piksel
6. WHEN seorang editor memilih gambar dari File_Manager melalui Text_Editor, THE Text_Editor SHALL menyisipkan gambar tersebut ke dalam konten pada posisi kursor aktif
7. WHEN seorang editor memasukkan minimal 2 karakter pada kolom pencarian, THE File_Manager SHALL menampilkan file yang namanya mengandung kata kunci pencarian dalam waktu maksimal 2 detik
8. IF seorang editor mencoba menghapus file yang sedang digunakan dalam konten, THEN THE File_Manager SHALL menampilkan peringatan konfirmasi yang menunjukkan daftar konten yang menggunakan file tersebut sebelum melanjutkan penghapusan

### Requirement 6: Rich Text Editor

**User Story:** Sebagai editor, saya ingin menulis konten dengan rich text editor yang terintegrasi dengan file manager, sehingga konten blog dan pages dapat dibuat dengan format kaya dan gambar.

#### Acceptance Criteria

1. THE Text_Editor SHALL menyediakan toolbar formatting: bold, italic, underline, heading (H1-H6), list (ordered/unordered), blockquote, link, dan code block
2. THE Text_Editor SHALL menyediakan tombol insert image yang membuka File_Manager dalam modal dialog
3. WHEN seorang editor memilih gambar dari File_Manager, THE Text_Editor SHALL menyisipkan tag gambar dengan atribut src berisi URL file yang dipilih dan atribut alt yang dapat diisi oleh editor sebelum penyisipan
4. WHEN seorang editor memasukkan URL video YouTube atau Vimeo melalui dialog embed, THE Text_Editor SHALL menyisipkan iframe responsive dengan URL yang telah dikonversi ke format embed
5. IF seorang editor memasukkan URL yang bukan merupakan URL valid YouTube atau Vimeo pada dialog embed video, THEN THE Text_Editor SHALL menampilkan pesan error yang menginformasikan bahwa URL tidak valid dan tidak menyisipkan elemen apapun ke konten
6. THE Text_Editor SHALL menghasilkan output HTML yang menggunakan tag semantik (p, h1-h6, ul, ol, li, blockquote, pre, code, a, img, strong, em, u) tanpa inline style dan tanpa tag kosong (tag tanpa konten teks atau child element)
7. THE Text_Editor SHALL menyediakan tombol toggle mode fullscreen yang memperluas area editor hingga memenuhi seluruh viewport browser
8. THE Text_Editor SHALL mendukung konten dengan panjang maksimal 100.000 karakter

### Requirement 7: SEO Management

**User Story:** Sebagai administrator, saya ingin mengelola SEO untuk semua halaman publik, sehingga website MonetraPay dapat ditemukan dengan mudah di mesin pencari.

#### Acceptance Criteria

1. THE SEO_Manager SHALL menyediakan field meta title (maksimal 60 karakter), meta description (maksimal 160 karakter), dan meta keywords (maksimal 10 keywords) untuk setiap blog post dan page
2. WHEN seorang administrator mempublikasikan, memperbarui, atau menghapus blog post, page, atau kategori, THE SEO_Manager SHALL meregenerasi sitemap.xml yang mencakup semua halaman publik, blog posts, dan kategori yang berstatus published
3. WHEN seorang administrator mengubah meta SEO sebuah halaman, THE Frontend SHALL merender meta tags yang diperbarui di HTML head
4. THE SEO_Manager SHALL menghasilkan robots.txt yang dapat dikonfigurasi melalui Setting_Manager dengan opsi: daftar User-agent, daftar path Disallow, dan path Sitemap
5. THE Frontend SHALL merender Open Graph tags (og:title, og:description, og:image, og:url, og:type) dan Twitter Card tags (twitter:card, twitter:title, twitter:description, twitter:image) untuk setiap halaman publik
6. THE SEO_Manager SHALL menyediakan canonical URL otomatis untuk setiap halaman berdasarkan URL absolut halaman tersebut
7. THE Frontend SHALL menggunakan struktur heading yang semantik (H1 untuk judul utama, H2-H6 untuk sub-heading) dengan maksimal satu H1 per halaman
8. THE SEO_Manager SHALL menghasilkan structured data (JSON-LD) untuk blog posts (Article schema) dan halaman organisasi (Organization schema)
9. IF field meta title atau meta description sebuah halaman kosong, THEN THE SEO_Manager SHALL menggunakan nilai default dari pengaturan SEO global di Setting_Manager
10. WHEN seorang administrator mengisi meta title melebihi 60 karakter atau meta description melebihi 160 karakter, THE SEO_Manager SHALL menampilkan indikator peringatan bahwa panjang karakter melebihi batas yang direkomendasikan

### Requirement 8: Google Analytics Integration

**User Story:** Sebagai administrator, saya ingin melihat data Google Analytics di dashboard admin, sehingga performa website dapat dipantau tanpa membuka platform terpisah.

#### Acceptance Criteria

1. WHEN seorang administrator memasukkan Google Analytics Measurement ID dengan format valid (diawali "G-" diikuti maksimum 15 karakter alfanumerik) di Setting_Manager, THE Frontend SHALL menyisipkan tracking code Google Analytics (gtag.js) di seluruh halaman yang dapat diakses tanpa login (halaman publik)
2. IF administrator memasukkan Measurement ID dengan format tidak valid, THEN THE Setting_Manager SHALL menolak penyimpanan dan menampilkan pesan error yang menjelaskan format yang diharapkan (G-XXXXXXXXXX)
3. THE Analytics_Dashboard SHALL menampilkan widget ringkasan yang berisi: total pengunjung (users), pageviews, bounce rate (dalam persentase), dan rata-rata durasi sesi (dalam menit dan detik) untuk 7 hari terakhir, dengan data yang di-cache maksimum 60 menit sejak pengambilan terakhir
4. THE Analytics_Dashboard SHALL menampilkan grafik tren jumlah pengunjung harian (satu titik data per hari) untuk 30 hari terakhir
5. THE Analytics_Dashboard SHALL menampilkan daftar 10 halaman dengan jumlah pageviews tertinggi dalam 30 hari terakhir, yang mencakup judul halaman dan jumlah pageviews
6. IF Google Analytics Measurement ID belum dikonfigurasi di Setting_Manager, THEN THE Analytics_Dashboard SHALL menampilkan pesan yang menginformasikan bahwa Measurement ID belum diatur beserta tautan langsung ke halaman Setting_Manager untuk melakukan konfigurasi
7. IF koneksi ke Google Analytics API gagal atau mengembalikan error, THEN THE Analytics_Dashboard SHALL menampilkan pesan error yang menginformasikan bahwa data analitik tidak dapat dimuat, dan mempertahankan data cache terakhir yang tersedia (jika ada)

### Requirement 9: Ad Management

**User Story:** Sebagai administrator, saya ingin mengelola penempatan iklan dari berbagai sumber, sehingga website dapat menghasilkan pendapatan dari iklan.

#### Acceptance Criteria

1. THE Ad_Manager SHALL menyediakan tiga tipe slot iklan yang dapat dibuat oleh administrator: Google AdSense (kode script), Adstera (kode script), dan manual (custom HTML dengan maksimal 10.000 karakter atau file gambar dengan maksimal 2MB dalam format JPG, PNG, atau GIF)
2. WHEN seorang administrator membuat slot iklan, THE Ad_Manager SHALL menyediakan field: nama (maksimal 100 karakter), tipe (Google AdSense/Adstera/manual), kode iklan (maksimal 5.000 karakter) atau gambar, posisi penempatan, dan status (aktif/nonaktif, default: nonaktif)
3. THE Ad_Manager SHALL menyediakan posisi penempatan: header, sidebar, dalam konten (setelah paragraf ke-N, dengan N bernilai 1 sampai 20), auto-insert dalam blog (otomatis setiap N paragraf, dengan N bernilai 2 sampai 10 yang dapat dikonfigurasi), dan footer, dengan maksimal 5 slot iklan aktif per posisi
4. WHEN seorang administrator mengaktifkan slot iklan, THE Frontend SHALL menampilkan iklan di posisi yang ditentukan pada pemuatan halaman berikutnya
5. WHEN seorang administrator menonaktifkan slot iklan, THE Frontend SHALL menghilangkan iklan dari posisi tersebut tanpa meninggalkan ruang kosong pada pemuatan halaman berikutnya
6. WHEN tanggal berakhir penjadwalan iklan tercapai, THE Ad_Manager SHALL secara otomatis mengubah status slot iklan menjadi nonaktif dan THE Frontend SHALL menghilangkan iklan tersebut pada pemuatan halaman berikutnya
7. WHEN seorang administrator membuat slot iklan dengan tipe "dalam konten" dan nilai N melebihi jumlah paragraf pada halaman, THE Frontend SHALL menampilkan iklan tersebut setelah paragraf terakhir
8. IF seorang administrator menyimpan slot iklan dengan field wajib (nama, tipe, posisi) yang kosong, THEN THE Ad_Manager SHALL menampilkan pesan error yang mengindikasikan field mana yang harus diisi dan tidak menyimpan slot iklan tersebut
9. WHEN seorang administrator membuat slot iklan dengan penjadwalan, THE Ad_Manager SHALL mewajibkan tanggal mulai tidak boleh di masa lalu dan tanggal berakhir harus setelah tanggal mulai
10. WHEN seorang administrator membuat slot iklan dengan posisi "auto-insert dalam blog", THE Ad_Manager SHALL menyediakan field interval paragraf (nilai N antara 2 sampai 10) yang menentukan iklan ditampilkan setiap N paragraf dalam konten blog post
11. WHEN slot iklan dengan posisi "auto-insert dalam blog" berstatus aktif, THE Frontend SHALL secara otomatis menyisipkan iklan tersebut setelah setiap kelipatan N paragraf dalam konten blog post, dimulai dari paragraf ke-N hingga paragraf terakhir yang memenuhi kelipatan
12. IF konten blog post memiliki jumlah paragraf kurang dari nilai N yang dikonfigurasi, THEN THE Frontend SHALL tidak menampilkan iklan auto-insert dalam blog post tersebut

### Requirement 10: Settings Management

**User Story:** Sebagai administrator, saya ingin mengelola konfigurasi umum website dari satu tempat, sehingga perubahan pengaturan dapat dilakukan dengan mudah.

#### Acceptance Criteria

1. THE Setting_Manager SHALL menyediakan pengaturan umum dengan field berikut: nama website (maksimal 100 karakter), tagline (maksimal 255 karakter), logo (file gambar berformat PNG/JPG/SVG, maksimal 2MB), favicon (file gambar berformat ICO/PNG, maksimal 512KB), email kontak (format email valid), dan nomor telepon (maksimal 20 karakter)
2. THE Setting_Manager SHALL menyediakan pengaturan sosial media berupa field URL (maksimal 255 karakter, format URL valid) untuk masing-masing platform: Facebook, Instagram, Twitter, YouTube, dan TikTok
3. THE Setting_Manager SHALL menyediakan pengaturan SEO global: meta title default (maksimal 70 karakter), meta description default (maksimal 160 karakter), dan Google Analytics Measurement ID (format "G-" diikuti 10 karakter alfanumerik)
4. THE Setting_Manager SHALL menyediakan pengaturan footer: teks copyright (maksimal 255 karakter) dan konten tambahan (maksimal 1000 karakter)
5. WHEN seorang administrator menyimpan perubahan pengaturan dengan data valid, THE Setting_Manager SHALL menyimpan data dan Frontend SHALL menampilkan perubahan tersebut pada pemuatan halaman berikutnya tanpa memerlukan deployment ulang
6. IF seorang administrator menyimpan pengaturan dengan data yang tidak valid, THEN THE Setting_Manager SHALL menampilkan pesan error yang menunjukkan field mana yang tidak valid beserta alasannya, dan tidak menyimpan perubahan apapun
7. WHILE maintenance mode aktif, THE Setting_Manager SHALL menampilkan halaman "Coming Soon" kepada seluruh Visitor yang tidak terautentikasi, sementara administrator yang terautentikasi tetap dapat mengakses seluruh halaman website secara normal
8. THE Setting_Manager SHALL mengizinkan field opsional (sosial media URL, Google Analytics Measurement ID, tagline, konten tambahan footer) untuk dikosongkan tanpa menyebabkan error

### Requirement 11: Frontend Company Profile

**User Story:** Sebagai visitor, saya ingin melihat website company profile MonetraPay dengan tampilan modern dan responsif, sehingga saya mendapatkan informasi lengkap tentang aplikasi MonetraPay.

#### Acceptance Criteria

1. THE Frontend SHALL menampilkan halaman beranda dengan hero section yang berisi heading utama, deskripsi singkat layanan MonetraPay (pulsa, PPOB, top-up game, e-money), dan call-to-action button
2. THE Frontend SHALL menggunakan TailwindCSS untuk seluruh styling dengan desain responsif pada breakpoint mobile (di bawah 768px), tablet (768px–1023px), dan desktop (1024px ke atas) sehingga seluruh konten dapat dibaca dan dinavigasi tanpa horizontal scrolling
3. THE Navbar SHALL merender item menu sesuai konfigurasi Menu_Builder dengan dukungan dropdown hingga 3 level kedalaman sesuai batas Menu_Builder
4. THE Frontend SHALL menampilkan halaman daftar blog dengan pagination maksimal 10 artikel per halaman, filter berdasarkan kategori, dan sidebar
5. THE Frontend SHALL menampilkan halaman detail blog post dengan konten lengkap, nama penulis, tanggal publikasi, dan maksimal 3 artikel terkait dari kategori yang sama
6. WHEN halaman detail blog post dirender dan terdapat slot iklan "auto-insert dalam blog" yang berstatus aktif, THE Frontend SHALL secara otomatis menyisipkan iklan AdSense setiap N paragraf sesuai konfigurasi interval di Ad_Manager, tanpa mengubah konten asli yang tersimpan di database
7. THE Frontend SHALL menampilkan halaman daftar kategori dengan jumlah artikel per kategori
8. THE Frontend SHALL menampilkan halaman statis sesuai konten yang dibuat di Page_Manager
9. WHEN Visitor memasukkan kata kunci pencarian minimal 3 karakter, THE Frontend SHALL menampilkan hasil pencarian dari blog posts dan pages yang cocok, atau menampilkan pesan "tidak ada hasil" jika tidak ditemukan kecocokan
10. THE Frontend SHALL menampilkan footer dengan informasi kontak, link sosial media, dan menu footer sesuai konfigurasi Setting_Manager dan Menu_Builder
11. WHILE maintenance mode aktif, THE Frontend SHALL menampilkan halaman "Coming Soon" kepada semua Visitor dan memblokir akses ke halaman lain, kecuali pengguna yang sudah login ke Admin_Panel tetap dapat mengakses Frontend
12. IF Visitor mengakses URL blog post atau page yang tidak ditemukan, THEN THE Frontend SHALL menampilkan halaman 404 dengan navigasi kembali ke beranda

### Requirement 12: Frontend Performance dan Aksesibilitas

**User Story:** Sebagai visitor, saya ingin website dimuat dengan cepat dan dapat diakses dengan baik, sehingga pengalaman browsing saya nyaman.

#### Acceptance Criteria

1. THE Frontend SHALL menggunakan lazy loading dengan atribut `loading="lazy"` untuk semua gambar yang berada di bawah viewport, dan setiap gambar yang di-lazy-load SHALL memiliki atribut `width` dan `height` eksplisit untuk mencegah layout shift
2. THE Frontend SHALL menyediakan breadcrumb navigation di halaman blog post, page, dan kategori, dengan format hierarki "Home > [Parent] > [Current Page]" yang setiap segmennya merupakan link yang dapat diklik kecuali segmen halaman saat ini
3. THE Frontend SHALL menggunakan semantic HTML tags (header, nav, main, article, aside, footer) pada setiap halaman, dengan tepat satu elemen `main` per halaman dan setiap halaman memiliki minimal satu landmark `header`, `nav`, `main`, dan `footer`
4. THE Frontend SHALL menyediakan skip-to-content link sebagai elemen fokus pertama dalam DOM yang tersembunyi secara visual namun menjadi terlihat saat menerima fokus keyboard, dan WHEN link tersebut diaktifkan, THE Frontend SHALL memindahkan fokus ke elemen `main` content
5. THE Frontend SHALL menggunakan transisi CSS pada navigasi dan interaksi UI dengan durasi maksimal 300ms
6. IF visitor mengaktifkan pengaturan `prefers-reduced-motion: reduce` pada sistem operasi, THEN THE Frontend SHALL menonaktifkan semua animasi dan transisi CSS, atau mengurangi durasi transisi menjadi 0ms
