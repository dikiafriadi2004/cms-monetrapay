<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SitemapService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Artisan;

class MaintenanceController extends Controller
{
    public function __construct(
        protected SitemapService $sitemapService,
    ) {}

    public function clearCache(): RedirectResponse
    {
        Artisan::call('optimize:clear');

        return back()->with('success', 'Cache aplikasi berhasil dibersihkan.');
    }

    public function regenerateSitemap(): RedirectResponse
    {
        $this->sitemapService->regenerate();

        return back()->with('success', 'Sitemap berhasil dibuat ulang.');
    }
}
