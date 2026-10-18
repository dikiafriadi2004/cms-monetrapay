<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,   // Users, roles, permissions
            SettingsSeeder::class,          // Site settings & homepage content
            CategorySeeder::class,          // Blog categories
            BlogSeeder::class,              // Sample blog posts
            PageSeeder::class,              // Static pages
            MenuSeeder::class,              // Navigation menus
            ThemeSeeder::class,             // 10 themes
            DefaultPagesSeeder::class,      // Kontak, Tentang Kami, Layanan with page builder
        ]);
    }
}
