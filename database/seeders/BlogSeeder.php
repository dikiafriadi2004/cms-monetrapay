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

        $catPengumuman = Category::where('slug', 'pengumuman')->first();
        $catTutorial   = Category::where('slug', 'tutorial')->first();
        $catTips       = Category::where('slug', 'tips-trik')->first();

        $blogs = [
            [
                'title'        => 'Selamat Datang di Platform Kami',
                'slug'         => 'selamat-datang',
                'excerpt'      => 'Kami dengan bangga mempersembahkan platform digital terbaru yang dirancang untuk memenuhi kebutuhan Anda.',
                'status'       => 'published',
                'published_at' => Carbon::now()->subDays(7),
                'category'     => $catPengumuman?->id,
                'content'      => '
<p>Kami dengan bangga mempersembahkan platform digital terbaru yang dirancang untuk memenuhi kebutuhan Anda. Platform ini hadir sebagai solusi terpadu yang menggabungkan kemudahan, kecepatan, dan keandalan dalam satu ekosistem digital yang modern.</p>

<h2>Apa yang Kami Tawarkan?</h2>
<p>Platform kami menyediakan berbagai fitur unggulan yang dirancang untuk mendukung produktivitas dan efisiensi bisnis Anda sehari-hari.</p>

<h3>Manajemen Konten</h3>
<p>Kelola semua konten bisnis Anda dengan mudah melalui antarmuka yang intuitif. Tambah, edit, dan publikasikan konten kapan saja dan di mana saja tanpa memerlukan keahlian teknis khusus.</p>

<h3>Pengelolaan Layanan</h3>
<p>Pantau dan kelola seluruh layanan yang Anda tawarkan dari satu dashboard terpusat. Visibilitas penuh atas operasional bisnis Anda memudahkan pengambilan keputusan yang lebih baik.</p>

<h3>Dashboard Analitik</h3>
<p>Dapatkan wawasan mendalam tentang performa bisnis Anda melalui dashboard analitik yang komprehensif. Data real-time membantu Anda memahami tren dan mengoptimalkan strategi bisnis.</p>

<h2>Mulai Perjalanan Digital Anda</h2>
<p>Bergabunglah dengan ribuan pengguna yang telah merasakan manfaat platform kami. Daftarkan diri Anda sekarang dan nikmati kemudahan layanan digital di ujung jari Anda.</p>

<p>Kami berkomitmen untuk terus berinovasi dan meningkatkan pengalaman pengguna. Terima kasih telah mempercayai kami sebagai mitra digital Anda.</p>',
            ],
            [
                'title'        => 'Cara Menggunakan Platform Ini',
                'slug'         => 'cara-menggunakan-platform',
                'excerpt'      => 'Panduan lengkap untuk memulai menggunakan platform ini dengan mudah dan efisien.',
                'status'       => 'published',
                'published_at' => Carbon::now()->subDays(5),
                'category'     => $catTutorial?->id,
                'content'      => '
<p>Panduan lengkap untuk memulai menggunakan platform ini dengan mudah dan efisien. Ikuti langkah-langkah berikut untuk memaksimalkan pengalaman Anda.</p>

<h2>Langkah 1: Login ke Akun Anda</h2>
<p>Buka aplikasi atau situs web kami dan masukkan alamat email serta kata sandi yang telah Anda daftarkan. Jika belum memiliki akun, klik tombol "Daftar" dan ikuti proses pendaftaran yang mudah.</p>

<h2>Langkah 2: Navigasi Dashboard</h2>
<p>Setelah berhasil masuk, Anda akan diarahkan ke dashboard utama. Di sini Anda dapat melihat ringkasan aktivitas, notifikasi terbaru, dan akses cepat ke semua fitur utama platform.</p>
<ul>
<li><strong>Menu Utama</strong> — Terletak di sisi kiri layar, berisi akses ke semua fitur platform</li>
<li><strong>Area Konten</strong> — Bagian tengah layar untuk mengelola aktivitas utama Anda</li>
<li><strong>Panel Notifikasi</strong> — Kanan atas untuk melihat pembaruan dan pesan terbaru</li>
</ul>

<h2>Langkah 3: Mengelola Konten</h2>
<p>Untuk membuat konten baru, klik tombol "Tambah Baru" yang tersedia di setiap halaman. Isi formulir yang tersedia dengan informasi yang diperlukan, kemudian klik "Simpan" untuk menyimpan perubahan.</p>

<h2>Tips Navigasi Efisien</h2>
<ul>
<li>Gunakan fitur pencarian untuk menemukan konten dengan cepat</li>
<li>Manfaatkan pintasan keyboard untuk mempercepat pekerjaan</li>
<li>Aktifkan notifikasi untuk mendapatkan pembaruan secara real-time</li>
</ul>

<p>Jika Anda mengalami kesulitan, tim support kami siap membantu melalui fitur live chat atau email dukungan kami.</p>',
            ],
            [
                'title'        => 'Tips Memaksimalkan Fitur Digital',
                'slug'         => 'tips-memaksimalkan-fitur',
                'excerpt'      => 'Pelajari cara memaksimalkan setiap fitur untuk meningkatkan produktivitas dan efisiensi bisnis Anda.',
                'status'       => 'published',
                'published_at' => Carbon::now()->subDays(3),
                'category'     => $catTips?->id,
                'content'      => '
<p>Pelajari cara memaksimalkan setiap fitur untuk meningkatkan produktivitas dan efisiensi bisnis Anda. Berikut adalah tips dan trik yang bisa langsung Anda terapkan.</p>

<h2>1. Manfaatkan Fitur Otomatisasi</h2>
<p>Platform kami menyediakan berbagai fitur otomatisasi yang dapat menghemat waktu Anda secara signifikan. Atur jadwal publikasi konten, pengiriman notifikasi, dan laporan berkala agar pekerjaan berjalan dengan sendirinya.</p>

<h2>2. Gunakan Template yang Tersedia</h2>
<p>Daripada memulai dari nol, manfaatkan koleksi template yang sudah kami siapkan. Template ini dirancang oleh para ahli untuk memastikan hasil yang profesional dan konsisten.</p>

<h2>3. Integrasikan dengan Alat Lain</h2>
<p>Platform kami mendukung integrasi dengan berbagai aplikasi populer. Hubungkan dengan alat yang sudah Anda gunakan untuk menciptakan alur kerja yang seamless dan efisien.</p>

<h2>4. Pantau Analitik Secara Rutin</h2>
<p>Luangkan waktu setiap minggu untuk menganalisis data performa. Pemahaman yang baik tentang data akan membantu Anda membuat keputusan yang lebih tepat dan strategis.</p>

<h2>5. Aktifkan Notifikasi Penting</h2>
<p>Pastikan Anda mengaktifkan notifikasi untuk hal-hal yang benar-benar penting. Ini akan membantu Anda tetap responsif tanpa terganggu oleh informasi yang tidak relevan.</p>

<h2>6. Backup Data Secara Berkala</h2>
<p>Selalu lakukan backup data penting secara rutin. Platform kami menyediakan fitur ekspor data yang mudah digunakan untuk memastikan keamanan informasi bisnis Anda.</p>

<p>Dengan menerapkan tips di atas, Anda akan merasakan peningkatan produktivitas yang signifikan dalam waktu singkat. Mulai terapkan sekarang!</p>',
            ],
            [
                'title'        => 'Update Terbaru Platform',
                'slug'         => 'update-terbaru-platform',
                'excerpt'      => 'Kami terus berinovasi untuk memberikan pengalaman terbaik. Simak pembaruan terbaru platform kami.',
                'status'       => 'published',
                'published_at' => Carbon::now()->subDays(1),
                'category'     => $catPengumuman?->id,
                'content'      => '
<p>Kami terus berinovasi untuk memberikan pengalaman terbaik. Simak pembaruan terbaru platform kami yang hadir dengan berbagai peningkatan signifikan.</p>

<h2>Peningkatan Performa</h2>
<p>Kami telah melakukan optimasi menyeluruh pada infrastruktur platform. Hasilnya, kecepatan loading halaman meningkat hingga 40% dan waktu respons server berkurang secara drastis. Pengalaman penggunaan yang lebih cepat dan responsif kini tersedia untuk semua pengguna.</p>

<h2>Fitur Baru</h2>
<ul>
<li><strong>Dashboard yang Diperbarui</strong> — Tampilan baru yang lebih bersih dan intuitif untuk memudahkan navigasi</li>
<li><strong>Laporan Lanjutan</strong> — Ekspor data dalam berbagai format termasuk PDF dan Excel</li>
<li><strong>Mode Gelap</strong> — Pilihan tampilan baru untuk kenyamanan pengguna di berbagai kondisi pencahayaan</li>
<li><strong>Notifikasi Cerdas</strong> — Sistem notifikasi yang lebih pintar berdasarkan preferensi pengguna</li>
</ul>

<h2>Perbaikan Bug</h2>
<p>Update ini juga mencakup perbaikan atas beberapa bug yang dilaporkan oleh pengguna:</p>
<ul>
<li>Perbaikan masalah sinkronisasi data pada koneksi lambat</li>
<li>Penanganan error yang lebih baik pada proses unggah file</li>
<li>Perbaikan tampilan pada beberapa perangkat mobile</li>
</ul>

<h2>Yang Akan Datang</h2>
<p>Tim kami sedang mengerjakan berbagai fitur menarik lainnya yang akan segera hadir. Pantau terus pengumuman kami untuk mengetahui pembaruan terbaru.</p>

<p>Terima kasih atas dukungan dan masukan yang berharga dari seluruh pengguna setia kami.</p>',
            ],
            [
                'title'        => 'FAQ: Pertanyaan yang Sering Diajukan',
                'slug'         => 'faq-pertanyaan-sering-diajukan',
                'excerpt'      => 'Temukan jawaban atas pertanyaan-pertanyaan yang paling sering diajukan pengguna platform kami.',
                'status'       => 'published',
                'published_at' => Carbon::now()->subDays(14),
                'category'     => $catTutorial?->id,
                'content'      => '
<p>Temukan jawaban atas pertanyaan-pertanyaan yang paling sering diajukan pengguna platform kami. Jika pertanyaan Anda tidak terjawab di sini, jangan ragu untuk menghubungi tim support kami.</p>

<h2>Pendaftaran & Akun</h2>

<h3>Bagaimana cara mendaftar?</h3>
<p>Kunjungi halaman pendaftaran kami dan isi formulir dengan data yang diperlukan seperti nama lengkap, alamat email, dan kata sandi. Setelah itu, verifikasi email Anda melalui tautan yang kami kirimkan, dan akun Anda siap digunakan.</p>

<h3>Bagaimana jika saya lupa password?</h3>
<p>Klik tautan "Lupa Password" di halaman login. Masukkan alamat email terdaftar Anda, dan kami akan mengirimkan instruksi untuk mereset kata sandi melalui email. Pastikan untuk memeriksa folder spam jika email tidak muncul di kotak masuk.</p>

<h3>Apakah saya bisa mengubah alamat email?</h3>
<p>Ya, Anda bisa mengubah alamat email melalui menu Pengaturan Akun. Proses verifikasi akan diperlukan untuk mengkonfirmasi perubahan tersebut.</p>

<h2>Penggunaan Platform</h2>

<h3>Apakah platform ini gratis?</h3>
<p>Kami menyediakan paket gratis dengan fitur dasar untuk semua pengguna baru. Untuk fitur lebih lengkap, tersedia paket berbayar dengan berbagai pilihan yang sesuai dengan kebutuhan Anda.</p>

<h3>Di perangkat apa saja platform ini bisa diakses?</h3>
<p>Platform kami dapat diakses melalui semua perangkat modern — komputer desktop, laptop, tablet, maupun smartphone — menggunakan browser web terkini. Aplikasi mobile juga tersedia untuk iOS dan Android.</p>

<h2>Dukungan</h2>

<h3>Bagaimana cara menghubungi support?</h3>
<p>Anda dapat menghubungi tim support kami melalui beberapa saluran:</p>
<ul>
<li><strong>Live Chat</strong> — Tersedia di dalam aplikasi selama jam kerja</li>
<li><strong>Email</strong> — Kirim pertanyaan ke alamat email support kami</li>
<li><strong>Pusat Bantuan</strong> — Kunjungi halaman FAQ dan dokumentasi kami untuk solusi mandiri</li>
</ul>

<h3>Berapa lama waktu respon support?</h3>
<p>Tim kami berusaha merespons semua pertanyaan dalam waktu 1x24 jam pada hari kerja. Untuk pertanyaan melalui live chat, Anda biasanya akan mendapatkan respons dalam hitungan menit.</p>',
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
