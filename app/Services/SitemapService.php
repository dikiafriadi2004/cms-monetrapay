<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Page;
use Illuminate\Support\Carbon;

class SitemapService
{
    /**
     * Regenerate sitemap.xml with all published content.
     * Includes: homepage, published blog posts, published pages, categories.
     * Writes the sitemap.xml file to the public directory.
     *
     * The sitemap includes:
     * - Homepage URL (priority 1.0, changefreq daily)
     * - All published blog posts (priority 0.7, changefreq weekly)
     * - All published pages (priority 0.8, changefreq weekly)
     * - All categories (priority 0.6, changefreq weekly)
     *
     * Does NOT include:
     * - Draft content
     * - Soft-deleted content
     */
    public function regenerate(): void
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;

        // Homepage
        $xml .= $this->buildUrlEntry(
            url('/'),
            Carbon::now(),
            'daily',
            '1.0'
        );

        // Published blog posts (excludes drafts and soft-deleted via query)
        $blogs = Blog::where('status', 'published')->get();
        foreach ($blogs as $blog) {
            $xml .= $this->buildUrlEntry(
                url('/blog/'.$blog->slug),
                $blog->updated_at,
                'weekly',
                '0.7'
            );
        }

        // Published pages (excludes drafts and soft-deleted via query)
        $pages = Page::where('status', 'published')->get();
        foreach ($pages as $page) {
            $xml .= $this->buildUrlEntry(
                url('/'.$page->slug),
                $page->updated_at,
                'weekly',
                '0.8'
            );
        }

        // All categories
        $categories = Category::all();
        foreach ($categories as $category) {
            $xml .= $this->buildUrlEntry(
                url('/category/'.$category->slug),
                $category->updated_at,
                'weekly',
                '0.6'
            );
        }

        $xml .= '</urlset>'.PHP_EOL;

        file_put_contents(public_path('sitemap.xml'), $xml);
    }

    /**
     * Build a single <url> entry for the sitemap.
     */
    protected function buildUrlEntry(string $loc, Carbon $lastmod, string $changefreq, string $priority): string
    {
        $entry = '    <url>'.PHP_EOL;
        $entry .= '        <loc>'.htmlspecialchars($loc, ENT_XML1, 'UTF-8').'</loc>'.PHP_EOL;
        $entry .= '        <lastmod>'.$lastmod->toW3cString().'</lastmod>'.PHP_EOL;
        $entry .= '        <changefreq>'.$changefreq.'</changefreq>'.PHP_EOL;
        $entry .= '        <priority>'.$priority.'</priority>'.PHP_EOL;
        $entry .= '    </url>'.PHP_EOL;

        return $entry;
    }
}
