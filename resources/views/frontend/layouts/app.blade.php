<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'MonetraPay')</title>
    {{-- Pre-paint dark mode application to avoid flash --}}
    <script>
        (function() {
            try {
                var t = localStorage.getItem('mp-theme');
                if (t === 'dark' || (!t && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                    document.documentElement.classList.add('dark');
                }
            } catch (e) {}
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Preload critical assets --}}
    @php $logoPreload = \App\Models\Setting::get('logo', 'general'); @endphp
    @if($logoPreload)
    <link rel="preload" href="{{ Storage::url($logoPreload) }}" as="image">
    @elseif(file_exists(public_path('assets/images/logo.png')))
    <link rel="preload" href="/assets/images/logo.png" as="image">
    @endif
    @if(file_exists(public_path('assets/images/favico.png')))
    <link rel="icon" href="/assets/images/favico.png" type="image/png">
    @endif
    @php $faviconSetting = \App\Models\Setting::get('favicon', 'general'); @endphp
    @if($faviconSetting)
    <link rel="icon" href="{{ Storage::url($faviconSetting) }}" type="image/png">
    @endif

    {{-- RSS Feed discovery --}}
    <link rel="alternate" type="application/rss+xml" title="{{ $siteName ?? 'MonetraPay' }} - Blog RSS" href="{{ url('/feed.xml') }}">

    {{-- Default SEO meta (overridable per page) --}}
    @hasSection('seo')
        @yield('seo')
    @else
        @php
            $seoDefaults = \App\Models\Setting::getGroup('seo');
            $defaultTitle = $seoDefaults['meta_title_default'] ?? ($siteName ?? 'MonetraPay');
            $defaultDesc = $seoDefaults['meta_description_default'] ?? '';
            $canonical = url(request()->path() === '/' ? '/' : '/' . request()->path());
        @endphp
        <meta name="description" content="{{ $defaultDesc }}">
        <link rel="canonical" href="{{ $canonical }}">
        <meta property="og:title" content="{{ $defaultTitle }}">
        <meta property="og:description" content="{{ $defaultDesc }}">
        <meta property="og:url" content="{{ $canonical }}">
        <meta property="og:type" content="website">
        <meta property="og:site_name" content="{{ $siteName ?? 'MonetraPay' }}">
        @if(!empty($logo))
        <meta property="og:image" content="{{ url(Storage::url($logo)) }}">
        @elseif(file_exists(public_path('assets/images/logo.png')))
        <meta property="og:image" content="{{ url('/assets/images/logo.png') }}">
        @endif
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $defaultTitle }}">
        <meta name="twitter:description" content="{{ $defaultDesc }}">
        @if(!empty($logo))
        <meta name="twitter:image" content="{{ url(Storage::url($logo)) }}">
        @elseif(file_exists(public_path('assets/images/logo.png')))
        <meta name="twitter:image" content="{{ url('/assets/images/logo.png') }}">
        @endif
    @endif

    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Google Analytics (only if GA ID is configured and not in local env) --}}
    @php $gaId = \App\Models\Setting::get('ga_measurement_id', 'seo'); @endphp
    @if($gaId && !app()->isLocal())
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}', {
            anonymize_ip: true,
            page_title: document.title,
            page_location: window.location.href,
        });
    </script>
    @endif
</head>
@php
    $currentPath = request()->path();
    $isBlog = str_starts_with($currentPath, 'blog');
    
    // Use MenuService for dynamic menus (instead of hardcoded)
    $menuService = app(\App\Services\MenuService::class);
    // location 'header' matches MenuSeeder
    $primaryMenu = $menuService->getMenuByLocation('header') ?? [];
    $footerMenuItems = $menuService->getMenuByLocation('footer') ?? [];
    $socialMenuItems = $menuService->getMenuByLocation('social') ?? [];
@endphp
<body class="min-h-screen bg-white flex flex-col font-sans text-slate-700 antialiased"
      x-data="{ mobileMenuOpen: false, scrolled: false }"
      x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 }, { passive: true })">

