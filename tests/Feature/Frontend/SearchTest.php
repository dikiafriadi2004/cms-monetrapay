<?php

use App\Models\Blog;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Search controller', function () {
    it('renders search page with empty query', function () {
        $response = $this->get('/search');

        $response->assertStatus(200);
        $response->assertSee('Pencarian');
    });

    it('shows warning when query is too short', function () {
        $response = $this->get('/search?q=ab');

        $response->assertStatus(200);
        $response->assertSee('minimal 3 karakter');
    });

    it('finds blog posts matching title', function () {
        $user = User::factory()->create();
        Blog::factory()->create([
            'user_id' => $user->id,
            'title' => 'Pulsa Murah Indonesia',
            'status' => 'published',
            'content' => 'Konten artikel pulsa.',
        ]);

        $response = $this->get('/search?q=Pulsa');

        $response->assertStatus(200);
        // Title is wrapped in <mark> for highlights, so assert on stable parts
        $response->assertSee('Murah Indonesia', false);
    });

    it('finds pages matching content', function () {
        Page::factory()->create([
            'title' => 'Tentang Kami Test',
            'status' => 'published',
            'content' => 'Kami adalah platform PPOB terpercaya.',
        ]);

        $response = $this->get('/search?q=PPOB');

        $response->assertStatus(200);
        $response->assertSee('Tentang Kami Test', false);
    });

    it('does not show draft content', function () {
        $user = User::factory()->create();
        Blog::factory()->create([
            'user_id' => $user->id,
            'title' => 'Secret Draft Article',
            'status' => 'draft',
        ]);

        $response = $this->get('/search?q=Secret');

        $response->assertStatus(200);
        $response->assertDontSee('Draft Article', false);
    });

    it('ranks exact title match higher than content match', function () {
        $user = User::factory()->create();
        Blog::factory()->create([
            'user_id' => $user->id,
            'title' => 'Random Topic Article',
            'content' => 'A post that mentions monetrapay only in body.',
            'status' => 'published',
        ]);
        Blog::factory()->create([
            'user_id' => $user->id,
            'title' => 'monetrapay',
            'content' => 'Different content here.',
            'status' => 'published',
        ]);

        $response = $this->get('/search?q=monetrapay');

        $response->assertStatus(200);
        $body = $response->getContent();
        $exactTitlePos = mb_stripos($body, '>monetrapay<');
        $randomPos = mb_stripos($body, 'Random Topic Article');

        // both should be found, exact title should appear before content-only match
        if ($exactTitlePos !== false && $randomPos !== false) {
            expect($exactTitlePos)->toBeLessThan($randomPos);
        }
        expect($exactTitlePos)->not->toBeFalse();
    });

    it('filters by type=blog', function () {
        $user = User::factory()->create();
        Blog::factory()->create(['user_id' => $user->id, 'title' => 'Sample Blog Title', 'status' => 'published']);
        Page::factory()->create(['title' => 'Sample Page Title', 'status' => 'published']);

        $response = $this->get('/search?q=Sample&type=blog');

        $response->assertStatus(200);
        $response->assertSee('Blog Title', false);
        $response->assertDontSee('Page Title', false);
    });

    it('filters by type=page', function () {
        $user = User::factory()->create();
        Blog::factory()->create(['user_id' => $user->id, 'title' => 'Sample Blog Title', 'status' => 'published']);
        Page::factory()->create(['title' => 'Sample Page Title', 'status' => 'published']);

        $response = $this->get('/search?q=Sample&type=page');

        $response->assertStatus(200);
        $response->assertDontSee('Blog Title', false);
        $response->assertSee('Page Title', false);
    });
});
