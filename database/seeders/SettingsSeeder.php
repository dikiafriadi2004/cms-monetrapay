<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // ── GENERAL ────────────────────────────────────────────────
            ['group' => 'general', 'key' => 'site_name',             'value' => 'MonetraPay'],
            ['group' => 'general', 'key' => 'tagline',               'value' => 'All-in-One Payment Solution untuk Bisnis Digital Anda'],
            ['group' => 'general', 'key' => 'contact_email',         'value' => 'support@monetrapay.com'],
            ['group' => 'general', 'key' => 'contact_phone',         'value' => '+62 812-3456-7890'],
            ['group' => 'general', 'key' => 'logo',                  'value' => null],
            ['group' => 'general', 'key' => 'favicon',               'value' => null],
            // Navbar CTA — ubah teks & URL sesuai kebutuhan
            ['group' => 'general', 'key' => 'navbar_cta_text',       'value' => 'Daftar Jadi Agen'],
            ['group' => 'general', 'key' => 'navbar_cta_url',        'value' => '/kontak'],
            // WhatsApp Floating Button
            ['group' => 'general', 'key' => 'whatsapp_float_enabled','value' => '1'],
            ['group' => 'general', 'key' => 'whatsapp_number',       'value' => '6281234567890'],
            ['group' => 'general', 'key' => 'whatsapp_text',         'value' => 'Halo, saya ingin tahu lebih lanjut tentang layanan MonetraPay'],

            // ── SOCIAL MEDIA ──────────────────────────────────────────
            ['group' => 'social', 'key' => 'facebook_url',         'value' => 'https://facebook.com/monetrapay'],
            ['group' => 'social', 'key' => 'instagram_url',        'value' => 'https://instagram.com/monetrapay'],
            ['group' => 'social', 'key' => 'twitter_url',          'value' => 'https://twitter.com/monetrapay'],
            ['group' => 'social', 'key' => 'youtube_url',          'value' => 'https://youtube.com/@monetrapay'],
            ['group' => 'social', 'key' => 'tiktok_url',           'value' => 'https://tiktok.com/@monetrapay'],
            ['group' => 'social', 'key' => 'whatsapp_url',         'value' => 'https://wa.me/6281234567890'],
            ['group' => 'social', 'key' => 'linkedin_url',         'value' => ''],

            // ── SEO ───────────────────────────────────────────────────
            ['group' => 'seo', 'key' => 'meta_title_default',      'value' => 'MonetraPay - Platform Transaksi Digital Terpercaya'],
            ['group' => 'seo', 'key' => 'meta_description_default','value' => 'MonetraPay adalah platform all-in-one untuk penjualan pulsa, PPOB, top-up game, dan e-money. Bergabung sekarang dan mulai bisnis digital Anda.'],
            ['group' => 'seo', 'key' => 'ga_measurement_id',       'value' => ''],
            ['group' => 'seo', 'key' => 'robots_txt',              'value' => "User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: /sitemap.xml"],

            // ── FOOTER ────────────────────────────────────────────────
            ['group' => 'footer', 'key' => 'copyright_text',       'value' => '© ' . date('Y') . ' MonetraPay. All rights reserved.'],
            ['group' => 'footer', 'key' => 'additional_content',   'value' => 'Terdaftar dan diawasi oleh Bank Indonesia'],

            // ── MAINTENANCE ───────────────────────────────────────────
            ['group' => 'maintenance', 'key' => 'maintenance_mode','value' => '0'],
            ['group' => 'maintenance', 'key' => 'maintenance_message', 'value' => 'Kami sedang melakukan pemeliharaan. Kembali lagi sebentar.'],

            // ── MAIL ──────────────────────────────────────────────────
            // Konfigurasi ini bisa di-override dari Admin → Settings → Mail
            ['group' => 'mail', 'key' => 'mail_driver',            'value' => 'smtp'],
            ['group' => 'mail', 'key' => 'mail_host',              'value' => 'smtp.gmail.com'],
            ['group' => 'mail', 'key' => 'mail_port',              'value' => '587'],
            ['group' => 'mail', 'key' => 'mail_username',          'value' => ''],
            ['group' => 'mail', 'key' => 'mail_password',          'value' => ''],
            ['group' => 'mail', 'key' => 'mail_encryption',        'value' => 'tls'],
            ['group' => 'mail', 'key' => 'mail_from_name',         'value' => 'MonetraPay'],
            ['group' => 'mail', 'key' => 'mail_from_address',      'value' => 'noreply@monetrapay.com'],

            // ── HOMEPAGE HERO ─────────────────────────────────────────
            ['group' => 'homepage', 'key' => 'hero_title',         'value' => 'All-in-One Payment Solution untuk Bisnis Digital Anda'],
            ['group' => 'homepage', 'key' => 'hero_subtitle',      'value' => 'Platform terlengkap untuk penjualan pulsa, pembayaran PPOB, top-up game, dan e-money. Mulai bisnis digital Anda sekarang dengan modal minimal.'],
            ['group' => 'homepage', 'key' => 'hero_cta_text',      'value' => 'Daftar Jadi Agen'],
            ['group' => 'homepage', 'key' => 'hero_cta_url',       'value' => '/kontak'],
            ['group' => 'homepage', 'key' => 'hero_image',         'value' => null],

            // ── HOMEPAGE SERVICES ─────────────────────────────────────
            ['group' => 'homepage', 'key' => 'service_1_title',    'value' => 'Pulsa All Operator'],
            ['group' => 'homepage', 'key' => 'service_1_description', 'value' => 'Jual pulsa semua operator (Telkomsel, Indosat, XL, Tri, Smartfren) dengan harga kompetitif dan margin menguntungkan.'],
            ['group' => 'homepage', 'key' => 'service_2_title',    'value' => 'PPOB & Tagihan'],
            ['group' => 'homepage', 'key' => 'service_2_description', 'value' => 'Bayar tagihan PLN, PDAM, BPJS, internet, TV kabel, dan berbagai tagihan lainnya dalam satu platform.'],
            ['group' => 'homepage', 'key' => 'service_3_title',    'value' => 'Top-Up Game'],
            ['group' => 'homepage', 'key' => 'service_3_description', 'value' => 'Top-up diamond Mobile Legends, UC PUBG, Free Fire, Genshin Impact, dan 100+ game populer lainnya.'],
            ['group' => 'homepage', 'key' => 'service_4_title',    'value' => 'E-Money & E-Wallet'],
            ['group' => 'homepage', 'key' => 'service_4_description', 'value' => 'Isi saldo GoPay, OVO, DANA, ShopeePay, LinkAja, dan berbagai dompet digital lainnya dengan mudah.'],

            // ── HOMEPAGE CTA ──────────────────────────────────────────
            ['group' => 'homepage', 'key' => 'cta_title',          'value' => 'Siap Mengembangkan Bisnis Pulsa & PPOB?'],
            ['group' => 'homepage', 'key' => 'cta_description',    'value' => 'Bergabung sekarang dan nikmati keuntungan menjadi mitra MonetraPay. Deposit mulai dari Rp 10.000, margin kompetitif, dan dukungan 24/7.'],
            ['group' => 'homepage', 'key' => 'cta_button_text',    'value' => 'Daftar Jadi Agen'],
            ['group' => 'homepage', 'key' => 'cta_button_url',     'value' => '/kontak'],
        ];

        foreach ($settings as $s) {
            Setting::updateOrCreate(
                ['group' => $s['group'], 'key' => $s['key']],
                ['value' => $s['value']]
            );
        }

        $this->command->info('✅ Settings seeded (' . count($settings) . ' items)');
    }
}
