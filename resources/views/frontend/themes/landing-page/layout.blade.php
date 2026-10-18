<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'MonetraPay')</title>
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;600;700;800;900&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    @hasSection('seo') @yield('seo') @endif
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--theme-primary:#FF5722;--theme-secondary:#00BCD4}
        body{font-family:'Open Sans',sans-serif;background:white}
        h1,h2,h3,h4,h5{font-family:'Raleway',sans-serif;font-weight:900}
        /* Transparent header — sits OVER first section */
        .lp-header{position:fixed;width:100%;top:0;z-index:50;transition:all .3s}
        .lp-header.scrolled{background:rgba(255,255,255,0.96);backdrop-filter:blur(12px);box-shadow:0 2px 24px rgba(255,87,34,0.12)}
        /* Push content below fixed header */
        .lp-main > *:first-child{padding-top:80px}
    </style>
</head>
<body class="min-h-screen flex flex-col" x-data="{mob:false,scrolled:false}"
      x-init="window.addEventListener('scroll',()=>{scrolled=window.scrollY>60},{passive:true})">

{{-- ── NAVBAR: fixed transparent → solid on scroll ── --}}
<header class="lp-header" :class="scrolled ? 'scrolled' : ''">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="font-black text-xl">
                @php $logo = \App\Models\Setting::get('logo','general'); @endphp
                @if($logo)
                    <img src="{{ Storage::url($logo) }}" class="h-9 w-auto" alt="{{ $siteName }}"
                         :style="scrolled ? '' : 'filter:brightness(10) saturate(0)'">
                @else
                    <span :style="scrolled ? 'color:#FF5722' : 'color:white'" style="font-family:'Raleway',sans-serif">{{ $siteName ?? 'MonetraPay' }}</span>
                @endif
            </a>
            <nav class="hidden lg:flex items-center gap-1">
                @foreach($primaryMenu ?? [] as $item)
                @php $cp=request()->path();$p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp);$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}"
                   class="px-4 py-2 rounded-lg text-sm font-bold transition-all"
                   :style="scrolled ? '{{ $active ? 'color:#FF5722' : 'color:#374151' }}' : 'color:rgba(255,255,255,0.9)'">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            @php $ctaText=\App\Models\Setting::get('navbar_cta_text','general')?:'Mulai Sekarang'; $ctaUrl=\App\Models\Setting::get('navbar_cta_url','general')?:url('/kontak'); @endphp
            @if($ctaText)
            <a href="{{ $ctaUrl }}"
               class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-black text-white transition-all hover:-translate-y-0.5 hover:shadow-xl"
               style="background:linear-gradient(135deg,#FF5722,#FF8A65)">
                {{ $ctaText }} 🚀
            </a>
            @endif
            <button @click="mob=!mob" class="lg:hidden p-2" :style="scrolled ? 'color:#FF5722' : 'color:white'">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mob" x-cloak class="lg:hidden bg-white shadow-xl px-4 py-4 space-y-1">
        @foreach($primaryMenu ?? [] as $item)
        <a href="{{ $item['url'] }}" @click="mob=false" class="block px-4 py-2.5 rounded-xl text-sm font-bold" style="color:#374151">{{ $item['label'] }}</a>
        @endforeach
        @if($ctaText)
        <a href="{{ $ctaUrl }}" class="block mt-2 px-4 py-3 rounded-full text-sm font-black text-white text-center"
           style="background:linear-gradient(135deg,#FF5722,#FF8A65)">{{ $ctaText }}</a>
        @endif
    </div>
</header>

<main class="flex-1 lp-main">@yield('content')</main>

{{-- ── FOOTER: simple dark ── --}}
<footer style="background:#0f172a">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <p class="font-black text-xl mb-2" style="font-family:'Raleway',sans-serif;color:#FF5722">{{ $siteName ?? 'MonetraPay' }}</p>
                <p class="text-sm" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['tagline'] ?? '' }}</p>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#FF5722">Navigasi</h4>
                <ul class="space-y-2">
                    @foreach($footerMenuItems ?? [] as $item)
                    <li><a href="{{ $item['url'] }}" class="text-sm" style="color:rgba(255,255,255,0.45)">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#00BCD4">Hubungi Kami</h4>
                @if($ctaText)<a href="{{ $ctaUrl }}" class="inline-block px-5 py-2.5 rounded-full text-sm font-black text-white mt-2"
                   style="background:linear-gradient(135deg,#FF5722,#FF8A65)">{{ $ctaText }}</a>@endif
            </div>
        </div>
        <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.06);padding-top:1rem;color:rgba(255,255,255,0.2)">
            {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
        </p>
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
