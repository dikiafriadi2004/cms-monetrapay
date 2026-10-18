<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageComponent;
use Illuminate\Database\Seeder;

class DefaultPagesSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. HALAMAN KONTAK ───────────────────────────────────────────
        $kontak = Page::updateOrCreate(
            ['slug' => 'kontak'],
            [
                'title'            => 'Kontak',
                'slug'             => 'kontak',
                'content'          => '',
                'status'           => 'published',
                'template'         => 'kontak',
                'use_page_builder' => true,
                'meta_title'       => 'Hubungi Kami — MonetraPay',
                'meta_description' => 'Hubungi tim MonetraPay untuk pertanyaan, pendaftaran agen, atau informasi layanan.',
            ]
        );

        // Hapus komponen lama lalu buat default
        $kontak->components()->delete();

        PageComponent::create([
            'page_id'        => $kontak->id,
            'component_type' => 'hero',
            'content'        => [
                'title'           => 'Kami Siap Membantu Anda',
                'subtitle'        => 'Tim MonetraPay siap menjawab pertanyaan Anda setiap saat. Hubungi kami atau isi formulir di bawah.',
                'badge_text'      => 'Hubungi Kami',
                'button_text'     => 'Isi Formulir',
                'button_link'     => '#contact',
                'button2_text'    => '',
                'height'          => 'medium',
                'text_align'      => 'center',
                'background_type' => 'gradient',
            ],
            'settings'   => [],
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        PageComponent::create([
            'page_id'        => $kontak->id,
            'component_type' => 'contact-form',
            'content'        => [
                'title'          => 'Daftar Menjadi Agen',
                'subtitle'       => 'Isi formulir di bawah dan tim kami akan menghubungi Anda dalam 1×24 jam.',
                'button_text'    => 'Kirim Pendaftaran',
                'success_message'=> 'Terima kasih! Tim kami akan segera menghubungi Anda.',
                'show_city'      => true,
            ],
            'settings'   => [],
            'sort_order' => 2,
            'is_visible' => true,
        ]);

        // ── 2. HALAMAN TENTANG KAMI ────────────────────────────────────
        $tentang = Page::updateOrCreate(
            ['slug' => 'tentang-kami'],
            [
                'title'            => 'Tentang Kami',
                'slug'             => 'tentang-kami',
                'content'          => '',
                'status'           => 'published',
                'template'         => 'tentang-kami',
                'use_page_builder' => true,
                'meta_title'       => 'Tentang MonetraPay',
                'meta_description' => 'MonetraPay adalah platform transaksi digital terpercaya untuk agen pulsa, PPOB, dan e-wallet.',
            ]
        );

        $tentang->components()->delete();

        PageComponent::create([
            'page_id'        => $tentang->id,
            'component_type' => 'hero',
            'content'        => [
                'title'           => 'Kami Hadir untuk Memberdayakan Bisnis Anda',
                'subtitle'        => 'MonetraPay adalah platform transaksi digital terpercaya yang telah melayani lebih dari 100.000 agen di seluruh Indonesia.',
                'badge_text'      => 'Tentang Kami',
                'button_text'     => 'Mulai Bergabung',
                'button_link'     => url('/kontak'),
                'button2_text'    => 'Lihat Layanan',
                'button2_link'    => url('/layanan'),
                'height'          => 'large',
                'text_align'      => 'center',
                'background_type' => 'gradient',
            ],
            'settings'   => [],
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        PageComponent::create([
            'page_id'        => $tentang->id,
            'component_type' => 'features',
            'content'        => [
                'section_title'    => 'Mengapa Memilih MonetraPay?',
                'section_subtitle' => 'Kami dibangun untuk memenuhi kebutuhan agen modern.',
                'columns'          => 3,
                'style'            => 'cards',
                'features'         => [
                    ['icon_emoji' => '🔒', 'title' => 'Terpercaya & Aman',      'description' => 'Sistem keamanan berlapis untuk setiap transaksi Anda.'],
                    ['icon_emoji' => '⚡', 'title' => 'Transaksi Instan',        'description' => 'Proses selesai kurang dari 30 detik, tanpa gangguan.'],
                    ['icon_emoji' => '💰', 'title' => 'Margin Kompetitif',       'description' => 'Harga terbaik untuk semua produk digital.'],
                    ['icon_emoji' => '🤝', 'title' => 'Support 24/7',           'description' => 'Tim siap membantu kapan saja Anda butuhkan.'],
                    ['icon_emoji' => '📊', 'title' => 'Laporan Real-time',       'description' => 'Monitor performa bisnis dari dashboard lengkap.'],
                    ['icon_emoji' => '🚀', 'title' => 'Mudah Digunakan',         'description' => 'Antarmuka intuitif, siap dipakai tanpa pelatihan.'],
                ],
            ],
            'settings'   => [],
            'sort_order' => 2,
            'is_visible' => true,
        ]);

        PageComponent::create([
            'page_id'        => $tentang->id,
            'component_type' => 'cta',
            'content'        => [
                'heading'     => 'Bergabung Sekarang dan Rasakan Perbedaannya',
                'subheading'  => 'Lebih dari 100.000 agen telah membuktikan keandalan MonetraPay.',
                'button_text' => 'Daftar Jadi Agen',
                'button_link' => url('/kontak'),
                'button2_text'=> 'Lihat Layanan',
                'button2_link'=> url('/layanan'),
                'background'  => 'gradient',
            ],
            'settings'   => [],
            'sort_order' => 3,
            'is_visible' => true,
        ]);

        // ── 3. HALAMAN LAYANAN ─────────────────────────────────────────
        $layanan = Page::updateOrCreate(
            ['slug' => 'layanan'],
            [
                'title'            => 'Layanan',
                'slug'             => 'layanan',
                'content'          => '',
                'status'           => 'published',
                'template'         => 'layanan',
                'use_page_builder' => true,
                'meta_title'       => 'Layanan MonetraPay — Pulsa, PPOB, Game & E-Wallet',
                'meta_description' => 'Semua kebutuhan transaksi digital dalam satu platform: pulsa, PPOB, top-up game, dan e-wallet.',
            ]
        );

        $layanan->components()->delete();

        PageComponent::create([
            'page_id'        => $layanan->id,
            'component_type' => 'hero',
            'content'        => [
                'title'           => 'Semua Kebutuhan Digital dalam Satu Platform',
                'subtitle'        => 'Pulsa, PPOB, top-up game, dan e-wallet dengan transaksi cepat, margin jelas, dan support 24/7.',
                'badge_text'      => 'Layanan Kami',
                'button_text'     => 'Daftar Sekarang',
                'button_link'     => url('/kontak'),
                'button2_text'    => 'Lihat Harga',
                'button2_link'    => '#pricing',
                'height'          => 'large',
                'text_align'      => 'center',
                'background_type' => 'gradient',
            ],
            'settings'   => [],
            'sort_order' => 1,
            'is_visible' => true,
        ]);

        PageComponent::create([
            'page_id'        => $layanan->id,
            'component_type' => 'features',
            'content'        => [
                'section_title'    => 'Produk Lengkap untuk Semua Kebutuhan',
                'section_subtitle' => 'Dari pulsa hingga e-wallet, semua tersedia dengan harga kompetitif.',
                'columns'          => 2,
                'style'            => 'cards',
                'features'         => [
                    ['icon_emoji' => '📱', 'title' => 'Pulsa All Operator',   'description' => 'Telkomsel, Indosat, XL, Tri, Smartfren, Axis — semua operator tersedia dengan proses instan.'],
                    ['icon_emoji' => '🏠', 'title' => 'PPOB & Tagihan',       'description' => 'Bayar PLN, PDAM, BPJS, internet, TV kabel, dan cicilan dalam satu platform.'],
                    ['icon_emoji' => '🎮', 'title' => 'Top-Up Game',          'description' => 'Mobile Legends, PUBG, Free Fire, Genshin Impact, Valorant, dan 100+ game populer.'],
                    ['icon_emoji' => '💳', 'title' => 'E-Money & E-Wallet',   'description' => 'Isi saldo GoPay, OVO, DANA, ShopeePay, LinkAja, dan dompet digital lainnya.'],
                ],
            ],
            'settings'   => [],
            'sort_order' => 2,
            'is_visible' => true,
        ]);

        PageComponent::create([
            'page_id'        => $layanan->id,
            'component_type' => 'pricing',
            'content'        => [
                'section_title'    => 'Pilih Paket yang Sesuai',
                'section_subtitle' => 'Mulai gratis, upgrade kapan saja sesuai perkembangan bisnis Anda.',
                'currency'         => 'Rp',
                'plans'            => [
                    [
                        'name'        => 'Starter',
                        'price'       => '0',
                        'period'      => 'bulan',
                        'description' => 'Cocok untuk memulai',
                        'is_popular'  => false,
                        'button_text' => 'Mulai Gratis',
                        'button_link' => url('/kontak'),
                        'features'    => ['Transaksi s/d 100x/bulan', 'Support via email', 'Dashboard dasar', '1 pengguna'],
                    ],
                    [
                        'name'        => 'Growth',
                        'price'       => '149000',
                        'period'      => 'bulan',
                        'description' => 'Paling populer untuk UMKM',
                        'is_popular'  => true,
                        'button_text' => 'Pilih Growth',
                        'button_link' => url('/kontak'),
                        'features'    => ['Transaksi unlimited', 'Support prioritas 24/7', 'Dashboard lengkap', '5 pengguna', 'Laporan harian'],
                    ],
                    [
                        'name'        => 'Enterprise',
                        'price'       => '399000',
                        'period'      => 'bulan',
                        'description' => 'Untuk bisnis skala besar',
                        'is_popular'  => false,
                        'button_text' => 'Hubungi Kami',
                        'button_link' => url('/kontak'),
                        'features'    => ['Semua fitur Growth', 'API akses penuh', 'Account manager', 'Unlimited pengguna', 'SLA 99.9%'],
                    ],
                ],
            ],
            'settings'   => [],
            'sort_order' => 3,
            'is_visible' => true,
        ]);

        PageComponent::create([
            'page_id'        => $layanan->id,
            'component_type' => 'faq',
            'content'        => [
                'section_title'    => 'Pertanyaan yang Sering Ditanyakan',
                'section_subtitle' => 'Temukan jawaban untuk pertanyaan umum seputar layanan kami.',
                'columns'          => 1,
                'items'            => [
                    ['question' => 'Berapa modal awal yang dibutuhkan?',    'answer' => 'Paket Starter gratis tanpa deposit awal. Paket Growth dan Enterprise memiliki biaya langganan bulanan yang fleksibel.'],
                    ['question' => 'Apakah transaksi berjalan 24 jam?',     'answer' => 'Ya, platform kami beroperasi 24/7 dengan uptime 99.9%. Transaksi dapat dilakukan kapan saja termasuk hari libur.'],
                    ['question' => 'Bagaimana jika transaksi gagal?',        'answer' => 'Setiap transaksi gagal akan otomatis di-refund ke saldo dalam maksimal 5 menit. Tidak ada uang yang hilang.'],
                    ['question' => 'Apakah ada pelatihan untuk agen baru?', 'answer' => 'Kami menyediakan onboarding session, video tutorial, dan panduan lengkap untuk memulai dengan mudah.'],
                ],
            ],
            'settings'   => [],
            'sort_order' => 4,
            'is_visible' => true,
        ]);

        PageComponent::create([
            'page_id'        => $layanan->id,
            'component_type' => 'cta',
            'content'        => [
                'heading'     => 'Siap Mulai Bisnis Digital Anda?',
                'subheading'  => 'Bergabung dengan 100.000+ agen yang sudah sukses bersama MonetraPay.',
                'button_text' => 'Daftar Jadi Agen',
                'button_link' => url('/kontak'),
                'background'  => 'gradient',
            ],
            'settings'   => [],
            'sort_order' => 5,
            'is_visible' => true,
        ]);

        $this->command->info('✅ Default pages seeded: kontak, tentang-kami, layanan');
        $this->command->info('   Kontak: ' . $kontak->components()->count() . ' components');
        $this->command->info('   Tentang Kami: ' . $tentang->components()->count() . ' components');
        $this->command->info('   Layanan: ' . $layanan->components()->count() . ' components');
    }
}
