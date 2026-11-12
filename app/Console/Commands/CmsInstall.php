<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CmsInstall extends Command
{
    /**
     * Supports both interactive mode and non-interactive (Docker/CI) mode:
     *
     * Interactive (local):
     *   php artisan cms:install
     *
     * Non-interactive (Docker/CI):
     *   docker compose exec app php artisan cms:install \
     *     --admin-name="Super Admin" \
     *     --admin-email="admin@domain.com" \
     *     --admin-password="Admin@2024!"
     */
    protected $signature = 'cms:install
        {--admin-name=   : Nama super admin}
        {--admin-email=  : Email super admin}
        {--admin-password= : Password super admin (min 8 karakter)}
        {--force         : Skip semua pertanyaan, gunakan nilai dari --option atau default}';

    protected $description = 'Install CMS: setup admin credentials, migrate, seed, dan optimize.';

    public function handle(): int
    {
        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║         MonetraCMS Installer             ║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->info('');

        // ── Resolve credentials ───────────────────────────────────
        // Priority: --option argument > ask interactively (IGNORE .env values)
        // .env values are WRITTEN after user confirms, not read as defaults
        $nonInteractive = $this->option('force') || ! $this->input->isInteractive();

        $adminName = $this->resolveOption(
            optionKey: 'admin-name',
            envKey: null, // Don't read from .env — user must input
            default: 'Super Admin',
            prompt: 'Nama admin?',
            nonInteractive: $nonInteractive
        );

        $adminEmail = $this->resolveEmail($nonInteractive);

        $adminPassword = $this->resolvePassword($nonInteractive);

        // ── Write to .env BEFORE seeding ─────────────────────────
        // Must clear config cache after writing so seeder reads new values
        $this->setEnvValue('ADMIN_NAME', $adminName);
        $this->setEnvValue('ADMIN_EMAIL', $adminEmail);
        $this->setEnvValue('ADMIN_PASSWORD', $adminPassword);

        // Reload config so env() returns new values in this same process
        // This ensures RolePermissionSeeder reads the values just written
        $app = app();
        $app['config']->set('app.admin_email',    $adminEmail);
        $app['config']->set('app.admin_name',     $adminName);
        $app['config']->set('app.admin_password', $adminPassword);

        // Also force-reload the env values in memory
        putenv("ADMIN_EMAIL={$adminEmail}");
        putenv("ADMIN_NAME={$adminName}");
        putenv("ADMIN_PASSWORD={$adminPassword}");
        $_ENV['ADMIN_EMAIL']    = $adminEmail;
        $_ENV['ADMIN_NAME']     = $adminName;
        $_ENV['ADMIN_PASSWORD'] = $adminPassword;

        // ── Run install steps ─────────────────────────────────────
        $this->info('');
        $steps = [
            ['⚙  Menjalankan migrasi...', 'migrate', ['--force' => true]],
            ['⚙  Menjalankan seeder...', 'db:seed', ['--force' => true]],
            ['⚙  Membuat storage link...', 'storage:link', []],
            ['⚙  Optimasi...', 'optimize', []],
        ];

        foreach ($steps as [$label, $command, $args]) {
            $this->info($label);
            Artisan::call($command, $args, $this->output);
        }

        // ── Summary ───────────────────────────────────────────────
        $appUrl = rtrim(env('APP_URL', 'http://localhost:8000'), '/');
        $adminUrl = $appUrl . '/admin';

        $this->info('');
        $this->info('╔══════════════════════════════════════════╗');
        $this->info('║       ✅  CMS Berhasil Diinstall!        ║');
        $this->info('╠══════════════════════════════════════════╣');
        $this->info('║  URL Admin  : ' . str_pad($adminUrl, 27) . '║');
        $this->info('║  Email      : ' . str_pad($adminEmail, 27) . '║');
        $this->info('║  Password   : ' . str_pad($adminPassword, 27) . '║');
        $this->info('╚══════════════════════════════════════════╝');
        $this->warn('⚠ Simpan credentials di atas sebelum menutup terminal!');
        $this->info('');

        return self::SUCCESS;
    }

    // ── Helpers ───────────────────────────────────────────────────

    private function resolveOption(
        string $optionKey,
        ?string $envKey,
        string $default,
        string $prompt,
        bool $nonInteractive
    ): string {
        // 1. From --option argument (highest priority)
        if ($val = $this->option($optionKey)) {
            return $val;
        }
        // 2. From .env only if envKey specified
        if ($envKey && $val = env($envKey)) {
            return $val;
        }
        // 3. Ask interactively or use default
        if ($nonInteractive) {
            return $default;
        }
        return $this->ask($prompt, $default);
    }

    private function resolveEmail(bool $nonInteractive): string
    {
        // 1. From --option
        if ($email = $this->option('admin-email')) {
            if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->error("Email tidak valid: {$email}");
                exit(1);
            }
            return $email;
        }
        // 2. Non-interactive default (don't read from .env)
        if ($nonInteractive) {
            return 'admin@example.com';
        }
        // 3. Interactive
        do {
            $email = $this->ask('Email admin?');
            $valid = filter_var($email, FILTER_VALIDATE_EMAIL);
            if (! $valid) {
                $this->error('Format email tidak valid.');
            }
        } while (! $valid);

        return $email;
    }

    private function resolvePassword(bool $nonInteractive): string
    {
        // 1. From --option
        if ($pass = $this->option('admin-password')) {
            if (strlen($pass) < 8) {
                $this->error('Password terlalu pendek (minimum 8 karakter).');
                exit(1);
            }
            return $pass;
        }
        // 2. Non-interactive — generate random (don't read from .env)
        if ($nonInteractive) {
            $pass = \Illuminate\Support\Str::password(16);
            $this->warn("Password tidak disediakan — generated: {$pass}");
            return $pass;
        }
        // 3. Interactive
        do {
            $pass = $this->secret('Password admin? (min 8 karakter)');
            if (strlen($pass) < 8) {
                $this->error('Password terlalu pendek (minimum 8 karakter).');
                continue;
            }
            $confirm = $this->secret('Konfirmasi password?');
            if ($pass !== $confirm) {
                $this->error('Password tidak cocok.');
                $pass = null;
            }
        } while (! $pass);

        return $pass;
    }

    private function setEnvValue(string $key, string $value): void
    {
        $path = base_path('.env');
        if (! file_exists($path)) {
            return;
        }
        $content = file_get_contents($path);
        $escaped = str_contains($value, ' ') ? '"' . $value . '"' : $value;

        if (preg_match("/^{$key}=.*/m", $content)) {
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$escaped}", $content);
        } else {
            $content .= "\n{$key}={$escaped}";
        }
        file_put_contents($path, $content);
    }
}
