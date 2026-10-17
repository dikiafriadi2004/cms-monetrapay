<?php

use App\Models\Blog;
use App\Models\Page;
use App\Models\Setting;
use App\Models\User;
use App\Services\SeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class);

beforeEach(function () {
    $this->seoService = new SeoService;
});

describe('SeoService::getMetaTags()', function () {
    it('returns meta tags from content model when present', function () {
        $blog = Blog::factory()->create([
            'meta_title' => 'Custom Meta Title',
            'meta_description' => 'Custom meta description for this post.',
            'meta_keywords' => 'laravel, php, cms',
        ]);

        $result = $this->seoService->getMetaTags($blog);

        expect($result)->toBe([
            'title' => 'Custom Meta Title',
            'description' => 'Custom meta description for this post.',
            'keywords' => 'laravel, php, cms',
        ]);
    });

    it('falls back to global defaults when meta_title is empty', function () {
        Setting::create([
            'group' => 'seo',
            'key' => 'meta_title_default',
            'value' => 'Default Site Title',
        ]);

        $blog = Blog::factory()->create([
            'meta_title' => null,
            'meta_description' => 'Has description',
            'meta_keywords' => null,
        ]);

        $result = $this->seoService->getMetaTags($blog);

        expect($result['title'])->toBe('Default Site Title');
    });

    it('falls back to content title when meta_title is empty and no global default', function () {
        $blog = Blog::factory()->create([
            'title' => 'Blog Post Title',
            'meta_title' => null,
            'meta_description' => null,
            'meta_keywords' => null,
        ]);

        $result = $this->seoService->getMetaTags($blog);

        expect($result['title'])->toBe('Blog Post Title');
    });

    it('falls back to global defaults when meta_description is empty', function () {
        Setting::create([
            'group' => 'seo',
            'key' => 'meta_description_default',
            'value' => 'Default site description.',
        ]);

        $blog = Blog::factory()->create([
            'meta_title' => 'Title',
            'meta_description' => null,
            'meta_keywords' => null,
        ]);

        $result = $this->seoService->getMetaTags($blog);

        expect($result['description'])->toBe('Default site description.');
    });

    it('returns empty keywords when meta_keywords is null', function () {
        $blog = Blog::factory()->create([
            'meta_title' => 'Title',
            'meta_description' => 'Desc',
            'meta_keywords' => null,
        ]);

        $result = $this->seoService->getMetaTags($blog);

        expect($result['keywords'])->toBe('');
    });

    it('works with Page model', function () {
        $page = Page::factory()->create([
            'meta_title' => 'Page Meta Title',
            'meta_description' => 'Page meta description.',
            'meta_keywords' => 'page, static',
        ]);

        $result = $this->seoService->getMetaTags($page);

        expect($result)->toBe([
            'title' => 'Page Meta Title',
            'description' => 'Page meta description.',
            'keywords' => 'page, static',
        ]);
    });
});

describe('SeoService::getOpenGraphTags()', function () {
    it('returns og:type as article for Blog', function () {
        $blog = Blog::factory()->create([
            'meta_title' => 'Blog Title',
            'meta_description' => 'Blog description',
            'featured_image' => 'images/blog.jpg',
        ]);

        $result = $this->seoService->getOpenGraphTags($blog, 'https://example.com/blog/test');

        expect($result['og:type'])->toBe('article');
        expect($result['og:title'])->toBe('Blog Title');
        expect($result['og:description'])->toBe('Blog description');
        expect($result['og:image'])->toContain('images/blog.jpg');
        expect($result['og:image'])->toStartWith('http');
        expect($result['og:url'])->toBe('https://example.com/blog/test');
    });

    it('returns og:type as website for Page', function () {
        $page = Page::factory()->create([
            'meta_title' => 'Page Title',
            'meta_description' => 'Page description',
            'featured_image' => 'images/page.jpg',
        ]);

        $result = $this->seoService->getOpenGraphTags($page, 'https://example.com/page/about');

        expect($result['og:type'])->toBe('website');
    });

    it('returns empty string for og:image when no featured_image', function () {
        $blog = Blog::factory()->create([
            'meta_title' => 'Title',
            'meta_description' => 'Desc',
            'featured_image' => null,
        ]);

        $result = $this->seoService->getOpenGraphTags($blog, 'https://example.com/blog/test');

        expect($result['og:image'])->toBe('');
    });
});

