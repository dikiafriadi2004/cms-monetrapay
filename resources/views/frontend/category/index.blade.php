@extends($themeLayout ?? 'frontend.layouts.app')

@section('title', 'Kategori')

@section('seo')
@php
    $catsTitle = 'Kategori - ' . ($siteName ?? 'MonetraPay');
    $catsDesc = 'Jelajahi semua kategori artikel di MonetraPay.';
    $catsUrl = route('categories.index');
@endphp
<title>{{ $catsTitle }}</title>
<meta name="description" content="{{ $catsDesc }}">
<link rel="canonical" href="{{ $catsUrl }}">
<meta property="og:title" content="{{ $catsTitle }}">
<meta property="og:description" content="{{ $catsDesc }}">
<meta property="og:url" content="{{ $catsUrl }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName ?? 'MonetraPay' }}">
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <x-frontend.breadcrumb :items="[['label' => 'Kategori', 'url' => '']]" />

    <h1 class="text-3xl font-bold text-slate-800 mb-8">Kategori</h1>

    @if ($categories->isEmpty())
        <p class="text-slate-600">Belum ada kategori yang tersedia.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($categories as $category)
            <a href="{{ route('category.show', $category->slug) }}" class="block bg-white rounded-xl shadow-sm border border-slate-100 p-6 hover:shadow-md hover:border-blue-200 transition-all duration-200">
                <h2 class="text-lg font-semibold text-slate-800 mb-2">{{ $category->name }}</h2>
                @if ($category->description)
                    <p class="text-sm text-slate-600 mb-3 line-clamp-2">{{ $category->description }}</p>
                @endif
                <span class="text-sm text-blue-600 font-medium">
                    {{ $category->blogs_count }} {{ $category->blogs_count === 1 ? 'artikel' : 'artikel' }}
                </span>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
