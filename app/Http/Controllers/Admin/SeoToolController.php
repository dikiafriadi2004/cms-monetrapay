<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Services\SeoService;
use App\Services\SitemapService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeoToolController extends Controller
{
    public function __construct(
        protected SeoService $seoService,
        protected SitemapService $sitemapService,
    ) {}

    public function index(): View
    {
        $latestBlogs = Blog::where('status', 'published')->latest('published_at')->limit(10)->get();
        $latestPages = Page::where('status', 'published')->latest()->limit(10)->get();

        return view('admin.seo.index', compact('latestBlogs', 'latestPages'));
    }

    public function preview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:blog,page'],
            'id' => ['required', 'integer'],
        ]);

        $content = $validated['type'] === 'blog'
            ? Blog::findOrFail($validated['id'])
            : Page::findOrFail($validated['id']);

        $canonicalUrl = $this->seoService->getCanonicalUrl($content);

        return response()->json([
            'canonical_url' => $canonicalUrl,
            'meta' => $this->seoService->getMetaTags($content),
            'open_graph' => $this->seoService->getOpenGraphTags($content, $canonicalUrl),
            'twitter' => $this->seoService->getTwitterCardTags($content),
        ]);
    }

    public function regenerateSitemap(): RedirectResponse
    {
        $this->sitemapService->regenerate();

        return back()->with('success', 'Sitemap berhasil dibuat ulang.');
    }
}
