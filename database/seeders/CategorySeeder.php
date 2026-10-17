<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Tips & Trik', 'slug' => 'tips-trik', 'description' => 'Tips dan trik untuk memaksimalkan bisnis digital Anda'],
            ['name' => 'Pulsa & Data', 'slug' => 'pulsa-data', 'description' => 'Informasi seputar produk pulsa dan paket data'],
            ['name' => 'PPOB', 'slug' => 'ppob', 'description' => 'Panduan dan informasi layanan PPOB'],
            ['name' => 'Top-Up Game', 'slug' => 'topup-game', 'description' => 'Berita dan panduan top-up game online'],
            ['name' => 'E-Money', 'slug' => 'e-money', 'description' => 'Informasi seputar dompet digital dan e-money'],
            ['name' => 'Bisnis Digital', 'slug' => 'bisnis-digital', 'description' => 'Panduan memulai dan mengembangkan bisnis digital'],
            ['name' => 'Promo & Penawaran', 'slug' => 'promo-penawaran', 'description' => 'Promo terbaru dan penawaran spesial dari MonetraPay'],
            ['name' => 'Berita', 'slug' => 'berita', 'description' => 'Berita terkini seputar industri fintech dan digital'],
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
