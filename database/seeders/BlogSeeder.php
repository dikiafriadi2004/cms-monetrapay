<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::first();
        if (! $admin) {
            $this->command->warn('No user found. Please run RolePermissionSeeder first.');

            return;
        }

        $blogs = [
            [
                'title' => 'Cara Memulai Bisnis Pulsa dari Nol dengan Modal Minim',
                'slug' => 'cara-memulai-bisnis-pulsa-dari-nol',
                'excerpt' => 'Bisnis pulsa adalah salah satu peluang usaha yang menjanjikan di era digital. Pelajari cara memulainya dengan modal minimal dan keuntungan maksimal.',
                'content' => '<p>Bisnis pulsa adalah salah satu peluang usaha yang paling mudah dimulai di era digital ini. Dengan modal yang relatif kecil, Anda sudah bisa memulai bisnis yang menghasilkan pendapatan konsisten setiap harinya.</p>

<h2>Mengapa Bisnis Pulsa Menguntungkan?</h2>
<p>Pulsa adalah kebutuhan primer masyarakat modern. Hampir semua orang membutuhkan pulsa untuk berkomunikasi, mengakses internet, dan berbagai keperluan digital lainnya. Ini berarti permintaan akan selalu ada sepanjang waktu.</p>

<h2>Langkah Memulai Bisnis Pulsa</h2>
<p>Berikut adalah langkah-langkah untuk memulai bisnis pulsa:</p>
<ul>
<li>Daftar sebagai agen di platform terpercaya seperti MonetraPay</li>
<li>Lakukan deposit awal minimal Rp 10.000</li>
<li>Mulai jual pulsa ke keluarga, teman, dan tetangga</li>
<li>Kembangkan jaringan pelanggan secara bertahap</li>
</ul>

<h2>Tips Sukses Bisnis Pulsa</h2>
<p>Untuk sukses dalam bisnis pulsa, Anda perlu memperhatikan beberapa hal penting:</p>
<ul>
<li>Pilih platform dengan harga kompetitif dan sistem yang stabil</li>
<li>Berikan pelayanan yang cepat dan ramah</li>
<li>Manfaatkan media sosial untuk promosi</li>
<li>Selalu jaga stok saldo agar tidak kehabisan</li>
</ul>

<p>Dengan MonetraPay, Anda mendapatkan akses ke ribuan produk digital dengan harga terbaik. Daftar sekarang dan mulai perjalanan bisnis digital Anda!</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(5),
                'meta_title' => 'Cara Memulai Bisnis Pulsa dari Nol - MonetraPay',
                'meta_description' => 'Panduan lengkap memulai bisnis pulsa dengan modal minimal. Pelajari tips dan strategi sukses menjadi agen pulsa bersama MonetraPay.',
                'categories' => ['bisnis-digital', 'pulsa-data'],
            ],
            [
                'title' => 'Panduan Lengkap Top-Up Game Mobile Legends Diamond',
                'slug' => 'panduan-top-up-game-mobile-legends-diamond',
                'excerpt' => 'Cara mudah dan cepat top-up diamond Mobile Legends melalui MonetraPay. Harga terjangkau dengan proses instan.',
                'content' => '<p>Mobile Legends adalah salah satu game mobile paling populer di Indonesia. Jutaan pemain aktif setiap harinya membutuhkan diamond untuk membeli skin, hero, dan item lainnya.</p>

<h2>Apa itu Diamond Mobile Legends?</h2>
<p>Diamond adalah mata uang premium dalam game Mobile Legends yang digunakan untuk membeli berbagai item eksklusif. Dengan diamond, pemain bisa mendapatkan skin hero terbaru, efek recall, dan berbagai item menarik lainnya.</p>

<h2>Cara Top-Up Diamond ML via MonetraPay</h2>
<p>Proses top-up diamond Mobile Legends melalui MonetraPay sangat mudah:</p>
<ul>
<li>Login ke akun MonetraPay Anda</li>
<li>Pilih menu Top-Up Game</li>
<li>Pilih Mobile Legends</li>
<li>Masukkan User ID dan Zone ID</li>
<li>Pilih nominal diamond yang diinginkan</li>
<li>Konfirmasi dan bayar</li>
</ul>

<h2>Keuntungan Top-Up via MonetraPay</h2>
<p>Mengapa harus top-up melalui MonetraPay?</p>
<ul>
<li>Harga lebih murah dibanding top-up langsung</li>
<li>Proses instan, diamond langsung masuk</li>
<li>Tersedia 24 jam sehari, 7 hari seminggu</li>
<li>Berbagai metode pembayaran tersedia</li>
</ul>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(10),
                'meta_title' => 'Panduan Top-Up Diamond Mobile Legends - MonetraPay',
                'meta_description' => 'Cara mudah top-up diamond Mobile Legends dengan harga murah dan proses instan melalui MonetraPay.',
                'categories' => ['topup-game', 'tips-trik'],
            ],
            [
                'title' => '5 Keuntungan Menjadi Agen PPOB di Era Digital',
                'slug' => '5-keuntungan-menjadi-agen-ppob',
                'excerpt' => 'PPOB (Payment Point Online Bank) adalah bisnis yang terus berkembang. Temukan 5 keuntungan utama menjadi agen PPOB bersama MonetraPay.',
                'content' => '<p>PPOB atau Payment Point Online Bank adalah layanan pembayaran berbagai tagihan secara online. Bisnis ini semakin diminati karena kemudahan dan potensi keuntungannya yang besar.</p>

<h2>1. Modal Awal yang Kecil</h2>
<p>Salah satu keunggulan bisnis PPOB adalah modal awal yang sangat terjangkau. Dengan deposit mulai dari Rp 10.000, Anda sudah bisa mulai melayani pelanggan.</p>

<h2>2. Produk yang Beragam</h2>
<p>Sebagai agen PPOB, Anda bisa melayani berbagai jenis tagihan:</p>
<ul>
<li>Tagihan listrik PLN</li>
<li>Tagihan air PDAM</li>
<li>BPJS Kesehatan dan Ketenagakerjaan</li>
<li>Internet dan TV kabel</li>
<li>Cicilan kendaraan dan KPR</li>
</ul>

<h2>3. Komisi yang Menarik</h2>
<p>Setiap transaksi PPOB memberikan komisi yang langsung masuk ke saldo Anda. Semakin banyak transaksi, semakin besar penghasilan Anda.</p>

<h2>4. Bisa Dilakukan dari Rumah</h2>
<p>Bisnis PPOB bisa dijalankan dari mana saja, termasuk dari rumah. Anda hanya membutuhkan smartphone dan koneksi internet.</p>

<h2>5. Permintaan yang Stabil</h2>
<p>Tagihan listrik, air, dan internet adalah kebutuhan bulanan yang tidak bisa dihindari. Ini menjamin permintaan yang stabil sepanjang tahun.</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(15),
                'meta_title' => '5 Keuntungan Menjadi Agen PPOB - MonetraPay',
                'meta_description' => 'Temukan 5 keuntungan utama menjadi agen PPOB bersama MonetraPay. Modal kecil, komisi menarik, dan bisa dari rumah.',
                'categories' => ['ppob', 'bisnis-digital'],
            ],
            [
                'title' => 'Cara Isi Saldo GoPay, OVO, dan DANA dengan Mudah',
                'slug' => 'cara-isi-saldo-gopay-ovo-dana',
                'excerpt' => 'Panduan lengkap cara mengisi saldo berbagai e-wallet populer di Indonesia melalui MonetraPay dengan cepat dan mudah.',
                'content' => '<p>E-wallet atau dompet digital semakin populer di Indonesia. GoPay, OVO, dan DANA adalah tiga e-wallet terbesar yang digunakan oleh jutaan orang setiap harinya.</p>

<h2>Mengapa Isi Saldo E-Wallet via MonetraPay?</h2>
<p>MonetraPay menawarkan kemudahan pengisian saldo e-wallet dengan berbagai keunggulan:</p>
<ul>
<li>Proses cepat dan instan</li>
<li>Harga kompetitif</li>
<li>Tersedia 24/7</li>
<li>Berbagai pilihan nominal</li>
</ul>

<h2>Cara Isi Saldo GoPay</h2>
<p>Langkah-langkah mengisi saldo GoPay melalui MonetraPay:</p>
<ul>
<li>Buka aplikasi atau website MonetraPay</li>
<li>Pilih menu E-Money</li>
<li>Pilih GoPay</li>
<li>Masukkan nomor HP yang terdaftar di GoPay</li>
<li>Pilih nominal dan konfirmasi</li>
</ul>

<h2>Cara Isi Saldo OVO</h2>
<p>Proses pengisian saldo OVO hampir sama dengan GoPay. Pastikan nomor HP yang Anda masukkan sudah terdaftar di aplikasi OVO.</p>

<h2>Cara Isi Saldo DANA</h2>
<p>Untuk mengisi saldo DANA, Anda perlu memasukkan nomor HP atau email yang terdaftar di akun DANA Anda.</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(20),
                'meta_title' => 'Cara Isi Saldo GoPay, OVO, DANA - MonetraPay',
                'meta_description' => 'Panduan lengkap cara mengisi saldo GoPay, OVO, dan DANA dengan mudah dan cepat melalui MonetraPay.',
                'categories' => ['e-money', 'tips-trik'],
            ],
            [
                'title' => 'Promo Spesial Ramadan: Bonus Saldo untuk Agen Baru',
                'slug' => 'promo-spesial-ramadan-bonus-saldo',
                'excerpt' => 'Rayakan Ramadan bersama MonetraPay! Dapatkan bonus saldo eksklusif untuk setiap agen baru yang bergabung selama bulan Ramadan.',
                'content' => '<p>MonetraPay hadir dengan promo spesial di bulan Ramadan yang penuh berkah ini. Kami memberikan bonus saldo eksklusif untuk setiap agen baru yang bergabung selama periode promo.</p>

<h2>Detail Promo</h2>
<p>Berikut adalah detail promo Ramadan MonetraPay:</p>
<ul>
<li>Bonus saldo Rp 5.000 untuk deposit pertama minimal Rp 50.000</li>
<li>Cashback 2% untuk transaksi pulsa selama Ramadan</li>
<li>Gratis biaya admin untuk top-up e-wallet</li>
<li>Hadiah menarik untuk agen dengan transaksi terbanyak</li>
</ul>

<h2>Syarat dan Ketentuan</h2>
<p>Promo ini berlaku dengan syarat dan ketentuan sebagai berikut:</p>
<ul>
<li>Berlaku untuk agen baru yang mendaftar selama periode promo</li>
<li>Bonus saldo akan dikreditkan dalam 1x24 jam</li>
<li>Promo tidak dapat digabungkan dengan promo lainnya</li>
<li>MonetraPay berhak mengubah syarat dan ketentuan sewaktu-waktu</li>
</ul>

<p>Jangan lewatkan kesempatan emas ini! Daftar sekarang dan mulai raih keuntungan bersama MonetraPay.</p>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(3),
                'meta_title' => 'Promo Ramadan MonetraPay - Bonus Saldo Agen Baru',
                'meta_description' => 'Dapatkan bonus saldo eksklusif di bulan Ramadan. Daftar sebagai agen MonetraPay sekarang dan nikmati berbagai keuntungan.',
                'categories' => ['promo-penawaran'],
            ],
            [
                'title' => 'Mengenal Sistem Deposit dan Cara Kerja Bisnis Pulsa Online',
                'slug' => 'mengenal-sistem-deposit-bisnis-pulsa-online',
                'excerpt' => 'Pelajari cara kerja sistem deposit dalam bisnis pulsa online dan bagaimana cara mengelola saldo dengan efektif.',
                'content' => '<p>Sistem deposit adalah fondasi dari bisnis pulsa online. Memahami cara kerjanya akan membantu Anda mengelola bisnis dengan lebih efektif dan menguntungkan.</p>

<h2>Apa itu Sistem Deposit?</h2>
<p>Sistem deposit dalam bisnis pulsa berarti Anda perlu mengisi saldo terlebih dahulu sebelum bisa melakukan transaksi. Saldo ini kemudian digunakan untuk membeli produk digital yang akan dijual ke pelanggan.</p>

<h2>Cara Melakukan Deposit di MonetraPay</h2>
<p>MonetraPay menyediakan berbagai metode deposit yang mudah:</p>
<ul>
<li>Transfer bank (BCA, Mandiri, BNI, BRI)</li>
<li>Virtual Account</li>
<li>QRIS</li>
<li>E-wallet (GoPay, OVO, DANA)</li>
<li>Minimarket (Indomaret, Alfamart)</li>
</ul>

<h2>Tips Mengelola Saldo dengan Efektif</h2>
<p>Berikut tips untuk mengelola saldo bisnis pulsa Anda:</p>
<ul>
<li>Selalu jaga saldo minimal untuk menghindari kehabisan stok</li>
<li>Lakukan deposit secara rutin sesuai volume transaksi</li>
<li>Manfaatkan fitur notifikasi saldo rendah</li>
<li>Catat setiap transaksi untuk memantau keuntungan</li>
</ul>',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(25),
                'meta_title' => 'Sistem Deposit Bisnis Pulsa Online - MonetraPay',
                'meta_description' => 'Panduan lengkap memahami sistem deposit dalam bisnis pulsa online dan cara mengelola saldo dengan efektif di MonetraPay.',
                'categories' => ['bisnis-digital', 'tips-trik'],
            ],
        ];

        foreach ($blogs as $blogData) {
            $categorySlug = $blogData['categories'];
            unset($blogData['categories']);

            $blog = Blog::updateOrCreate(
                ['slug' => $blogData['slug']],
                array_merge($blogData, ['user_id' => $admin->id])
            );

            // Sync categories
            $categoryIds = Category::whereIn('slug', $categorySlug)->pluck('id');
            $blog->categories()->sync($categoryIds);
        }

        $this->command->info('Blogs seeded successfully.');
    }
}
