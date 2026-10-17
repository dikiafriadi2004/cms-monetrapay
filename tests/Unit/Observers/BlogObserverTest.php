<?php

use App\Models\Blog;
use App\Observers\BlogObserver;
use App\Services\SitemapService;

beforeEach(function () {
    $this->sitemapService = Mockery::mock(SitemapService::class);
    $this->observer = new BlogObserver($this->sitemapService);
});

describe('BlogObserver::created()', function () {
    it('regenerates sitemap when a published blog is created', function () {
        $blog = new Blog(['status' => 'published']);

        $this->sitemapService->shouldReceive('regenerate')->once();

        $this->observer->created($blog);
    });

    it('does not regenerate sitemap when a draft blog is created', function () {
        $blog = new Blog(['status' => 'draft']);

        $this->sitemapService->shouldNotReceive('regenerate');

        $this->observer->created($blog);
    });
});

describe('BlogObserver::updated()', function () {
    it('regenerates sitemap when a published blog is updated', function () {
        $blog = Mockery::mock(Blog::class)->makePartial();
        $blog->status = 'published';
        $blog->shouldReceive('isDirty')->with('status')->andReturn(false);

        $this->sitemapService->shouldReceive('regenerate')->once();

        $this->observer->updated($blog);
    });

    it('regenerates sitemap when blog status changes', function () {
        $blog = Mockery::mock(Blog::class)->makePartial();
        $blog->status = 'draft';
        $blog->shouldReceive('isDirty')->with('status')->andReturn(true);

        $this->sitemapService->shouldReceive('regenerate')->once();

        $this->observer->updated($blog);
    });

    it('does not regenerate sitemap when a draft blog is updated without status change', function () {
        $blog = Mockery::mock(Blog::class)->makePartial();
        $blog->status = 'draft';
        $blog->shouldReceive('isDirty')->with('status')->andReturn(false);

        $this->sitemapService->shouldNotReceive('regenerate');

        $this->observer->updated($blog);
    });
});

describe('BlogObserver::deleted()', function () {
    it('always regenerates sitemap when a blog is deleted', function () {
        $blog = new Blog(['status' => 'published']);

        $this->sitemapService->shouldReceive('regenerate')->once();

        $this->observer->deleted($blog);
    });

    it('regenerates sitemap even when a draft blog is deleted', function () {
        $blog = new Blog(['status' => 'draft']);

        $this->sitemapService->shouldReceive('regenerate')->once();

        $this->observer->deleted($blog);
    });
});

describe('BlogObserver::restored()', function () {
    it('regenerates sitemap when a published blog is restored', function () {
        $blog = new Blog(['status' => 'published']);

        $this->sitemapService->shouldReceive('regenerate')->once();

        $this->observer->restored($blog);
    });

    it('does not regenerate sitemap when a draft blog is restored', function () {
        $blog = new Blog(['status' => 'draft']);

        $this->sitemapService->shouldNotReceive('regenerate');

        $this->observer->restored($blog);
    });
});

describe('BlogObserver::forceDeleted()', function () {
    it('always regenerates sitemap when a blog is force deleted', function () {
        $blog = new Blog(['status' => 'published']);

        $this->sitemapService->shouldReceive('regenerate')->once();

        $this->observer->forceDeleted($blog);
    });
});
