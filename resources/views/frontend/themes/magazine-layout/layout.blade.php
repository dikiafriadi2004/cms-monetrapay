<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'MonetraPay')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Source+Sans+3:wght@300;400;600;700&display=swap" rel="stylesheet">
    @hasSection('seo') @yield('seo') @endif
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--theme-primary:#C0392B;--theme-secondary:#2C3E50}
        body{font-family:'Source Sans 3','Helvetica Neue',sans-serif;background:white;color:#1a1a1a}
        h1,h2,h3{font-family:'Playfair Display',Georgia,serif;font-weight:900;color:#1a1a1a}
        h4,h5,h6{font-family:'Source Sans 3',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;font-size:0.8rem;color:#C0392B}
        .mag-rule{width:3rem;height:3px;background:#C0392B;display:block;margin:.5rem 0 1rem}
    </style>
</head>
<body class="min-h-screen flex flex-col" x-data="{mob:false,scrolled:false}"
      x-init="window.addEventListener('scroll',()=>{scrolled=window.scrollY>40},{passive:true})">

{{-- ── HEADER: top stripe + masthead ── --}}
<div style="background:#C0392B;height:4px"></div>
<header class="bg-white sticky top-0 z-50 transition-all" :class="scrolled ? 'shadow-md' : ''"
        style="border-bottom:1px solid #e0e0e0">
    {{-- Top bar: date + utilities --}}
    <div style="background:#2C3E50">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between py-1.5">
            <span class="text-xs text-white/60">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
            <div class="flex items-center gap-4">
                @php $ctaText=\App\Models\Setting::get('navbar_cta_text','general')?:'Daftar Agen'; $ctaUrl=\App\Models\Setting::get('navbar_cta_url','general')?:url('/kontak'); @endphp
                @if($ctaText)
                <a href="{{ $ctaUrl }}" class="text-xs font-bold text-white hover:text-red-300 transition-colors uppercase tracking-wider">{{ $ctaText }}</a>
                @endif
            </div>
        </div>
    </div>
    {{-- Masthead --}}
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <a href="{{ url('/') }}" class="font-black text-2xl" style="font-family:'Playfair Display',serif;color:#1a1a1a">
            @php $logo = \App\Models\Setting::get('logo','general'); @endphp
            @if($logo) <img src="{{ Storage::url($logo) }}" class="h-10 w-auto" alt="{{ $siteName }}">
            @else {{ $siteName ?? 'MonetraPay' }} @endif
        </a>
        {{-- Main Nav --}}
        <nav class="hidden lg:flex items-center gap-0 border-l border-r" style="border-color:#e0e0e0">
            @foreach($primaryMenu ?? [] as $item)
            @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
            <a href="{{ $item['url'] }}"
               class="px-5 py-1.5 text-xs font-bold uppercase tracking-wider transition-colors border-l first:border-l-0"
               style="color:{{ $active ? '#C0392B' : '#1a1a1a' }};border-color:#e0e0e0;{{ $active ? 'background:#FFF5F5' : '' }}"
               onmouseover="if(!{{ $active ? 'true':'false' }})this.style.background='#FFF5F5';this.style.color='#C0392B'"
               onmouseout="if(!{{ $active ? 'true':'false' }})this.style.background='transparent';this.style.color='#1a1a1a'">
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>
        <button @click="mob=!mob" class="lg:hidden p-2" style="color:#1a1a1a">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
    {{-- Mobile nav --}}
    <div x-show="mob" x-cloak class="lg:hidden px-6 py-3" style="border-top:1px solid #e0e0e0">
        @foreach($primaryMenu ?? [] as $item)
        <a href="{{ $item['url'] }}" @click="mob=false" class="block py-2 text-sm font-bold uppercase tracking-wider border-b"
           style="color:#1a1a1a;border-color:#f0f0f0">{{ $item['label'] }}</a>
        @endforeach
    </div>
</header>

<main class="flex-1 bg-white">@yield('content')</main>

{{-- ── FOOTER: dark with red accent ── --}}
<footer style="background:#1a1a1a;border-top:4px solid #C0392B">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="md:col-span-2">
                <div class="font-black text-2xl mb-2" style="font-family:'Playfair Display',serif;color:white">
                    {{ $siteName ?? 'MonetraPay' }}
                </div>
                <span class="mag-rule" style="background:#C0392B"></span>
                <p class="text-sm leading-relaxed mt-2" style="color:rgba(255,255,255,0.5)">{{ $footerSettings['tagline'] ?? '' }}</p>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#C0392B">Navigasi</h4>
                <ul class="space-y-2">
                    @foreach($footerMenuItems ?? [] as $item)
                    <li><a href="{{ $item['url'] }}" class="text-xs font-semibold uppercase tracking-wide transition-colors"
                           style="color:rgba(255,255,255,0.5)"
                           onmouseover="this.style.color='#C0392B'" onmouseout="this.style.color='rgba(255,255,255,0.5)'">
                        {{ $item['label'] }}
                    </a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#C0392B">Kontak</h4>
                @if(!empty($footerSettings['contact_email']))
                <p class="text-xs" style="color:rgba(255,255,255,0.5)">{{ $footerSettings['contact_email'] }}</p>
                @endif
            </div>
        </div>
        <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.08);padding-top:1.5rem;color:rgba(255,255,255,0.25)">
            {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
        </p>
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
