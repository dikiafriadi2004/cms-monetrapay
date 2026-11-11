{{-- Template: Landing Page — konversi fokus, header/footer minimal, no breadcrumb --}}
@extends('frontend.layouts.app')
@section('title', $page->meta_title ?: $page->title)
@section('seo')
<x-seo-meta :title="$metaTags['title']" :description="$metaTags['description']" :keywords="$metaTags['keywords']" :canonicalUrl="$canonicalUrl" :ogTags="$ogTags" :twitterTags="$twitterTags" :structuredData="$structuredData"/>
@endsection

@section('content')
@if($page->use_page_builder && $page->visibleComponents->isNotEmpty())
    {{-- Landing page ideal dibangun via Page Builder --}}
    <div class="landing-page-wrapper">
        @foreach($page->visibleComponents as $component)
            @include("components.builder.{$component->component_type}", [
                'content'  => $component->content,
                'settings' => $component->settings ?? [],
            ])
        @endforeach
    </div>
@else
    {{-- Fallback: content from editor, no sidebar, centered, conversion-focused --}}
    <div class="min-h-screen flex flex-col">
        {{-- Hero area --}}
        <div class="pt-0 pb-20 md:pb-32 text-center px-4"
             style="background:var(--theme-primary,#3D9E7A)">
            <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight">{{ $page->title }}</h1>
            @if($page->meta_description)
            <p class="text-xl text-white/85 max-w-2xl mx-auto">{{ $page->meta_description }}</p>
            @endif
        </div>

        {{-- Content --}}
        <div class="flex-1 max-w-3xl mx-auto px-4 sm:px-6 py-16 w-full">
            @if($page->featured_image)
            <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                 class="w-full rounded-2xl mb-10 shadow-xl">
            @endif
            <div class="prose prose-slate prose-lg max-w-none">{!! $page->content !!}</div>
        </div>

        {{-- CTA bottom --}}
        <div class="py-16 text-center px-4 bg-slate-50 border-t border-slate-100">
            <a href="{{ url('/kontak') }}"
               class="inline-flex items-center gap-2 px-10 py-4 text-white font-bold text-lg rounded-2xl shadow-xl hover:-translate-y-0.5 transition-all duration-200"
               style="background:var(--theme-primary,#3D9E7A)">
                Mulai Sekarang
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
@endif
@endsection
