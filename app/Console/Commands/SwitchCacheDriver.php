<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Helper command to check Redis availability and optionally switch drivers.
 */
class SwitchCacheDriver extends Command
{
    protected $signature = 'cache:check-redis {--switch : Switch to Redis if available, database otherwise}';

    protected $description = 'Check Redis connectivity and optionally auto-switch cache/queue/session drivers';

    public function handle(): int
    {
        $this->info('Checking Redis connectivity...');

        try {
            $redis = new \Redis;
            $connected = @$redis->connect(
                config('database.redis.default.host', '127.0.0.1'),
                (int) config('database.redis.default.port', 6379),
                2 // 2 second timeout
            );

            if (! $connected) {
                throw new \Exception('Connection refused');
            }

            $redis->ping();
            $this->info('✓ Redis is available at '.config('database.redis.default.host').':'.config('database.redis.default.port'));

            if ($this->option('switch')) {
                $this->switchToRedis();
            } else {
                $this->line('Run with --switch to automatically update .env to use Redis drivers.');
            }

            return self::SUCCESS;

        } catch (\Throwable $e) {
            $this->warn('✗ Redis not available: '.$e->getMessage());
            $this->line('Continuing with database drivers (cache, queue, session).');

            return self::SUCCESS;
        }
    }

    protected function switchToRedis(): void
    {
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);

        $replacements = [
            '/^CACHE_STORE=.*/m' => 'CACHE_STORE=redis',
            '/^QUEUE_CONNECTION=.*/m' => 'QUEUE_CONNECTION=redis',
            '/^SESSION_DRIVER=.*/m' => 'SESSION_DRIVER=redis',
        ];

        foreach ($replacements as $pattern => $replacement) {
            $content = preg_replace($pattern, $replacement, $content);
        }

        file_put_contents($envPath, $content);

        $this->info('✓ Switched CACHE_STORE, QUEUE_CONNECTION, SESSION_DRIVER to redis in .env');
        $this->line('Run: php artisan config:clear && php artisan cache:clear');
    }
}
