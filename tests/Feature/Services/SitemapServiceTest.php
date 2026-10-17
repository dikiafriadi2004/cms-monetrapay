<?php

use App\Models\Blog;
use App\Models\Category;
use App\Models\Page;
use App\Models\User;
use App\Services\SitemapService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->sitemapService = new SitemapService;
    $this->sitemapPath = public_path('sitemap.xml');

    // Clean up any existing sitemap
    if (file_exists($this->sitemapPath)) {
        unlink($this->sitemapPath);
    }
});

afterEach(function () {
    // Clean up generated sitemap
    if (file_exists($this->sitemapPath)) {
        unlink($this->sitemapPath);
    }
});

it('generates a valid sitemap.xml file', function () {
    $this->sitemapService->regenerate();

    expect(file_exists($this->sitemapPath))->toBeTrue();

    $content = file_get_contents($this->sitemapPath);
    expect($content)->toContain('<?xml version="1.0" encoding="UTF-8"?>');
    expect($content)->toContain('<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">');
    expect($content)->toContain('</urlset>');
});

it('includes the homepage in the sitemap', function () {
    $this->sitemapService->regenerate();

    $content = file_get_contents($this->sitemapPath);
    expect($content)->toContain('<loc>'.htmlspecialchars(url('/'), ENT_XML1, 'UTF-8').'</loc>');
    expect($content)->toContain('<priority>1.0</priority>');
    expect($content)->toContain('<changefreq>daily</changefreq>');
});

it('includes published blog posts in the sitemap', function () {
    $user = User::factory()->create();
    $publishedBlog = Blog::factory()->published()->create([
        'user_id' => $user->id,
        'slug' => 'published-post',
    ]);

    $this->sitemapService->regenerate();

    $content = file_get_contents($this->sitemapPath);
    expect($content)->toContain(url('/blog/published-post'));
    expect($content)->toContain('<priority>0.7</priority>');
});

it('excludes draft blog posts from the sitemap', function () {
    $user = User::factory()->create();
    $draftBlog = Blog::factory()->draft()->create([
        'user_id' => $user->id,
        'slug' => 'draft-post',
    ]);

    $this->sitemapService->regenerate();

    $content = file_get_contents($this->sitemapPath);
    expect($content)->not->toContain('draft-post');
});

it('excludes soft-deleted blog posts from the sitemap', function () {
    $user = User::factory()->create();
    $deletedBlog = Blog::factory()->published()->create([
        'user_id' => $user->id,
        'slug' => 'deleted-post',
    ]);
    $deletedBlog->delete();

    $this->sitemapService->regenerate();

    $content = file_get_contents($this->sitemapPath);
    expect($content)->not->toContain('deleted-post');
});

it('includes published pages in the sitemap', function () {
    $publishedPage = Page::factory()->published()->create([
        'slug' => 'about-us',
    ]);

    $this->sitemapService->regenerate();

    $content = file_get_contents($this->sitemapPath);
    expect($content)->toContain(url('/about-us'));
    expect($content)->not->toContain(url('/page/about-us'));
    expect($content)->toContain('<priority>0.8</priority>');
});

it('excludes draft pages from the sitemap', function () {
    $draftPage = Page::factory()->draft()->create([
        'slug' => 'draft-page',
    ]);

    $this->sitemapService->regenerate();

    $content = file_get_contents($this->sitemapPath);
    expect($content)->not->toContain('draft-page');
});

it('excludes soft-deleted pages from the sitemap', function () {
    $deletedPage = Page::factory()->published()->create([
        'slug' => 'deleted-page',
    ]);
    $deletedPage->delete();

    $this->sitemapService->regenerate();

    $content = file_get_contents($this->sitemapPath);
    expect($content)->not->toContain('deleted-page');
});

it('includes categories in the sitemap', function () {
    $category = Category::factory()->create([
        'slug' => 'technology',
    ]);

    $this->sitemapService->regenerate();

    $content = file_get_contents($this->sitemapPath);
    expect($content)->toContain(url('/category/technology'));
    expect($content)->toContain('<priority>0.6</priority>');
});

it('includes lastmod for all entries', function () {
    $user = User::factory()->create();
    Blog::factory()->published()->create(['user_id' => $user->id]);
    Page::factory()->published()->create();
    Category::factory()->create();

    $this->sitemapService->regenerate();

    $content = file_get_contents($this->sitemapPath);

    // Count the number of <lastmod> entries (homepage + 1 blog + 1 page + 1 category = 4)
    $lastmodCount = substr_count($content, '<lastmod>');
    expect($lastmodCount)->toBe(4);
});

it('generates sitemap with multiple published items and excludes drafts', function () {
    $user = User::factory()->create();

    // Create published content
    Blog::factory()->published()->create(['user_id' => $user->id, 'slug' => 'blog-one']);
    Blog::factory()->published()->create(['user_id' => $user->id, 'slug' => 'blog-two']);
    Page::factory()->published()->create(['slug' => 'page-one']);
    Category::factory()->create(['slug' => 'cat-one']);
    Category::factory()->create(['slug' => 'cat-two']);

    // Create draft/deleted content that should NOT appear
    Blog::factory()->draft()->create(['user_id' => $user->id, 'slug' => 'draft-blog']);
    Page::factory()->draft()->create(['slug' => 'draft-page']);

    $this->sitemapService->regenerate();

    $content = file_get_contents($this->sitemapPath);

    // Published content should be present
    expect($content)->toContain('blog-one');
    expect($content)->toContain('blog-two');
    expect($content)->toContain('page-one');
    expect($content)->toContain('cat-one');
    expect($content)->toContain('cat-two');

    // Draft content should NOT be present
    expect($content)->not->toContain('draft-blog');
    expect($content)->not->toContain('draft-page');

    // Count URL entries: homepage + 2 blogs + 1 page + 2 categories = 6
    $urlCount = substr_count($content, '<url>');
    expect($urlCount)->toBe(6);
});
