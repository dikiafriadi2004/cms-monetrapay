<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SentryTest extends Command
{
    protected $signature = 'sentry:test {--info : Send an info message instead of an exception}';

    protected $description = 'Send a test event to Sentry to verify integration';

    public function handle(): int
    {
        $dsn = config('sentry.dsn');

        if (empty($dsn)) {
            $this->error('Sentry DSN is not configured. Set SENTRY_LARAVEL_DSN in .env');

            return self::FAILURE;
        }

        $this->info('DSN: '.preg_replace('/(@).+(\..+)/', '$1***$2', $dsn));
        $this->info('Environment: '.config('sentry.environment', config('app.env')));

        if ($this->option('info')) {
            \Sentry\captureMessage('Sentry test message from MonetraCMS at '.now()->toIso8601String());
            $this->info('✓ Test message sent.');
        } else {
            try {
                throw new \Exception('Sentry test exception from MonetraCMS at '.now()->toIso8601String());
            } catch (\Throwable $e) {
                \Sentry\captureException($e);
                $this->info('✓ Test exception sent.');
            }
        }

        $this->info('Check your Sentry dashboard to verify the event arrived.');

        return self::SUCCESS;
    }
}
