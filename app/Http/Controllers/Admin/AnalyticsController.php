<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AnalyticsService;
use Illuminate\Http\JsonResponse;

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

    /**
     * Test GA4 API connection using stored credentials.
     */
    public function testConnection(): JsonResponse
    {
        try {
            $propertyId      = Setting::get('ga_property_id', 'seo');
            $credentialsJson = Setting::get('ga_credentials_json', 'seo');

            if (!$propertyId || !$credentialsJson) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property ID atau Credentials belum diisi.',
                ]);
            }

            $credentials = json_decode(decrypt($credentialsJson), true);
            $this->analyticsService->testConnection($credentials, $propertyId);

            return response()->json([
                'success' => true,
                'message' => 'Koneksi GA4 berhasil! Property: ' . $propertyId,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Koneksi gagal: ' . $e->getMessage(),
            ]);
        }
    }
}
