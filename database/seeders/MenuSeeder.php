<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Header Menu
        $headerMenu = Menu::updateOrCreate(
            ['location' => 'header'],
            ['name' => 'Menu Utama', 'location' => 'header', 'is_active' => true]
        );

        $headerMenu->items()->delete();

        $headerItems = [
            ['label' => 'Beranda',      'type' => 'custom', 'url' => '/',             'order' => 0],
            ['label' => 'Layanan',      'type' => 'custom', 'url' => '/layanan',      'order' => 1],
            ['label' => 'Blog',         'type' => 'custom', 'url' => '/blog',         'order' => 2],
            ['label' => 'Tentang Kami', 'type' => 'custom', 'url' => '/tentang-kami', 'order' => 3],
            ['label' => 'Kontak',       'type' => 'custom', 'url' => '/kontak',       'order' => 4],
        ];

        foreach ($headerItems as $item) {
            MenuItem::create(array_merge($item, [
                'menu_id' => $headerMenu->id,
                'parent_id' => null,
                'target' => '_self',
            ]));
        }

        // Footer Menu
        $footerMenu = Menu::updateOrCreate(
            ['location' => 'footer'],
            ['name' => 'Menu Footer', 'location' => 'footer', 'is_active' => true]
        );

        $footerMenu->items()->delete();

        $footerItems = [
            ['label' => 'Beranda', 'type' => 'custom', 'url' => '/', 'order' => 0],
            ['label' => 'Blog', 'type' => 'custom', 'url' => '/blog', 'order' => 1],
            ['label' => 'Kebijakan Privasi', 'type' => 'custom', 'url' => '/kebijakan-privasi', 'order' => 2],
            ['label' => 'Syarat & Ketentuan', 'type' => 'custom', 'url' => '/syarat-ketentuan', 'order' => 3],
            ['label' => 'FAQ', 'type' => 'custom', 'url' => '/faq', 'order' => 4],
        ];

        foreach ($footerItems as $item) {
            MenuItem::create(array_merge($item, [
                'menu_id' => $footerMenu->id,
                'parent_id' => null,
                'target' => '_self',
            ]));
        }

        $this->command->info('Menus seeded successfully.');
    }
}
