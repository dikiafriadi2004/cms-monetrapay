{{-- Template: Full Width — tidak ada padding samping, konten melebar penuh --}}
@extends('frontend.layouts.app')
@section('title', $page->meta_title ?: $page->title)
@section('seo')
<x-seo-meta :title="$metaTags['title']" :description="$metaTags['description']" :keywords="$metaTags['keywords']" :canonicalUrl="$canonicalUrl" :ogTags="$ogTags" :twitterTags="$twitterTags" :structuredData="$structuredData"/>
@endsection

@section('content')
{{-- Full-width hero bar --}}
<div class="w-full bg-slate-900 pt-0 pb-12 px-4">
    <div class="max-w-screen-xl mx-auto">
        <x-frontend.breadcrumb :items="$breadcrumbs ?? []" class="mb-3" />
        <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-tight">{{ $page->title }}</h1>
    </div>
</div>

{{-- Full-width content — no max-width constraint --}}
<div class="w-full">
    @if($page->featured_image)
    <div class="w-full" style="max-height:500px;overflow:hidden">
        <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
             class="w-full object-cover" style="max-height:500px">
    </div>
    @endif

    <div class="px-4 sm:px-8 lg:px-16 py-12">
        <div class="prose prose-slate prose-lg max-w-none">{!! $page->content !!}</div>
    </div>
</div>
@endsection
