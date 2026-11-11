<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing menus (disable foreign key checks)
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        MenuItem::truncate();
        Menu::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Header Menu (Primary Navigation)
        $headerMenu = Menu::create([
            'name' => 'Primary Navigation',
            'location' => 'header',
            'is_active' => true,
        ]);

        $headerItems = [
            ['label' => 'Beranda',      'url' => '/',             'type' => 'custom', 'order' => 1],
            ['label' => 'Layanan',      'url' => '/layanan',      'type' => 'custom', 'order' => 2],
            ['label' => 'Blog',         'url' => '/blog',         'type' => 'custom', 'order' => 3],
            ['label' => 'Tentang Kami', 'url' => '/tentang-kami', 'type' => 'custom', 'order' => 4],
            ['label' => 'Kontak',       'url' => '/kontak',       'type' => 'custom', 'order' => 5],
        ];

        foreach ($headerItems as $item) {
            MenuItem::create([
                'menu_id' => $headerMenu->id,
                'label'   => $item['label'],
                'url'     => $item['url'],
                'type'    => $item['type'],
                'order'   => $item['order'],
            ]);
        }

        // 2. Footer Menu
        $footerMenu = Menu::create([
            'name' => 'Footer Menu',
            'location' => 'footer',
            'is_active' => true,
        ]);

        $footerItems = [
            ['label' => 'Tentang MonetraPay', 'url' => '/tentang-kami',       'type' => 'custom', 'order' => 1],
            ['label' => 'Layanan Kami',        'url' => '/layanan',            'type' => 'custom', 'order' => 2],
            ['label' => 'Blog & Artikel',      'url' => '/blog',               'type' => 'custom', 'order' => 3],
            ['label' => 'Kontak Kami',         'url' => '/kontak',             'type' => 'custom', 'order' => 4],
            ['label' => 'Syarat & Ketentuan',  'url' => '/syarat-ketentuan',   'type' => 'custom', 'order' => 5],
            ['label' => 'Kebijakan Privasi',   'url' => '/kebijakan-privasi',  'type' => 'custom', 'order' => 6],
        ];

        foreach ($footerItems as $item) {
            MenuItem::create([
                'menu_id' => $footerMenu->id,
                'label'   => $item['label'],
                'url'     => $item['url'],
                'type'    => $item['type'],
                'order'   => $item['order'],
            ]);
        }

        // 3. Social Menu
        $socialMenu = Menu::create([
            'name' => 'Social Media',
            'location' => 'social',
            'is_active' => true,
        ]);

        $socialItems = [
            ['label' => 'Facebook',  'url' => 'https://facebook.com/monetrapay',         'type' => 'external', 'order' => 1],
            ['label' => 'Twitter',   'url' => 'https://twitter.com/monetrapay',           'type' => 'external', 'order' => 2],
            ['label' => 'Instagram', 'url' => 'https://instagram.com/monetrapay',         'type' => 'external', 'order' => 3],
            ['label' => 'LinkedIn',  'url' => 'https://linkedin.com/company/monetrapay',  'type' => 'external', 'order' => 4],
            ['label' => 'YouTube',   'url' => 'https://youtube.com/@monetrapay',          'type' => 'external', 'order' => 5],
        ];

        foreach ($socialItems as $item) {
            MenuItem::create([
                'menu_id' => $socialMenu->id,
                'label'   => $item['label'],
                'url'     => $item['url'],
                'type'    => $item['type'],
                'order'   => $item['order'],
            ]);
        }

        $this->command->info('✅ Menus seeded successfully!');
        $this->command->info('   - Header Menu: ' . $headerMenu->items()->count() . ' items');
        $this->command->info('   - Footer Menu: ' . $footerMenu->items()->count() . ' items');
        $this->command->info('   - Social Menu: ' . $socialMenu->items()->count() . ' items');
    }
}
