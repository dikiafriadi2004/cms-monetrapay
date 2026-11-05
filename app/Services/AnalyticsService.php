<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Comment;
use App\Models\ContactSubmission;
use App\Models\NewsletterSubscriber;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalyticsService
{
    /**
     * Cache TTL in minutes.
     */
    protected int $cacheTtl = 60;

    /**
     * Returns true when GA4 credentials are configured in settings.
     */
    public function isConfigured(): bool
    {
        $propertyId = Setting::get('ga_property_id', 'seo');
        $credentialsJson = Setting::get('ga_credentials_json', 'seo');
        return !empty($propertyId) && !empty($credentialsJson);
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
     * Fetch summary from GA4 API if configured, otherwise fallback to local DB.
     */
    protected function fetchSummaryFromApi(): array
    {
        $propertyId      = Setting::get('ga_property_id', 'seo');
        $credentialsJson = Setting::get('ga_credentials_json', 'seo');

        if (!$propertyId || !$credentialsJson) {
            return $this->getLocalSummary();
        }

        try {
            $credentials = json_decode(decrypt($credentialsJson), true);
            $accessToken = $this->getGoogleAccessToken($credentials);

            if (!$accessToken) {
                return $this->getLocalSummary();
            }

            $response = Http::withToken($accessToken)
                ->post("https://analyticsdata.googleapis.com/v1beta/{$propertyId}:runReport", [
                    'dateRanges' => [['startDate' => '30daysAgo', 'endDate' => 'today']],
                    'metrics'    => [
                        ['name' => 'activeUsers'],
                        ['name' => 'sessions'],
                        ['name' => 'screenPageViews'],
                        ['name' => 'bounceRate'],
                        ['name' => 'averageSessionDuration'],
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $row  = $data['rows'][0]['metricValues'] ?? [];

                // Merge GA4 real-time visitor metrics with local DB content counts
                $local = $this->getLocalSummary();
                return array_merge($local, [
                    'users'        => (int) ($row[0]['value'] ?? 0),
                    'sessions'     => (int) ($row[1]['value'] ?? 0),
                    'pageviews'    => (int) ($row[2]['value'] ?? 0),
                    'bounce_rate'  => round((float) ($row[3]['value'] ?? 0) * 100, 1),
                    'avg_duration' => round((float) ($row[4]['value'] ?? 0)),
                    'source'       => 'google_analytics',
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('GA4 API error: ' . $e->getMessage());
        }

        return $this->getLocalSummary();
    }

    /**
     * Generate a Google OAuth2 access token from a service account JWT.
     */
    private function getGoogleAccessToken(array $credentials): ?string
    {
        try {
            $now     = time();
            $header  = rtrim(strtr(base64_encode(json_encode(['alg' => 'RS256', 'typ' => 'JWT'])), '+/', '-_'), '=');
            $payload = rtrim(strtr(base64_encode(json_encode([
                'iss'   => $credentials['client_email'],
                'scope' => 'https://www.googleapis.com/auth/analytics.readonly',
                'aud'   => 'https://oauth2.googleapis.com/token',
                'exp'   => $now + 3600,
                'iat'   => $now,
            ])), '+/', '-_'), '=');

            $unsignedToken = $header . '.' . $payload;
            $privateKey    = openssl_pkey_get_private($credentials['private_key']);
            openssl_sign($unsignedToken, $signature, $privateKey, 'SHA256');
            $jwt = $unsignedToken . '.' . rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]);

            return $response->json('access_token');
        } catch (\Throwable $e) {
            Log::error('GA4 token error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Test GA4 API connectivity with the given credentials and property ID.
     *
     * @throws \RuntimeException on failure
     */
    public function testConnection(array $credentials, string $propertyId): bool
    {
        $accessToken = $this->getGoogleAccessToken($credentials);
        if (!$accessToken) {
            throw new \RuntimeException('Gagal mendapatkan access token. Periksa credentials JSON.');
        }

        $response = Http::withToken($accessToken)
            ->post("https://analyticsdata.googleapis.com/v1beta/{$propertyId}:runReport", [
                'dateRanges' => [['startDate' => 'yesterday', 'endDate' => 'today']],
                'metrics'    => [['name' => 'activeUsers']],
            ]);

        if (!$response->successful()) {
            $error = $response->json('error.message') ?? $response->body();
            throw new \RuntimeException('GA4 API error: ' . $error);
        }

        return true;
    }

    /**
     * Fetch summary data from the local database (fallback).
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
    protected function getLocalSummary(): array
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
