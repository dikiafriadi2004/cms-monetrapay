<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;

class AnalyticsController extends Controller
{
    public function __construct(
        protected AnalyticsService $analyticsService
    ) {}

    /**
     * Display the analytics dashboard.
     */
    public function index()
    {
        $isConfigured = $this->analyticsService->isConfigured();
        $summary = null;
        $dailyTrend = null;
        $topPages = null;
        $error = null;

        if ($isConfigured) {
            $summary = $this->analyticsService->getSummary();
            $dailyTrend = $this->analyticsService->getDailyTrend();
            $topPages = $this->analyticsService->getTopPages();

            // If all data is null despite being configured, there might be an API error
            if ($summary === null && $dailyTrend === null && $topPages === null) {
                $error = 'Data analitik tidak dapat dimuat. Silakan coba lagi nanti.';
            }
        }

        return view('admin.analytics.index', compact(
            'isConfigured',
            'summary',
            'dailyTrend',
            'topPages',
            'error'
        ));
    }
}
