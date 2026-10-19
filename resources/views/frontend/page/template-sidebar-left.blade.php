{{-- Template: Sidebar Kiri — sidebar 1/3 di kiri, konten 2/3 --}}
@extends($themeLayout ?? 'frontend.layouts.app')
@section('title', $page->meta_title ?: $page->title)
@section('seo')
<x-seo-meta :title="$metaTags['title']" :description="$metaTags['description']" :keywords="$metaTags['keywords']" :canonicalUrl="$canonicalUrl" :ogTags="$ogTags" :twitterTags="$twitterTags" :structuredData="$structuredData"/>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <x-frontend.breadcrumb :items="$breadcrumbs ?? []" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mt-6">
        {{-- Sidebar left --}}
        <aside class="lg:col-span-1 space-y-6 order-2 lg:order-1">
            {{-- Page navigation --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Navigasi</h3>
                @php
                    $navPages = \App\Models\Page::where('status','published')
                        ->whereNull('parent_id')->orderBy('title')->limit(8)->get();
                @endphp
                <ul class="space-y-1">
                    @foreach($navPages as $np)
                    <li>
                        <a href="{{ url('/'.$np->slug) }}"
                           class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition-colors
                                  {{ $np->id === $page->id
                                     ? 'font-bold text-white'
                                     : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}"
                           @if($np->id === $page->id)
                               style="background:var(--theme-primary,#3D9E7A)"
                           @endif>
                            {{ $np->title }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Tags / meta info --}}
            @if($page->meta_keywords)
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Tags</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach(explode(',', $page->meta_keywords) as $kw)
                    <span class="text-xs font-medium px-3 py-1 rounded-full bg-slate-100 text-slate-600">{{ trim($kw) }}</span>
                    @endforeach
                </div>
            </div>
            @endif
        </aside>

        {{-- Main content --}}
        <main class="lg:col-span-2 order-1 lg:order-2">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-6 leading-tight">{{ $page->title }}</h1>

            @if($page->featured_image)
            <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                 class="w-full rounded-2xl mb-8 object-cover max-h-80">
            @endif

            <div class="prose prose-slate prose-lg max-w-none">{!! $page->content !!}</div>
        </main>
    </div>
</div>
@endsection
