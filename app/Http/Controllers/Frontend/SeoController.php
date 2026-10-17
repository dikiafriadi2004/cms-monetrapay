<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    /**
     * Serve the sitemap.xml file.
     * Returns the pre-generated sitemap from the public directory.
     */
    public function sitemap(): Response
    {
        $sitemapPath = public_path('sitemap.xml');

        if (! file_exists($sitemapPath)) {
            // Generate a minimal sitemap if file doesn't exist
            $content = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;
            $content .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'.PHP_EOL;
            $content .= '    <url>'.PHP_EOL;
            $content .= '        <loc>'.url('/').'</loc>'.PHP_EOL;
            $content .= '    </url>'.PHP_EOL;
            $content .= '</urlset>'.PHP_EOL;
        } else {
            $content = file_get_contents($sitemapPath);
        }

        return response($content, 200, [
            'Content-Type' => 'application/xml',
        ]);
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
