<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Berita',          'slug' => 'berita',          'description' => 'Berita dan informasi terkini'],
            ['name' => 'Tips & Trik',     'slug' => 'tips-trik',       'description' => 'Tips dan trik bermanfaat'],
            ['name' => 'Tutorial',        'slug' => 'tutorial',        'description' => 'Panduan langkah demi langkah'],
            ['name' => 'Promo',           'slug' => 'promo',           'description' => 'Penawaran dan promosi spesial'],
            ['name' => 'Pengumuman',      'slug' => 'pengumuman',      'description' => 'Pengumuman resmi'],
            ['name' => 'Produk & Layanan','slug' => 'produk-layanan',  'description' => 'Informasi produk dan layanan'],
            ['name' => 'Teknologi',       'slug' => 'teknologi',       'description' => 'Dunia teknologi digital'],
            ['name' => 'Inspirasi',       'slug' => 'inspirasi',       'description' => 'Kisah inspiratif dan motivasi'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('Categories seeded successfully.');
    }
}
