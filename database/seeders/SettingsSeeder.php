<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['group' => 'general', 'key' => 'site_name', 'value' => 'MonetraPay'],
            ['group' => 'general', 'key' => 'tagline', 'value' => 'All-in-One Payment Solution untuk Bisnis Digital Anda'],
            ['group' => 'general', 'key' => 'contact_email', 'value' => 'support@monetrapay.com'],
            ['group' => 'general', 'key' => 'contact_phone', 'value' => '+62 812-3456-7890'],
            ['group' => 'general', 'key' => 'logo', 'value' => null],
            ['group' => 'general', 'key' => 'favicon', 'value' => null],

            // Social Media
            ['group' => 'social', 'key' => 'facebook_url', 'value' => 'https://facebook.com/monetrapay'],
            ['group' => 'social', 'key' => 'instagram_url', 'value' => 'https://instagram.com/monetrapay'],
            ['group' => 'social', 'key' => 'twitter_url', 'value' => 'https://twitter.com/monetrapay'],
            ['group' => 'social', 'key' => 'youtube_url', 'value' => 'https://youtube.com/@monetrapay'],
            ['group' => 'social', 'key' => 'tiktok_url', 'value' => 'https://tiktok.com/@monetrapay'],

            // SEO
            ['group' => 'seo', 'key' => 'meta_title_default', 'value' => 'MonetraPay - Platform Transaksi Digital Terpercaya'],
            ['group' => 'seo', 'key' => 'meta_description_default', 'value' => 'MonetraPay adalah platform all-in-one untuk penjualan pulsa, PPOB, top-up game, dan e-money. Bergabung sekarang dan mulai bisnis digital Anda.'],
            ['group' => 'seo', 'key' => 'ga_measurement_id', 'value' => null],

            // Footer
            ['group' => 'footer', 'key' => 'copyright_text', 'value' => '© '.date('Y').' MonetraPay. All rights reserved.'],
            ['group' => 'footer', 'key' => 'additional_content', 'value' => 'Terdaftar dan diawasi oleh Bank Indonesia'],

            // Maintenance
            ['group' => 'maintenance', 'key' => 'maintenance_mode', 'value' => '0'],

            // Homepage Settings
            ['group' => 'homepage', 'key' => 'hero_title', 'value' => 'All-in-One Payment Solution untuk Bisnis Digital Anda'],
            ['group' => 'homepage', 'key' => 'hero_subtitle', 'value' => 'Platform terlengkap untuk penjualan pulsa, pembayaran PPOB, top-up game, dan e-money. Mulai bisnis digital Anda sekarang dengan modal minimal.'],
            ['group' => 'homepage', 'key' => 'hero_cta_text', 'value' => 'Daftar Jadi Agen'],
            ['group' => 'homepage', 'key' => 'hero_cta_url', 'value' => '#daftar'],
            ['group' => 'homepage', 'key' => 'hero_image', 'value' => null],

            // Services
            ['group' => 'homepage', 'key' => 'service_1_title', 'value' => 'Pulsa All Operator'],
            ['group' => 'homepage', 'key' => 'service_1_description', 'value' => 'Jual pulsa semua operator (Telkomsel, Indosat, XL, Tri, Smartfren) dengan harga kompetitif dan margin menguntungkan.'],
            ['group' => 'homepage', 'key' => 'service_2_title', 'value' => 'PPOB & Tagihan'],
            ['group' => 'homepage', 'key' => 'service_2_description', 'value' => 'Bayar tagihan PLN, PDAM, BPJS Kesehatan, internet, TV kabel, dan berbagai tagihan lainnya dalam satu platform.'],
            ['group' => 'homepage', 'key' => 'service_3_title', 'value' => 'Top-Up Game'],
            ['group' => 'homepage', 'key' => 'service_3_description', 'value' => 'Top-up diamond Mobile Legends, UC PUBG, Free Fire, Genshin Impact, dan 100+ game populer lainnya.'],
            ['group' => 'homepage', 'key' => 'service_4_title', 'value' => 'E-Money & E-Wallet'],
            ['group' => 'homepage', 'key' => 'service_4_description', 'value' => 'Isi saldo GoPay, OVO, DANA, ShopeePay, LinkAja, dan berbagai dompet digital lainnya dengan mudah.'],

            // About
            ['group' => 'homepage', 'key' => 'about_title', 'value' => 'Mengapa Memilih MonetraPay?'],
            ['group' => 'homepage', 'key' => 'about_description', 'value' => 'MonetraPay hadir sebagai solusi bisnis digital terpercaya yang telah melayani lebih dari 100.000 agen di seluruh Indonesia. Dengan teknologi terkini, sistem kami menjamin transaksi yang cepat, aman, dan menguntungkan. Bergabunglah dengan komunitas agen sukses MonetraPay dan raih penghasilan tambahan dari rumah.'],
            ['group' => 'homepage', 'key' => 'about_image', 'value' => null],

            // CTA
            ['group' => 'homepage', 'key' => 'cta_title', 'value' => 'Siap Mengembangkan Bisnis Pulsa & PPOB?'],
            ['group' => 'homepage', 'key' => 'cta_description', 'value' => 'Bergabung sekarang dan nikmati keuntungan menjadi mitra MonetraPay. Deposit mulai dari Rp 10.000, margin kompetitif, dan dukungan 24/7.'],
            ['group' => 'homepage', 'key' => 'cta_button_text', 'value' => 'Daftar Jadi Agen'],
            ['group' => 'homepage', 'key' => 'cta_button_url', 'value' => '#daftar'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        $this->command->info('Settings seeded successfully.');
    }
}
