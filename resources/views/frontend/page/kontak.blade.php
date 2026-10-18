@extends('frontend.layouts.app')
@section('title', $page->meta_title ?: ($page->title . ' - ' . ($siteName ?? 'MonetraPay')))
@section('seo')
<x-seo-meta :title="$metaTags['title']" :description="$metaTags['description']" :keywords="$metaTags['keywords']" :canonicalUrl="$canonicalUrl" :ogTags="$ogTags" :twitterTags="$twitterTags" :structuredData="$structuredData"/>
@endsection

@section('content')
{{--
    Halaman ini sepenuhnya dikelola melalui Page Builder di Admin.
    Buka Admin → Pages → Edit "Kontak" → aktifkan Page Builder → tambahkan komponen.
    Komponen yang disarankan: hero, contact-form, map, social
--}}
@if($page->use_page_builder && $page->visibleComponents->isNotEmpty())
    {{-- Render dari Page Builder --}}
    @foreach($page->visibleComponents as $component)
        @include("components.builder.{$component->component_type}", [
            'content'  => $component->content,
            'settings' => $component->settings ?? [],
        ])
    @endforeach
@else
    {{-- Fallback: konten dari editor atau notifikasi setup --}}
    @if(!empty(trim(strip_tags($page->content ?? ''))))
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <x-frontend.breadcrumb :items="$breadcrumbs ?? []" />
            <h1 class="text-3xl font-extrabold text-slate-800 mb-6">{{ $page->title }}</h1>
            <div class="prose prose-slate max-w-none">{!! $page->content !!}</div>
        </div>
    @else
        {{-- Setup notice (hanya terlihat jika halaman benar-benar kosong) --}}
        <div class="min-h-[60vh] flex items-center justify-center">
            <div class="text-center max-w-md px-4">
                <div class="w-20 h-20 bg-primary/10 rounded-2xl flex items-center justify-center text-4xl mx-auto mb-4">📧</div>
                <h2 class="text-xl font-bold text-slate-800 mb-2">Halaman Kontak Belum Dikonfigurasi</h2>
                <p class="text-slate-500 text-sm mb-4">
                    Buka <strong>Admin → Pages → Edit "Kontak"</strong>,
                    aktifkan <strong>Page Builder</strong>, lalu tambahkan komponen
                    <code class="bg-slate-100 px-1 rounded">contact-form</code> dan <code class="bg-slate-100 px-1 rounded">map</code>.
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
