<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'MonetraPay')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;900&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    @hasSection('seo') @yield('seo') @endif
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root { --theme-primary:#FF6B6B; --theme-secondary:#4ECDC4; }
        body { font-family:'Open Sans',sans-serif; background:#fafafa; }
        h1,h2,h3,h4,h5 { font-family:'Montserrat',sans-serif; font-weight:900; letter-spacing:-0.04em; }
        /* Pill sections */
        .theme-section { border-radius:2rem; overflow:hidden; margin:0.5rem 1rem; }
    </style>
</head>
<body class="min-h-screen flex flex-col" x-data="{mob:false,scrolled:false}"
      x-init="window.addEventListener('scroll',()=>{scrolled=window.scrollY>30},{passive:true})">

{{-- ── NAVBAR: centered logo, colorful underline ── --}}
<header class="sticky top-0 z-50 bg-white transition-all duration-300"
        :class="scrolled ? 'shadow-lg' : ''"
        style="border-bottom:3px solid #FF6B6B">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="font-black text-xl tracking-tight" style="font-family:'Montserrat',sans-serif">
                @php $logo = \App\Models\Setting::get('logo','general'); @endphp
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-10 w-auto" alt="{{ $siteName }}">
                @else <span style="color:#FF6B6B">{{ $siteName ?? 'MonetraPay' }}</span> @endif
            </a>
            <nav class="hidden lg:flex items-center gap-1">
                @foreach($primaryMenu ?? [] as $item)
                @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$currentPath=request()->path());$active=($p===''&&$currentPath==='/')||($p!==''&&str_starts_with($currentPath,$p)); @endphp
                <a href="{{ $item['url'] }}"
                   class="px-4 py-2 rounded-full text-sm font-bold transition-all duration-200"
                   style="{{ $active ? 'background:#FF6B6B;color:white' : 'color:#374151' }}"
                   onmouseover="if(!{{ $active ? 'true' : 'false' }})this.style.background='#FFF0F0';this.style.color='#FF6B6B'"
                   onmouseout="if(!{{ $active ? 'true' : 'false' }})this.style.background='transparent';this.style.color='#374151'">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            @php $ctaText=\App\Models\Setting::get('navbar_cta_text','general')?:'Daftar Sekarang'; $ctaUrl=\App\Models\Setting::get('navbar_cta_url','general')?:url('/kontak'); @endphp
            @if($ctaText)
            <a href="{{ $ctaUrl }}"
               class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-bold text-white transition-all hover:scale-105 hover:shadow-lg"
               style="background:linear-gradient(135deg,#FF6B6B,#4ECDC4)">
                {{ $ctaText }} →
            </a>
            @endif
            <button @click="mob=!mob" class="lg:hidden p-2 rounded-xl" style="color:#FF6B6B">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    {{-- Mobile --}}
    <div x-show="mob" x-cloak class="lg:hidden border-t px-4 py-4 space-y-1 bg-white" style="border-color:#FFDEDE">
        @foreach($primaryMenu ?? [] as $item)
        <a href="{{ $item['url'] }}" @click="mob=false"
           class="block px-4 py-2.5 rounded-2xl text-sm font-bold transition-colors"
           style="color:#374151"
           onmouseover="this.style.background='#FFF0F0';this.style.color='#FF6B6B'"
           onmouseout="this.style.background='transparent';this.style.color='#374151'">
            {{ $item['label'] }}
        </a>
        @endforeach
        @if($ctaText)
        <a href="{{ $ctaUrl }}" class="block mt-2 px-4 py-3 rounded-2xl text-sm font-bold text-white text-center"
           style="background:linear-gradient(135deg,#FF6B6B,#4ECDC4)">{{ $ctaText }}</a>
        @endif
    </div>
</header>

<main class="flex-1">
    {{-- Wrap page builder sections with rounded pill style --}}
    <div class="page-builder-wrapper">
        @yield('content')
    </div>
</main>

{{-- ── FOOTER: gradient background ── --}}
<footer style="background:linear-gradient(135deg,#1a1a2e,#16213e)">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
            <div>
                <div class="font-black text-2xl mb-3" style="font-family:'Montserrat',sans-serif;color:#FF6B6B">
                    {{ $siteName ?? 'MonetraPay' }}
                </div>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.6)">{{ $footerSettings['tagline'] ?? '' }}</p>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#4ECDC4">Navigasi</h4>
                <ul class="space-y-2">
                    @foreach($footerMenuItems ?? [] as $item)
                    <li><a href="{{ $item['url'] }}" class="text-sm transition-colors" style="color:rgba(255,255,255,0.6)"
                           onmouseover="this.style.color='#FF6B6B'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">
                        {{ $item['label'] }}
                    </a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#4ECDC4">Kontak</h4>
                @if(!empty($footerSettings['contact_email']))
                <p class="text-sm mb-1" style="color:rgba(255,255,255,0.6)">{{ $footerSettings['contact_email'] }}</p>
                @endif
                @if(!empty($footerSettings['contact_phone']))
                <p class="text-sm" style="color:rgba(255,255,255,0.6)">{{ $footerSettings['contact_phone'] }}</p>
                @endif
            </div>
        </div>
        <div class="mt-10 pt-6 text-center text-xs" style="border-top:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.3)">
            {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay').'. All rights reserved.' }}
        </div>
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
