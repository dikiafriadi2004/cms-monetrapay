<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class LegalPageSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus record lama jika ada
        Page::withTrashed()->whereIn('slug', ['faq', 'kebijakan-privasi', 'syarat-ketentuan'])->forceDelete();

        // =============================================
        // 1. FAQ
        // =============================================
        Page::create([
            'title'            => 'FAQ - Pertanyaan yang Sering Diajukan',
            'slug'             => 'faq',
            'template'         => 'default',
            'status'           => 'published',
            'use_page_builder' => false,
            'order'            => 10,
            'meta_title'       => 'FAQ - Pertanyaan yang Sering Diajukan | MonetraPay',
            'meta_description' => 'Temukan jawaban atas pertanyaan umum seputar layanan MonetraPay, platform pembayaran dan PPOB terpercaya.',
            'content'          => <<<'HTML'
<div class="faq-container">
  <h2>Pertanyaan yang Sering Diajukan</h2>

  <div class="faq-item">
    <h3>1. Apa itu MonetraPay?</h3>
    <p>MonetraPay adalah platform layanan pembayaran digital dan PPOB (Payment Point Online Bank) yang memungkinkan agen untuk melayani berbagai kebutuhan transaksi masyarakat, seperti pembayaran tagihan listrik, air, pulsa, internet, dan berbagai produk digital lainnya secara mudah dan cepat.</p>
  </div>

  <div class="faq-item">
    <h3>2. Bagaimana cara daftar menjadi agen MonetraPay?</h3>
    <p>Untuk menjadi agen MonetraPay, Anda dapat mendaftarkan diri melalui website resmi kami atau menghubungi tim kami. Syarat pendaftaran meliputi: KTP yang masih berlaku, nomor telepon aktif, dan rekening bank atas nama sendiri. Setelah verifikasi data, akun Anda akan aktif dalam 1x24 jam kerja.</p>
  </div>

  <div class="faq-item">
    <h3>3. Produk apa saja yang tersedia di MonetraPay?</h3>
    <p>MonetraPay menyediakan berbagai produk dan layanan, antara lain:</p>
    <ul>
      <li>Pulsa dan Paket Data (semua operator)</li>
      <li>Token dan Tagihan Listrik PLN</li>
      <li>Tagihan PDAM / Air</li>
      <li>Pembayaran BPJS Kesehatan dan Ketenagakerjaan</li>
      <li>Top Up E-Wallet (GoPay, OVO, Dana, dll)</li>
      <li>Voucher Game Online</li>
      <li>Tiket Pesawat dan Kereta Api</li>
      <li>Pembayaran TV Kabel / Internet</li>
      <li>Angsuran Pinjaman</li>
    </ul>
  </div>

  <div class="faq-item">
    <h3>4. Bagaimana cara melakukan transaksi?</h3>
    <p>Transaksi di MonetraPay sangat mudah. Login ke akun Anda, pilih kategori produk yang diinginkan, masukkan nomor tujuan atau ID pelanggan, pilih nominal, lalu konfirmasi transaksi. Pastikan saldo Anda mencukupi sebelum melakukan transaksi. Struk transaksi akan otomatis tersimpan di riwayat akun Anda.</p>
  </div>

  <div class="faq-item">
    <h3>5. Berapa lama proses deposit saldo?</h3>
    <p>Proses deposit saldo biasanya berlangsung dalam <strong>1-5 menit</strong> setelah pembayaran terkonfirmasi. Deposit via transfer bank otomatis akan diproses 24 jam sehari, 7 hari seminggu. Jika dalam 30 menit saldo belum masuk, segera hubungi customer service kami dengan menyertakan bukti transfer.</p>
  </div>

  <div class="faq-item">
    <h3>6. Apakah ada biaya pendaftaran menjadi agen?</h3>
    <p>Pendaftaran sebagai agen MonetraPay tidak dikenakan biaya pendaftaran. Namun, Anda diwajibkan melakukan deposit awal minimal sesuai ketentuan yang berlaku untuk dapat mulai bertransaksi. Informasi lebih lanjut mengenai syarat deposit awal dapat ditanyakan kepada tim kami.</p>
  </div>

  <div class="faq-item">
    <h3>7. Apa yang harus dilakukan jika transaksi gagal?</h3>
    <p>Jika transaksi Anda gagal, saldo akan otomatis dikembalikan ke akun Anda dalam waktu maksimal <strong>1x24 jam</strong>. Jika setelah 24 jam saldo belum kembali, segera hubungi customer service kami dengan menyertakan ID transaksi, nominal, dan bukti transaksi. Tim kami siap membantu menyelesaikan masalah Anda.</p>
  </div>

  <div class="faq-item">
    <h3>8. Bagaimana cara menghubungi customer service MonetraPay?</h3>
    <p>Anda dapat menghubungi customer service MonetraPay melalui beberapa saluran:</p>
    <ul>
      <li><strong>WhatsApp:</strong> Tersedia di halaman Kontak kami</li>
      <li><strong>Email:</strong> support@monetrapay.com</li>
      <li><strong>Live Chat:</strong> Melalui aplikasi atau website (jam operasional 08.00 - 22.00 WIB)</li>
    </ul>
    <p>Tim kami siap membantu Anda setiap hari.</p>
  </div>

  <div class="faq-item">
    <h3>9. Apakah data saya aman di MonetraPay?</h3>
    <p>Keamanan data pengguna adalah prioritas utama kami. MonetraPay menggunakan enkripsi SSL/TLS untuk melindungi setiap transaksi dan data pribadi Anda. Kami tidak pernah membagikan data pribadi Anda kepada pihak ketiga tanpa izin Anda, kecuali yang diwajibkan oleh hukum. Untuk detail lebih lanjut, silakan baca <a href="/kebijakan-privasi">Kebijakan Privasi</a> kami.</p>
  </div>

  <div class="faq-item">
    <h3>10. Bagaimana cara withdraw saldo?</h3>
    <p>Untuk melakukan withdraw saldo, masuk ke menu "Keuangan" di akun Anda, pilih "Tarik Saldo", masukkan jumlah yang ingin ditarik, pilih rekening tujuan, dan konfirmasi. Proses withdraw biasanya memakan waktu <strong>1x24 jam kerja</strong>. Pastikan rekening bank yang Anda daftarkan adalah rekening atas nama Anda sendiri.</p>
  </div>
</div>
HTML,
        ]);

        // =============================================
        // 2. Kebijakan Privasi
        // =============================================
        Page::create([
            'title'            => 'Kebijakan Privasi',
            'slug'             => 'kebijakan-privasi',
            'template'         => 'default',
            'status'           => 'published',
            'use_page_builder' => false,
            'order'            => 11,
            'meta_title'       => 'Kebijakan Privasi | MonetraPay',
            'meta_description' => 'Baca kebijakan privasi MonetraPay tentang pengumpulan dan penggunaan data pribadi Anda dalam layanan kami.',
            'content'          => <<<'HTML'
<div class="privacy-policy-container">
  <p><em>Terakhir diperbarui: Januari 2025</em></p>

  <h2>1. Pendahuluan</h2>
  <p>MonetraPay (&ldquo;kami&rdquo;, &ldquo;kita&rdquo;, atau &ldquo;Perusahaan&rdquo;) berkomitmen untuk melindungi privasi pengguna dan agen kami. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, mengungkapkan, dan melindungi informasi Anda ketika Anda menggunakan layanan, platform, atau aplikasi kami.</p>
  <p>Dengan menggunakan layanan MonetraPay, Anda menyetujui praktik yang dijelaskan dalam Kebijakan Privasi ini. Jika Anda tidak setuju dengan kebijakan ini, mohon untuk tidak menggunakan layanan kami.</p>

  <h2>2. Informasi yang Kami Kumpulkan</h2>

  <h3>2.1 Data Pribadi</h3>
  <p>Kami dapat mengumpulkan informasi berikut dari Anda:</p>
  <ul>
    <li>Nama lengkap</li>
    <li>Nomor Induk Kependudukan (NIK)</li>
    <li>Alamat tempat tinggal</li>
    <li>Nomor telepon dan alamat email</li>
    <li>Tanggal lahir</li>
    <li>Informasi rekening bank</li>
    <li>Foto KTP dan selfie untuk verifikasi identitas</li>
  </ul>

  <h3>2.2 Data Transaksi</h3>
  <p>Setiap transaksi yang Anda lakukan melalui platform kami dicatat, meliputi:</p>
  <ul>
    <li>Jenis dan nominal transaksi</li>
    <li>Waktu dan tanggal transaksi</li>
    <li>Nomor referensi transaksi</li>
    <li>Informasi produk atau layanan yang dibeli</li>
    <li>Riwayat saldo dan mutasi akun</li>
  </ul>

  <h3>2.3 Data Teknis</h3>
  <p>Secara otomatis kami mengumpulkan data teknis saat Anda menggunakan layanan kami:</p>
  <ul>
    <li>Alamat IP dan lokasi perkiraan</li>
    <li>Jenis dan versi browser atau perangkat</li>
    <li>Sistem operasi perangkat</li>
    <li>Data log akses dan aktivitas</li>
    <li>Cookie dan data sesi</li>
  </ul>

  <h2>3. Bagaimana Kami Menggunakan Informasi</h2>
  <p>Informasi yang kami kumpulkan digunakan untuk:</p>
  <ul>
    <li>Memverifikasi identitas Anda dan mencegah penipuan</li>
    <li>Memproses transaksi dan memberikan layanan yang Anda minta</li>
    <li>Mengelola akun dan memberikan dukungan pelanggan</li>
    <li>Mengirimkan notifikasi transaksi dan informasi akun</li>
    <li>Meningkatkan kualitas layanan dan mengembangkan fitur baru</li>
    <li>Mematuhi kewajiban hukum dan regulasi yang berlaku</li>
    <li>Mendeteksi dan mencegah aktivitas yang mencurigakan atau ilegal</li>
  </ul>

  <h2>4. Berbagi Informasi dengan Pihak Ketiga</h2>
  <p>Kami tidak menjual, menyewakan, atau memperdagangkan data pribadi Anda kepada pihak ketiga. Namun, kami dapat berbagi informasi Anda dalam situasi berikut:</p>
  <ul>
    <li><strong>Mitra Bisnis:</strong> Penyedia layanan pembayaran, bank, dan mitra teknis yang membantu kami mengoperasikan platform.</li>
    <li><strong>Kewajiban Hukum:</strong> Ketika diwajibkan oleh peraturan perundang-undangan, perintah pengadilan, atau otoritas pemerintah yang berwenang.</li>
    <li><strong>Keamanan:</strong> Untuk melindungi hak, properti, atau keselamatan MonetraPay, pengguna kami, atau masyarakat umum.</li>
  </ul>

  <h2>5. Keamanan Data</h2>
  <p>Kami mengimplementasikan langkah-langkah keamanan teknis dan organisasional yang tepat untuk melindungi data pribadi Anda, termasuk:</p>
  <ul>
    <li>Enkripsi data menggunakan SSL/TLS untuk transmisi data</li>
    <li>Enkripsi data sensitif yang tersimpan di database</li>
    <li>Akses terbatas hanya untuk karyawan yang memerlukan data tersebut</li>
    <li>Pemantauan sistem secara berkala untuk mendeteksi potensi ancaman</li>
    <li>Cadangan data yang aman dan prosedur pemulihan</li>
  </ul>
  <p>Meskipun kami berupaya keras untuk melindungi data Anda, tidak ada metode transmisi atau penyimpanan data yang 100% aman. Kami tidak dapat menjamin keamanan mutlak dari informasi yang Anda berikan.</p>

  <h2>6. Cookie dan Teknologi Pelacakan</h2>
  <p>Kami menggunakan cookie dan teknologi pelacakan serupa untuk meningkatkan pengalaman pengguna. Cookie membantu kami:</p>
  <ul>
    <li>Menjaga sesi login Anda tetap aktif</li>
    <li>Mengingat preferensi Anda</li>
    <li>Menganalisis cara penggunaan layanan kami</li>
    <li>Meningkatkan keamanan akun Anda</li>
  </ul>
  <p>Anda dapat mengatur browser Anda untuk menolak cookie, namun hal ini dapat mempengaruhi fungsi beberapa fitur layanan kami.</p>

  <h2>7. Hak-Hak Pengguna</h2>
  <p>Sebagai pengguna, Anda memiliki hak-hak berikut terkait data pribadi Anda:</p>
  <ul>
    <li><strong>Hak Akses:</strong> Meminta salinan data pribadi yang kami miliki tentang Anda.</li>
    <li><strong>Hak Koreksi:</strong> Meminta perbaikan data yang tidak akurat atau tidak lengkap.</li>
    <li><strong>Hak Penghapusan:</strong> Meminta penghapusan data pribadi Anda dalam kondisi tertentu.</li>
    <li><strong>Hak Pembatasan:</strong> Meminta pembatasan pemrosesan data Anda.</li>
    <li><strong>Hak Portabilitas:</strong> Menerima data Anda dalam format yang terstruktur dan mudah dibaca.</li>
  </ul>
  <p>Untuk menggunakan hak-hak ini, silakan hubungi kami melalui kontak yang tersedia di bawah.</p>

  <h2>8. Perubahan Kebijakan</h2>
  <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan material akan diberitahukan melalui email atau notifikasi di platform kami setidaknya 30 hari sebelum berlaku. Penggunaan layanan kami setelah perubahan berlaku berarti Anda menerima kebijakan yang diperbarui.</p>

  <h2>9. Kontak Kami</h2>
  <p>Jika Anda memiliki pertanyaan, kekhawatiran, atau ingin menggunakan hak-hak Anda terkait Kebijakan Privasi ini, silakan hubungi kami:</p>
  <ul>
    <li><strong>Email:</strong> privacy@monetrapay.com</li>
    <li><strong>Alamat:</strong> Sesuai alamat resmi yang tercantum di halaman Tentang Kami</li>
    <li><strong>Halaman Kontak:</strong> <a href="/kontak">Hubungi Kami</a></li>
  </ul>
</div>
HTML,
        ]);

        // =============================================
        // 3. Syarat & Ketentuan
        // =============================================
        Page::create([
            'title'            => 'Syarat & Ketentuan',
            'slug'             => 'syarat-ketentuan',
            'template'         => 'default',
            'status'           => 'published',
            'use_page_builder' => false,
            'order'            => 12,
            'meta_title'       => 'Syarat & Ketentuan | MonetraPay',
            'meta_description' => 'Baca syarat dan ketentuan penggunaan layanan MonetraPay sebelum mendaftar dan menggunakan platform kami.',
            'content'          => <<<'HTML'
<div class="terms-container">
  <p><em>Terakhir diperbarui: Januari 2025</em></p>

  <h2>1. Definisi dan Ketentuan Umum</h2>
  <p>Dalam Syarat &amp; Ketentuan ini, yang dimaksud dengan:</p>
  <ul>
    <li><strong>"MonetraPay"</strong> adalah platform layanan pembayaran digital dan PPOB yang dioperasikan oleh Perusahaan.</li>
    <li><strong>"Agen"</strong> adalah individu atau badan usaha yang telah terdaftar dan disetujui untuk menggunakan layanan MonetraPay.</li>
    <li><strong>"Layanan"</strong> mencakup seluruh fitur, produk, dan fasilitas yang tersedia di platform MonetraPay.</li>
    <li><strong>"Transaksi"</strong> adalah setiap aktivitas keuangan yang dilakukan melalui platform MonetraPay.</li>
    <li><strong>"Saldo"</strong> adalah nilai uang elektronik yang tersimpan di akun MonetraPay milik Agen.</li>
  </ul>
  <p>Dengan mendaftar dan menggunakan layanan MonetraPay, Anda menyatakan bahwa Anda telah membaca, memahami, dan menyetujui seluruh Syarat &amp; Ketentuan ini.</p>

  <h2>2. Persyaratan Pendaftaran Agen</h2>
  <p>Untuk mendaftar sebagai Agen MonetraPay, Anda harus memenuhi persyaratan berikut:</p>
  <ul>
    <li>Warga Negara Indonesia (WNI) yang berusia minimal 17 tahun</li>
    <li>Memiliki KTP yang masih berlaku</li>
    <li>Memiliki nomor telepon aktif yang terdaftar atas nama sendiri</li>
    <li>Memiliki rekening bank atas nama sendiri</li>
    <li>Memberikan informasi yang akurat, lengkap, dan terkini</li>
    <li>Menyetujui proses verifikasi identitas yang diperlukan</li>
  </ul>
  <p>MonetraPay berhak menolak pendaftaran atau menangguhkan akun jika ditemukan informasi yang tidak valid atau mencurigakan.</p>

  <h2>3. Hak dan Kewajiban Pengguna</h2>
  <h3>3.1 Hak Pengguna</h3>
  <ul>
    <li>Mengakses dan menggunakan seluruh layanan yang tersedia sesuai dengan paket yang dipilih</li>
    <li>Mendapatkan dukungan teknis dan customer service</li>
    <li>Mendapatkan informasi transparan mengenai biaya dan komisi transaksi</li>
    <li>Memperoleh struk atau bukti setiap transaksi yang dilakukan</li>
  </ul>
  <h3>3.2 Kewajiban Pengguna</h3>
  <ul>
    <li>Menjaga kerahasiaan username, password, dan PIN akun</li>
    <li>Bertanggung jawab atas setiap aktivitas yang terjadi di akun Anda</li>
    <li>Segera melaporkan kepada MonetraPay jika terjadi akses tidak sah pada akun</li>
    <li>Menggunakan layanan hanya untuk keperluan yang sah dan legal</li>
    <li>Tidak menggunakan layanan untuk tujuan penipuan, pencucian uang, atau kegiatan ilegal lainnya</li>
    <li>Memastikan kecukupan saldo sebelum melakukan transaksi</li>
  </ul>

  <h2>4. Hak dan Kewajiban MonetraPay</h2>
  <h3>4.1 Hak MonetraPay</h3>
  <ul>
    <li>Mengubah, memperbarui, atau menghentikan layanan sewaktu-waktu dengan pemberitahuan yang wajar</li>
    <li>Menangguhkan atau menutup akun yang melanggar syarat dan ketentuan</li>
    <li>Melakukan verifikasi identitas dan aktivitas pengguna untuk tujuan keamanan</li>
    <li>Mengubah tarif dan biaya layanan dengan pemberitahuan terlebih dahulu</li>
  </ul>
  <h3>4.2 Kewajiban MonetraPay</h3>
  <ul>
    <li>Menyediakan layanan yang handal dan dapat diandalkan</li>
    <li>Memberikan informasi yang akurat mengenai produk dan layanan</li>
    <li>Melindungi data dan privasi pengguna sesuai Kebijakan Privasi</li>
    <li>Menangani keluhan dan sengketa dengan cepat dan adil</li>
    <li>Mengembalikan saldo jika terjadi kegagalan transaksi yang bukan disebabkan oleh pengguna</li>
  </ul>

  <h2>5. Transaksi dan Pembayaran</h2>
  <ul>
    <li>Setiap transaksi yang telah berhasil diproses bersifat final dan tidak dapat dibatalkan, kecuali dalam kondisi khusus yang diatur oleh MonetraPay.</li>
    <li>Harga dan tarif produk dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya.</li>
    <li>Komisi dan keuntungan Agen akan dikreditkan ke saldo akun sesuai ketentuan yang berlaku.</li>
    <li>MonetraPay tidak bertanggung jawab atas kerugian yang timbul akibat kesalahan input data oleh Agen (misalnya: salah nomor tujuan).</li>
  </ul>

  <h2>6. Kebijakan Deposit dan Withdraw</h2>
  <h3>6.1 Deposit</h3>
  <ul>
    <li>Deposit dapat dilakukan melalui metode pembayaran yang tersedia di platform.</li>
    <li>Saldo akan dikreditkan setelah pembayaran terkonfirmasi oleh sistem.</li>
    <li>Deposit minimum sesuai ketentuan yang berlaku dan dapat berubah sewaktu-waktu.</li>
  </ul>
  <h3>6.2 Withdraw</h3>
  <ul>
    <li>Penarikan saldo hanya dapat dilakukan ke rekening bank yang terdaftar atas nama Agen.</li>
    <li>Proses withdraw memerlukan waktu maksimal 1x24 jam kerja.</li>
    <li>Terdapat batas minimum dan maksimum penarikan yang berlaku.</li>
    <li>MonetraPay berhak menahan proses withdraw jika terdapat indikasi aktivitas mencurigakan.</li>
  </ul>

  <h2>7. Larangan Penggunaan</h2>
  <p>Pengguna dilarang untuk:</p>
  <ul>
    <li>Melakukan transaksi palsu atau manipulasi data transaksi</li>
    <li>Menggunakan akun untuk kegiatan penipuan, phishing, atau kejahatan siber</li>
    <li>Mencoba meretas, memanipulasi, atau mengganggu sistem MonetraPay</li>
    <li>Mendaftarkan lebih dari satu akun untuk satu identitas</li>
    <li>Memperjualbelikan akun MonetraPay kepada pihak lain</li>
    <li>Melakukan transaksi yang berkaitan dengan pencucian uang atau pendanaan terorisme</li>
    <li>Menggunakan bot atau otomatisasi tanpa izin tertulis dari MonetraPay</li>
  </ul>

  <h2>8. Penghentian Akun</h2>
  <p>MonetraPay berhak menangguhkan atau menutup akun Agen secara permanen dalam kondisi berikut:</p>
  <ul>
    <li>Pelanggaran terhadap Syarat &amp; Ketentuan ini</li>
    <li>Aktivitas yang terindikasi penipuan atau ilegal</li>
    <li>Permintaan dari otoritas hukum yang berwenang</li>
    <li>Akun tidak aktif selama lebih dari 12 bulan</li>
    <li>Atas permintaan Agen sendiri</li>
  </ul>
  <p>Dalam hal penutupan akun, saldo yang tersisa akan dikembalikan setelah melewati proses verifikasi, kecuali jika saldo tersebut berkaitan dengan aktivitas yang melanggar ketentuan.</p>

  <h2>9. Batasan Tanggung Jawab</h2>
  <p>MonetraPay tidak bertanggung jawab atas:</p>
  <ul>
    <li>Kerugian yang timbul akibat gangguan jaringan, server, atau sistem di luar kendali kami</li>
    <li>Kerugian tidak langsung, insidental, atau konsekuensial</li>
    <li>Kesalahan transaksi yang disebabkan oleh kelalaian pengguna</li>
    <li>Gangguan layanan yang disebabkan oleh keadaan memaksa (force majeure)</li>
    <li>Tindakan atau kelalaian pihak ketiga yang terkait dengan layanan</li>
  </ul>

  <h2>10. Perubahan Syarat &amp; Ketentuan</h2>
  <p>MonetraPay berhak mengubah Syarat &amp; Ketentuan ini kapan saja. Perubahan signifikan akan diberitahukan melalui email atau notifikasi di platform minimal 14 hari sebelum berlaku. Penggunaan layanan yang berkelanjutan setelah perubahan berlaku dianggap sebagai penerimaan terhadap syarat yang diperbarui.</p>

  <h2>11. Hukum yang Berlaku</h2>
  <p>Syarat &amp; Ketentuan ini diatur dan ditafsirkan sesuai dengan hukum Republik Indonesia. Setiap sengketa yang timbul akan diselesaikan melalui musyawarah mufakat. Apabila tidak tercapai kesepakatan, sengketa akan diselesaikan melalui Badan Arbitrase Nasional Indonesia (BANI) atau pengadilan yang berwenang di Indonesia.</p>

  <h2>12. Kontak</h2>
  <p>Untuk pertanyaan mengenai Syarat &amp; Ketentuan ini, silakan hubungi kami:</p>
  <ul>
    <li><strong>Email:</strong> legal@monetrapay.com</li>
    <li><strong>Halaman Kontak:</strong> <a href="/kontak">Hubungi Kami</a></li>
  </ul>
</div>
HTML,
        ]);

        echo "LegalPageSeeder: 3 halaman legal berhasil dibuat (FAQ, Kebijakan Privasi, Syarat & Ketentuan).\n";
    }
}
