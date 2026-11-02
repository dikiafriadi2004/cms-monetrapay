@extends('frontend.layouts.app')

@section('title', 'Blog - ' . ($siteName ?? config('app.name')))

@section('seo')
@php
    $blogTitle = 'Blog - ' . ($siteName ?? config('app.name'));
    $blogDesc = 'Baca artikel terbaru di blog kami.';
    $blogUrl = url('/blog');
@endphp
<meta name="description" content="{{ $blogDesc }}">
<link rel="canonical" href="{{ $blogUrl }}">
<meta property="og:title" content="{{ $blogTitle }}">
<meta property="og:description" content="{{ $blogDesc }}">
<meta property="og:url" content="{{ $blogUrl }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName ?? config('app.name') }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $blogTitle }}">
<meta name="twitter:description" content="{{ $blogDesc }}">
@endsection

@section('content')

{{-- Page Hero --}}
<section class="bg-slate-800 relative overflow-hidden py-16 md:py-20">
    <div class="tech-grid-pattern absolute inset-0"></div>
    <div class="absolute top-0 left-1/2 w-96 h-96 bg-primary/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block text-xs font-semibold text-primary bg-primary/10 border border-primary/20 px-4 py-1.5 rounded-full mb-4 uppercase tracking-wider">Blog</span>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-4">Artikel & Tips Digital</h1>
        <p class="text-slate-400 text-lg max-w-xl mx-auto">Informasi terbaru seputar pulsa, PPOB, top-up game, dan bisnis digital.</p>
    </div>
</section>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <div class="flex flex-col lg:flex-row gap-10">

        {{-- ── Main Content ── --}}
        <div class="flex-1 min-w-0">

            {{-- Active category label --}}
            @if($activeCategory)
            <div class="flex items-center gap-3 mb-8">
                <span class="text-sm text-slate-500">Filter:</span>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-light text-primary text-sm font-semibold rounded-full">
                    {{ $categories->firstWhere('slug', $activeCategory)?->name ?? $activeCategory }}
                    <a href="{{ route('blog.index') }}" class="hover:text-primary-dark" aria-label="Hapus filter">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </a>
                </span>
            </div>
            @endif

            @if ($blogs->isEmpty())
                <div class="text-center py-20">
                    <div class="w-20 h-20 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-5">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 font-medium">Belum ada artikel yang dipublikasikan.</p>
                </div>
            @else
                {{-- Card Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach ($blogs as $index => $blog)
                    <article class="group bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/60 hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">

                        {{-- Thumbnail --}}
                        <a href="{{ route('blog.show', $blog->slug) }}" class="block overflow-hidden shrink-0">
                            @if ($blog->featured_image)
                                <img
                                    src="{{ Storage::url($blog->featured_image) }}"
                                    alt="{{ $blog->title }}"
                                    loading="{{ $index < 3 ? 'eager' : 'lazy' }}"
                                    width="400" height="220"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500"
                                >
                            @else
                                <div class="w-full h-48 bg-primary-light flex items-center justify-center">
                                    <svg class="w-12 h-12 text-primary/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                </div>
                            @endif
                        </a>

                        {{-- Body --}}
                        <div class="p-5 flex flex-col flex-1">

                            {{-- Categories --}}
                            @if($blog->categories->isNotEmpty())
                            <div class="flex flex-wrap gap-1.5 mb-3">
                                @foreach ($blog->categories->take(2) as $category)
                                    <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                                       class="text-xs font-semibold text-primary bg-primary-light px-2.5 py-1 rounded-full hover:bg-primary hover:text-white transition-all duration-200">
                                        {{ $category->name }}
                                    </a>
                                @endforeach
                            </div>
                            @endif

                            {{-- Title --}}
                            <h2 class="text-base font-bold text-slate-800 mb-2 line-clamp-2 group-hover:text-primary transition-colors duration-200 flex-1">
                                <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
                            </h2>

                            {{-- Excerpt --}}
                            @if ($blog->excerpt)
                            <p class="text-sm text-slate-500 line-clamp-2 leading-relaxed mb-3">{{ $blog->excerpt }}</p>
                            @endif

                            {{-- Reading time --}}
                            @php $readMin = max(1, (int) ceil(str_word_count(strip_tags($blog->content)) / 200)); @endphp
                            <div class="flex items-center gap-1 text-xs text-slate-400 mb-3">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $readMin }} menit baca
                            </div>

                            {{-- Footer --}}
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold shrink-0">
                                        {{ strtoupper(substr($blog->author?->name ?? 'A', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-slate-700 leading-none">{{ $blog->author?->name ?? 'Admin' }}</p>
                                        <time datetime="{{ $blog->published_at?->toIso8601String() }}"
                                              class="text-xs text-slate-400">
                                            {{ $blog->published_at?->translatedFormat('d M Y') }}
                                        </time>
                                    </div>
                                </div>
                                <a href="{{ route('blog.show', $blog->slug) }}"
                                   class="inline-flex items-center gap-1 text-xs font-semibold text-primary hover:gap-2 transition-all duration-200">
                                    Baca
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </article>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($blogs->hasPages())
                <div class="mt-10">
                    {{ $blogs->links("vendor.pagination.frontend") }}
                </div>
                @endif
            @endif
        </div>

        {{-- ── Sidebar ── --}}
        <aside class="w-full lg:w-72 shrink-0">
            <div class="sticky top-24 space-y-6">

                {{-- Category Filter --}}
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider mb-4">Kategori</h3>
                    <ul class="space-y-1">
                        <li>
                            <a href="{{ route('blog.index') }}"
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                      {{ empty($activeCategory) ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
                                <span>Semua Artikel</span>
                                <span class="text-xs {{ empty($activeCategory) ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-500' }} px-2 py-0.5 rounded-full font-semibold">
                                    {{ $blogs->total() }}
                                </span>
                            </a>
                        </li>
                        @foreach ($categories as $category)
                        <li>
                            <a href="{{ route('blog.index', ['category' => $category->slug]) }}"
                               class="flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                                      {{ $activeCategory === $category->slug ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
                                <span>{{ $category->name }}</span>
                                <span class="text-xs {{ $activeCategory === $category->slug ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-500' }} px-2 py-0.5 rounded-full font-semibold">
                                    {{ $category->blogs_count }}
                                </span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Ad slot --}}
                <x-frontend.ad-slot position="sidebar" />

            </div>
        </aside>

    </div>
</div>
@endsection
