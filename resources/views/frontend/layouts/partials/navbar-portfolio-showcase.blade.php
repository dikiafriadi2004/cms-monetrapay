{{-- Portfolio Showcase Navbar: black bg, gold accents, Oswald uppercase --}}
@php $logo = \App\Models\Setting::get('logo','general'); @endphp
<header class="sticky top-0 z-50 transition-all" :style="scrolled ? 'box-shadow:0 2px 32px rgba(0,0,0,0.8)' : ''"
        style="background:#000;border-bottom:1px solid rgba(255,215,0,0.2)">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="font-bold text-xl shrink-0" style="font-family:'Oswald',sans-serif;letter-spacing:0.08em;color:#FFD700">
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-9 w-auto" alt="{{ $siteName }}" style="filter:brightness(1.5)">
                @else {{ $siteName ?? 'MonetraPay' }} @endif
            </a>
            <nav class="hidden lg:flex items-center gap-6">
                @foreach($primaryMenu as $item)
                @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}" class="text-xs font-bold uppercase tracking-widest transition-colors relative"
                   style="color:{{ $active ? '#FFD700' : 'rgba(255,255,255,0.6)' }}">
                    {{ $item['label'] }}
                    @if($active)<span class="absolute -bottom-1 left-0 w-full h-0.5" style="background:#FFD700"></span>@endif
                </a>
                @endforeach
            </nav>
            @if(!empty($ctaText))
            <a href="{{ $ctaUrl }}" class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 text-sm font-bold uppercase tracking-wider transition-all"
               style="border:1px solid #FFD700;color:#FFD700"
               onmouseover="this.style.background='#FFD700';this.style.color='#111'"
               onmouseout="this.style.background='transparent';this.style.color='#FFD700'">
                {{ $ctaText }}
            </a>
            @endif
            <button @click="mobileMenuOpen=true" class="lg:hidden p-2" style="color:#FFD700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
        <div class="absolute inset-0 bg-black/80" @click="mobileMenuOpen=false"></div>
        <div class="relative w-72 h-full p-6 overflow-y-auto" style="background:#0a0a0a;border-right:1px solid rgba(255,215,0,0.15)">
            <button @click="mobileMenuOpen=false" class="absolute top-4 right-4 p-2" style="color:rgba(255,255,255,0.5)">✕</button>
            <div class="font-bold text-xl mb-6" style="font-family:'Oswald',sans-serif;color:#FFD700">{{ $siteName }}</div>
            @foreach($primaryMenu as $item)
            <a href="{{ $item['url'] }}" @click="mobileMenuOpen=false" class="block py-2.5 text-xs font-bold uppercase tracking-widest border-b" style="color:rgba(255,255,255,0.6);border-color:rgba(255,255,255,0.06)">{{ $item['label'] }}</a>
            @endforeach
        </div>
    </div>
</header>
