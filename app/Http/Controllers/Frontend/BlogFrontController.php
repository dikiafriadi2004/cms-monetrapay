<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\Blog;
use App\Models\Category;
use App\Services\AdInsertionService;
use App\Services\ContentCacheService;
use App\Services\ContentEnhancer;
use App\Services\SeoService;
use Illuminate\Http\Request;

class BlogFrontController extends Controller
{
    public function __construct(
        protected SeoService $seoService,
        protected AdInsertionService $adInsertionService,
        protected ContentEnhancer $contentEnhancer,
        protected ContentCacheService $cache,
    ) {}

    /**
     * Blog listing page with pagination and category filter.
     */
    public function index(Request $request)
    {
        $query = Blog::where('status', 'published')
            ->with(['author', 'categories'])
            ->orderBy('published_at', 'desc');

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->input('category'));
            });
        }

        $blogs = $query->paginate(10)->withQueryString();

        $categories = Category::withCount(['blogs' => function ($q) {
            $q->where('status', 'published');
        }])->get();

        $activeCategory = $request->input('category');

        return view('frontend.blog.index', compact('blogs', 'categories', 'activeCategory'));
    }

    /**
     * Blog detail page with related articles and ad insertion.
     */
    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)
            ->where('status', 'published')
            ->with(['author', 'categories', 'tags'])
            ->firstOrFail();

        // Get related articles (max 3 from same categories)
        $relatedBlogs = Blog::where('status', 'published')
            ->where('id', '!=', $blog->id)
            ->whereHas('categories', function ($q) use ($blog) {
                $q->whereIn('categories.id', $blog->categories->pluck('id'));
            })
            ->with(['author', 'categories'])
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Auto-insert ads into paragraphs (position = auto_insert)
        $autoInsertAds = Ad::where('position', 'auto_insert')
            ->where('is_active', true)
            ->whereNotNull('interval')
            ->where('interval', '>', 0)
            ->where(function ($q) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', today());
            })
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', today());
            })
            ->get();

        $content = $this->adInsertionService->insertAdsInContent($blog->content, $autoInsertAds);
        $content = $this->contentEnhancer->enhance($content);

        // Recent posts for sidebar (excluding current) — cached
        $recentPosts = $this->cache->recentBlogsExcept($blog->id, 5);

        // Categories for sidebar — cached
        $sidebarCategories = $this->cache->categoriesWithBlogCount();

        // SEO data
        $canonicalUrl = $this->seoService->getCanonicalUrl($blog);
        $metaTags = $this->seoService->getMetaTags($blog);
        $ogTags = $this->seoService->getOpenGraphTags($blog, $canonicalUrl);
        $twitterTags = $this->seoService->getTwitterCardTags($blog);
        $structuredData = $this->seoService->getStructuredData($blog);

        return view('frontend.blog.show', compact(
            'blog',
            'content',
            'relatedBlogs',
            'recentPosts',
            'sidebarCategories',
            'canonicalUrl',
            'metaTags',
            'ogTags',
            'twitterTags',
            'structuredData',
        ));
    }

    /**
     * Show articles in a specific category.
     */
    public function category(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $blogs = Blog::where('status', 'published')
            ->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category->id);
            })
            ->with(['author', 'categories'])
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return view('frontend.category.show', compact('category', 'blogs'));
    }

    /**
     * List all categories with article count.
     */
    public function categories()
    {
        $categories = $this->cache->categoriesWithBlogCount();

        return view('frontend.category.index', compact('categories'));
    }
}
