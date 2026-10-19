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
    {{-- Load Google Fonts dynamically based on active theme --}}
    @php
        try {
            $activeThemeFonts = app(\App\Services\ThemeService::class)->getActiveTheme();
            $fontHeading = $activeThemeFonts?->config['font_heading'] ?? 'Plus Jakarta Sans';
            $fontBody    = $activeThemeFonts?->config['font_body']    ?? 'Plus Jakarta Sans';
            // Fonts that don't need external loading (system/web-safe)
            $systemFonts = ['Georgia','Times New Roman','Arial','Helvetica','Verdana','Tahoma','system-ui'];
            $needGoogleFonts = !in_array($fontHeading, $systemFonts) || !in_array($fontBody, $systemFonts);
            // Build Google Fonts URL
            $fontsToLoad = collect([$fontHeading, $fontBody])
                ->unique()
                ->filter(fn($f) => !in_array($f, $systemFonts))
                ->map(fn($f) => urlencode($f) . ':wght@300;400;500;600;700;800')
                ->implode('&family=');
        } catch (\Throwable $e) {
            $needGoogleFonts = true;
            $fontsToLoad = 'Plus+Jakarta+Sans:wght@300;400;500;600;700;800';
        }
    @endphp
    @if($needGoogleFonts && $fontsToLoad)
    <link href="https://fonts.googleapis.com/css2?family={{ $fontsToLoad }}&display=swap" rel="stylesheet">
    @else
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @endif
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
    {{-- Active Theme CSS Variables --}}
    @php
        try {
            $themeService = app(\App\Services\ThemeService::class);
            $themeStyles  = $themeService->applyThemeStyles();
            $themeSlug    = $themeService->getActiveTheme()?->slug ?? 'modern-corporate';
        } catch (\Throwable $e) {
            $themeStyles = '';
            $themeSlug   = 'modern-corporate';
        }
    @endphp
    @if(!empty($themeStyles))
    <style id="theme-vars">{!! $themeStyles !!}</style>
    @endif
</head>
@php
    $currentPath = request()->path();
    $isBlog = str_starts_with($currentPath, 'blog');

    // Use MenuService for dynamic menus
    $menuService     = app(\App\Services\MenuService::class);
    $primaryMenu     = $menuService->getMenuByLocation('header') ?? [];
    $footerMenuItems = $menuService->getMenuByLocation('footer') ?? [];
    $socialMenuItems = $menuService->getMenuByLocation('social') ?? [];

    // CTA button from Settings (fallback to defaults)
    $ctaText = \App\Models\Setting::get('navbar_cta_text', 'general') ?: 'Daftar Jadi Agen';
    $ctaUrl  = \App\Models\Setting::get('navbar_cta_url',  'general') ?: url('/kontak');
@endphp
<body class="min-h-screen bg-white flex flex-col font-sans text-slate-700 antialiased theme-{{ $themeSlug }}"
      x-data="{ mobileMenuOpen: false, scrolled: false }"
      x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 }, { passive: true })">

{{-- ===== NAVBAR: load per-theme partial if exists, else default ===== --}}
@if(View::exists('frontend.layouts.partials.navbar-' . $themeSlug))
    @include('frontend.layouts.partials.navbar-' . $themeSlug)
@else
    @include('frontend.layouts.partials.navbar')
@endif

{{-- Main Content --}}
<main role="main" class="flex-1">
    @yield('content')
</main>

{{-- ===== FOOTER: load per-theme partial if exists, else default ===== --}}
@if(View::exists('frontend.layouts.partials.footer-' . $themeSlug))
    @include('frontend.layouts.partials.footer-' . $themeSlug)
@else
    @include('frontend.layouts.partials.footer')
@endif
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
                @if(!empty($ctaText))
                <a href="{{ $ctaUrl }}"
                   class="hidden lg:inline-flex items-center gap-2 px-4 py-2.5 bg-secondary text-white text-sm font-bold rounded-xl hover:bg-secondary-dark transition-all duration-200 shadow-md shadow-secondary/25 hover:-translate-y-0.5">
                    {{ $ctaText }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @endif
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
        @if(!empty($ctaText))
        <div class="px-5 py-5 border-t border-slate-100">
            <a href="{{ $ctaUrl }}" @click="mobileMenuOpen = false"
               class="flex items-center justify-center gap-2 w-full px-5 py-3.5 bg-secondary text-white text-sm font-bold rounded-xl hover:bg-secondary-dark transition-all duration-200 shadow-md shadow-secondary/25">
                {{ $ctaText }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        @endif
    </nav>
</header>

{{-- Main Content --}}
<main role="main" class="flex-1">
    @yield('content')
</main>

{{-- Footer --}}


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
