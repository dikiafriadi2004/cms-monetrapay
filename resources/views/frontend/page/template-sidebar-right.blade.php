{{-- Template: Sidebar Kanan — konten 2/3, sidebar 1/3 di kanan --}}
@extends(\$themeLayout ?? 'frontend.layouts.app')
@section('title', $page->meta_title ?: $page->title)
@section('seo')
<x-seo-meta :title="$metaTags['title']" :description="$metaTags['description']" :keywords="$metaTags['keywords']" :canonicalUrl="$canonicalUrl" :ogTags="$ogTags" :twitterTags="$twitterTags" :structuredData="$structuredData"/>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <x-frontend.breadcrumb :items="$breadcrumbs ?? []" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mt-6">
        {{-- Main content --}}
        <main class="lg:col-span-2">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-6 leading-tight">{{ $page->title }}</h1>

            @if($page->featured_image)
            <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                 class="w-full rounded-2xl mb-8 object-cover max-h-80">
            @endif

            <div class="prose prose-slate prose-lg max-w-none">{!! $page->content !!}</div>
        </main>

        {{-- Sidebar right --}}
        <aside class="lg:col-span-1 space-y-6">
            {{-- Recent pages --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Halaman Lainnya</h3>
                @php
                    $relatedPages = \App\Models\Page::where('status','published')
                        ->where('id','!=',$page->id)
                        ->latest()->limit(6)->get();
                @endphp
                <ul class="space-y-2">
                    @foreach($relatedPages as $rp)
                    <li>
                        <a href="{{ url('/'.$rp->slug) }}"
                           class="flex items-center gap-2 text-sm text-slate-600 hover:text-primary transition-colors py-1.5 border-b border-slate-50 last:border-0">
                            <svg class="w-3.5 h-3.5 shrink-0 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            {{ $rp->title }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact widget --}}
            <div class="rounded-2xl p-6 text-white" style="background:linear-gradient(135deg,var(--theme-primary,#3D9E7A),var(--theme-secondary,#FF6B35))">
                <h3 class="font-bold mb-2">Ada Pertanyaan?</h3>
                <p class="text-sm text-white/80 mb-4">Hubungi tim kami untuk informasi lebih lanjut.</p>
                <a href="{{ url('/kontak') }}"
                   class="inline-block px-4 py-2 bg-white text-sm font-bold rounded-xl hover:bg-slate-100 transition-colors"
                   style="color:var(--theme-primary,#3D9E7A)">
                    Hubungi Kami
                </a>
            </div>

            {{-- Recent blogs --}}
            @php $recentBlogs = \App\Models\Blog::published()->latest()->limit(3)->get(); @endphp
            @if($recentBlogs->isNotEmpty())
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-wider mb-4">Artikel Terbaru</h3>
                <ul class="space-y-4">
                    @foreach($recentBlogs as $blog)
                    <li>
                        <a href="{{ route('blog.show', $blog->slug) }}"
                           class="block text-sm font-semibold text-slate-700 hover:text-primary transition-colors leading-snug">
                            {{ $blog->title }}
                        </a>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $blog->published_at?->diffForHumans() }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </aside>
    </div>
</div>
@endsection
