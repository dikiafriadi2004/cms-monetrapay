<?php

namespace App\Observers;

use App\Services\SitemapService;

class SitemapInvalidationObserver
{
    /**
     * Mark sitemap for regeneration. Uses a per-request flag so multiple
     * model saves in one request only trigger one regeneration during
     * application terminate phase.
     */
    public static function markDirty(): void
    {
        if (app()->bound('sitemap.dirty')) {
            return;
        }

        app()->instance('sitemap.dirty', true);

        app()->terminating(function () {
            try {
                app(SitemapService::class)->regenerate();
            } catch (\Throwable $e) {
                report($e);
            }
        });
    }

    public function saved($model): void
    {
        self::markDirty();
    }

    public function deleted($model): void
    {
        self::markDirty();
    }

    public function restored($model): void
    {
        self::markDirty();
    }

    public function forceDeleted($model): void
    {
        self::markDirty();
    }
}
