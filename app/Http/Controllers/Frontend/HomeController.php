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
        $homepage = Setting::getGroup('homepage');

        return view('frontend.home', compact('latestBlogs', 'homepage'));
    }
}
