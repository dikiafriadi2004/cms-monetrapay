@extends('frontend.layouts.app')

@section('title', $blog->title)

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
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-10 lg:pt-5 lg:pb-14">
    {{-- Breadcrumb --}}
    <x-frontend.breadcrumb :items="[
        ['label' => 'Blog', 'url' => route('blog.index')],
        ['label' => $blog->title, 'url' => ''],
    ]" />

    <div class="flex flex-col lg:flex-row gap-10 lg:gap-14 mt-8">
        {{-- Main Content (Left - ~65%) --}}
        <div class="flex-1 lg:max-w-[calc(100%-22rem)] min-w-0">
            <article class="animate-on-scroll">
                {{-- Article Header --}}
                <header class="mb-10">
                    {{-- Categories --}}
                    <div class="flex flex-wrap gap-2 mb-5">
                        @foreach ($blog->categories as $category)
                            <a href="{{ route('category.show', $category->slug) }}" class="text-xs font-semibold text-primary bg-primary-light px-3.5 py-1.5 rounded-full hover:bg-primary hover:text-white transition-all duration-200">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>

                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-slate-800 mb-6 leading-tight">
                        {{ $blog->title }}
                    </h1>

                    <div class="flex flex-wrap items-center gap-4 text-sm text-slate-500 pb-6 border-b border-slate-100">
                        @if ($blog->author)
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center shadow-sm">
                                <span class="text-sm font-bold text-white">{{ strtoupper(substr($blog->author->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-slate-700 block text-sm">{{ $blog->author->name }}</span>
                                <span class="text-xs text-slate-400">Penulis</span>
                            </div>
                        </div>
                        @endif
                        <span class="text-slate-200">|</span>
                        <time datetime="{{ $blog->published_at?->toIso8601String() }}" class="flex items-center gap-1.5 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $blog->published_at?->translatedFormat('d F Y') }}
                        </time>
                        @php
                            $wordCount = str_word_count(strip_tags($blog->content));
                            $readMinutes = max(1, (int) ceil($wordCount / 200));
                        @endphp
                        <span class="text-slate-200">|</span>
                        <span class="flex items-center gap-1.5 text-slate-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $readMinutes }} menit baca
                        </span>
                    </div>
                </header>

                {{-- Featured Image --}}
                @if ($blog->featured_image)
                <figure class="mb-10 -mx-4 sm:mx-0">
                    <img
                        src="{{ Storage::url($blog->featured_image) }}"
                        alt="{{ $blog->title }}"
                        loading="lazy"
                        width="800"
                        height="450"
                        class="w-full sm:rounded-2xl object-cover max-h-[32rem] shadow-lg"
                    >
                </figure>
                @endif

                {{-- Article Content (with auto-inserted ads) --}}
                <div class="prose-enhanced max-w-none mb-10">
                    {!! $content !!}
                </div>

                {{-- Tags --}}
                @if ($blog->tags->isNotEmpty())
                <div class="border-t border-slate-200 pt-6 mb-8">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-sm font-semibold text-slate-700 mr-1">Tags:</span>
                        @foreach ($blog->tags as $tag)
                            <span class="text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full hover:bg-primary-light hover:text-primary transition-colors duration-200 cursor-default">
                                #{{ $tag->name }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Share Section --}}
                <div class="border-t border-slate-200 pt-6 mb-8">
                    <h4 class="text-sm font-semibold text-slate-700 mb-4">Bagikan Artikel</h4>
                    <div class="flex items-center gap-3">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="share-btn bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white"
                           aria-label="Share on Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($blog->title) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="share-btn bg-slate-100 text-slate-900 hover:bg-black hover:text-white"
                           aria-label="Share on X">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        <a href="https://wa.me/?text={{ urlencode($blog->title . ' ' . request()->url()) }}"
                           target="_blank" rel="noopener noreferrer"
                           class="share-btn bg-green-50 text-green-600 hover:bg-green-600 hover:text-white"
                           aria-label="Share on WhatsApp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <button onclick="navigator.clipboard.writeText(window.location.href); this.querySelector('span').textContent = 'Tersalin!'; setTimeout(() => this.querySelector('span').textContent = 'Salin Link', 2000)"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-100 text-slate-600 text-sm font-medium rounded-full hover:bg-slate-200 transition-colors duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                            <span>Salin Link</span>
                        </button>
                    </div>
                </div>

                {{-- Author Box removed --}}
            </article>

            {{-- Comments --}}
            <x-frontend.comment-section :blog="$blog" />

            {{-- Related Articles --}}
            @if ($relatedBlogs->isNotEmpty())
            <section class="border-t border-slate-200 pt-12 animate-on-scroll">
                <h2 class="text-xl font-bold text-slate-800 mb-8">Artikel Terkait</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach ($relatedBlogs as $related)
                    <article class="hover-lift group bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition-all duration-300">
                        @if ($related->featured_image)
                        <div class="img-zoom">
                            <img
                                src="{{ Storage::url($related->featured_image) }}"
                                alt="{{ $related->title }}"
                                loading="lazy"
                                width="300"
                                height="170"
                                class="w-full h-36 object-cover"
                            >
                        </div>
                        @endif
                        <div class="p-5">
                            <h3 class="text-sm font-semibold text-slate-800 mb-2 line-clamp-2 group-hover:text-primary transition-colors duration-200">
                                <a href="{{ route('blog.show', $related->slug) }}">
                                    {{ $related->title }}
                                </a>
                            </h3>
                            <time datetime="{{ $related->published_at?->toIso8601String() }}" class="text-xs text-primary font-medium">
                                {{ $related->published_at?->translatedFormat('d F Y') }}
                            </time>
                        </div>
                    </article>
                    @endforeach
                </div>
            </section>
            @endif
        </div>

        {{-- Sidebar (Right - ~35%) --}}
        <aside class="w-full lg:w-80 shrink-0">
            <div class="sidebar-sticky space-y-8">
                {{-- Ad Slot --}}
                <x-frontend.ad-slot position="sidebar" />

                {{-- Recent Posts --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm animate-on-scroll">
                    <h3 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2 pb-3 border-b border-slate-100">
                        <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Artikel Terbaru
                    </h3>
                    <div class="space-y-4">
                        @foreach($recentPosts as $recent)
                        <a href="{{ route('blog.show', $recent->slug) }}" class="flex items-start gap-3 group p-2 -mx-2 rounded-lg hover:bg-slate-50 transition-colors duration-200">
                            @if($recent->featured_image)
                            <img src="{{ Storage::url($recent->featured_image) }}" alt="{{ $recent->title }}" class="w-16 h-16 rounded-xl object-cover shrink-0 shadow-sm">
                            @else
                            <div class="w-16 h-16 rounded-xl bg-slate-100 shrink-0 flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                            <div class="min-w-0">
                                <h4 class="text-sm font-medium text-slate-700 line-clamp-2 group-hover:text-primary transition-colors duration-200">{{ $recent->title }}</h4>
                                <time class="text-xs text-slate-400 mt-1.5 block">{{ $recent->published_at?->translatedFormat('d M Y') }}</time>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Categories --}}
                <div class="bg-white rounded-2xl border border-slate-100 p-6 shadow-sm animate-on-scroll">
                    <h3 class="text-base font-bold text-slate-800 mb-5 flex items-center gap-2 pb-3 border-b border-slate-100">
                        <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Kategori
                    </h3>
                    <div class="space-y-1">
                        @foreach($sidebarCategories as $cat)
                        <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center justify-between py-2.5 px-3 rounded-lg text-sm text-slate-600 hover:bg-primary-light hover:text-primary transition-all duration-200 group">
                            <span class="font-medium">{{ $cat->name }}</span>
                            <span class="text-xs bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full group-hover:bg-primary/10 group-hover:text-primary transition-colors duration-200">{{ $cat->blogs_count }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>

                {{-- Sidebar Ad Slot --}}
                <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6 text-center animate-on-scroll">
                    <x-frontend.ad-slot position="sidebar_bottom" />
                </div>
            </div>
        </aside>
    </div>
</div>
@endsection
