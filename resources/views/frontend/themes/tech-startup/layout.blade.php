<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'MonetraPay')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    @hasSection('seo') @yield('seo') @endif
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--theme-primary:#667EEA;--theme-secondary:#764BA2}
        body{font-family:'Inter',sans-serif;background:#f8faff;color:#374151}
        h1,h2,h3,h4,h5{font-family:'Space Grotesk',sans-serif;font-weight:700;background:linear-gradient(135deg,#667EEA,#764BA2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text}
        /* Override gradient text for dark sections */
        .invert-heading h1,.invert-heading h2,.invert-heading h3{background:none;-webkit-text-fill-color:white;color:white}
        section:nth-child(odd){background:linear-gradient(135deg,rgba(102,126,234,0.04),rgba(118,75,162,0.04))}
        section:nth-child(even){background:white}
        .glow-primary{box-shadow:0 4px 24px rgba(102,126,234,0.35)}
    </style>
</head>
<body class="min-h-screen flex flex-col" x-data="{mob:false,scrolled:false}"
      x-init="window.addEventListener('scroll',()=>{scrolled=window.scrollY>20},{passive:true})">

{{-- ── NAVBAR: glassmorphism light, gradient border ── --}}
<header class="sticky top-0 z-50 transition-all duration-300"
        :style="scrolled ? 'background:rgba(248,250,255,0.92);backdrop-filter:blur(20px);box-shadow:0 1px 32px rgba(102,126,234,0.12)' : 'background:rgba(248,250,255,0.7);backdrop-filter:blur(8px)'"
        style="border-bottom:2px solid transparent;background-image:linear-gradient(white,white),linear-gradient(90deg,#667EEA,#764BA2);background-origin:border-box;background-clip:padding-box,border-box">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="font-bold text-lg" style="font-family:'Space Grotesk',sans-serif">
                @php $logo = \App\Models\Setting::get('logo','general'); @endphp
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-9 w-auto" alt="{{ $siteName }}">
                @else <span style="background:linear-gradient(135deg,#667EEA,#764BA2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">{{ $siteName ?? 'MonetraPay' }}</span> @endif
            </a>
            <nav class="hidden lg:flex items-center gap-1">
                @foreach($primaryMenu ?? [] as $item)
                @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all"
                   style="{{ $active ? 'background:rgba(102,126,234,0.1);color:#667EEA;font-weight:600' : 'color:#4B5563' }}"
                   onmouseover="if(!{{ $active?'true':'false' }})this.style.background='rgba(102,126,234,0.08)';this.style.color='#667EEA'"
                   onmouseout="if(!{{ $active?'true':'false' }})this.style.background='transparent';this.style.color='#4B5563'">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            @php $ctaText=\App\Models\Setting::get('navbar_cta_text','general')?:'Mulai Gratis'; $ctaUrl=\App\Models\Setting::get('navbar_cta_url','general')?:url('/kontak'); @endphp
            @if($ctaText)
            <a href="{{ $ctaUrl }}"
               class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all glow-primary hover:-translate-y-0.5"
               style="background:linear-gradient(135deg,#667EEA,#764BA2)">
                {{ $ctaText }} ✦
            </a>
            @endif
            <button @click="mob=!mob" class="lg:hidden p-2 rounded-lg" style="color:#667EEA">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mob" x-cloak class="lg:hidden bg-white px-4 py-4 space-y-1 shadow-lg">
        @foreach($primaryMenu ?? [] as $item)
        <a href="{{ $item['url'] }}" @click="mob=false"
           class="block px-4 py-2.5 rounded-xl text-sm font-medium" style="color:#4B5563">{{ $item['label'] }}</a>
        @endforeach
        @if($ctaText)
        <a href="{{ $ctaUrl }}" class="block mt-2 px-4 py-3 rounded-xl text-sm font-bold text-white text-center"
           style="background:linear-gradient(135deg,#667EEA,#764BA2)">{{ $ctaText }}</a>
        @endif
    </div>
</header>

<main class="flex-1">@yield('content')</main>

{{-- ── FOOTER: ultra dark with gradient accent ── --}}
<footer style="background:#0f0f1a;border-top:3px solid transparent;background-image:linear-gradient(#0f0f1a,#0f0f1a),linear-gradient(90deg,#667EEA,#764BA2);background-origin:border-box;background-clip:padding-box,border-box">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
            <div class="md:col-span-2">
                <div class="font-bold text-xl mb-3" style="font-family:'Space Grotesk',sans-serif;background:linear-gradient(135deg,#667EEA,#764BA2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">
                    {{ $siteName ?? 'MonetraPay' }}
                </div>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['tagline'] ?? '' }}</p>
            </div>
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#667EEA">Navigasi</h4>
                <ul class="space-y-2">
                    @foreach($footerMenuItems ?? [] as $item)
                    <li><a href="{{ $item['url'] }}" class="text-sm transition-colors" style="color:rgba(255,255,255,0.4)"
                           onmouseover="this.style.color='#667EEA'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-widest mb-4" style="color:#764BA2">Kontak</h4>
                @if(!empty($footerSettings['contact_email']))<p class="text-xs mb-1" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_email'] }}</p>@endif
                @if(!empty($footerSettings['contact_phone']))<p class="text-xs" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_phone'] }}</p>@endif
            </div>
        </div>
        <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.06);padding-top:1.5rem;color:rgba(255,255,255,0.2)">
            {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
        </p>
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
