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
                'title' => 'Layanan',
                'slug' => 'layanan',
                'status' => 'published',
                'template' => 'layanan',
                'meta_title' => 'Layanan MonetraPay - Pulsa, PPOB, Top-Up Game & E-Money',
                'meta_description' => 'Temukan semua layanan digital MonetraPay: pulsa, PPOB, top-up game, dan e-money.',
                'content' => '<p>Halaman layanan MonetraPay.</p>',
            ],
            [
                'title' => 'Tentang Kami',
                'slug' => 'tentang-kami',
                'status' => 'published',
                'template' => 'tentang-kami',
                'meta_title' => 'Tentang MonetraPay - Platform Transaksi Digital Terpercaya',
                'meta_description' => 'Pelajari lebih lanjut tentang MonetraPay, platform all-in-one untuk bisnis digital Anda.',
                'content' => '<p>Halaman tentang kami MonetraPay.</p>',
            ],
            [
                'title' => 'Kontak',
                'slug' => 'kontak',
                'status' => 'published',
                'template' => 'kontak',
                'meta_title' => 'Kontak MonetraPay - Hubungi Kami',
                'meta_description' => 'Hubungi tim MonetraPay untuk informasi lebih lanjut atau bantuan.',
                'content' => '<p>Halaman kontak MonetraPay.</p>',
            ],
            [
                'title' => 'Kebijakan Privasi',
                'slug' => 'kebijakan-privasi',
                'status' => 'published',
                'template' => 'default',
                'meta_title' => 'Kebijakan Privasi - MonetraPay',
                'meta_description' => 'Baca kebijakan privasi MonetraPay.',
                'content' => '<h2>Kebijakan Privasi MonetraPay</h2><p>MonetraPay berkomitmen untuk melindungi privasi dan keamanan data pribadi pengguna kami.</p>',
            ],
            [
                'title' => 'Syarat & Ketentuan',
                'slug' => 'syarat-ketentuan',
                'status' => 'published',
                'template' => 'default',
                'meta_title' => 'Syarat & Ketentuan - MonetraPay',
                'meta_description' => 'Baca syarat dan ketentuan penggunaan layanan MonetraPay.',
                'content' => '<h2>Syarat dan Ketentuan MonetraPay</h2><p>Dengan menggunakan layanan MonetraPay, Anda menyetujui syarat dan ketentuan berikut.</p>',
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'status' => 'published',
                'template' => 'default',
                'meta_title' => 'FAQ - MonetraPay',
                'meta_description' => 'Pertanyaan yang sering diajukan tentang layanan MonetraPay.',
                'content' => '<h2>FAQ MonetraPay</h2><h3>Bagaimana cara mendaftar?</h3><p>Kunjungi halaman kontak kami dan isi formulir pendaftaran.</p>',
            ],
        ];

        foreach ($pages as $pageData) {
            Page::updateOrCreate(['slug' => $pageData['slug']], $pageData);
        }

        $this->command->info('Pages seeded successfully.');
    }
}
