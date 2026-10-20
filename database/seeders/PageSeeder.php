<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            [
                'title'            => 'Kebijakan Privasi',
                'slug'             => 'kebijakan-privasi',
                'status'           => 'published',
                'template'         => 'default',
                'use_page_builder' => false,
                'meta_title'       => 'Kebijakan Privasi - MonetraPay',
                'meta_description' => 'Kebijakan privasi MonetraPay tentang pengumpulan dan penggunaan data pribadi pengguna.',
                'content'          => '
<h2>Kebijakan Privasi MonetraPay</h2>
<p>MonetraPay berkomitmen untuk melindungi privasi dan keamanan data pribadi pengguna kami. Kebijakan ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda.</p>

<h3>Informasi yang Kami Kumpulkan</h3>
<p>Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami, termasuk:</p>
<ul>
<li>Nama lengkap dan informasi kontak</li>
<li>Informasi akun seperti username dan password (terenkripsi)</li>
<li>Riwayat transaksi dan aktivitas platform</li>
<li>Informasi perangkat dan penggunaan layanan</li>
</ul>

<h3>Penggunaan Informasi</h3>
<p>Kami menggunakan informasi yang dikumpulkan untuk:</p>
<ul>
<li>Memproses transaksi dan memberikan layanan</li>
<li>Mengirimkan notifikasi transaksi dan pembaruan layanan</li>
<li>Meningkatkan keamanan dan mencegah penipuan</li>
<li>Memenuhi kewajiban hukum dan regulasi</li>
</ul>

<h3>Keamanan Data</h3>
<p>Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang sesuai untuk melindungi data pribadi Anda dari akses tidak sah, pengubahan, pengungkapan, atau penghancuran.</p>

<h3>Hak Pengguna</h3>
<p>Anda memiliki hak untuk mengakses, memperbaiki, atau menghapus data pribadi Anda. Untuk menggunakan hak ini, hubungi kami melalui halaman kontak.</p>

<h3>Perubahan Kebijakan</h3>
<p>Kami dapat memperbarui kebijakan privasi ini sewaktu-waktu. Perubahan signifikan akan diberitahukan melalui email atau notifikasi di platform kami.</p>

<p><strong>Terakhir diperbarui:</strong> ' . date('d F Y') . '</p>',
            ],
            [
                'title'            => 'Syarat & Ketentuan',
                'slug'             => 'syarat-ketentuan',
                'status'           => 'published',
                'template'         => 'default',
                'use_page_builder' => false,
                'meta_title'       => 'Syarat & Ketentuan - MonetraPay',
                'meta_description' => 'Syarat dan ketentuan penggunaan layanan platform MonetraPay.',
                'content'          => '
<h2>Syarat dan Ketentuan MonetraPay</h2>
<p>Dengan mendaftar dan menggunakan layanan MonetraPay, Anda menyetujui syarat dan ketentuan berikut. Bacalah dengan seksama sebelum menggunakan platform kami.</p>

<h3>1. Penerimaan Syarat</h3>
<p>Dengan mengakses atau menggunakan layanan MonetraPay, Anda menyatakan telah membaca, memahami, dan setuju untuk terikat dengan syarat dan ketentuan ini.</p>

<h3>2. Kualifikasi Pengguna</h3>
<p>Layanan kami tersedia untuk:</p>
<ul>
<li>Individu berusia minimal 17 tahun</li>
<li>Badan usaha yang terdaftar secara legal di Indonesia</li>
<li>Pengguna yang memiliki rekening bank atau dompet digital yang valid</li>
</ul>

<h3>3. Kebijakan Deposit dan Saldo</h3>
<p>Deposit bersifat tidak dapat dikembalikan kecuali dalam kondisi tertentu sesuai kebijakan refund kami. Saldo yang tidak digunakan dalam jangka waktu 12 bulan dapat dikenakan biaya administrasi.</p>

<h3>4. Tanggung Jawab Pengguna</h3>
<p>Pengguna bertanggung jawab untuk:</p>
<ul>
<li>Menjaga kerahasiaan akun dan password</li>
<li>Memastikan kebenaran data transaksi sebelum dikonfirmasi</li>
<li>Melaporkan transaksi yang mencurigakan segera kepada kami</li>
</ul>

<h3>5. Batasan Tanggung Jawab</h3>
<p>MonetraPay tidak bertanggung jawab atas kerugian yang timbul akibat: gangguan sistem dari pihak ketiga, force majeure, atau kesalahan pengguna dalam memasukkan data transaksi.</p>

<h3>6. Penghentian Layanan</h3>
<p>Kami berhak menangguhkan atau menghentikan akun pengguna yang melanggar syarat dan ketentuan ini tanpa pemberitahuan sebelumnya.</p>

<p><strong>Terakhir diperbarui:</strong> ' . date('d F Y') . '</p>',
            ],
            [
                'title'            => 'FAQ',
                'slug'             => 'faq',
                'status'           => 'published',
                'template'         => 'default',
                'use_page_builder' => false,
                'meta_title'       => 'FAQ - Pertanyaan yang Sering Diajukan | MonetraPay',
                'meta_description' => 'Temukan jawaban atas pertanyaan yang sering diajukan tentang layanan MonetraPay.',
                'content'          => '
<h2>Pertanyaan yang Sering Diajukan (FAQ)</h2>

<h3>Umum</h3>
<h4>Apa itu MonetraPay?</h4>
<p>MonetraPay adalah platform transaksi digital all-in-one yang menyediakan layanan penjualan pulsa, pembayaran tagihan (PPOB), top-up game, dan pengisian e-money. Kami hadir untuk memudahkan Anda membangun bisnis digital yang menguntungkan.</p>

<h4>Bagaimana cara mendaftar menjadi agen?</h4>
<p>Pendaftaran sangat mudah. Isi formulir di halaman Kontak kami, tim kami akan menghubungi Anda dalam 1x24 jam untuk proses aktivasi akun.</p>

<h4>Apakah ada biaya pendaftaran?</h4>
<p>Tidak ada biaya pendaftaran. Anda hanya perlu melakukan deposit awal untuk mulai bertransaksi.</p>

<h3>Transaksi</h3>
<h4>Berapa minimum deposit?</h4>
<p>Deposit minimum mulai dari Rp 10.000. Tidak ada maksimum deposit untuk akun yang telah terverifikasi.</p>

<h4>Bagaimana jika transaksi gagal?</h4>
<p>Setiap transaksi yang gagal akan otomatis di-refund ke saldo akun Anda dalam waktu maksimal 5 menit. Tidak ada uang yang hilang.</p>

<h4>Berapa lama proses transaksi?</h4>
<p>Transaksi diproses secara real-time, biasanya selesai dalam hitungan detik. Dalam kondisi jaringan sibuk, maksimal 5 menit.</p>

<h3>Produk</h3>
<h4>Operator apa saja yang tersedia untuk pulsa?</h4>
<p>Kami menyediakan pulsa untuk semua operator nasional: Telkomsel, Indosat, XL, Tri, Smartfren, dan Axis.</p>

<h4>Game apa saja yang tersedia untuk top-up?</h4>
<p>Tersedia lebih dari 100 game populer termasuk Mobile Legends, PUBG Mobile, Free Fire, Genshin Impact, Valorant, Call of Duty Mobile, dan banyak lagi.</p>

<h3>Keamanan</h3>
<h4>Apakah data saya aman?</h4>
<p>Ya. Kami menggunakan enkripsi SSL/TLS untuk semua transaksi dan menyimpan data dengan standar keamanan tinggi. Baca Kebijakan Privasi kami untuk informasi lebih lengkap.</p>

<h4>Bagaimana cara menghubungi support?</h4>
<p>Anda dapat menghubungi kami melalui halaman Kontak, email, atau WhatsApp yang tertera. Tim support kami aktif 24 jam, 7 hari seminggu.</p>',
            ],
        ];

        foreach ($pages as $data) {
            Page::updateOrCreate(['slug' => $data['slug']], $data);
        }

        $this->command->info('✅ Pages seeded (' . count($pages) . ' pages)');
    }
}
