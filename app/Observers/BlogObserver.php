<?php

namespace App\Observers;

use App\Models\Blog;
use App\Services\SitemapService;

class BlogObserver
{
    public function __construct(protected SitemapService $sitemapService) {}

    /**
     * Handle the Blog "created" event.
     * Regenerate sitemap when a published blog post is created.
     */
    public function created(Blog $blog): void
    {
        if ($blog->status === 'published') {
            $this->sitemapService->regenerate();
        }
    }

    /**
     * Handle the Blog "updated" event.
     * Regenerate sitemap if status changed or if a published post was updated.
     */
    public function updated(Blog $blog): void
    {
        if ($blog->isDirty('status') || $blog->status === 'published') {
            $this->sitemapService->regenerate();
        }
    }

    /**
     * Handle the Blog "deleted" event.
     * Regenerate sitemap when any blog post is deleted (soft delete).
     */
    public function deleted(Blog $blog): void
    {
        $this->sitemapService->regenerate();
    }

    /**
     * Handle the Blog "restored" event.
     * Regenerate sitemap when a published blog post is restored from trash.
     */
    public function restored(Blog $blog): void
    {
        if ($blog->status === 'published') {
            $this->sitemapService->regenerate();
        }
    }

    /**
     * Handle the Blog "forceDeleted" event.
     * Regenerate sitemap when a blog post is permanently deleted.
     */
    public function forceDeleted(Blog $blog): void
    {
        $this->sitemapService->regenerate();
    }
}
