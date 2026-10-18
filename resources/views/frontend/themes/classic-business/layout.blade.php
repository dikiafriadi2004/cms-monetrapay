<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'MonetraPay')</title>
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700;900&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    @hasSection('seo') @yield('seo') @endif
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--theme-primary:#1A5C38;--theme-secondary:#8B4513}
        body{font-family:'Lato','Times New Roman',serif;background:#fdfdf8;color:#333;line-height:1.8}
        h1,h2,h3,h4,h5{font-family:'Merriweather',Georgia,serif;font-weight:900;color:#1A5C38;line-height:1.3}
        section:nth-child(even){background:#f5f2eb!important}
        a{color:#1A5C38}
        a:hover{color:#8B4513}
    </style>
</head>
<body class="min-h-screen flex flex-col" x-data="{mob:false,scrolled:false}"
      x-init="window.addEventListener('scroll',()=>{scrolled=window.scrollY>20},{passive:true})">

{{-- ── HEADER: traditional 3-row layout ── --}}
{{-- Top bar --}}
<div style="background:#1A5C38">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between py-2">
        <span class="text-xs font-light text-white/70">Terpercaya Sejak 2020</span>
        <div class="flex items-center gap-4 text-xs text-white/70">
            @if(!empty($footerSettings['contact_email']))
            <span>✉ {{ $footerSettings['contact_email'] }}</span>
            @endif
            @if(!empty($footerSettings['contact_phone']))
            <span>☎ {{ $footerSettings['contact_phone'] }}</span>
            @endif
        </div>
    </div>
</div>

{{-- Logo bar --}}
<div style="background:#fdfdf8;border-bottom:2px solid #e8e0d0">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ url('/') }}" class="font-black text-2xl" style="font-family:'Merriweather',serif;color:#1A5C38">
            @php $logo = \App\Models\Setting::get('logo','general'); @endphp
            @if($logo) <img src="{{ Storage::url($logo) }}" class="h-12 w-auto" alt="{{ $siteName }}">
            @else {{ $siteName ?? 'MonetraPay' }} @endif
        </a>
        @php $ctaText=\App\Models\Setting::get('navbar_cta_text','general')?:'Daftar Menjadi Agen'; $ctaUrl=\App\Models\Setting::get('navbar_cta_url','general')?:url('/kontak'); @endphp
        @if($ctaText)
        <a href="{{ $ctaUrl }}"
           class="hidden lg:inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all"
           style="background:#8B4513;border:2px solid #8B4513"
           onmouseover="this.style.background='transparent';this.style.color='#8B4513'"
           onmouseout="this.style.background='#8B4513';this.style.color='white'">
            {{ $ctaText }}
        </a>
        @endif
    </div>
</div>

{{-- Nav bar --}}
<header class="sticky top-0 z-50 transition-all" :class="scrolled ? 'shadow-md' : ''"
        style="background:#1A5C38;border-bottom:3px solid #8B4513">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between">
            <nav class="hidden lg:flex items-center">
                @foreach($primaryMenu ?? [] as $item)
                @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}"
                   class="px-5 py-3 text-xs font-bold uppercase tracking-wider transition-colors"
                   style="{{ $active ? 'background:rgba(255,255,255,0.15);color:white' : 'color:rgba(255,255,255,0.8)' }}"
                   onmouseover="if(!{{ $active?'true':'false' }})this.style.background='rgba(255,255,255,0.1)';this.style.color='white'"
                   onmouseout="if(!{{ $active?'true':'false' }})this.style.background='transparent';this.style.color='rgba(255,255,255,0.8)'">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            <button @click="mob=!mob" class="lg:hidden p-3 text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mob" x-cloak class="lg:hidden py-2" style="background:#145030;border-top:1px solid rgba(255,255,255,0.1)">
        @foreach($primaryMenu ?? [] as $item)
        <a href="{{ $item['url'] }}" @click="mob=false" class="block px-6 py-2.5 text-xs font-bold uppercase tracking-wider"
           style="color:rgba(255,255,255,0.8)">{{ $item['label'] }}</a>
        @endforeach
    </div>
</header>

<main class="flex-1 bg-slate-50">@yield('content')</main>

{{-- ── FOOTER: forest green ── --}}
<footer style="background:#1A5C38;border-top:4px solid #8B4513">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <div>
                <p class="font-black text-xl mb-3 text-white" style="font-family:'Merriweather',serif">{{ $siteName ?? 'MonetraPay' }}</p>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.5)">{{ $footerSettings['tagline'] ?? '' }}</p>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#8B4513">Navigasi</h4>
                <ul class="space-y-2">
                    @foreach($footerMenuItems ?? [] as $item)
                    <li><a href="{{ $item['url'] }}" class="text-sm transition-colors" style="color:rgba(255,255,255,0.55)"
                           onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.55)'">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#8B4513">Kontak</h4>
                @if(!empty($footerSettings['contact_email']))<p class="text-sm mb-1" style="color:rgba(255,255,255,0.55)">{{ $footerSettings['contact_email'] }}</p>@endif
                @if(!empty($footerSettings['contact_phone']))<p class="text-sm" style="color:rgba(255,255,255,0.55)">{{ $footerSettings['contact_phone'] }}</p>@endif
            </div>
        </div>
        <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.15);padding-top:1rem;color:rgba(255,255,255,0.3)">
            {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
        </p>
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
