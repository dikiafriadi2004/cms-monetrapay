@extends('frontend.layouts.app')
@section('title', $page->meta_title ?: ($page->title . ' - ' . ($siteName ?? config('app.name'))))
@section('seo')
<x-seo-meta :title="$metaTags['title']" :description="$metaTags['description']" :keywords="$metaTags['keywords']" :canonicalUrl="$canonicalUrl" :ogTags="$ogTags" :twitterTags="$twitterTags" :structuredData="$structuredData"/>
@endsection

@section('content')
{{--
    Halaman ini dikelola melalui Page Builder.
    Admin → Pages → Edit "Layanan" → aktifkan Page Builder
    Komponen disarankan: hero, features, pricing, cta, faq
--}}
@if($page->use_page_builder && $page->visibleComponents->isNotEmpty())
    @foreach($page->visibleComponents as $component)
        @include("components.builder.{$component->component_type}", [
            'content'  => $component->content,
            'settings' => $component->settings ?? [],
        ])
    @endforeach
@else
    @if(!empty(trim(strip_tags($page->content ?? ''))))
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-0 pb-12">
            <x-frontend.breadcrumb :items="$breadcrumbs ?? []" />
            <h1 class="text-3xl font-extrabold text-slate-800 mb-6">{{ $page->title }}</h1>
            <div class="prose prose-slate max-w-none">{!! $page->content !!}</div>
        </div>
    @else
        <div class="min-h-[60vh] flex items-center justify-center">
            <div class="text-center max-w-md px-4">
                <div class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center text-4xl mx-auto mb-4">⚡</div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">Halaman Layanan Belum Dikonfigurasi</h2>
                <p class="text-slate-500 text-sm mb-4">
                    Buka <strong>Admin → Pages → Edit "Layanan"</strong>,
                    aktifkan <strong>Page Builder</strong>, lalu tambahkan komponen
                    <code class="bg-slate-100 px-1 rounded">hero</code>, <code class="bg-slate-100 px-1 rounded">features</code>, <code class="bg-slate-100 px-1 rounded">pricing</code>.
                </p>
                @auth
                <a href="{{ route('admin.pages.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-dark transition-colors">
                    Buka Admin
                </a>
                @endauth
            </div>
        </div>
    @endif
@endif
@endsection
