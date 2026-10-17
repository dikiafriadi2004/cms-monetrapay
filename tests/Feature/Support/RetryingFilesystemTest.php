<?php

use App\Support\RetryingFilesystem;
use Illuminate\Filesystem\Filesystem;

it('retries replace rename failures before succeeding', function () {
    $filesystem = new class extends RetryingFilesystem
    {
        public int $attempts = 0;

        protected function performRename(string $from, string $to): bool
        {
            $this->attempts++;

            if ($this->attempts < 3) {
                return false;
            }

            return parent::performRename($from, $to);
        }
    };

    $path = storage_path('framework/views/retrying-filesystem-test.php');
    @unlink($path);

    $filesystem->replace($path, 'compiled-view');

    expect($filesystem->attempts)->toBe(3);
    expect(file_get_contents($path))->toBe('compiled-view');

    @unlink($path);
});

it('binds the retrying filesystem as the application filesystem service', function () {
    expect(app('files'))->toBeInstanceOf(RetryingFilesystem::class);
    expect(app(Filesystem::class))->toBeInstanceOf(RetryingFilesystem::class);
});
