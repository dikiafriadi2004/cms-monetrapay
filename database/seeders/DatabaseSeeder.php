<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Urutan seeding penting — jangan diubah:
     * 1. RolePermissionSeeder  → buat roles, permissions, dan akun admin
     * 2. SettingsSeeder        → semua konfigurasi site (nama, kontak, social, dll)
     * 3. CategorySeeder        → kategori blog
     * 4. MenuSeeder            → menu navigasi (header, footer, social)
     * 5. PageSeeder            → halaman statis dasar
     * 6. LegalPageSeeder       → halaman legal (FAQ, Kebijakan Privasi, Syarat & Ketentuan)
     * 7. DefaultPagesSeeder    → halaman utama dengan page builder (kontak, layanan, tentang)
     * 8. BlogSeeder            → artikel/blog contoh
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            SettingsSeeder::class,
            CategorySeeder::class,
            MenuSeeder::class,
            PageSeeder::class,
            LegalPageSeeder::class,
            DefaultPagesSeeder::class,
            LayananPageSeeder::class,
            TentangKamiPageSeeder::class,
            KontakPageSeeder::class,
            BlogSeeder::class,
            AdsSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('🎉 Database seeding selesai!');
        $this->command->newLine();
        $this->command->table(
            ['Item', 'Keterangan'],
            [
                ['Admin Login',  env('ADMIN_EMAIL', 'admin@monetrapay.com')],
                ['Password',     env('ADMIN_PASSWORD') ? '(dari .env ADMIN_PASSWORD)' : 'password'],
                ['Admin URL',    url('/admin/dashboard')],
                ['Frontend URL', url('/')],
            ]
        );
        $this->command->newLine();
        $this->command->warn('⚠️  Segera ganti password admin setelah login pertama kali!');
        $this->command->warn('⚠️  Set APP_ENV=production dan APP_DEBUG=false di .env sebelum go-live!');
    }
}
