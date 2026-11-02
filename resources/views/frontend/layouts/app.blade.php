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
    @php
        $needGoogleFonts = true;
        $fontsToLoad = 'Plus+Jakarta+Sans:wght@300;400;500;600;700;800';
    @endphp
    @if($needGoogleFonts && $fontsToLoad)
    <link href="https://fonts.googleapis.com/css2?family={{$fontsToLoad}}&display=swap" rel="stylesheet">
    @endif
    {{-- Preload critical assets --}}
    @php $logoPreload = \App\Models\Setting::get('logo', 'general'); @endphp
    @if($logoPreload)
    <link rel="preload" href="{{ Storage::url($logoPreload) }}" as="image">
    @elseif(file_exists(public_path('assets/images/logo.png')))
    <link rel="preload" href="/assets/images/logo.png" as="image">
    @endif
    @php
        $faviconBase = \App\Models\Setting::get('favicon', 'general');
        if ($faviconBase) {
            $favDir = dirname($faviconBase);
            $fav16  = $favDir . '/favicon-16.png';
            $fav32  = $favDir . '/favicon-32.png';
            $fav180 = $favDir . '/favicon-180.png';
            $fav192 = $favDir . '/favicon-192.png';
            $has16  = Storage::disk('public')->exists($fav16);
            $has32  = Storage::disk('public')->exists($fav32);
            $has180 = Storage::disk('public')->exists($fav180);
            $has192 = Storage::disk('public')->exists($fav192);
        }
    @endphp
    @if(!empty($faviconBase))
        @if($has32)<link rel="icon" type="image/png" sizes="32x32" href="{{ Storage::url($fav32) }}">@endif
        @if($has16)<link rel="icon" type="image/png" sizes="16x16" href="{{ Storage::url($fav16) }}">@endif
        @if($has180)<link rel="apple-touch-icon" sizes="180x180" href="{{ Storage::url($fav180) }}">@endif
        @if($has192)<link rel="icon" type="image/png" sizes="192x192" href="{{ Storage::url($fav192) }}">@endif
        @if(!$has32 && !$has16)<link rel="icon" type="image/png" href="{{ Storage::url($faviconBase) }}">@endif
    @else
        {{-- SVG favicon sebagai fallback — ringan dan selalu muncul --}}
        <link rel="icon" href="/assets/images/favicon.svg" type="image/svg+xml">
        <link rel="icon" href="/assets/images/favico.png" type="image/png">
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
        <meta name="robots" content="index, follow">
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
    @php $themeSlug = 'modern-corporate'; @endphp
</head>
@php
    $currentPath = request()->path();
    $isBlog = str_starts_with($currentPath, 'blog');

    // Primary and footer menus are injected by FrontendComposer (via $headerMenu / $footerMenu).
    // Map to the variable names expected by navbar and footer partials.
    $primaryMenu     = $headerMenu  ?? [];
    $footerMenuItems = $footerMenu  ?? [];
    // Social menu items are not provided by FrontendComposer as menu-item arrays;
    // $socialLinks (from FrontendComposer) contains settings values, not menu items.
    // Keep an empty array so footer partials don't error if they reference the variable.
    $socialMenuItems = [];

    // CTA button from Settings (fallback to defaults) — not provided by FrontendComposer
    $ctaText = \App\Models\Setting::get('navbar_cta_text', 'general') ?: 'Daftar Jadi Agen';
    $ctaUrl  = \App\Models\Setting::get('navbar_cta_url',  'general') ?: url('/kontak');
@endphp
<body class="min-h-screen bg-white flex flex-col font-sans text-slate-700 antialiased theme-{{ $themeSlug }}"
      x-data="{ mobileMenuOpen: false, scrolled: false }"
      x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 20 }, { passive: true })">

@include('frontend.layouts.partials.navbar')

{{-- Main Content --}}
<main role="main" class="flex-1 pt-12 lg:pt-14">
    @yield('content')
</main>

@include('frontend.layouts.partials.footer')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')

{{-- Back to Top Button --}}
{{-- ── WhatsApp Floating Button ── --}}
@php
    $waEnabled = \App\Models\Setting::get('whatsapp_float_enabled', 'general');
    $waNumber  = \App\Models\Setting::get('whatsapp_number', 'general');
    $waText    = \App\Models\Setting::get('whatsapp_text', 'general') ?: 'Halo, saya ingin tahu lebih lanjut';
@endphp
@if($waEnabled && $waNumber)
<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waNumber) }}?text={{ urlencode($waText) }}"
   target="_blank"
   rel="noopener noreferrer"
   id="wa-float"
   class="fixed z-50 flex items-center justify-center shadow-xl transition-all duration-300 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2"
   style="bottom:5.5rem;right:1.5rem;width:52px;height:52px;border-radius:50%;background:#25D366"
   aria-label="Chat via WhatsApp"
   title="Chat via WhatsApp">
    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
    </svg>
    {{-- Pulse ring --}}
    <span class="absolute inset-0 rounded-full animate-ping opacity-30" style="background:#25D366"></span>
</a>
@endif

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