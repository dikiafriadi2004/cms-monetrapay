<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscriber;
use App\Models\Page;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AnalyticsService
{
    /**
     * Cache TTL in minutes.
     */
    protected int $cacheTtl = 60;

    /**
     * Always configured — this implementation uses the local database.
     */
    public function isConfigured(): bool
    {
        return true;
    }

    /**
     * Get summary metrics for the last 7 days. Cached for 60 minutes.
     *
     * @return array|null Returns null on unexpected error.
     */
    public function getSummary(): ?array
    {
        try {
            return Cache::remember('analytics.summary', $this->cacheTtl * 60, function () {
                return $this->fetchSummaryFromApi();
            });
        } catch (\Exception $e) {
            Log::error('Analytics DB error (getSummary): ' . $e->getMessage());

            return Cache::get('analytics.summary');
        }
    }

    /**
     * Get daily activity trend for the last 30 days. Cached for 60 minutes.
     *
     * @return array|null
     */
    public function getDailyTrend(): ?array
    {
        try {
            return Cache::remember('analytics.daily_trend', $this->cacheTtl * 60, function () {
                return $this->fetchDailyTrendFromApi();
            });
        } catch (\Exception $e) {
            Log::error('Analytics DB error (getDailyTrend): ' . $e->getMessage());

            return Cache::get('analytics.daily_trend');
        }
    }

    /**
     * Get top 5 latest published blog posts. Cached for 60 minutes.
     *
     * @return array|null
     */
    public function getTopPages(): ?array
    {
        try {
            return Cache::remember('analytics.top_pages', $this->cacheTtl * 60, function () {
                return $this->fetchTopPagesFromApi();
            });
        } catch (\Exception $e) {
            Log::error('Analytics DB error (getTopPages): ' . $e->getMessage());

            return Cache::get('analytics.top_pages');
        }
    }

    /**
     * Fetch summary data from the local database.
     *
     * @return array{
     *   users: int,
     *   pageviews: int,
     *   comments: int,
     *   contacts: int,
     *   total_blogs_published: int,
     *   total_blogs_draft: int,
     *   total_pages_published: int,
     *   total_comments_approved: int,
     *   total_comments_pending: int,
     *   total_contacts_unread: int,
     *   total_contacts_total: int,
     *   total_subscribers_active: int,
     *   total_subscribers_total: int,
     * }
     */
    protected function fetchSummaryFromApi(): array
    {
        $blogsPublished = Blog::where('status', 'published')->count();
        $blogsDraft     = Blog::where('status', 'draft')->count();
        $pagesPublished = Page::where('status', 'published')->count();

        $commentsApproved = Comment::where('status', Comment::STATUS_APPROVED)->count();
        $commentsPending  = Comment::where('status', Comment::STATUS_PENDING)->count();

        $contactsTotal  = ContactSubmission::count();
        $contactsUnread = ContactSubmission::where('is_read', false)->count();

        $subscribersTotal  = NewsletterSubscriber::count();
        $subscribersActive = NewsletterSubscriber::active()->count();

        return [
            'users'                    => $subscribersActive,
            'pageviews'                => $blogsPublished + $pagesPublished,
            'comments'                 => $commentsApproved,
            'contacts'                 => $contactsTotal,
            'total_blogs_published'    => $blogsPublished,
            'total_blogs_draft'        => $blogsDraft,
            'total_pages_published'    => $pagesPublished,
            'total_comments_approved'  => $commentsApproved,
            'total_comments_pending'   => $commentsPending,
            'total_contacts_unread'    => $contactsUnread,
            'total_contacts_total'     => $contactsTotal,
            'total_subscribers_active' => $subscribersActive,
            'total_subscribers_total'  => $subscribersTotal,
        ];
    }

    /**
     * Fetch daily publish activity for the last 30 days.
     * Each entry is ['date' => 'YYYY-MM-DD', 'visitors' => int].
     * 'visitors' is used as a proxy: number of blogs published that day.
     *
     * @return array<int, array{date: string, visitors: int}>
     */
    protected function fetchDailyTrendFromApi(): array
    {
        $start = Carbon::now()->subDays(29)->startOfDay();
        $end   = Carbon::now()->endOfDay();

        // Count blogs published per day over the last 30 days
        $publishedByDay = Blog::where('status', 'published')
            ->whereBetween('published_at', [$start, $end])
            ->selectRaw('DATE(published_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $result = [];
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now()->subDays(29 - $i)->format('Y-m-d');
            $result[] = [
                'date'     => $date,
                'visitors' => (int) ($publishedByDay[$date] ?? 0),
            ];
        }

        return $result;
    }

    /**
     * Fetch top 5 latest published blog posts as proxy "top pages".
     *
     * @return array<int, array{title: string, pageviews: int}>
     */
    protected function fetchTopPagesFromApi(): array
    {
        $blogs = Blog::where('status', 'published')
            ->orderByDesc('published_at')
            ->limit(5)
            ->get(['title']);

        return $blogs->values()->map(function (Blog $blog, int $index) {
            return [
                'title'     => $blog->title,
                'pageviews' => 5 - $index, // rank-based proxy: #1 gets 5, #5 gets 1
            ];
        })->toArray();
    }
}
