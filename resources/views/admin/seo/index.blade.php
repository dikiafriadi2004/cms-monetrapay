@extends('admin.layouts.app')
@section('title', 'SEO Tools')
@section('page-title', 'SEO Tools')

@section('content')
<div class="space-y-5">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold text-slate-800">SEO Tools</h1>
                <p class="text-sm text-slate-500 mt-1">Preview metadata, canonical URL, OpenGraph, dan regenerate sitemap.</p>
            </div>
            <form method="POST" action="{{ route('admin.seo.regenerate-sitemap') }}">
                @csrf
                <button class="inline-flex items-center px-4 py-2 rounded-xl bg-primary text-white text-sm font-semibold hover:bg-primary-dark">
                    Regenerate Sitemap
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h2 class="text-sm font-bold text-slate-800 mb-3">Artikel Published Terbaru</h2>
            <div class="divide-y divide-slate-100">
                @forelse($latestBlogs as $blog)
                    <div class="py-3">
                        <p class="text-sm font-semibold text-slate-700">{{ $blog->title }}</p>
                        <p class="text-xs text-slate-500">{{ route('admin.seo.preview', ['type' => 'blog', 'id' => $blog->id]) }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 py-4">Belum ada artikel published.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h2 class="text-sm font-bold text-slate-800 mb-3">Halaman Published Terbaru</h2>
            <div class="divide-y divide-slate-100">
                @forelse($latestPages as $page)
                    <div class="py-3">
                        <p class="text-sm font-semibold text-slate-700">{{ $page->title }}</p>
                        <p class="text-xs text-slate-500">{{ route('admin.seo.preview', ['type' => 'page', 'id' => $page->id]) }}</p>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 py-4">Belum ada halaman published.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
