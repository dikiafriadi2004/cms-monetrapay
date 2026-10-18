# 📖 Panduan Pengguna Page Builder

## 🚀 Cara Menggunakan Page Builder

### Langkah 1: Buat atau Edit Halaman

#### Untuk Halaman Baru:
1. Buka menu **Pages** di sidebar admin
2. Klik tombol **"Tambah Halaman Baru"**
3. Isi **Judul** halaman
4. Scroll ke sidebar kanan, cari bagian **"Page Builder"**
5. **Aktifkan toggle "Gunakan Page Builder"**
6. Klik tombol **"Simpan"**

#### Untuk Halaman Yang Sudah Ada:
1. Buka menu **Pages** di sidebar admin
2. Klik tombol **"Edit"** pada halaman yang ingin diubah
3. Scroll ke sidebar kanan, cari bagian **"Page Builder"**
4. **Aktifkan toggle "Gunakan Page Builder"**
5. Klik tombol **"Perbarui"**

⚠️ **PENTING**: Anda HARUS menyimpan/update halaman terlebih dahulu sebelum membuka Page Builder!

---

### Langkah 2: Buka Page Builder Editor

Setelah halaman disimpan dengan Page Builder aktif:

1. Anda akan melihat **box hijau** dengan tulisan "Page Builder Aktif"
2. Klik tombol biru **"Open Page Builder"**
3. Anda akan diarahkan ke halaman Page Builder Editor

---

### Langkah 3: Tambah Komponen

Di halaman Page Builder, Anda akan melihat 15 komponen yang tersedia:

#### Komponen Headers
- **🚀 Hero** - Banner besar di bagian atas halaman
  - Background image/gradient
  - Judul besar + subtitle
  - Call-to-action button

#### Komponen Content
- **📝 Text Block** - Rich text content dengan editor WYSIWYG
- **✨ Features Grid** - Tampilan fitur dalam grid 2-4 kolom
- **👥 Team Members** - Profil team dengan foto
- **❓ FAQ Accordion** - Pertanyaan & jawaban yang bisa dibuka-tutup
- **📍 Map Location** - Embed Google Maps

#### Komponen Media
- **🖼️ Gallery** - Grid galeri foto
- **🎬 Video** - Embed YouTube/Vimeo

#### Komponen Forms
- **📧 Contact Form** - Form kontak dengan validasi
- **📬 Newsletter** - Email signup form

#### Komponen Conversion
- **🎯 Call-to-Action** - Section untuk ajakan aksi
- **💰 Pricing Tables** - Tabel harga/paket

#### Komponen Social
- **💬 Testimonials** - Testimoni pelanggan
- **🔗 Social Media** - Link ke social media

#### Komponen Advanced
- **💻 Custom HTML** - Insert HTML/JavaScript custom

---

### Langkah 4: Mengisi Konten Komponen

1. **Klik pada komponen** yang ingin ditambahkan
2. **Form akan muncul** di bawah grid komponen
3. **Isi semua field** yang diperlukan:
   - Title, Content, Images, Links, dll.
   - Setiap komponen punya field yang berbeda
4. **Klik "Add Component"**
5. Komponen akan muncul di list **"Active Components"**

---

### Langkah 5: Atur Urutan Komponen

Di bagian **"Active Components"**, Anda bisa:

- **↑ Arrow Up** - Pindahkan komponen ke atas
- **↓ Arrow Down** - Pindahkan komponen ke bawah
- **👁️ Eye Icon** - Show/Hide komponen
- **✏️ Edit** - Edit konten komponen
- **🗑️ Delete** - Hapus komponen

---

### Langkah 6: Preview & Publish

1. **Klik tombol "Preview"** di header untuk melihat hasil
2. Jika sudah sesuai, tutup preview
3. **Komponen otomatis tersimpan** saat Anda menambah/edit
4. Untuk mempublikasikan:
   - Kembali ke **Pages > Edit**
   - Ubah **Status** menjadi **"Published"**
   - Klik **"Perbarui"**

---

## ❌ Troubleshooting

### Error: "Page not found"
**Penyebab**: Anda mencoba akses URL Page Builder langsung tanpa ID page yang valid

