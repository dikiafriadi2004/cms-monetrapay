<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AnalyticsService
{
    /**
     * Cache TTL in minutes.
     */
    protected int $cacheTtl = 60;

    /**
     * Check if Google Analytics is configured.
     */
    public function isConfigured(): bool
    {
        $measurementId = Setting::get('ga_measurement_id', 'seo');

        return ! empty($measurementId) && preg_match('/^G-[A-Za-z0-9]{1,15}$/', $measurementId);
    }

    /**
     * Get summary metrics (users, pageviews, bounce rate, avg session duration)
     * for the last 7 days. Cached for 60 minutes.
     *
     * @return array|null Returns null if not configured, array of metrics otherwise
     */
    public function getSummary(): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            return Cache::remember('analytics.summary', $this->cacheTtl * 60, function () {
                return $this->fetchSummaryFromApi();
            });
        } catch (\Exception $e) {
            Log::error('Analytics API error (getSummary): '.$e->getMessage());

            // Try to serve cached data even if expired
            $cached = Cache::get('analytics.summary');
            if ($cached) {
                return $cached;
            }

            return null;
        }
    }

    /**
     * Get daily visitor trend for the last 30 days.
     * Cached for 60 minutes.
     *
     * @return array|null Returns null if not configured, array of daily data otherwise
     */
    public function getDailyTrend(): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            return Cache::remember('analytics.daily_trend', $this->cacheTtl * 60, function () {
                return $this->fetchDailyTrendFromApi();
            });
        } catch (\Exception $e) {
            Log::error('Analytics API error (getDailyTrend): '.$e->getMessage());

            $cached = Cache::get('analytics.daily_trend');
            if ($cached) {
                return $cached;
            }

            return null;
        }
    }

    /**
     * Get top 10 pages by pageviews for the last 30 days.
     * Cached for 60 minutes.
     *
     * @return array|null Returns null if not configured, array of top pages otherwise
     */
    public function getTopPages(): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            return Cache::remember('analytics.top_pages', $this->cacheTtl * 60, function () {
                return $this->fetchTopPagesFromApi();
            });
        } catch (\Exception $e) {
            Log::error('Analytics API error (getTopPages): '.$e->getMessage());

            $cached = Cache::get('analytics.top_pages');
            if ($cached) {
                return $cached;
            }

            return null;
        }
    }

    /**
     * Fetch summary data from Google Analytics Data API.
     *
     * @return array{users: int, pageviews: int, bounce_rate: float, avg_session_duration: int}
     */
    protected function fetchSummaryFromApi(): array
    {
        $this->ensureReportingCredentialsAreAvailable();

        // Google Analytics Data API reporting is intentionally not mocked here.
        // Returning null through the public methods is safer than showing random
        // numbers that look like real business data.
        throw new \RuntimeException('Google Analytics Data API reporting is not implemented yet.');
    }

    /**
     * Fetch daily trend data from Google Analytics Data API.
     * Returns data for the last 30 days.
     *
     * @return array<int, array{date: string, visitors: int}>
     */
    protected function fetchDailyTrendFromApi(): array
    {
        $this->ensureReportingCredentialsAreAvailable();

        throw new \RuntimeException('Google Analytics Data API reporting is not implemented yet.');
    }

    /**
     * Fetch top pages data from Google Analytics Data API.
     * Returns top 10 pages by pageviews.
     *
     * @return array<int, array{title: string, pageviews: int}>
     */
    protected function fetchTopPagesFromApi(): array
    {
        $this->ensureReportingCredentialsAreAvailable();

        throw new \RuntimeException('Google Analytics Data API reporting is not implemented yet.');
    }

    /**
     * Guard analytics reporting until a real GA Data API integration exists.
     */
    protected function ensureReportingCredentialsAreAvailable(): void
    {
        throw new \RuntimeException('Google Analytics Data API credentials and client integration are not configured.');
    }
}