{{-- ===== NAVBAR ===== --}}
<header role="banner"
        :class="scrolled ? 'shadow-md shadow-slate-200/60 bg-white' : 'bg-white/98 backdrop-blur-md'"
        class="header-sticky border-b border-slate-100 transition-all duration-300 z-50">
    <x-frontend.ad-slot position="header" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-15 lg:h-16">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center shrink-0 group">
                @php $logo = \App\Models\Setting::get('logo', 'general'); @endphp
                @if($logo)
                    <img src="{{ Storage::url($logo) }}" alt="{{ $siteName ?? 'MonetraPay' }}"
                         class="h-14 lg:h-16 w-auto max-w-[250px] object-contain transition-transform group-hover:scale-105">
                @elseif(file_exists(public_path('assets/images/logo.png')))
                    <img src="/assets/images/logo.png" alt="{{ $siteName ?? 'MonetraPay' }}"
                         class="h-14 lg:h-16 w-auto max-w-[250px] object-contain transition-transform group-hover:scale-105">
                @else
                    <span class="text-xl lg:text-2xl font-extrabold tracking-tight">
                        <span class="text-primary">Monetra</span><span class="text-secondary">Pay</span>
                    </span>
                @endif
            </a>
            {{-- Desktop Nav - Use MenuService (dynamic from database) --}}
            <nav class="hidden lg:flex items-center gap-0.5" role="navigation" aria-label="Main navigation">
                @foreach($primaryMenu as $item)
                @php
                    $p = ltrim(parse_url($item['url'] ?? '/', PHP_URL_PATH) ?? '/', '/');
                    $active = ($p === '' && $currentPath === '/') || ($p !== '' && str_starts_with($currentPath, $p));
                @endphp
                <a href="{{ $item['url'] ?? '#' }}"
                   class="relative px-3.5 py-2 text-sm font-semibold rounded-lg transition-all duration-200 group
                          {{ $active ? 'text-primary' : 'text-slate-600 hover:text-primary hover:bg-primary-light/60' }}">
                    {{ $item['label'] ?? '' }}
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 rounded-full bg-primary transition-all duration-300 {{ $active ? 'w-5' : 'w-0 group-hover:w-5' }}"></span>
                </a>
                @endforeach
            </nav>
            {{-- Right: Search + CTA + Hamburger --}}
            <div class="flex items-center gap-2">
                {{-- Dark mode toggle --}}
                <button x-data="{ dark: document.documentElement.classList.contains('dark') }"
                        @click="dark = !dark; document.documentElement.classList.toggle('dark', dark); localStorage.setItem('mp-theme', dark ? 'dark' : 'light')"
                        class="hidden sm:flex items-center justify-center w-9 h-9 text-slate-600 hover:text-primary rounded-xl hover:bg-slate-100 transition-colors dark:text-slate-300 dark:hover:bg-slate-800"
                        :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                    <svg x-show="!dark" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="dark" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
                {{-- Search (desktop) --}}
                <div class="hidden lg:flex items-center" x-data="{ open: false }">
                    <div class="relative flex items-center">
                        <button @click="open = !open; $nextTick(() => open && $refs.si.focus())"
                                class="p-2 text-slate-500 hover:text-primary rounded-lg hover:bg-slate-100 transition-colors" aria-label="Cari">
                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-x-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-x-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-x-4"
                             class="absolute right-0 top-1/2 -translate-y-1/2 z-10">
                            <form action="{{ route('search') }}" method="GET">
                                <input x-ref="si" type="text" name="q" placeholder="Cari artikel..."
                                       value="{{ request('q') }}" @keydown.escape="open = false"
                                       class="w-64 text-sm border border-slate-200 rounded-xl px-4 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary bg-white shadow-lg">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- CTA --}}
                <a href="{{ url('/kontak') }}"
                   class="hidden lg:inline-flex items-center gap-2 px-4 py-2.5 bg-secondary text-white text-sm font-bold rounded-xl hover:bg-secondary-dark transition-all duration-200 shadow-md shadow-secondary/25 hover:-translate-y-0.5">
                    Daftar Jadi Agen
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                {{-- Hamburger --}}
                <button @click="mobileMenuOpen = true"
                        class="lg:hidden flex items-center justify-center w-9 h-9 text-slate-600 hover:text-primary rounded-xl hover:bg-slate-100 transition-colors"
                        aria-label="Buka menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    {{-- Mobile Drawer Backdrop --}}
    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>
    {{-- Mobile Drawer Panel --}}
    <nav x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
         class="fixed top-0 left-0 bottom-0 z-50 w-[300px] bg-white shadow-2xl lg:hidden flex flex-col"
         role="navigation" aria-label="Mobile navigation">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            @if(file_exists(public_path('assets/images/logo.png')))
                <img src="/assets/images/logo.png" alt="{{ $siteName ?? 'MonetraPay' }}" class="h-9 w-auto max-w-[145px]">
            @else
                <span class="text-lg font-extrabold"><span class="text-primary">Monetra</span><span class="text-secondary">Pay</span></span>
            @endif
            <button @click="mobileMenuOpen = false" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-5 py-4 border-b border-slate-100">
            <form action="{{ route('search') }}" method="GET">
                <div class="relative">
                    <input type="text" name="q" placeholder="Cari artikel..." value="{{ request('q') }}"
                           class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </form>
        </div>
        <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            @if(!empty($primaryMenu))
                @foreach($primaryMenu as $item)
                @php $p = ltrim(parse_url($item['url'] ?? '/', PHP_URL_PATH) ?? '/', '/'); $ma = ($p === '' && $currentPath === '/') || ($p !== '' && str_starts_with($currentPath, $p)); @endphp
                <a href="{{ $item['url'] ?? '#' }}" @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $ma ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $ma ? 'bg-primary' : 'bg-slate-300' }} shrink-0"></span>
                    {{ $item['label'] ?? '' }}
                </a>
                @endforeach
            @endif
        </div>
        <div class="px-5 py-5 border-t border-slate-100">
            <a href="{{ url('/kontak') }}" @click="mobileMenuOpen = false"
               class="flex items-center justify-center gap-2 w-full px-5 py-3.5 bg-secondary text-white text-sm font-bold rounded-xl hover:bg-secondary-dark transition-all duration-200 shadow-md shadow-secondary/25">
                Daftar Jadi Agen Sekarang
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </nav>
</header>

