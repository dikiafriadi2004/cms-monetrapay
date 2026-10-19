<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ContentCacheService;

class HomeController extends Controller
{
    public function __construct(
        protected ContentCacheService $cache,
    ) {}

    public function index()
    {
        $latestBlogs = $this->cache->latestBlogs(3);
        $homepage    = Setting::getGroup('homepage');

        // Resolve active theme slug for home page layout switching
        try {
            $themeSlug = app(\App\Services\ThemeService::class)->getActiveTheme()?->slug ?? 'modern-corporate';
        } catch (\Throwable $e) {
            $themeSlug = 'modern-corporate';
        }

        return view('frontend.home', compact('latestBlogs', 'homepage', 'themeSlug'));
    }
}
