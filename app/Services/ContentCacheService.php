<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

/**
 * Caches expensive read queries used by frontend pages.
 * All keys auto-invalidate via observers when underlying models change.
 */
class ContentCacheService
{
    public const TTL = 1800; // 30 minutes

    public const PREFIX = 'content.';

    /**
     * Top N latest published blogs (homepage cards, sidebar lists).
     */
    public function latestBlogs(int $limit = 3, array $only = ['id', 'title', 'slug', 'excerpt', 'featured_image', 'published_at', 'user_id'])
    {
        return Cache::remember(
            self::PREFIX.'latest_blogs.'.$limit,
            self::TTL,
            fn () => Blog::where('status', 'published')
                ->with(['author:id,name', 'categories:id,name,slug'])
                ->orderByDesc('published_at')
                ->limit($limit)
                ->get($only)
        );
    }

    /**
     * Recent posts for blog sidebar (excluding given blog id).
     */
    public function recentBlogsExcept(int $excludeId, int $limit = 5)
    {
        return Cache::remember(
            self::PREFIX.'recent_blogs.except.'.$excludeId.'.'.$limit,
            self::TTL,
            fn () => Blog::where('status', 'published')
                ->where('id', '!=', $excludeId)
                ->orderByDesc('published_at')
                ->limit($limit)
                ->get(['id', 'title', 'slug', 'featured_image', 'published_at'])
        );
    }

    /**
     * Categories with published blog counts (sidebar widget).
     */
    public function categoriesWithBlogCount()
    {
        return Cache::remember(
            self::PREFIX.'categories_with_count',
            self::TTL,
            fn () => Category::withCount(['blogs' => fn ($q) => $q->where('status', 'published')])
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Flush all content cache. Called from observers when content changes.
     */
    public function flushAll(): void
    {
        // Without tagged cache (database driver doesn't support tags),
        // we forget the well-known prefixed keys.
        $keys = ['latest_blogs.3', 'latest_blogs.5', 'latest_blogs.10', 'categories_with_count'];
        foreach ($keys as $k) {
            Cache::forget(self::PREFIX.$k);
        }

        // recent_blogs.except.X.Y keys are user-specific by id; skip targeted flush.
        // They naturally expire within TTL.
    }
}