describe('SeoService::getTwitterCardTags()', function () {
    it('returns twitter card tags with summary_large_image type', function () {
        $blog = Blog::factory()->create([
            'meta_title' => 'Twitter Title',
            'meta_description' => 'Twitter description',
            'featured_image' => 'images/twitter.jpg',
        ]);

        $result = $this->seoService->getTwitterCardTags($blog);

        expect($result['twitter:card'])->toBe('summary_large_image');
        expect($result['twitter:title'])->toBe('Twitter Title');
        expect($result['twitter:description'])->toBe('Twitter description');
        expect($result['twitter:image'])->toContain('images/twitter.jpg');
        expect($result['twitter:image'])->toStartWith('http');
    });

    it('returns empty string for twitter:image when no featured_image', function () {
        $page = Page::factory()->create([
            'meta_title' => 'Page Title',
            'meta_description' => 'Page desc',
            'featured_image' => null,
        ]);

        $result = $this->seoService->getTwitterCardTags($page);

        expect($result['twitter:image'])->toBe('');
    });
});

describe('SeoService::getCanonicalUrl()', function () {
    it('generates canonical URL for Blog with /blog/ prefix', function () {
        $blog = Blog::factory()->create(['slug' => 'my-blog-post']);

        $result = $this->seoService->getCanonicalUrl($blog);

        expect($result)->toContain('/blog/my-blog-post');
    });

    it('generates canonical URL for Page using public slug URL', function () {
        $page = Page::factory()->create(['slug' => 'about-us']);

        $result = $this->seoService->getCanonicalUrl($page);

        expect($result)->toContain('/about-us');
    });

    it('returns absolute URL', function () {
        $blog = Blog::factory()->create(['slug' => 'test-post']);

        $result = $this->seoService->getCanonicalUrl($blog);

        expect($result)->toStartWith('http');
    });
});

describe('SeoService::getStructuredData()', function () {
    it('returns Article schema for Blog', function () {
        $user = User::factory()->create(['name' => 'John Author']);
        $blog = Blog::factory()->create([
            'user_id' => $user->id,
            'title' => 'Test Article',
            'slug' => 'test-article',
            'featured_image' => 'images/article.jpg',
            'published_at' => now(),
        ]);
        $blog->load('author');

        $result = $this->seoService->getStructuredData($blog);

        expect($result['@context'])->toBe('https://schema.org');
        expect($result['@type'])->toBe('Article');
        expect($result['headline'])->toBe('Test Article');
        expect($result['author']['@type'])->toBe('Person');
        expect($result['author']['name'])->toBe('John Author');
        expect($result['image'])->toContain('images/article.jpg');
        expect($result['image'])->toStartWith('http');
        expect($result)->toHaveKeys(['datePublished', 'dateModified', 'url']);
    });

    it('returns Article schema without author when not loaded', function () {
        $blog = Blog::factory()->create([
            'title' => 'No Author Loaded',
            'slug' => 'no-author',
        ]);

        $result = $this->seoService->getStructuredData($blog);

        expect($result['@type'])->toBe('Article');
        expect($result)->not->toHaveKey('author');
    });

    it('returns Article schema without image when featured_image is null', function () {
        $blog = Blog::factory()->create([
            'title' => 'No Image',
            'slug' => 'no-image',
            'featured_image' => null,
        ]);

        $result = $this->seoService->getStructuredData($blog);

        expect($result['@type'])->toBe('Article');
        expect($result)->not->toHaveKey('image');
    });

    it('returns Organization schema for about page', function () {
        Setting::create([
            'group' => 'general',
            'key' => 'site_name',
            'value' => 'MonetraPay',
        ]);

        $page = Page::factory()->create([
            'slug' => 'about',
            'meta_description' => 'About MonetraPay company.',
        ]);

        $result = $this->seoService->getStructuredData($page);

        expect($result['@context'])->toBe('https://schema.org');
        expect($result['@type'])->toBe('Organization');
        expect($result['name'])->toBe('MonetraPay');
        expect($result['description'])->toBe('About MonetraPay company.');
    });

    it('returns WebPage schema for non-about pages', function () {
        $page = Page::factory()->create([
            'title' => 'Contact Us',
            'slug' => 'contact',
            'meta_description' => 'Contact page description.',
        ]);

        $result = $this->seoService->getStructuredData($page);

        expect($result['@context'])->toBe('https://schema.org');
        expect($result['@type'])->toBe('WebPage');
        expect($result['name'])->toBe('Contact Us');
        expect($result['description'])->toBe('Contact page description.');
        expect($result['url'])->toContain('/contact');
    });

    it('uses published_at for datePublished when available', function () {
        $publishedAt = now()->subDays(5);
        $blog = Blog::factory()->create([
            'title' => 'Published Post',
            'slug' => 'published-post',
            'published_at' => $publishedAt,
        ]);

        $result = $this->seoService->getStructuredData($blog);

        expect($result['datePublished'])->toBe($publishedAt->toIso8601String());
    });

    it('uses created_at for datePublished when published_at is null', function () {
        $blog = Blog::factory()->create([
            'title' => 'Draft Post',
            'slug' => 'draft-post',
            'published_at' => null,
        ]);

        $result = $this->seoService->getStructuredData($blog);

        expect($result['datePublished'])->toBe($blog->created_at->toIso8601String());
    });
});