{{-- Main Content --}}
<main role="main" class="flex-1">
    @yield('content')
</main>

{{-- Footer --}}
<footer role="contentinfo" class="mt-auto">
    <x-frontend.ad-slot position="footer" />
    <div class="bg-dark relative overflow-hidden">
        <div class="tech-grid-pattern absolute inset-0"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-12">
                <div class="md:col-span-12 lg:col-span-5">
                    @php $footerLogo = \App\Models\Setting::get('logo', 'general'); @endphp
                    @if($footerLogo)
                        <img src="{{ Storage::url($footerLogo) }}" alt="{{ $siteName ?? 'MonetraPay' }}" class="h-12 w-auto mb-5">
                    @elseif(file_exists(public_path('assets/images/logo.png')))
                        <img src="/assets/images/logo.png" alt="{{ $siteName ?? 'MonetraPay' }}" class="h-12 w-auto mb-5">
                    @else
                        <span class="text-xl font-extrabold text-white block mb-5"><span class="text-primary">Monetra</span><span class="text-secondary">Pay</span></span>
                    @endif
                    <p class="text-slate-400 text-sm leading-relaxed max-w-md mb-5">
                        {{ $footerSettings['tagline'] ?? 'Solusi lengkap untuk kebutuhan transaksi digital Anda. Pulsa, PPOB, Top-Up Game, dan E-Money dalam satu platform.' }}
                    </p>

                    {{-- Newsletter inline --}}
                    <div class="mb-6">
                        <h4 class="text-xs font-semibold text-white/80 uppercase tracking-wider mb-2">Newsletter</h4>
                        <p class="text-slate-400 text-xs mb-3">Update artikel terbaru langsung ke email Anda.</p>
                        <x-frontend.newsletter-form />
                    </div>

                    @if(!empty($socialLinks))
                    <div class="flex items-center gap-3">
                        @if(!empty($socialLinks['facebook_url']))
                        <a href="{{ $socialLinks['facebook_url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['instagram_url']))
                        <a href="{{ $socialLinks['instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['twitter_url']))
                        <a href="{{ $socialLinks['twitter_url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-black hover:border-black hover:text-white transition-all duration-200" aria-label="X">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['youtube_url']))
                        <a href="{{ $socialLinks['youtube_url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="YouTube">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zM9 16V8l8 4-8 4z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['tiktok_url']))
                        <a href="{{ $socialLinks['tiktok_url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="TikTok">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.88 2.89 2.89 0 01-2.88-2.88 2.89 2.89 0 012.88-2.88c.28 0 .56.04.82.11v-3.5a6.37 6.37 0 00-.82-.05A6.34 6.34 0 003.15 15.7a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V9.41a8.16 8.16 0 004.76 1.52v-3.4a4.85 4.85 0 01-1-.84z"/></svg>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="md:col-span-6 lg:col-span-3">
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-5">Kontak</h4>
                    <ul class="space-y-4">
                        @if(!empty($footerSettings['contact_email']))
                        <li><a href="mailto:{{ $footerSettings['contact_email'] }}" class="text-sm text-slate-400 hover:text-primary transition-colors flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $footerSettings['contact_email'] }}</a></li>
                        @endif
                        @if(!empty($footerSettings['contact_phone']))
                        <li><a href="tel:{{ $footerSettings['contact_phone'] }}" class="text-sm text-slate-400 hover:text-primary transition-colors flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $footerSettings['contact_phone'] }}</a></li>
                        @endif
                    </ul>
                </div>
                <div class="md:col-span-6 lg:col-span-4">
                    @php
                        // Merge any extra footer items from database that aren't already shown
                        $shownFooterPaths = ['kebijakan-privasi', 'syarat-ketentuan', 'faq'];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-5">Navigasi</h4>
                            <ul class="space-y-3">
                                @forelse($footerMenuItems as $item)
                                    <li><a href="{{ $item['url'] ?? '#' }}" class="text-sm text-slate-400 hover:text-primary transition-colors">{{ $item['label'] ?? '' }}</a></li>
                                @empty
                                    {{-- Minimal fallback if footer menu empty in DB --}}
                                    <li><a href="{{ url('/') }}" class="text-sm text-slate-400 hover:text-primary transition-colors">Beranda</a></li>
                                    <li><a href="{{ url('/blog') }}" class="text-sm text-slate-400 hover:text-primary transition-colors">Blog</a></li>
                                @endforelse
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-5">Legal & Bantuan</h4>
                            <ul class="space-y-3">
                                <li><a href="{{ url('/kebijakan-privasi') }}" class="text-sm text-slate-400 hover:text-primary transition-colors">Kebijakan Privasi</a></li>
                                <li><a href="{{ url('/syarat-ketentuan') }}" class="text-sm text-slate-400 hover:text-primary transition-colors">Syarat & Ketentuan</a></li>
                                <li><a href="{{ url('/faq') }}" class="text-sm text-slate-400 hover:text-primary transition-colors">FAQ</a></li>
                            </ul>
                            <div class="mt-6 rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                <p class="text-xs font-semibold text-white/80 uppercase tracking-wide">Keamanan Transaksi</p>
                                <p class="mt-2 text-xs leading-relaxed text-slate-400">Dipantau 24/7, mendukung verifikasi admin, dan siap untuk proses audit operasional.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom bg-[#0a1120] border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-slate-500">{{ $footerSettings['copyright_text'] ?? '© ' . date('Y') . ' ' . ($siteName ?? 'MonetraPay') . '. All rights reserved.' }}</p>
            @if(!empty($footerSettings['footer_extra_content']))
            <p class="text-sm text-slate-500">{{ $footerSettings['footer_extra_content'] }}</p>
            @endif
        </div>
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')

{{-- Back to Top Button --}}
<button id="back-to-top"
        onclick="window.scrollTo({top:0,behavior:'smooth'})"
        class="fixed bottom-6 right-6 z-50 w-11 h-11 bg-primary text-white rounded-full shadow-lg shadow-primary/30 flex items-center justify-center opacity-0 translate-y-4 transition-all duration-300 hover:bg-primary-dark hover:shadow-primary/50 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
        aria-label="Kembali ke atas"
        style="pointer-events:none">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
    </svg>
</button>
<script>
(function() {
    var btn = document.getElementById('back-to-top');
    if (!btn) return;
    var visible = false;
    window.addEventListener('scroll', function() {
        var shouldShow = window.scrollY > 400;
        if (shouldShow !== visible) {
            visible = shouldShow;
            btn.style.opacity = shouldShow ? '1' : '0';
            btn.style.transform = shouldShow ? 'translateY(0)' : 'translateY(1rem)';
            btn.style.pointerEvents = shouldShow ? 'auto' : 'none';
        }
    }, { passive: true });
})();
</script>
</body>
</html>
