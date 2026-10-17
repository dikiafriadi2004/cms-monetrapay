<?php

use App\Models\Blog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('RSS feed', function () {
    it('returns valid xml at /feed.xml', function () {
        $response = $this->get('/feed.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/rss+xml; charset=utf-8');
        expect($response->getContent())->toStartWith('<?xml');
    });

    it('includes published blog posts', function () {
        $user = User::factory()->create(['name' => 'Author Name']);
        Blog::factory()->create([
            'user_id' => $user->id,
            'title' => 'Latest News Article',
            'slug' => 'latest-news',
            'status' => 'published',
            'content' => 'Article body content.',
            'excerpt' => 'A short excerpt.',
            'published_at' => now(),
        ]);

        $response = $this->get('/feed.xml');

        $body = $response->getContent();
        expect($body)->toContain('<title>Latest News Article</title>');
        expect($body)->toContain('A short excerpt.');
        expect($body)->toContain('<dc:creator>Author Name</dc:creator>');
    });

    it('does not include draft posts', function () {
        $user = User::factory()->create();
        Blog::factory()->create([
            'user_id' => $user->id,
            'title' => 'Draft Article XYZ',
            'status' => 'draft',
        ]);

        $response = $this->get('/feed.xml');

        expect($response->getContent())->not->toContain('Draft Article XYZ');
    });

    it('escapes special chars in content', function () {
        $user = User::factory()->create();
        Blog::factory()->create([
            'user_id' => $user->id,
            'title' => 'Title with <script>',
            'slug' => 'title-with-script',
            'status' => 'published',
            'published_at' => now(),
        ]);

        $response = $this->get('/feed.xml');

        expect($response->getContent())->toContain('&lt;script&gt;');
        expect($response->getContent())->not->toContain('<title>Title with <script></title>');
    });
});

describe('Robots.txt', function () {
    it('returns plain text', function () {
        $response = $this->get('/robots.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
    });

    it('uses default robots when settings empty', function () {
        $response = $this->get('/robots.txt');

        expect($response->getContent())->toContain('User-agent: *');
        expect($response->getContent())->toContain('Disallow: /admin/');
        expect($response->getContent())->toContain('Sitemap:');
    });

    it('uses custom robots from settings when provided', function () {
        Setting::create([
            'group' => 'seo',
            'key' => 'robots_txt',
            'value' => "User-agent: GoogleBot\nAllow: /\n",
        ]);

        $response = $this->get('/robots.txt');

        expect($response->getContent())->toContain('User-agent: GoogleBot');
        expect($response->getContent())->not->toContain('Disallow: /admin/');
    });
});
