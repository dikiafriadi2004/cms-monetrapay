<?php

use App\Models\Blog;
use App\Models\Category;
use App\Models\User;
use App\Services\SlugService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->slugService = new SlugService;
    $this->user = User::forceCreate([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
    ]);
});

describe('SlugService::makeUnique()', function () {
    it('returns the original slug when no conflict exists', function () {
        $result = $this->slugService->makeUnique('hello-world', Blog::class);

        expect($result)->toBe('hello-world');
    });

    it('appends -2 when slug already exists', function () {
        Blog::forceCreate([
            'user_id' => $this->user->id,
            'title' => 'Hello World',
            'slug' => 'hello-world',
            'content' => 'Test content',
            'status' => 'published',
        ]);

        $result = $this->slugService->makeUnique('hello-world', Blog::class);

        expect($result)->toBe('hello-world-2');
    });

    it('appends -3 when slug and slug-2 already exist', function () {
        Blog::forceCreate([
            'user_id' => $this->user->id,
            'title' => 'Hello World',
            'slug' => 'hello-world',
            'content' => 'Test content',
            'status' => 'published',
        ]);
        Blog::forceCreate([
            'user_id' => $this->user->id,
            'title' => 'Hello World 2',
            'slug' => 'hello-world-2',
            'content' => 'Test content',
            'status' => 'published',
        ]);

        $result = $this->slugService->makeUnique('hello-world', Blog::class);

        expect($result)->toBe('hello-world-3');
    });

    it('excludes a specific ID from uniqueness check (for updates)', function () {
        $blog = Blog::forceCreate([
            'user_id' => $this->user->id,
            'title' => 'Hello World',
            'slug' => 'hello-world',
            'content' => 'Test content',
            'status' => 'published',
        ]);

        $result = $this->slugService->makeUnique('hello-world', Blog::class, $blog->id);

        expect($result)->toBe('hello-world');
    });

    it('checks against soft-deleted records for models with SoftDeletes', function () {
        $blog = Blog::forceCreate([
            'user_id' => $this->user->id,
            'title' => 'Hello World',
            'slug' => 'hello-world',
            'content' => 'Test content',
            'status' => 'published',
        ]);
        $blog->delete(); // soft delete

        $result = $this->slugService->makeUnique('hello-world', Blog::class);

        expect($result)->toBe('hello-world-2');
    });

    it('works with models that do not use SoftDeletes', function () {
        Category::forceCreate([
            'name' => 'PHP',
            'slug' => 'php',
        ]);

        $result = $this->slugService->makeUnique('php', Category::class);

        expect($result)->toBe('php-2');
    });
});
