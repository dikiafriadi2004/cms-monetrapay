<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Services\ContentEnhancer;
use App\Services\SeoService;

class PageFrontController extends Controller
{
    public function __construct(
        protected SeoService $seoService,
        protected ContentEnhancer $contentEnhancer,
    ) {}

    /**
     * Show a static page by slug.
     */
    public function show(string $slug)
    {
        $page = Page::where('slug', $slug)
            ->where('status', 'published')
            ->first();

        // Page not found — show a graceful "coming soon" page instead of 500
        if (! $page) {
            return response()->view('frontend.page.not-found', [
                'slug' => $slug,
            ], 404);
        }

        // GrapesJS pages — has gjs_css set means designed with Visual Editor
        if (!empty($page->gjs_css)) {
            return $this->showWithGrapesJs($page);
        }

        // Legacy page builder
        if ($page->use_page_builder) {
            return $this->showWithPageBuilder($page);
        }

        // Enhance content (lazy images, secure external links)
        $page->content = $this->contentEnhancer->enhance($page->content);

        // SEO data
        $canonicalUrl = $this->seoService->getCanonicalUrl($page);
        $metaTags = $this->seoService->getMetaTags($page);
        $ogTags = $this->seoService->getOpenGraphTags($page, $canonicalUrl);
        $twitterTags = $this->seoService->getTwitterCardTags($page);
        $structuredData = $this->seoService->getStructuredData($page);

        // Build breadcrumb
        $breadcrumbs = $this->buildBreadcrumbs($page);

        // Choose template based on page template field
        $templateMap = [
            'full-width'   => 'frontend.page.template-full-width',
            'sidebar-right'=> 'frontend.page.template-sidebar-right',
            'sidebar-left' => 'frontend.page.template-sidebar-left',
            'landing'      => 'frontend.page.template-landing',
            'docs'         => 'frontend.page.template-docs',
        ];

        $viewName = $templateMap[$page->template] ?? 'frontend.page.show';

        // Fallback to show if template view doesn't exist
        if (! view()->exists($viewName)) {
            $viewName = 'frontend.page.show';
        }

        return view($viewName, compact(
            'page',
            'breadcrumbs',
            'canonicalUrl',
            'metaTags',
            'ogTags',
            'twitterTags',
            'structuredData',
        ));
    }

    /**
     * Show page designed with GrapesJS Visual Editor
     */
    protected function showWithGrapesJs(Page $page)
    {
        $canonicalUrl   = $this->seoService->getCanonicalUrl($page);
        $metaTags       = $this->seoService->getMetaTags($page);
        $ogTags         = $this->seoService->getOpenGraphTags($page, $canonicalUrl);
        $twitterTags    = $this->seoService->getTwitterCardTags($page);
        $structuredData = $this->seoService->getStructuredData($page);
        $breadcrumbs    = $this->buildBreadcrumbs($page);

        return view('frontend.page.grapesjs', compact(
            'page', 'breadcrumbs', 'canonicalUrl', 'metaTags',
            'ogTags', 'twitterTags', 'structuredData',
        ));
    }

    /**
     * Show page with page builder components (legacy)
     */
    protected function showWithPageBuilder(Page $page)
    {
        // If page was built with GrapesJS (has gjs_css set means GrapesJS was used)
        if (!empty($page->gjs_css)) {
            // SEO data
            $canonicalUrl   = $this->seoService->getCanonicalUrl($page);
            $metaTags       = $this->seoService->getMetaTags($page);
            $ogTags         = $this->seoService->getOpenGraphTags($page, $canonicalUrl);
            $twitterTags    = $this->seoService->getTwitterCardTags($page);
            $structuredData = $this->seoService->getStructuredData($page);
            $breadcrumbs    = $this->buildBreadcrumbs($page);

            return view('frontend.page.grapesjs', compact(
                'page', 'breadcrumbs', 'canonicalUrl', 'metaTags',
                'ogTags', 'twitterTags', 'structuredData',
            ));
        }

        // Load visible components ordered (legacy page builder)
        $page->load('visibleComponents');

        // SEO data
        $canonicalUrl   = $this->seoService->getCanonicalUrl($page);
        $metaTags       = $this->seoService->getMetaTags($page);
        $ogTags         = $this->seoService->getOpenGraphTags($page, $canonicalUrl);
        $twitterTags    = $this->seoService->getTwitterCardTags($page);
        $structuredData = $this->seoService->getStructuredData($page);
        $breadcrumbs    = $this->buildBreadcrumbs($page);

        return view('frontend.page.builder', compact(
            'page', 'breadcrumbs', 'canonicalUrl', 'metaTags',
            'ogTags', 'twitterTags', 'structuredData',
        ));
    }

    /**
     * Build breadcrumb items for a page (including parent hierarchy).
     */
    protected function buildBreadcrumbs(Page $page): array
    {
        $items = [];
        $current = $page;

        // Collect ancestors
        $ancestors = [];
        $maxDepth = 10; // Prevent infinite loops
        while ($current->parent_id && $maxDepth-- > 0) {
            $current = Page::find($current->parent_id);
            if ($current) {
                $ancestors[] = [
                    'label' => $current->title,
                    'url' => route('page.show', $current->slug),
                ];
            } else {
                break;
            }
        }

        // Reverse to get top-down order
        $items = array_reverse($ancestors);

        // Add current page (last item, not linked)
        $items[] = ['label' => $page->title, 'url' => ''];

        return $items;
    }
}
