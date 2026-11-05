<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Generate and serve a dynamic sitemap from the database.
     */
    public function sitemap(): Response
    {
        $xml = $this->buildSitemapXml();

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    /**
     * Build the full sitemap XML string.
     */
    public function buildSitemapXml(): string
    {
        $lines = [];
        $lines[] = '<?xml version="1.0" encoding="UTF-8"?>';
        $lines[] = '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

        // Static pages
        $staticPages = [
            ['loc' => url('/'),             'priority' => '0.8'],
            ['loc' => url('/blog'),         'priority' => '0.8'],
            ['loc' => url('/tentang-kami'), 'priority' => '0.8'],
            ['loc' => url('/layanan'),      'priority' => '0.8'],
            ['loc' => url('/kontak'),       'priority' => '0.8'],
        ];

        foreach ($staticPages as $page) {
            $lines[] = '    <url>';
            $lines[] = '        <loc>' . htmlspecialchars($page['loc'], ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</loc>';
            $lines[] = '        <changefreq>weekly</changefreq>';
            $lines[] = '        <priority>' . $page['priority'] . '</priority>';
            $lines[] = '    </url>';
        }

        // Published pages from the database
        $pages = Page::where('status', 'published')->get(['slug', 'updated_at']);
        foreach ($pages as $page) {
            $lines[] = '    <url>';
            $lines[] = '        <loc>' . htmlspecialchars(url('/' . $page->slug), ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</loc>';
            $lines[] = '        <lastmod>' . $page->updated_at->toAtomString() . '</lastmod>';
            $lines[] = '        <changefreq>monthly</changefreq>';
            $lines[] = '        <priority>0.7</priority>';
            $lines[] = '    </url>';
        }

        // Published blogs from the database
        $blogs = Blog::where('status', 'published')->get(['slug', 'updated_at']);
        foreach ($blogs as $blog) {
            $lines[] = '    <url>';
            $lines[] = '        <loc>' . htmlspecialchars(route('blog.show', $blog->slug), ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</loc>';
            $lines[] = '        <lastmod>' . $blog->updated_at->toAtomString() . '</lastmod>';
            $lines[] = '        <changefreq>monthly</changefreq>';
            $lines[] = '        <priority>0.6</priority>';
            $lines[] = '    </url>';
        }

        $lines[] = '</urlset>';

        return implode(PHP_EOL, $lines);
    }

    /**
     * Generate and serve robots.txt from settings.
     * Falls back to a sensible default if not configured.
     */
    public function robots(): Response
    {
        $settings = Setting::getGroup('seo');

        // Check if custom robots.txt content is configured
        $robotsContent = $settings['robots_txt'] ?? null;

        if (empty($robotsContent)) {
            // Generate default robots.txt
            $robotsContent = $this->generateDefaultRobots();
        }

        return response($robotsContent, 200, [
            'Content-Type' => 'text/plain',
        ]);
    }

    /**
     * Generate a default robots.txt content.
     */
    protected function generateDefaultRobots(): string
    {
        $lines = [];
        $lines[] = 'User-agent: *';
        $lines[] = 'Allow: /';
        $lines[] = '';
        $lines[] = 'Disallow: /admin/';
        $lines[] = 'Disallow: /login';
        $lines[] = '';
        $lines[] = 'Sitemap: '.url('/sitemap.xml');
        $lines[] = '';

        return implode(PHP_EOL, $lines);
    }
}
