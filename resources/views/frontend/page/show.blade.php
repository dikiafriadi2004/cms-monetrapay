@extends('frontend.layouts.app')

@section('title', $page->title)

@section('seo')
<x-seo-meta
    :title="$metaTags['title']"
    :description="$metaTags['description']"
    :keywords="$metaTags['keywords']"
    :canonicalUrl="$canonicalUrl"
    :ogTags="$ogTags"
    :twitterTags="$twitterTags"
    :structuredData="$structuredData"
/>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-0 pb-8">
    {{-- Breadcrumb --}}
    <x-frontend.breadcrumb :items="$breadcrumbs" />

    <article class="max-w-4xl mx-auto">
        <header class="mb-8">
            <h1 class="text-3xl md:text-4xl font-bold text-slate-800 leading-tight">
                {{ $page->title }}
            </h1>
        </header>

        {{-- Featured Image --}}
        @if ($page->featured_image)
        <figure class="mb-8">
            <img
                src="{{ Storage::url($page->featured_image) }}"
                alt="{{ $page->title }}"
                loading="lazy"
                width="800"
                height="450"
                class="w-full rounded-xl object-cover max-h-96"
            >
        </figure>
        @endif

        {{-- Page Content --}}
        <div class="prose-enhanced max-w-none">
            {!! $page->content !!}
        </div>
    </article>
</div>
@endsection
