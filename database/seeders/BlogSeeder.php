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
            $this->command->warn('No user found. Run RolePermissionSeeder first.');
            return;
        }

        $catTips  = Category::where('slug', 'tips-trik')->first();
        $catPulsa = Category::where('slug', 'pulsa-data')->first();
        $catBisnis= Category::where('slug', 'bisnis-digital')->first();
        $catPpob  = Category::where('slug', 'ppob')->first();
        $catGame  = Category::where('slug', 'topup-game')->first();

        $blogs = [
            [
                'title'        => 'Cara Memulai Bisnis Pulsa dari Nol dengan Modal Minim',
                'slug'         => 'cara-memulai-bisnis-pulsa-dari-nol',
                'excerpt'      => 'Bisnis pulsa adalah salah satu peluang usaha yang menjanjikan di era digital. Pelajari cara memulainya dengan modal minimal dan keuntungan maksimal.',
                'status'       => 'published',
                'published_at' => Carbon::now()->subDays(7),
                'category'     => $catBisnis?->id,
                'content'      => '
<p>Bisnis pulsa adalah salah satu peluang usaha yang paling mudah dimulai di era digital ini. Dengan modal yang relatif kecil, Anda sudah bisa memulai bisnis yang menghasilkan pendapatan konsisten setiap harinya.</p>

<h2>Mengapa Bisnis Pulsa Menguntungkan?</h2>
<p>Pulsa adalah kebutuhan primer masyarakat modern. Hampir semua orang membutuhkan pulsa setiap hari untuk berkomunikasi dan mengakses internet. Permintaan akan selalu ada sepanjang waktu, menjadikan bisnis ini sangat stabil.</p>

<h2>Langkah Memulai Bisnis Pulsa</h2>
<h3>1. Pilih Platform yang Tepat</h3>
<p>Daftarkan diri ke platform terpercaya seperti MonetraPay yang menyediakan akses ke semua operator dengan harga grosir. Pastikan platform memiliki sistem yang stabil dan support yang responsif.</p>

<h3>2. Tentukan Modal Awal</h3>
<p>Modal awal untuk bisnis pulsa sangat fleksibel. Anda bisa memulai dari Rp 50.000 - Rp 500.000 tergantung target pasar dan volume transaksi yang diinginkan.</p>

<h3>3. Bangun Jaringan Pelanggan</h3>
<p>Mulai dari lingkungan terdekat: keluarga, teman, tetangga, dan rekan kerja. Manfaatkan media sosial untuk memperluas jangkauan pelanggan Anda.</p>

<h3>4. Kelola Saldo dengan Bijak</h3>
<p>Pastikan selalu ada saldo yang cukup untuk melayani pelanggan. Lakukan top-up secara berkala dan pantau tren kebutuhan pelanggan Anda.</p>

<h2>Tips Meningkatkan Keuntungan</h2>
<ul>
<li>Tawarkan berbagai produk digital selain pulsa (PPOB, game, e-money)</li>
<li>Berikan pelayanan 24 jam untuk pelanggan yang butuh transaksi malam</li>
<li>Manfaatkan program referral untuk mendapatkan komisi tambahan</li>
<li>Catat semua transaksi untuk evaluasi dan perencanaan bisnis</li>
</ul>

<p>Dengan strategi yang tepat dan platform yang andal, bisnis pulsa bisa menjadi sumber pendapatan utama yang menguntungkan. Mulailah hari ini!</p>',
            ],
            [
                'title'        => 'Panduan Lengkap PPOB untuk Pemula: Dari A sampai Z',
                'slug'         => 'panduan-lengkap-ppob-untuk-pemula',
                'excerpt'      => 'PPOB (Payment Point Online Bank) adalah layanan pembayaran tagihan yang menguntungkan. Pelajari cara kerjanya dan bagaimana memaksimalkan keuntungan dari bisnis ini.',
                'status'       => 'published',
                'published_at' => Carbon::now()->subDays(14),
                'category'     => $catPpob?->id,
                'content'      => '
<p>PPOB (Payment Point Online Bank) adalah sistem pembayaran tagihan yang memungkinkan siapa saja menjadi agen pembayaran. Dengan PPOB, Anda bisa membantu pelanggan membayar berbagai tagihan sambil mendapatkan komisi dari setiap transaksi.</p>

<h2>Apa Saja yang Bisa Dibayar Melalui PPOB?</h2>
<ul>
<li><strong>Tagihan PLN</strong> — listrik pascabayar, token listrik</li>
<li><strong>PDAM</strong> — tagihan air bulanan</li>
<li><strong>BPJS</strong> — BPJS Kesehatan dan BPJS Ketenagakerjaan</li>
<li><strong>Internet & TV</strong> — Indihome, Biznet, First Media, dll</li>
<li><strong>Cicilan</strong> — angsuran kendaraan, KPR, kredit lainnya</li>
<li><strong>Pajak</strong> — PBB dan pajak kendaraan bermotor</li>
</ul>

<h2>Cara Kerja PPOB</h2>
<p>Sistem PPOB bekerja sederhana: Anda sebagai agen menerima pembayaran dari pelanggan, kemudian melakukan transaksi melalui platform seperti MonetraPay. Setiap transaksi berhasil, Anda mendapatkan komisi yang sudah ditetapkan.</p>

<h2>Keunggulan Bisnis PPOB</h2>
<ol>
<li>Modal kecil, komisi stabil setiap transaksi</li>
<li>Permintaan tidak pernah sepi karena tagihan selalu ada setiap bulan</li>
<li>Transaksi mudah dan cepat, bisa dilakukan dari smartphone</li>
<li>Tidak perlu toko fisik, bisa dijalankan dari rumah</li>
</ol>',
            ],
            [
                'title'        => '5 Game Mobile Terpopuler yang Paling Banyak Dicari untuk Top-Up',
                'slug'         => '5-game-mobile-terpopuler-top-up',
                'excerpt'      => 'Mengetahui game yang paling banyak dicari pelanggan membantu Anda memaksimalkan layanan top-up. Berikut 5 game terpopuler yang wajib Anda sediakan.',
                'status'       => 'published',
                'published_at' => Carbon::now()->subDays(3),
                'category'     => $catGame?->id,
                'content'      => '
<p>Bisnis top-up game semakin menjanjikan seiring meningkatnya jumlah gamer mobile di Indonesia. Mengetahui game mana yang paling banyak dicari akan membantu Anda melayani pelanggan dengan lebih baik.</p>

<h2>1. Mobile Legends: Bang Bang</h2>
<p>Mobile Legends tetap menjadi raja game mobile di Indonesia. Diamond ML selalu menjadi produk top-up yang paling banyak dicari. Permintaan meningkat signifikan saat ada event in-game atau update karakter baru.</p>

<h2>2. PUBG Mobile</h2>
<p>UC (Unknown Cash) PUBG Mobile selalu jadi andalan. Banyak pemain yang rutin membeli UC untuk Elite Pass bulanan dan skin senjata terbaru.</p>

<h2>3. Free Fire</h2>
<p>Diamond Free Fire sangat populer di segmen pemain muda. Event-event spesial membuat permintaan diamond melonjak drastis.</p>

<h2>4. Genshin Impact</h2>
<p>Genesis Crystal Genshin Impact banyak dicari oleh pemain yang ingin mendapatkan karakter atau senjata baru melalui sistem gacha.</p>

<h2>5. Valorant</h2>
<p>VP (Valorant Points) untuk platform PC semakin banyak dicari seiring pertumbuhan basis pemain Valorant di Indonesia.</p>

<h2>Tips Melayani Pelanggan Top-Up Game</h2>
<ul>
<li>Pantau update dan event terbaru di setiap game untuk antisipasi lonjakan permintaan</li>
<li>Sediakan semua denominasi top-up yang tersedia</li>
<li>Proses transaksi dengan cepat karena gamer biasanya tidak suka menunggu</li>
<li>Tawarkan konfirmasi instan via pesan</li>
</ul>',
            ],
            [
                'title'        => 'Tips Meningkatkan Omzet Bisnis Digital: Strategi yang Terbukti Berhasil',
                'slug'         => 'tips-meningkatkan-omzet-bisnis-digital',
                'excerpt'      => 'Ingin omzet bisnis digital Anda terus meningkat? Berikut strategi yang terbukti berhasil digunakan oleh agen-agen sukses MonetraPay.',
                'status'       => 'published',
                'published_at' => Carbon::now()->subDays(1),
                'category'     => $catTips?->id,
                'content'      => '
<p>Meningkatkan omzet bisnis digital membutuhkan strategi yang tepat. Berikut adalah tips yang sudah terbukti berhasil digunakan oleh ribuan agen sukses.</p>

<h2>1. Perluas Produk yang Ditawarkan</h2>
<p>Jangan hanya menjual satu jenis produk. Agen yang sukses biasanya menawarkan pulsa, PPOB, top-up game, dan e-money sekaligus. Semakin banyak produk, semakin besar peluang transaksi.</p>

<h2>2. Manfaatkan Media Sosial</h2>
<p>WhatsApp Business, Instagram, dan Facebook adalah media yang sangat efektif untuk promosi. Buat konten menarik seputar promo dan kemudahan transaksi digital.</p>

<h2>3. Berikan Pelayanan Prima</h2>
<p>Kecepatan dan keramahan adalah kunci. Pelanggan yang puas akan merekomendasikan layanan Anda ke orang lain. Word of mouth adalah marketing terbaik dan paling murah.</p>

<h2>4. Aktif 24 Jam</h2>
<p>Banyak transaksi digital terjadi di malam hari. Dengan sistem otomatis dari platform seperti MonetraPay, Anda bisa melayani pelanggan kapan saja tanpa harus terjaga sepanjang malam.</p>

<h2>5. Program Referral</h2>
<p>Daftarkan kerabat dan teman sebagai sub-agen. Setiap transaksi yang mereka lakukan akan memberikan komisi tambahan untuk Anda. Semakin banyak jaringan, semakin besar pendapatan.</p>',
            ],
            [
                'title'        => 'Dompet Digital Mana yang Paling Banyak Digunakan di Indonesia?',
                'slug'         => 'dompet-digital-terpopuler-indonesia',
                'excerpt'      => 'GoPay, OVO, DANA, atau ShopeePay? Mana yang paling banyak digunakan masyarakat Indonesia? Simak data dan analisisnya di sini.',
                'status'       => 'published',
                'published_at' => Carbon::now()->subDays(10),
                'category'     => $catPulsa?->id,
                'content'      => '
<p>Penggunaan dompet digital di Indonesia terus meningkat pesat. Sebagai agen bisnis digital, memahami preferensi pengguna e-wallet akan membantu Anda melayani pelanggan dengan lebih baik.</p>

<h2>GoPay</h2>
<p>GoPay tetap menjadi salah satu e-wallet terpopuler berkat integrasi dengan ekosistem Gojek yang sangat luas. Digunakan untuk bayar GoRide, GoFood, belanja online, dan berbagai transaksi harian.</p>

<h2>OVO</h2>
<p>OVO dengan ekosistem Grab dan Tokopedia menjadi pilihan utama pengguna kedua platform tersebut. Program poin OVO juga menjadi daya tarik tersendiri.</p>

<h2>DANA</h2>
<p>DANA populer di kalangan pengguna yang sering berbelanja di marketplace. Fitur kirim uang yang mudah membuatnya jadi pilihan untuk transfer antar pengguna.</p>

<h2>ShopeePay</h2>
<p>ShopeePay berkembang pesat seiring pertumbuhan Shopee sebagai marketplace terbesar. Cashback yang agresif membuat banyak pengguna beralih ke ShopeePay.</p>

<h2>LinkAja</h2>
<p>LinkAja merupakan e-wallet yang terintegrasi dengan layanan BUMN. Cocok untuk pembayaran transportasi umum, tagihan, dan berbagai layanan pemerintah.</p>

<h2>Kesimpulan</h2>
<p>Sebagai agen bisnis digital, pastikan Anda menyediakan top-up untuk semua e-wallet populer. Dengan platform MonetraPay, Anda bisa mengisi semua dompet digital tersebut dalam satu sistem yang terintegrasi.</p>',
            ],
        ];

        foreach ($blogs as $data) {
            $catId = $data['category'];
            unset($data['category']);

            $blog = Blog::updateOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['user_id' => $admin->id])
            );

            if ($catId && $blog) {
                $blog->categories()->syncWithoutDetaching([$catId]);
            }
        }

        $this->command->info('✅ Blogs seeded (' . count($blogs) . ' posts)');
    }
}
