<?php

namespace App\Support;

use ErrorException;
use Illuminate\Filesystem\Filesystem;

class RetryingFilesystem extends Filesystem
{
    /**
     * Retry count for Windows/transient file-lock rename failures.
     */
    protected int $replaceRetries = 10;

    /**
     * Delay between retries in microseconds.
     */
    protected int $replaceRetryDelay = 50_000;

    /**
     * Replace a file atomically, retrying transient rename failures.
     *
     * Laravel's default implementation writes a temp file and renames it into
     * place once. On Windows, compiled Blade views can be briefly locked by a
     * concurrent refresh, antivirus, or the PHP server, causing an intermittent
     * "Access is denied (code: 5)". Retrying keeps the atomic-write behavior
     * without patching vendor files.
     */
    public function replace($path, $content, $mode = null)
    {
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;
        $tempPath = tempnam(dirname($path), basename($path));

        if ($tempPath === false) {
            throw new ErrorException('Unable to create temporary file for replacement.');
        }

        if (! is_null($mode)) {
            @chmod($tempPath, $mode);
        } else {
            @chmod($tempPath, 0777 - umask());
        }

        file_put_contents($tempPath, $content);

        $lastError = null;
        for ($attempt = 0; $attempt <= $this->replaceRetries; $attempt++) {
            clearstatcache(true, $path);
            clearstatcache(true, $tempPath);

            if ($this->performRename($tempPath, $path)) {
                return;
            }

            $lastError = error_get_last();

            if ($attempt < $this->replaceRetries) {
                usleep($this->replaceRetryDelay);
            }
        }

        @unlink($tempPath);

        $message = $lastError['message'] ?? "rename({$tempPath},{$path}): Access is denied";
        throw new ErrorException($message);
    }

    protected function performRename(string $from, string $to): bool
    {
        return @rename($from, $to);
    }
}
