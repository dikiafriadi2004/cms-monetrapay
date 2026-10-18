<!DOCTYPE html>
<html lang="id" class="scroll-smooth" style="background:#080810">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'MonetraPay')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Nunito:wght@300;400;600&display=swap" rel="stylesheet">
    @hasSection('seo') @yield('seo') @endif
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--theme-primary:#9F7AEA;--theme-secondary:#63B3ED}
        html,body{background:#080810!important;color:#e2e8f0!important}
        body{font-family:'Nunito',sans-serif}
        h1,h2,h3,h4,h5{font-family:'Poppins',sans-serif;font-weight:700;color:#f0f4ff!important}
        /* Glassmorphism card */
        .glass{background:rgba(255,255,255,0.04);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,0.08);border-radius:1.25rem}
        /* Glow effect on primary */
        .glow-btn{box-shadow:0 0 24px rgba(159,122,234,0.4)}
        a{color:#9F7AEA}
        a:hover{color:#63B3ED}
        /* Force dark on all standard bg classes */
        .bg-white,.bg-slate-50{background:#12121e!important;color:#e2e8f0!important}
        .text-slate-800,.text-slate-700{color:#e2e8f0!important}
        .text-slate-600,.text-slate-500{color:#a0aec0!important}
        .border-slate-100,.border-slate-200{border-color:rgba(255,255,255,0.08)!important}
        section{background:#080810!important}
        section:nth-child(even){background:#0e0e1a!important}
    </style>
</head>
<body class="min-h-screen flex flex-col" x-data="{mob:false,scrolled:false}"
      x-init="window.addEventListener('scroll',()=>{scrolled=window.scrollY>30},{passive:true})">

{{-- ── NAVBAR: glassmorphism floating ── --}}
<header class="sticky top-0 z-50 transition-all duration-300"
        :style="scrolled ? 'background:rgba(8,8,16,0.92);backdrop-filter:blur(20px);border-bottom:1px solid rgba(159,122,234,0.2);box-shadow:0 4px 32px rgba(0,0,0,0.5)' : 'background:rgba(8,8,16,0.6);backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,0.06)'">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="font-bold text-lg" style="font-family:'Poppins',sans-serif">
                @php $logo = \App\Models\Setting::get('logo','general'); @endphp
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-9 w-auto" alt="{{ $siteName }}" style="filter:brightness(1.3)">
                @else <span style="background:linear-gradient(135deg,#9F7AEA,#63B3ED);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">{{ $siteName ?? 'MonetraPay' }}</span> @endif
            </a>
            <nav class="hidden lg:flex items-center gap-1">
                @foreach($primaryMenu ?? [] as $item)
                @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}"
                   class="px-4 py-2 rounded-xl text-sm font-medium transition-all"
                   style="color:{{ $active ? '#9F7AEA' : 'rgba(226,232,240,0.7)' }};{{ $active ? 'background:rgba(159,122,234,0.12)' : '' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            @php $ctaText=\App\Models\Setting::get('navbar_cta_text','general')?:'Mulai Sekarang'; $ctaUrl=\App\Models\Setting::get('navbar_cta_url','general')?:url('/kontak'); @endphp
            @if($ctaText)
            <a href="{{ $ctaUrl }}"
               class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all glow-btn hover:shadow-2xl"
               style="background:linear-gradient(135deg,#9F7AEA,#63B3ED)">
                {{ $ctaText }}
            </a>
            @endif
            <button @click="mob=!mob" class="lg:hidden p-2 rounded-lg" style="color:rgba(255,255,255,0.7)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mob" x-cloak class="lg:hidden px-4 py-4 space-y-1" style="background:#0e0e1a;border-top:1px solid rgba(255,255,255,0.06)">
        @foreach($primaryMenu ?? [] as $item)
        <a href="{{ $item['url'] }}" @click="mob=false"
           class="block px-4 py-2.5 rounded-xl text-sm font-medium"
           style="color:rgba(226,232,240,0.75)">{{ $item['label'] }}</a>
        @endforeach
        @if($ctaText)
        <a href="{{ $ctaUrl }}" class="block mt-2 px-4 py-3 rounded-xl text-sm font-bold text-white text-center"
           style="background:linear-gradient(135deg,#9F7AEA,#63B3ED)">{{ $ctaText }}</a>
        @endif
    </div>
</header>

<main class="flex-1">@yield('content')</main>

{{-- ── FOOTER: ultra dark with glow divider ── --}}
<footer style="background:#050508;border-top:1px solid rgba(159,122,234,0.2)">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-14">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
            <div class="md:col-span-2">
                <div class="font-bold text-xl mb-3" style="font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#9F7AEA,#63B3ED);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">
                    {{ $siteName ?? 'MonetraPay' }}
                </div>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['tagline'] ?? '' }}</p>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#9F7AEA">Navigasi</h4>
                <ul class="space-y-2">
                    @foreach($footerMenuItems ?? [] as $item)
                    <li><a href="{{ $item['url'] }}" class="text-sm transition-colors" style="color:rgba(255,255,255,0.4)"
                           onmouseover="this.style.color='#9F7AEA'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
                        {{ $item['label'] }}
                    </a></li>
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#63B3ED">Kontak</h4>
                @if(!empty($footerSettings['contact_email']))
                <p class="text-sm mb-1" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_email'] }}</p>
                @endif
                @if(!empty($footerSettings['contact_phone']))
                <p class="text-sm" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_phone'] }}</p>
                @endif
            </div>
        </div>
        <div class="text-center text-xs" style="border-top:1px solid rgba(255,255,255,0.05);padding-top:1.5rem;color:rgba(255,255,255,0.2)">
            {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
        </div>
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