**Solusi**: 
- Buka menu **Pages**
- Klik **Edit** pada halaman yang ingin diubah
- Klik tombol **"Open Page Builder"**

---

### Error: "Page Builder is not enabled"
**Penyebab**: Toggle "Gunakan Page Builder" belum diaktifkan/disimpan

**Solusi**:
1. Edit halaman tersebut
2. Aktifkan toggle **"Gunakan Page Builder"**
3. **SIMPAN** halaman
4. Baru bisa buka Page Builder

---

### Error: "Trying to get property 'components' on null"
**Penyebab**: Page ID tidak valid atau page tidak ada di database

**Solusi**:
1. Jangan akses URL `/admin/pages/{ID}/builder` secara manual
2. Selalu gunakan tombol **"Open Page Builder"** dari halaman edit
3. Pastikan halaman sudah tersimpan di database

---

### Toggle Page Builder tidak tersimpan
**Penyebab**: JavaScript error atau form tidak submit dengan benar

**Solusi**:
1. Buka browser console (F12) untuk cek error
2. Clear cache browser (`Ctrl + Shift + Del`)
3. Refresh halaman dan coba lagi
4. Pastikan klik tombol **"Simpan/Perbarui"**, bukan "Kembali"

---

### Konten TinyMCE tidak muncul di frontend
**Ini BUKAN bug!**

**Penjelasan**: 
- Jika **Page Builder aktif**, sistem akan **mengabaikan konten di TinyMCE editor**
- Halaman akan di-render dari **Page Components** yang Anda buat di Page Builder
- Konten TinyMCE hanya digunakan jika Page Builder **tidak aktif**

**Jika ingin pakai konten TinyMCE**:
1. Edit halaman
2. **Matikan toggle "Gunakan Page Builder"**
3. Simpan
4. Konten dari TinyMCE akan muncul

---

## 🎨 Tips & Trik

### Membuat Landing Page
1. Buat halaman baru dengan Page Builder
2. Tambahkan komponen dalam urutan ini:
   - Hero (banner utama)
   - Features (fitur produk)
   - Testimonials (social proof)
   - Pricing (tabel harga)
   - CTA (call-to-action)
   - Contact Form

### Halaman About
1. Text Block (intro company)
2. Team Members (profil tim)
3. Gallery (foto kantor/team)
4. CTA (hiring atau partnership)

### Halaman Contact
1. Text Block (jam operasional)
2. Contact Form
3. Map (lokasi kantor)
4. Social Media (link sosmed)

---

## 📊 Best Practices

### Performance
- Jangan gunakan terlalu banyak komponen (maks 10-15 per halaman)
- Compress gambar sebelum upload (gunakan TinyPNG)
- Hindari embed video terlalu banyak (gunakan thumbnail + play button)

### SEO
- Isi **Meta Title** dan **Meta Description** di halaman edit
- Gunakan heading H2, H3 di Text Block secara teratur
- Alt text untuk semua gambar di Gallery

### UX
- Hero harus selalu di posisi paling atas
- CTA (call-to-action) sebaiknya di akhir atau tengah halaman
- Jangan taruh form terlalu panjang (maks 5-7 field)

---

## 🔗 URL yang Perlu Diketahui

- **List Pages**: `/admin/pages`
- **Create Page**: `/admin/pages/create`
- **Edit Page**: `/admin/pages/{id}/edit`
- **Page Builder**: `/admin/pages/{id}/builder` (akses via tombol "Open Page Builder")
- **Preview Page**: `/admin/pages/{id}/builder/preview`
- **Frontend View**: `/{slug}` (slug halaman)

---

## 📞 Bantuan Lebih Lanjut

Jika masih ada masalah:

1. **Check dokumentasi**: Baca file `THEME_AND_BUILDER_GUIDE.md` untuk developer
2. **Check logs**: `storage/logs/laravel.log`
3. **Clear cache**: 
   ```bash
   php artisan optimize:clear
   php artisan config:clear
   php artisan route:clear
   ```
4. **Check database**: 
   ```sql
   SELECT id, title, use_page_builder FROM pages;
   SELECT * FROM page_components WHERE page_id = {ID};
   ```

---

**Versi**: 1.0.0  
**Last Updated**: {{ date('Y-m-d') }}  
**Project**: Monetrapay CMS
