<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixStoragePermissions extends Command
{
    protected $signature = 'storage:fix-permissions';

    protected $description = 'Fix storage directory permissions (Windows icacls + clear cached views)';

    public function handle(): int
    {
        $this->info('Fixing storage permissions...');

        $dirs = [
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('framework/sessions'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        foreach ($dirs as $dir) {
            if (! is_dir($dir)) {
                mkdir($dir, 0777, true);
                $this->line("  Created: $dir");
            }

            if (PHP_OS_FAMILY === 'Windows') {
                $escapedDir = escapeshellarg($dir);
                exec("icacls {$escapedDir} /grant Everyone:(OI)(CI)F /T /Q 2>nul");
            } else {
                chmod($dir, 0775);
            }
        }

        $this->info('✓ Permissions fixed.');

        // Clear cached views so they are recompiled fresh
        $this->call('view:clear');
        $this->call('cache:clear');

        $this->info('Done.');

        return self::SUCCESS;
    }
}
