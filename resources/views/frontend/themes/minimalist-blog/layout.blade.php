<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'MonetraPay')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;0,700;1,400&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
    @hasSection('seo') @yield('seo') @endif
    @yield('meta')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--theme-primary:#2C3E50;--theme-secondary:#E74C3C}
        html{background:#fffef9}
        body{font-family:'Source Sans 3',sans-serif;font-size:1.0625rem;line-height:1.85;color:#2d2d2d;background:#fffef9}
        h1,h2,h3,h4,h5{font-family:'Lora',Georgia,serif;font-weight:700;line-height:1.3;color:#1a1a1a}
        p{margin-bottom:1.5rem}
        a{color:#2C3E50}
        a:hover{color:#E74C3C}
        /* Narrow reading column */
        .reading-column{max-width:42rem;margin:0 auto;padding:0 1.5rem}
        /* Section separator */
        .blog-section{padding:3rem 0;border-bottom:1px solid #e8e0d0}
    </style>
</head>
<body class="min-h-screen flex flex-col" x-data="{mob:false}">

{{-- ── NAVBAR: ultra minimal, thin border, no shadow ── --}}
<header class="sticky top-0 z-50" style="background:#fffef9;border-bottom:1px solid #e8e0d0">
    <div class="reading-column">
        <div class="flex items-center justify-between py-4">
            <a href="{{ url('/') }}" class="font-bold text-lg" style="font-family:'Lora',serif;color:#1a1a1a">
                @php $logo = \App\Models\Setting::get('logo','general'); @endphp
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-8 w-auto" alt="{{ $siteName }}">
                @else {{ $siteName ?? 'MonetraPay' }} @endif
            </a>
            <nav class="hidden lg:flex items-center gap-6">
                @foreach($primaryMenu ?? [] as $item)
                @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}"
                   class="text-sm transition-colors {{ $active ? 'font-semibold' : 'font-normal' }}"
                   style="color:{{ $active ? '#E74C3C' : '#555' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            <button @click="mob=!mob" class="lg:hidden p-2" style="color:#2C3E50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mob" x-cloak class="lg:hidden reading-column py-4 space-y-2" style="border-top:1px solid #e8e0d0">
        @foreach($primaryMenu ?? [] as $item)
        <a href="{{ $item['url'] }}" @click="mob=false" class="block text-sm py-2" style="color:#2C3E50">{{ $item['label'] }}</a>
        @endforeach
    </div>
</header>

{{-- ── MAIN: all content in narrow reading column ── --}}
<main class="flex-1">
    <div class="reading-column py-10">
        @yield('content')
    </div>
</main>

{{-- ── FOOTER: simple 2-col, warm bg ── --}}
<footer style="background:#1a1a1a;border-top:3px solid #E74C3C">
    <div class="reading-column py-10">
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
            <div>
                <p class="font-bold mb-2" style="font-family:'Lora',serif;color:white">{{ $siteName ?? 'MonetraPay' }}</p>
                <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.5)">{{ $footerSettings['tagline'] ?? '' }}</p>
            </div>
            <div>
                <h4 class="text-xs font-semibold uppercase tracking-widest mb-3" style="color:#E74C3C">Halaman</h4>
                <ul class="space-y-1.5">
                    @foreach($footerMenuItems ?? [] as $item)
                    <li><a href="{{ $item['url'] }}" class="text-sm" style="color:rgba(255,255,255,0.55)">{{ $item['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <p class="text-xs text-center" style="color:rgba(255,255,255,0.25);border-top:1px solid rgba(255,255,255,0.08);padding-top:1.5rem">
            {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
        </p>
    </div>
</footer>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@yield('scripts')
</body>
</html>
