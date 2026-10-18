<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'MonetraPay')</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @hasSection('seo') @yield('seo') @endif
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--theme-primary:#27AE60;--theme-secondary:#F39C12}
        body{font-family:'Roboto',sans-serif;font-size:0.9375rem;background:#f3f4f6;color:#1f2937}
        h1,h2,h3,h4,h5{font-family:'Roboto',sans-serif;font-weight:700;color:#111827}
        section{background:white;margin-bottom:0.375rem}
        section:nth-child(even){background:#fafafa}
    </style>
</head>
<body class="min-h-screen flex flex-col" x-data="{mob:false,scrolled:false}"
      x-init="window.addEventListener('scroll',()=>{scrolled=window.scrollY>10},{passive:true})">

{{-- ── TOP BAR ── --}}
<div style="background:#27AE60">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between py-1.5 text-xs text-white/80">
        <span>🚀 Transaksi real-time • Support 24/7</span>
        @if(!empty($footerSettings['contact_phone']))
        <span>📞 {{ $footerSettings['contact_phone'] }}</span>
        @endif
    </div>
</div>

{{-- ── NAVBAR: compact with search-style ── --}}
<header class="bg-white z-50 sticky top-0 transition-all"
        :class="scrolled ? 'shadow-md' : ''"
        style="border-bottom:2px solid #27AE60">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-14 gap-4">
            <a href="{{ url('/') }}" class="font-black text-xl shrink-0" style="color:#27AE60;font-family:'Roboto',sans-serif">
                @php $logo = \App\Models\Setting::get('logo','general'); @endphp
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-8 w-auto" alt="{{ $siteName }}">
                @else {{ $siteName ?? 'MonetraPay' }} @endif
            </a>
            <nav class="hidden lg:flex items-center gap-0 border border-gray-200 rounded-lg overflow-hidden">
                @foreach($primaryMenu ?? [] as $item)
                @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}"
                   class="px-4 py-2 text-xs font-bold uppercase tracking-wide border-r last:border-r-0 transition-colors"
                   style="{{ $active ? 'background:#27AE60;color:white' : 'color:#4B5563;border-color:#e5e7eb' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            @php $ctaText=\App\Models\Setting::get('navbar_cta_text','general')?:'Daftar Agen'; $ctaUrl=\App\Models\Setting::get('navbar_cta_url','general')?:url('/kontak'); @endphp
            @if($ctaText)
            <a href="{{ $ctaUrl }}"
               class="hidden lg:inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-bold text-white shrink-0"
               style="background:#F39C12">
                {{ $ctaText }}
            </a>
            @endif
            <button @click="mob=!mob" class="lg:hidden p-2" style="color:#27AE60">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mob" x-cloak class="lg:hidden bg-white px-4 pb-3 border-t" style="border-color:#e5e7eb">
        @foreach($primaryMenu ?? [] as $item)
        <a href="{{ $item['url'] }}" @click="mob=false" class="block py-2.5 text-sm font-bold border-b"
           style="color:#1f2937;border-color:#f3f4f6">{{ $item['label'] }}</a>
        @endforeach
    </div>
</header>

<main class="flex-1 bg-gray-100">
    <div class="max-w-7xl mx-auto">
        @yield('content')
    </div>
</main>

{{-- ── FOOTER: dark with orange border ── --}}
<footer style="background:#1f2937;border-top:4px solid #F39C12">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
            <div class="col-span-2">
                <p class="font-black text-lg mb-2" style="color:white">{{ $siteName ?? 'MonetraPay' }}</p>
                <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['tagline'] ?? '' }}</p>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#27AE60">Menu</h4>
                <ul class="space-y-1.5">
                    @foreach($footerMenuItems ?? [] as $item)
                    <li><a href="{{ $item['url'] }}" class="text-xs transition-colors" style="color:rgba(255,255,255,0.45)"
                           onmouseover="this.style.color='#27AE60'" onmouseout="this.style.color='rgba(255,255,255,0.45)'">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#F39C12">Kontak</h4>
                @if(!empty($footerSettings['contact_email']))<p class="text-xs mb-1" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_email'] }}</p>@endif
                @if(!empty($footerSettings['contact_phone']))<p class="text-xs" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_phone'] }}</p>@endif
            </div>
        </div>
        <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.08);padding-top:1rem;color:rgba(255,255,255,0.25)">
            {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
        </p>
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
