<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DummyBlogSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        
        // Pastikan ada kategori
        $categories = [
            ['name' => 'Tips & Trik', 'slug' => 'tips-trik', 'description' => 'Tips dan trik seputar bisnis digital'],
            ['name' => 'Berita', 'slug' => 'berita', 'description' => 'Berita terbaru seputar fintech'],
            ['name' => 'Tutorial', 'slug' => 'tutorial', 'description' => 'Panduan lengkap penggunaan layanan'],
            ['name' => 'Promo', 'slug' => 'promo', 'description' => 'Promo dan penawaran terbaru'],
        ];

        $categoryIds = [];
        foreach ($categories as $cat) {
            $category = Category::firstOrCreate(['slug' => $cat['slug']], [
                'name'        => $cat['name'],
                'description' => $cat['description'],
            ]);
            $categoryIds[] = $category->id;
        }

        $articles = [
            [
                'title'   => 'Cara Daftar Menjadi Agen MonetraPay dengan Mudah',
                'excerpt' => 'Panduan lengkap mendaftar sebagai agen MonetraPay dan mulai bisnis digital Anda.',
                'content' => '<p>MonetraPay membuka kesempatan bagi siapa saja untuk menjadi agen dan meraih penghasilan tambahan. Dengan modal minimal, Anda sudah bisa memulai bisnis digital yang menguntungkan.</p><h2>Persyaratan Pendaftaran</h2><p>Untuk mendaftar sebagai agen MonetraPay, Anda hanya perlu memenuhi beberapa persyaratan sederhana: KTP yang masih berlaku, nomor HP aktif, dan email yang valid.</p><h2>Langkah Pendaftaran</h2><ol><li>Kunjungi website MonetraPay</li><li>Klik tombol "Daftar Jadi Agen"</li><li>Isi formulir pendaftaran</li><li>Verifikasi email dan nomor HP</li><li>Lakukan deposit awal</li><li>Mulai bertransaksi!</li></ol>',
                'category' => 'tutorial',
            ],
            [
                'title'   => 'Keuntungan Menjadi Agen Pulsa di Era Digital',
                'excerpt' => 'Bisnis pulsa masih menjanjikan di era digital. Simak keuntungan menjadi agen pulsa bersama MonetraPay.',
                'content' => '<p>Di era digital seperti sekarang, bisnis pulsa dan PPOB masih sangat menjanjikan. Setiap orang membutuhkan pulsa, paket data, dan berbagai layanan digital lainnya setiap hari.</p><h2>Margin Keuntungan Kompetitif</h2><p>Sebagai agen MonetraPay, Anda mendapatkan margin keuntungan yang kompetitif untuk setiap transaksi. Semakin banyak transaksi, semakin besar penghasilan Anda.</p><h2>Produk yang Beragam</h2><p>Tidak hanya pulsa, Anda bisa menjual berbagai produk: token listrik PLN, BPJS, top-up e-wallet, voucher game, dan masih banyak lagi.</p>',
                'category' => 'tips-trik',
            ],
            [
                'title'   => 'MonetraPay Luncurkan Fitur Transfer Antar Bank Gratis',
                'excerpt' => 'MonetraPay kini hadir dengan fitur transfer antar bank gratis tanpa biaya admin.',
                'content' => '<p>Kabar gembira bagi seluruh pengguna MonetraPay! Mulai bulan ini, kami meluncurkan fitur transfer antar bank yang bebas biaya admin.</p><h2>Bank yang Didukung</h2><p>Fitur ini mendukung lebih dari 100 bank di seluruh Indonesia, termasuk BCA, BNI, BRI, Mandiri, dan bank-bank lainnya.</p><h2>Cara Menggunakan</h2><p>Untuk menggunakan fitur ini, cukup login ke aplikasi MonetraPay, pilih menu Transfer, masukkan nomor rekening tujuan, dan konfirmasi transaksi. Mudah dan cepat!</p>',
                'category' => 'berita',
            ],
            [
                'title'   => 'Tips Meningkatkan Omzet Bisnis Agen Pulsa',
                'excerpt' => 'Strategi jitu untuk meningkatkan omzet bisnis agen pulsa dan PPOB Anda.',
                'content' => '<p>Ingin omzet bisnis agen pulsa Anda terus meningkat? Berikut beberapa tips yang bisa Anda terapkan untuk mengembangkan bisnis Anda.</p><h2>1. Perluas Jangkauan Pelanggan</h2><p>Manfaatkan media sosial untuk mempromosikan layanan Anda. Buat konten yang menarik dan informatif untuk menarik calon pelanggan baru.</p><h2>2. Berikan Pelayanan Terbaik</h2><p>Pelanggan yang puas akan kembali dan merekomendasikan layanan Anda kepada orang lain. Pastikan setiap transaksi diproses dengan cepat dan tepat.</p><h2>3. Manfaatkan Fitur Cashback</h2><p>MonetraPay memberikan cashback untuk setiap transaksi tertentu. Manfaatkan program ini untuk memberikan nilai lebih kepada pelanggan Anda.</p>',
                'category' => 'tips-trik',
            ],
            [
                'title'   => 'Promo Spesial Hari Kemerdekaan: Cashback 17% untuk Semua Transaksi',
                'excerpt' => 'Rayakan Hari Kemerdekaan Indonesia dengan promo cashback 17% untuk semua transaksi di MonetraPay.',
                'content' => '<p>Dalam rangka memperingati Hari Kemerdekaan Indonesia, MonetraPay memberikan promo spesial cashback 17% untuk semua jenis transaksi!</p><h2>Syarat dan Ketentuan</h2><ul><li>Berlaku untuk semua pengguna aktif MonetraPay</li><li>Minimum transaksi Rp 50.000</li><li>Maksimum cashback Rp 170.000</li><li>Berlaku 17-31 Agustus</li></ul><h2>Cara Klaim</h2><p>Cashback akan otomatis masuk ke saldo MonetraPay Anda dalam 1x24 jam setelah transaksi berhasil.</p>',
                'category' => 'promo',
            ],
            [
                'title'   => 'Panduan Lengkap Pembayaran BPJS via MonetraPay',
                'excerpt' => 'Bayar BPJS Kesehatan dan BPJS Ketenagakerjaan kini lebih mudah lewat MonetraPay.',
                'content' => '<p>Membayar iuran BPJS kini tidak perlu antri panjang di kantor BPJS atau minimarket. Dengan MonetraPay, Anda bisa bayar BPJS kapan saja dan di mana saja.</p><h2>Jenis BPJS yang Bisa Dibayar</h2><ul><li>BPJS Kesehatan (per peserta)</li><li>BPJS Ketenagakerjaan</li></ul><h2>Cara Pembayaran</h2><ol><li>Buka aplikasi MonetraPay</li><li>Pilih menu BPJS</li><li>Masukkan nomor peserta BPJS</li><li>Pilih bulan pembayaran</li><li>Konfirmasi dan bayar</li></ol><p>Transaksi akan diproses dalam hitungan detik dan Anda akan menerima bukti pembayaran via email.</p>',
                'category' => 'tutorial',
            ],
            [
                'title'   => 'Top Up Game Online Lebih Hemat dengan MonetraPay',
                'excerpt' => 'Top up diamond, UC, dan berbagai item game favorit Anda dengan harga lebih hemat di MonetraPay.',
                'content' => '<p>Para gamer wajib tahu! MonetraPay menyediakan layanan top up game online dengan harga yang lebih hemat dibanding platform lain.</p><h2>Game yang Didukung</h2><p>MonetraPay mendukung ratusan game populer termasuk Mobile Legends, Free Fire, PUBG Mobile, Genshin Impact, dan masih banyak lagi.</p><h2>Keuntungan Top Up via MonetraPay</h2><ul><li>Harga lebih murah</li><li>Proses instan</li><li>Tersedia 24 jam</li><li>Berbagai metode pembayaran</li></ul>',
                'category' => 'tips-trik',
            ],
            [
                'title'   => 'MonetraPay Raih Penghargaan Fintech Terbaik 2026',
                'excerpt' => 'MonetraPay berhasil meraih penghargaan sebagai platform fintech terbaik di Indonesia tahun 2026.',
                'content' => '<p>Kami dengan bangga mengumumkan bahwa MonetraPay telah berhasil meraih penghargaan sebagai Platform Fintech Terbaik Indonesia 2026 dari lembaga independen FinAward Indonesia.</p><h2>Tentang Penghargaan</h2><p>Penghargaan ini diberikan berdasarkan penilaian terhadap inovasi produk, kepuasan pengguna, keamanan transaksi, dan kontribusi terhadap inklusi keuangan di Indonesia.</p><h2>Ucapan Terima Kasih</h2><p>Penghargaan ini adalah milik seluruh pengguna dan mitra MonetraPay yang telah mempercayakan transaksi keuangan mereka kepada kami.</p>',
                'category' => 'berita',
            ],
            [
                'title'   => 'Cara Cek Tagihan Listrik PLN dan Bayar Lewat MonetraPay',
                'excerpt' => 'Panduan mudah cek tagihan listrik PLN dan melakukan pembayaran melalui aplikasi MonetraPay.',
                'content' => '<p>Lupa membayar tagihan listrik? Jangan khawatir, dengan MonetraPay Anda bisa cek sekaligus bayar tagihan listrik PLN dalam satu aplikasi.</p><h2>Cara Cek Tagihan</h2><ol><li>Login ke MonetraPay</li><li>Pilih menu Listrik PLN</li><li>Masukkan ID Pelanggan</li><li>Klik "Cek Tagihan"</li></ol><h2>Cara Bayar</h2><p>Setelah tagihan muncul, klik "Bayar Sekarang" dan konfirmasi pembayaran. Dana akan otomatis terpotong dari saldo MonetraPay Anda.</p>',
                'category' => 'tutorial',
            ],
            [
                'title'   => 'Strategi Marketing untuk Agen MonetraPay Pemula',
                'excerpt' => 'Panduan marketing sederhana namun efektif untuk agen MonetraPay yang baru memulai bisnis.',
                'content' => '<p>Baru bergabung sebagai agen MonetraPay dan bingung cara menarik pelanggan? Berikut strategi marketing sederhana yang bisa langsung Anda terapkan.</p><h2>Media Sosial sebagai Senjata Utama</h2><p>Manfaatkan WhatsApp, Instagram, dan Facebook untuk mempromosikan layanan Anda. Buat status atau postingan rutin tentang produk dan layanan yang Anda tawarkan.</p><h2>Word of Mouth</h2><p>Mulai dari lingkungan terdekat: keluarga, tetangga, teman, dan rekan kerja. Kepercayaan dari orang-orang terdekat adalah modal terbaik untuk memulai bisnis.</p><h2>Program Referral</h2><p>Manfaatkan program referral MonetraPay untuk mendapatkan bonus tambahan setiap kali Anda berhasil mengajak agen baru bergabung.</p>',
                'category' => 'tips-trik',
            ],
            [
                'title'   => 'Update Aplikasi MonetraPay v3.0: Fitur Baru yang Wajib Dicoba',
                'excerpt' => 'MonetraPay merilis update besar v3.0 dengan tampilan baru dan fitur-fitur canggih.',
                'content' => '<p>MonetraPay v3.0 kini telah tersedia di App Store dan Google Play. Update ini membawa perubahan besar dengan tampilan yang lebih modern dan fitur-fitur baru yang memudahkan transaksi Anda.</p><h2>Fitur Baru v3.0</h2><ul><li>Tampilan dashboard yang lebih intuitif</li><li>Laporan transaksi real-time</li><li>Fitur multi-akun untuk agen dengan banyak outlet</li><li>Notifikasi transaksi yang lebih detail</li><li>Mode gelap (dark mode)</li></ul><h2>Cara Update</h2><p>Buka App Store atau Google Play, cari MonetraPay, dan klik Update. Atau kunjungi link yang tersedia di website kami.</p>',
                'category' => 'berita',
            ],
            [
                'title'   => 'Kenali Modus Penipuan Berkedok Agen Pulsa dan Cara Menghindarinya',
                'excerpt' => 'Waspadai berbagai modus penipuan yang mengatasnamakan bisnis agen pulsa. Simak cara menghindarinya.',
                'content' => '<p>Seiring berkembangnya bisnis agen pulsa dan PPOB, modus penipuan juga semakin beragam. Sebagai agen yang cerdas, Anda perlu mengenali dan menghindari modus-modus penipuan ini.</p><h2>Modus Penipuan yang Umum</h2><h3>1. Fake Transfer</h3><p>Penipu mengirimkan bukti transfer palsu dan meminta Anda memproses transaksi sebelum dana masuk ke rekening.</p><h3>2. Phishing</h3><p>Penipu membuat website atau aplikasi palsu yang mirip dengan MonetraPay untuk mencuri data login Anda.</p><h2>Cara Menghindari</h2><ul><li>Selalu verifikasi pembayaran langsung di aplikasi MonetraPay</li><li>Jangan pernah membagikan PIN atau password kepada siapapun</li><li>Hanya download aplikasi dari sumber resmi</li></ul>',
                'category' => 'tips-trik',
            ],
            [
                'title'   => 'Cara Withdraw Saldo MonetraPay ke Rekening Bank',
                'excerpt' => 'Panduan lengkap cara menarik saldo MonetraPay ke rekening bank Anda dengan aman dan cepat.',
                'content' => '<p>Setelah mengumpulkan saldo dari hasil transaksi, saatnya menarik keuntungan Anda ke rekening bank. Berikut panduan lengkap cara withdraw di MonetraPay.</p><h2>Syarat Withdraw</h2><ul><li>Saldo minimal Rp 50.000</li><li>Rekening bank sudah diverifikasi</li><li>Akun dalam status aktif</li></ul><h2>Cara Withdraw</h2><ol><li>Buka menu Keuangan di aplikasi MonetraPay</li><li>Pilih "Tarik Saldo"</li><li>Masukkan jumlah yang ingin ditarik</li><li>Pilih rekening tujuan</li><li>Masukkan PIN transaksi</li><li>Konfirmasi penarikan</li></ol><p>Dana akan masuk ke rekening Anda dalam 1x24 jam hari kerja.</p>',
                'category' => 'tutorial',
            ],
            [
                'title'   => 'Promo Double Cashback Akhir Tahun MonetraPay',
                'excerpt' => 'Sambut akhir tahun dengan promo Double Cashback eksklusif dari MonetraPay untuk semua pengguna.',
                'content' => '<p>Menyambut akhir tahun, MonetraPay hadir dengan promo Double Cashback yang sayang untuk dilewatkan! Dapatkan cashback 2x lipat untuk setiap transaksi selama periode promo berlangsung.</p><h2>Detail Promo</h2><ul><li>Periode: 20-31 Desember</li><li>Cashback: 2x lipat dari normal</li><li>Berlaku untuk semua jenis transaksi</li><li>Tidak ada minimum transaksi</li></ul><h2>Cara Mendapatkan Cashback</h2><p>Cashback akan otomatis dihitung dan dikreditkan ke saldo MonetraPay Anda setiap hari selama periode promo.</p>',
                'category' => 'promo',
            ],
            [
                'title'   => 'Inklusi Keuangan: Peran Agen MonetraPay di Daerah Terpencil',
                'excerpt' => 'MonetraPay berkomitmen mendukung inklusi keuangan dengan memberdayakan agen di seluruh penjuru Indonesia.',
                'content' => '<p>Salah satu misi utama MonetraPay adalah mendukung inklusi keuangan di Indonesia, terutama di daerah-daerah yang belum terjangkau oleh layanan perbankan konvensional.</p><h2>Kontribusi Agen MonetraPay</h2><p>Para agen MonetraPay yang tersebar di seluruh Indonesia berperan sebagai jembatan antara masyarakat dengan layanan keuangan digital. Mereka memungkinkan masyarakat di daerah terpencil untuk mengakses layanan seperti pembayaran tagihan, pengiriman uang, dan pembelian pulsa.</p><h2>Program Pemberdayaan Agen</h2><p>MonetraPay terus mengembangkan program pelatihan dan dukungan untuk para agen, memastikan mereka memiliki pengetahuan dan alat yang dibutuhkan untuk melayani masyarakat dengan baik.</p>',
                'category' => 'berita',
            ],
            [
                'title'   => 'Perbandingan Layanan PPOB: MonetraPay vs Kompetitor',
                'excerpt' => 'Analisis mendalam perbandingan layanan PPOB MonetraPay dengan platform lain di pasar.',
                'content' => '<p>Memilih platform PPOB yang tepat sangat penting untuk keberhasilan bisnis agen Anda. Berikut perbandingan objektif antara MonetraPay dengan beberapa kompetitor utama.</p><h2>Kategori Perbandingan</h2><h3>Harga & Margin</h3><p>MonetraPay konsisten menawarkan margin yang kompetitif dengan harga dasar yang lebih rendah dibanding rata-rata pasar.</p><h3>Stabilitas Layanan</h3><p>Dengan uptime 99.9%, MonetraPay memastikan transaksi Anda selalu bisa diproses kapan pun dibutuhkan.</p><h3>Dukungan Pelanggan</h3><p>Tim support MonetraPay tersedia 24/7 melalui berbagai channel: live chat, WhatsApp, email, dan telepon.</p>',
                'category' => 'tips-trik',
            ],
            [
                'title'   => 'Cara Aktifkan Two-Factor Authentication di Akun MonetraPay',
                'excerpt' => 'Lindungi akun MonetraPay Anda dengan Two-Factor Authentication untuk keamanan transaksi yang lebih baik.',
                'content' => '<p>Keamanan akun adalah prioritas utama. Dengan mengaktifkan Two-Factor Authentication (2FA), akun MonetraPay Anda akan terlindungi dengan lapisan keamanan tambahan.</p><h2>Apa itu 2FA?</h2><p>Two-Factor Authentication adalah metode keamanan yang mengharuskan Anda memasukkan kode verifikasi tambahan selain password saat login.</p><h2>Cara Aktifkan 2FA</h2><ol><li>Login ke akun MonetraPay</li><li>Buka Pengaturan Keamanan</li><li>Pilih "Aktifkan 2FA"</li><li>Scan QR Code dengan aplikasi authenticator</li><li>Masukkan kode verifikasi</li><li>2FA berhasil diaktifkan!</li></ol>',
                'category' => 'tutorial',
            ],
            [
                'title'   => 'Flash Sale Voucher Game: Diskon Hingga 30%',
                'excerpt' => 'Jangan lewatkan Flash Sale voucher game dengan diskon hingga 30% di MonetraPay!',
                'content' => '<p>Flash Sale spesial hadir untuk para gamer! Dapatkan voucher game favorit Anda dengan harga super hemat, diskon hingga 30%!</p><h2>Game yang Masuk Flash Sale</h2><ul><li>Mobile Legends - Diamond (diskon 20%)</li><li>Free Fire - Diamond (diskon 25%)</li><li>PUBG Mobile - UC (diskon 30%)</li><li>Genshin Impact - Genesis Crystal (diskon 15%)</li></ul><h2>Waktu Flash Sale</h2><p>Flash Sale berlangsung setiap hari Jumat pukul 12.00-14.00 WIB. Stok terbatas, jangan sampai ketinggalan!</p>',
                'category' => 'promo',
            ],
            [
                'title'   => 'Membangun Jaringan Agen: Kunci Sukses Bisnis PPOB',
                'excerpt' => 'Pelajari cara membangun jaringan agen yang kuat untuk mengembangkan bisnis PPOB Anda.',
                'content' => '<p>Salah satu cara tercepat untuk meningkatkan penghasilan sebagai agen MonetraPay adalah dengan membangun jaringan agen di bawah Anda.</p><h2>Sistem Multi-Level</h2><p>MonetraPay menerapkan sistem multi-level yang memungkinkan Anda mendapatkan bonus dari setiap transaksi yang dilakukan oleh agen yang Anda rekrut.</p><h2>Tips Membangun Jaringan</h2><ul><li>Identifikasi calon agen potensial di lingkungan Anda</li><li>Berikan pelatihan dan dukungan kepada agen baru</li><li>Buat grup WhatsApp atau Telegram untuk komunikasi dan berbagi informasi</li><li>Rayakan pencapaian bersama untuk menjaga motivasi tim</li></ul>',
                'category' => 'tips-trik',
            ],
            [
                'title'   => 'Mengenal Sistem Deposit dan Cara Isi Saldo MonetraPay',
                'excerpt' => 'Panduan lengkap cara melakukan deposit dan mengisi saldo akun MonetraPay Anda.',
                'content' => '<p>Sebelum bisa melakukan transaksi, Anda perlu mengisi saldo (deposit) ke akun MonetraPay Anda. Berikut panduan lengkapnya.</p><h2>Metode Deposit</h2><ul><li>Transfer Bank (BCA, BNI, BRI, Mandiri, dll)</li><li>Virtual Account</li><li>Indomaret/Alfamart</li><li>E-wallet (GoPay, OVO, Dana, ShopeePay)</li></ul><h2>Cara Deposit via Transfer Bank</h2><ol><li>Login ke MonetraPay</li><li>Pilih menu Deposit</li><li>Pilih metode Transfer Bank</li><li>Pilih bank tujuan</li><li>Transfer sesuai nominal yang tertera</li><li>Konfirmasi transfer</li><li>Saldo otomatis masuk dalam beberapa menit</li></ol>',
                'category' => 'tutorial',
            ],
        ];

        $published_dates = [
            now()->subDays(1),
            now()->subDays(3),
            now()->subDays(5),
            now()->subDays(7),
            now()->subDays(10),
            now()->subDays(12),
            now()->subDays(14),
            now()->subDays(16),
            now()->subDays(18),
            now()->subDays(20),
            now()->subDays(22),
            now()->subDays(25),
            now()->subDays(28),
            now()->subDays(30),
            now()->subDays(33),
            now()->subDays(35),
            now()->subDays(38),
            now()->subDays(40),
            now()->subDays(43),
            now()->subDays(45),
        ];

        foreach ($articles as $i => $article) {
            $slug = Str::slug($article['title']);
            
            // Skip jika sudah ada
            if (Blog::where('slug', $slug)->exists()) {
                $this->command->info("Skip (sudah ada): {$article['title']}");
                continue;
            }

            $blog = Blog::create([
                'title'            => $article['title'],
                'slug'             => $slug,
                'excerpt'          => $article['excerpt'],
                'content'          => $article['content'],
                'status'           => 'published',
                'published_at'     => $published_dates[$i] ?? now()->subDays($i * 2),
                'user_id'          => $user->id,
                'meta_title'       => Str::limit($article['title'], 55),
                'meta_description' => $article['excerpt'],
            ]);

            // Attach category
            $cat = Category::where('slug', $article['category'])->first();
            if ($cat) {
                $blog->categories()->attach($cat->id);
            }

            $this->command->info("Created: {$article['title']}");
        }

        $this->command->info('✅ DummyBlogSeeder selesai. Total: ' . count($articles) . ' artikel.');
    }
}
