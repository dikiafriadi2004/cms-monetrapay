@extends('frontend.layouts.app')

@section('title', $category->name)

@section('seo')
@php
    $catTitle = $category->name . ' - ' . ($siteName ?? 'MonetraPay');
    $catDesc = $category->description ?? 'Artikel dalam kategori ' . $category->name;
    $catUrl = route('category.show', $category->slug);
@endphp
<title>{{ $catTitle }}</title>
<meta name="description" content="{{ $catDesc }}">
<link rel="canonical" href="{{ $catUrl }}">
<meta property="og:title" content="{{ $catTitle }}">
<meta property="og:description" content="{{ $catDesc }}">
<meta property="og:url" content="{{ $catUrl }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName ?? 'MonetraPay' }}">
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $catTitle }}">
<meta name="twitter:description" content="{{ $catDesc }}">
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <x-frontend.breadcrumb :items="[
        ['label' => 'Kategori', 'url' => route('categories.index')],
        ['label' => $category->name, 'url' => ''],
    ]" />

    <header class="mb-8">
        <h1 class="text-3xl font-bold text-slate-800 mb-2">{{ $category->name }}</h1>
        @if ($category->description)
            <p class="text-slate-600">{{ $category->description }}</p>
        @endif
    </header>

    @if ($blogs->isEmpty())
        <p class="text-slate-600">Belum ada artikel dalam kategori ini.</p>
    @else
        <div class="space-y-6">
            @foreach ($blogs as $blog)
            <article class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-shadow duration-200">
                <div class="flex flex-col sm:flex-row">
                    @if ($blog->featured_image)
                    <div class="sm:w-48 md:w-56 shrink-0">
                        <img
                            src="{{ Storage::url($blog->featured_image) }}"
                            alt="{{ $blog->title }}"
                            loading="lazy"
                            width="224"
                            height="160"
                            class="w-full h-48 sm:h-full object-cover"
                        >
                    </div>
                    @endif
                    <div class="p-5 flex-1">
                        <div class="flex flex-wrap gap-2 mb-2">
                            @foreach ($blog->categories as $cat)
                                <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded">
                                    {{ $cat->name }}
                                </span>
                            @endforeach
                        </div>
                        <h2 class="text-lg font-semibold text-slate-800 mb-2">
                            <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-blue-600 transition-colors duration-200">
                                {{ $blog->title }}
                            </a>
                        </h2>
                        @if ($blog->excerpt)
                        <p class="text-sm text-slate-600 mb-3 line-clamp-2">{{ $blog->excerpt }}</p>
                        @endif
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            @if ($blog->author)
                            <span>{{ $blog->author->name }}</span>
                            <span>&middot;</span>
                            @endif
                            <time datetime="{{ $blog->published_at?->toIso8601String() }}">
                                {{ $blog->published_at?->translatedFormat('d F Y') }}
                            </time>
                        </div>
                    </div>
                </div>
            </article>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $blogs->links("vendor.pagination.frontend") }}
        </div>
    @endif
</div>
@endsection
