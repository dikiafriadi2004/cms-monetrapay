<!DOCTYPE html>
<html lang="id" class="scroll-smooth" style="background:#111">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'MonetraPay')</title>
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Roboto:wght@300;400&display=swap" rel="stylesheet">
    @hasSection('seo') @yield('seo') @endif
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--theme-primary:#1a1a1a;--theme-secondary:#FFD700}
        html,body{background:#111!important;color:#e5e5e5}
        body{font-family:'Roboto',sans-serif;font-weight:300}
        h1,h2,h3,h4,h5{font-family:'Oswald',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:0.05em;color:white}
        /* Gold accent */
        .gold{color:#FFD700}
        .gold-border{border-color:#FFD700}
        .bg-white,.bg-slate-50{background:#1a1a1a!important;color:#e5e5e5!important}
        .text-slate-700,.text-slate-800{color:#d1d5db!important}
        .text-slate-500,.text-slate-600{color:#9ca3af!important}
        .border-slate-100,.border-slate-200{border-color:rgba(255,255,255,0.08)!important}
        section{background:#111!important}
        section:nth-child(even){background:#1a1a1a!important}
        /* Gold underline on section titles */
        h2::after{content:'';display:block;width:3rem;height:2px;background:#FFD700;margin-top:0.5rem}
        .text-center h2::after{margin:0.5rem auto 0}
    </style>
</head>
<body class="min-h-screen flex flex-col" x-data="{mob:false,scrolled:false}"
      x-init="window.addEventListener('scroll',()=>{scrolled=window.scrollY>20},{passive:true})">

{{-- ── NAVBAR: black with gold accent ── --}}
<header class="sticky top-0 z-50 transition-all"
        :style="scrolled ? 'background:#000;box-shadow:0 2px 32px rgba(0,0,0,0.8)' : 'background:#111'"
        style="border-bottom:1px solid rgba(255,215,0,0.2)">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="font-bold text-xl gold" style="font-family:'Oswald',sans-serif;letter-spacing:0.08em">
                @php $logo = \App\Models\Setting::get('logo','general'); @endphp
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-9 w-auto" alt="{{ $siteName }}" style="filter:brightness(1.5)">
                @else {{ $siteName ?? 'MonetraPay' }} @endif
            </a>
            <nav class="hidden lg:flex items-center gap-6">
                @foreach($primaryMenu ?? [] as $item)
                @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}"
                   class="text-xs font-bold uppercase tracking-widest transition-colors relative"
                   style="color:{{ $active ? '#FFD700' : 'rgba(255,255,255,0.6)' }}">
                    {{ $item['label'] }}
                    @if($active)<span class="absolute -bottom-1 left-0 w-full h-0.5" style="background:#FFD700"></span>@endif
                </a>
                @endforeach
            </nav>
            @php $ctaText=\App\Models\Setting::get('navbar_cta_text','general')?:'Hubungi Kami'; $ctaUrl=\App\Models\Setting::get('navbar_cta_url','general')?:url('/kontak'); @endphp
            @if($ctaText)
            <a href="{{ $ctaUrl }}"
               class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold uppercase tracking-wider transition-all hover:-translate-y-0.5"
               style="border:1px solid #FFD700;color:#FFD700;background:transparent"
               onmouseover="this.style.background='#FFD700';this.style.color='#111'"
               onmouseout="this.style.background='transparent';this.style.color='#FFD700'">
                {{ $ctaText }}
            </a>
            @endif
            <button @click="mob=!mob" class="lg:hidden p-2 gold">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mob" x-cloak class="lg:hidden px-4 py-4 space-y-1" style="background:#0a0a0a;border-top:1px solid rgba(255,215,0,0.15)">
        @foreach($primaryMenu ?? [] as $item)
        <a href="{{ $item['url'] }}" @click="mob=false" class="block py-2.5 text-xs font-bold uppercase tracking-widest"
           style="color:rgba(255,255,255,0.6)">{{ $item['label'] }}</a>
        @endforeach
    </div>
</header>

<main class="flex-1">@yield('content')</main>

{{-- ── FOOTER: black with gold ── --}}
<footer style="background:#000;border-top:1px solid rgba(255,215,0,0.2)">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <p class="font-bold text-xl mb-3 gold" style="font-family:'Oswald',sans-serif;letter-spacing:0.08em">{{ $siteName ?? 'MonetraPay' }}</p>
                <p class="text-sm font-light" style="color:rgba(255,255,255,0.35)">{{ $footerSettings['tagline'] ?? '' }}</p>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4 gold">Navigasi</h4>
                <ul class="space-y-2">
                    @foreach($footerMenuItems ?? [] as $item)
                    <li><a href="{{ $item['url'] }}" class="text-xs font-light uppercase tracking-wider transition-colors"
                           style="color:rgba(255,255,255,0.35)"
                           onmouseover="this.style.color='#FFD700'" onmouseout="this.style.color='rgba(255,255,255,0.35)'">
                        {{ $item['label'] }}
                    </a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4 gold">Kontak</h4>
                @if(!empty($footerSettings['contact_email']))<p class="text-xs font-light" style="color:rgba(255,255,255,0.35)">{{ $footerSettings['contact_email'] }}</p>@endif
            </div>
        </div>
        <p class="text-xs text-center" style="border-top:1px solid rgba(255,215,0,0.1);padding-top:1rem;color:rgba(255,255,255,0.2)">
            {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
        </p>
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
