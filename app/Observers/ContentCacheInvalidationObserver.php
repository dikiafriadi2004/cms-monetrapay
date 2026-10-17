<?php

namespace App\Observers;

use App\Services\ContentCacheService;

class ContentCacheInvalidationObserver
{
    public function saved($model): void
    {
        app(ContentCacheService::class)->flushAll();
    }

    public function deleted($model): void
    {
        app(ContentCacheService::class)->flushAll();
    }

    public function restored($model): void
    {
        app(ContentCacheService::class)->flushAll();
    }
}
