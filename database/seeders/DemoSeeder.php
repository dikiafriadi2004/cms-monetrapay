<?php
// PERHATIAN: Seeder ini hanya untuk demo ke calon klien.
// JANGAN jalankan di production. Hapus data ini sebelum go-live.
// Cara pakai: php artisan db:seed --class=DemoSeeder

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Category;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🎭 Seeding demo data...');

        // Get first user sebagai author
        $user = User::first();

        // ── 5 Demo Blog Articles ─────────────────────────────
        $articles = [
            [
                'title' => 'Tips Sukses Memulai Bisnis Agen Digital',
                'excerpt' => 'Panduan lengkap untuk memulai bisnis agen digital dengan modal minimal dan keuntungan maksimal.',
                'content' => '<p>Bisnis agen digital semakin diminati karena modal yang relatif kecil namun potensi penghasilan yang besar...</p>',
                'category' => 'tips-trik',
            ],
            [
                'title' => 'Cara Meningkatkan Omzet Bisnis Pulsa',
                'excerpt' => 'Strategi jitu untuk meningkatkan penjualan pulsa dan meraih lebih banyak pelanggan.',
                'content' => '<p>Meningkatkan omzet bisnis pulsa membutuhkan strategi yang tepat dan konsistensi dalam pelayanan...</p>',
                'category' => 'tips-trik',
            ],
            [
                'title' => 'Panduan Lengkap Pembayaran PPOB Online',
                'excerpt' => 'Semua yang perlu Anda ketahui tentang pembayaran tagihan online melalui platform digital.',
                'content' => '<p>Pembayaran PPOB (Payment Point Online Bank) memudahkan masyarakat dalam membayar berbagai tagihan...</p>',
                'category' => 'tutorial',
            ],
            [
                'title' => 'Keuntungan Menjadi Agen Reseller Digital',
                'excerpt' => 'Pelajari berbagai keuntungan yang bisa Anda dapatkan sebagai agen reseller digital.',
                'content' => '<p>Menjadi agen reseller digital membuka peluang penghasilan yang tidak terbatas dari rumah...</p>',
                'category' => 'berita',
            ],
            [
                'title' => 'Top-Up Game: Peluang Bisnis yang Menjanjikan',
                'excerpt' => 'Industri gaming terus berkembang, top-up game menjadi salah satu bisnis paling menguntungkan.',
                'content' => '<p>Pasar game online di Indonesia terus bertumbuh pesat, membuka peluang bisnis top-up yang menjanjikan...</p>',
                'category' => 'berita',
            ],
        ];

        foreach ($articles as $article) {
            $slug = Str::slug($article['title']);
            if (Blog::where('slug', $slug)->exists()) continue;

            $cat = Category::where('slug', $article['category'])->first();
            $blog = Blog::create([
                'title' => $article['title'],
                'slug' => $slug,
                'excerpt' => $article['excerpt'],
                'content' => $article['content'],
                'status' => 'published',
                'published_at' => now()->subDays(rand(1, 30)),
                'user_id' => $user?->id ?? 1,
                'meta_title' => Str::limit($article['title'], 55),
                'meta_description' => $article['excerpt'],
            ]);

            if ($cat) $blog->categories()->attach($cat->id);
        }

        // ── 3 Demo Contact Submissions ─────────────────────
        $contacts = [
            ['name' => 'Budi Santoso',   'phone' => '081234567890', 'email' => 'budi@example.com',   'subject' => 'Daftar Agen/Reseller', 'message' => 'Saya tertarik untuk mendaftar sebagai agen. Mohon informasinya.'],
            ['name' => 'Siti Aminah',    'phone' => '082345678901', 'email' => 'siti@example.com',   'subject' => 'Bantuan transaksi',    'message' => 'Ada masalah dengan transaksi saya kemarin.'],
            ['name' => 'Ahmad Fauzi',    'phone' => '083456789012', 'email' => 'ahmad@example.com',  'subject' => 'Kerja sama',           'message' => 'Ingin berdiskusi tentang peluang kerja sama bisnis.'],
        ];

        foreach ($contacts as $contact) {
            ContactSubmission::create(array_merge($contact, [
                'city' => 'Jakarta',
                'ip_address' => '127.0.0.1',
                'is_read' => false,
            ]));
        }

        // ── 2 Demo Newsletter Subscribers ──────────────────
        $subscribers = [
            ['email' => 'subscriber1@example.com', 'name' => 'Subscriber Demo 1'],
            ['email' => 'subscriber2@example.com', 'name' => 'Subscriber Demo 2'],
        ];

        foreach ($subscribers as $sub) {
            NewsletterSubscriber::firstOrCreate(
                ['email' => $sub['email']],
                ['name' => $sub['name'], 'status' => 'active', 'token' => Str::random(32)]
            );
        }

        $this->command->info('');
        $this->command->info('✅ Demo data seeded!');
        $this->command->warn('⚠  INGAT: Hapus data demo ini sebelum go-live!');
        $this->command->warn('   Jalankan: php artisan migrate:fresh --seed');
        $this->command->info('');
    }
}
