<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class CmsInstall extends Command
{
    protected $signature = 'cms:install';
    protected $description = 'First-time interactive setup: configures admin credentials and runs migrations, seeder, and optimization.';

    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║         CMS MonetraPay Installer         ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info('');

        // 1. Admin name
        $adminName = $this->ask('Nama admin?', 'Super Admin');

        // 2. Admin email (with validation)
        do {
            $adminEmail = $this->ask('Email admin?');
            $valid = filter_var($adminEmail, FILTER_VALIDATE_EMAIL);
            if (! $valid) {
                $this->error('Format email tidak valid. Silakan coba lagi.');
            }
        } while (! $valid);

        // 3. Admin password (hidden, minimum 8 chars, confirm)
        do {
            $adminPassword = $this->secret('Password admin? (min 8 karakter)');
            if (strlen($adminPassword) < 8) {
                $this->error('Password terlalu pendek (minimum 8 karakter).');
                continue;
            }
            $confirm = $this->secret('Konfirmasi password?');
            if ($adminPassword !== $confirm) {
                $this->error('Password tidak cocok. Silakan coba lagi.');
                $adminPassword = null;
            }
        } while (! $adminPassword);

        // 4. Write ADMIN_* to .env
        $this->setEnvValue('ADMIN_NAME', $adminName);
        $this->setEnvValue('ADMIN_EMAIL', $adminEmail);
        $this->setEnvValue('ADMIN_PASSWORD', $adminPassword);

        $this->info('\n⚙  Menjalankan migrasi...');
        Artisan::call('migrate', ['--force' => true], $this->output);

        $this->info('\n⚙  Menjalankan seeder...');
        Artisan::call('db:seed', ['--force' => true], $this->output);

        $this->info('\n⚙  Membuat storage link...');
        Artisan::call('storage:link', [], $this->output);

        $this->info('\n⚙  Optimasi...');
        Artisan::call('optimize', [], $this->output);

        $appUrl = env('APP_URL', 'https://yourdomain.com');

        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║       ✅  CMS Berhasil Diinstall!        ║');
        $this->info('╠══════════════════════════════════════════╣');
        $this->info('║  URL Admin  : ' . str_pad($appUrl . '/admin', 27) . '║');
        $this->info('║  Email      : ' . str_pad($adminEmail, 27) . '║');
        $this->info('║  Password   : ' . str_pad($adminPassword, 27) . '║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->warn('⚠ Simpan credentials di atas sebelum menutup terminal ini!');
        $this->info('');

        return self::SUCCESS;
    }

    /**
     * Update or add a key=value pair in the .env file.
     */
    private function setEnvValue(string $key, string $value): void
    {
        $path = base_path('.env');
        if (! file_exists($path)) {
            return;
        }

        $content = file_get_contents($path);

        // Escape value: wrap in quotes if it contains spaces
        $escapedValue = str_contains($value, ' ') ? '"' . $value . '"' : $value;

        if (preg_match("/^{$key}=.*/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$escapedValue}", $content);
        } else {
            $content .= "\n{$key}={$escapedValue}";
        }

        file_put_contents($path, $content);
    }
}
