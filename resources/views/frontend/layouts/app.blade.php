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