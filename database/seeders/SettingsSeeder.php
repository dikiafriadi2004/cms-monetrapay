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
            ['group' => 'general', 'key' => 'site_name',             'value' => 'CMS'],
            ['group' => 'general', 'key' => 'tagline',               'value' => 'Platform Digital Terbaik untuk Bisnis Anda'],
            ['group' => 'general', 'key' => 'contact_email',         'value' => 'support@yourdomain.com'],
            ['group' => 'general', 'key' => 'contact_phone',         'value' => '+62 800-0000-0000'],
            ['group' => 'general', 'key' => 'maps_lat',              'value' => '-6.2088'],
            ['group' => 'general', 'key' => 'maps_lng',              'value' => '106.8456'],
            ['group' => 'general', 'key' => 'maps_zoom',             'value' => '15'],
            ['group' => 'general', 'key' => 'office_address',        'value' => 'Jl. Contoh No. 1, Jakarta Selatan'],
            ['group' => 'general', 'key' => 'logo',                  'value' => null],
            ['group' => 'general', 'key' => 'favicon',               'value' => null],
            // Navbar CTA — ubah teks & URL sesuai kebutuhan
            ['group' => 'general', 'key' => 'navbar_cta_text',       'value' => 'Daftar Jadi Agen'],
            ['group' => 'general', 'key' => 'navbar_cta_url',        'value' => '/kontak'],
            // WhatsApp Floating Button
            ['group' => 'general', 'key' => 'whatsapp_float_enabled','value' => '1'],
            ['group' => 'general', 'key' => 'whatsapp_number',       'value' => ''],
            ['group' => 'general', 'key' => 'whatsapp_text',         'value' => 'Halo, saya ingin tahu lebih lanjut tentang layanan kami'],

            // ── SOCIAL MEDIA ──────────────────────────────────────────
            ['group' => 'social', 'key' => 'facebook_url',         'value' => ''],
            ['group' => 'social', 'key' => 'instagram_url',        'value' => ''],
            ['group' => 'social', 'key' => 'twitter_url',          'value' => ''],
            ['group' => 'social', 'key' => 'youtube_url',          'value' => ''],
            ['group' => 'social', 'key' => 'tiktok_url',           'value' => ''],
            ['group' => 'social', 'key' => 'whatsapp_url',         'value' => ''],
            ['group' => 'social', 'key' => 'linkedin_url',         'value' => ''],

            // ── SEO ───────────────────────────────────────────────────
            ['group' => 'seo', 'key' => 'meta_title_default',      'value' => 'CMS - Platform Transaksi Digital Terpercaya'],
            ['group' => 'seo', 'key' => 'meta_description_default','value' => 'Platform all-in-one untuk penjualan pulsa, PPOB, top-up game, dan e-money. Bergabung sekarang dan mulai bisnis digital Anda.'],
            ['group' => 'seo', 'key' => 'ga_measurement_id',       'value' => ''],
            ['group' => 'seo', 'key' => 'robots_txt',              'value' => "User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: /sitemap.xml"],

            // ── FOOTER ────────────────────────────────────────────────
            ['group' => 'footer', 'key' => 'copyright_text',       'value' => '© ' . date('Y') . ' CMS. All rights reserved.'],
            ['group' => 'footer', 'key' => 'additional_content',   'value' => ' '],

            // ── MAINTENANCE ───────────────────────────────────────────
            ['group' => 'maintenance', 'key' => 'maintenance_mode','value' => '0'],
            ['group' => 'maintenance', 'key' => 'maintenance_message', 'value' => 'Kami sedang melakukan pemeliharaan. Kembali lagi sebentar.'],

            // ── EMAIL ─────────────────────────────────────────────────
            // Konfigurasi ini bisa di-override dari Admin → Settings → Email
            // Group harus 'email' agar sesuai dengan SettingController->validGroups dan MailConfigService
            ['group' => 'email', 'key' => 'mail_driver',           'value' => 'smtp'],
            ['group' => 'email', 'key' => 'mail_host',             'value' => 'smtp.gmail.com'],
            ['group' => 'email', 'key' => 'mail_port',             'value' => '587'],
            ['group' => 'email', 'key' => 'mail_username',         'value' => ''],
            ['group' => 'email', 'key' => 'mail_password',         'value' => ''],
            ['group' => 'email', 'key' => 'mail_encryption',       'value' => 'tls'],
            ['group' => 'email', 'key' => 'mail_from_name',        'value' => 'CMS'],
            ['group' => 'email', 'key' => 'mail_from_address',     'value' => 'noreply@yourdomain.com'],

            // ── HOMEPAGE HERO ─────────────────────────────────────────
            ['group' => 'homepage', 'key' => 'hero_title',         'value' => 'All-in-One Payment Solution untuk Bisnis Digital Anda'],
            ['group' => 'homepage', 'key' => 'hero_subtitle',      'value' => 'Platform terlengkap untuk penjualan pulsa, pembayaran PPOB, top-up game, dan e-money. Mulai bisnis digital Anda sekarang dengan modal minimal.'],
            ['group' => 'homepage', 'key' => 'hero_cta_text',      'value' => 'Daftar Jadi Agen'],
            ['group' => 'homepage', 'key' => 'hero_cta_url',       'value' => '/kontak'],
            ['group' => 'homepage', 'key' => 'hero_image',         'value' => null],

            // ── HOMEPAGE STATS (Hero) ─────────────────────────────────
            ['group' => 'homepage', 'key' => 'stat_1_value',       'value' => '100K+'],
            ['group' => 'homepage', 'key' => 'stat_1_label',       'value' => 'Pengguna Aktif'],
            ['group' => 'homepage', 'key' => 'stat_2_value',       'value' => '500+'],
            ['group' => 'homepage', 'key' => 'stat_2_label',       'value' => 'Produk Digital'],
            ['group' => 'homepage', 'key' => 'stat_3_value',       'value' => '99.9%'],
            ['group' => 'homepage', 'key' => 'stat_3_label',       'value' => 'Uptime Server'],
            ['group' => 'homepage', 'key' => 'stat_4_value',       'value' => '24/7'],
            ['group' => 'homepage', 'key' => 'stat_4_label',       'value' => 'Support'],

            // ── HOMEPAGE STATS BAR ────────────────────────────────────
            ['group' => 'homepage', 'key' => 'statsbar_1_value',   'value' => '500K+'],
            ['group' => 'homepage', 'key' => 'statsbar_1_label',   'value' => 'Agen Aktif'],
            ['group' => 'homepage', 'key' => 'statsbar_2_value',   'value' => 'Rp 2T+'],
            ['group' => 'homepage', 'key' => 'statsbar_2_label',   'value' => 'Total Transaksi'],
            ['group' => 'homepage', 'key' => 'statsbar_3_value',   'value' => '99.9%'],
            ['group' => 'homepage', 'key' => 'statsbar_3_label',   'value' => 'Uptime'],
            ['group' => 'homepage', 'key' => 'statsbar_4_value',   'value' => '24/7'],
            ['group' => 'homepage', 'key' => 'statsbar_4_label',   'value' => 'Dukungan'],

            // ── TESTIMONIALS ──────────────────────────────────────────
            ['group' => 'homepage', 'key' => 'testimonial_1_name', 'value' => 'Budi Santoso'],
            ['group' => 'homepage', 'key' => 'testimonial_1_role', 'value' => 'Agen Digital'],
            ['group' => 'homepage', 'key' => 'testimonial_1_text', 'value' => 'Platform ini sangat membantu bisnis saya. Transaksi pulsa dan PPOB lancar 24 jam, margin kompetitif, dan saldo top-up langsung masuk.'],
            ['group' => 'homepage', 'key' => 'testimonial_2_name', 'value' => 'Siti Aminah'],
            ['group' => 'homepage', 'key' => 'testimonial_2_role', 'value' => 'Reseller Online'],
            ['group' => 'homepage', 'key' => 'testimonial_2_text', 'value' => 'Margin yang diberikan sangat kompetitif. Pelanggan saya senang karena harga jual tetap bisa bersaing. Proses deposit juga sangat cepat!'],
            ['group' => 'homepage', 'key' => 'testimonial_3_name', 'value' => 'Rahmat Hidayat'],
            ['group' => 'homepage', 'key' => 'testimonial_3_role', 'value' => 'Pengusaha PPOB'],
            ['group' => 'homepage', 'key' => 'testimonial_3_text', 'value' => 'Support 24/7 membuat saya merasa aman berjualan. Tim selalu respons cepat dan selesaikan masalah dengan profesional.'],

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
            ['group' => 'homepage', 'key' => 'cta_description',    'value' => 'Bergabung sekarang dan nikmati keuntungan menjadi mitra kami. Deposit mulai dari Rp 10.000, margin kompetitif, dan dukungan 24/7.'],
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
