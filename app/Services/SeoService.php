<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SeoService
{
    /**
     * Get meta tags for a content model (Blog or Page).
     * Falls back to global defaults from settings if page-specific values are empty.
     *
     * @return array{title: string, description: string, keywords: string}
     */
    public function getMetaTags(Blog|Page $content): array
    {
        $title = $content->meta_title;
        $description = $content->meta_description;
        $keywords = $content->meta_keywords ?? '';

        // Fallback to global defaults if empty
        if (empty($title)) {
            $title = $this->getGlobalSetting('meta_title_default') ?? $content->title ?? '';
        }

        if (empty($description)) {
            $description = $this->getGlobalSetting('meta_description_default') ?? '';
        }

        return [
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
        ];
    }

    /**
     * Get Open Graph tags for a content model.
     *
     * @param  string  $url  The absolute URL of the content
     * @return array{'og:title': string, 'og:description': string, 'og:image': string, 'og:url': string, 'og:type': string, 'og:site_name': string}
     */
    public function getOpenGraphTags(Blog|Page $content, string $url): array
    {
        $metaTags = $this->getMetaTags($content);
        $type = $content instanceof Blog ? 'article' : 'website';
        $image = $this->resolveImageUrl($content->featured_image ?? null);

        return [
            'og:title' => $metaTags['title'],
            'og:description' => $metaTags['description'],
            'og:image' => $image,
            'og:url' => $url,
            'og:type' => $type,
            'og:site_name' => $this->getGlobalSetting('site_name', 'general') ?? 'MonetraPay',
        ];
    }

    /**
     * Get Twitter Card tags for a content model.
     *
     * @return array{'twitter:card': string, 'twitter:title': string, 'twitter:description': string, 'twitter:image': string}
     */
    public function getTwitterCardTags(Blog|Page $content): array
    {
        $metaTags = $this->getMetaTags($content);
        $image = $this->resolveImageUrl($content->featured_image ?? null);

        return [
            'twitter:card' => 'summary_large_image',
            'twitter:title' => $metaTags['title'],
            'twitter:description' => $metaTags['description'],
            'twitter:image' => $image,
        ];
    }

    /**
     * Resolve a stored image path to an absolute URL.
     */
    protected function resolveImageUrl(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        // Already a full URL
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return url(Storage::url($path));
    }

    /**
     * Get canonical URL for a content model.
     * Generates the full absolute URL based on the content's slug.
     *
     * @return string The absolute canonical URL
     */
    public function getCanonicalUrl(Blog|Page $content): string
    {
        if ($content instanceof Blog) {
            return url('/blog/'.$content->slug);
        }

        if ($content instanceof Page) {
            return url('/'.$content->slug);
        }

        return url('/');
    }

    /**
     * Get JSON-LD structured data for a content model.
     *
     * For Blog: Article schema with headline, author, datePublished, dateModified, image
     * For Page: Organization schema (if slug is 'about') or WebPage schema
     *
     * @return array The structured data array suitable for JSON-LD encoding
     */
    public function getStructuredData(Blog|Page $content): array
    {
        if ($content instanceof Blog) {
            return $this->getArticleSchema($content);
        }

        if ($content instanceof Page) {
            if ($content->slug === 'about') {
                return $this->getOrganizationSchema($content);
            }

            return $this->getWebPageSchema($content);
        }

        return [];
    }

    /**
     * Generate Article schema for a Blog post.
     */
    protected function getArticleSchema(Blog $blog): array
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $blog->title,
            'datePublished' => ($blog->published_at ?? $blog->created_at)->toIso8601String(),
            'dateModified' => $blog->updated_at->toIso8601String(),
            'url' => $this->getCanonicalUrl($blog),
        ];

        // Add author if relationship is loaded or available
        if ($blog->relationLoaded('author') && $blog->author) {
            $schema['author'] = [
                '@type' => 'Person',
                'name' => $blog->author->name,
            ];
        }

        // Add image if available
        if (! empty($blog->featured_image)) {
            $schema['image'] = $this->resolveImageUrl($blog->featured_image);
        }

        return $schema;
    }

    /**
     * Generate Organization schema for the about page.
     */
    protected function getOrganizationSchema(Page $page): array
    {
        $siteName = $this->getGlobalSetting('site_name', 'general') ?? 'MonetraPay';

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $siteName,
            'url' => url('/'),
            'description' => $page->meta_description ?? $this->getGlobalSetting('meta_description_default') ?? '',
        ];
    }

    /**
     * Generate WebPage schema for a generic page.
     */
    protected function getWebPageSchema(Page $page): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page->title,
            'url' => $this->getCanonicalUrl($page),
            'description' => $page->meta_description ?? '',
        ];
    }

    /**
     * Get a global setting value from the settings table (cached).
     *
     * @param  string  $key  The setting key
     * @param  string  $group  The setting group (defaults to 'seo')
     * @return string|null The setting value or null if not found
     */
    protected function getGlobalSetting(string $key, string $group = 'seo'): ?string
    {
        return Setting::get($key, $group);
    }
}
