{{-- Classic Business Navbar: 3-row traditional (topbar + logo + green nav) --}}
@php $logo = \App\Models\Setting::get('logo','general'); @endphp

{{-- Top info bar --}}
<div style="background:var(--theme-primary,#1A5C38)">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between py-1.5">
        <span class="text-xs font-light" style="color:rgba(255,255,255,0.6)">Terpercaya Sejak 2020</span>
        <div class="flex items-center gap-4 text-xs" style="color:rgba(255,255,255,0.6)">
            @if(!empty($footerSettings['contact_email']))<span>✉ {{ $footerSettings['contact_email'] }}</span>@endif
            @if(!empty($footerSettings['contact_phone']))<span>☎ {{ $footerSettings['contact_phone'] }}</span>@endif
        </div>
    </div>
</div>

{{-- Logo bar --}}
<div class="bg-white" style="border-bottom:2px solid #e8e0d0">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">
        <a href="{{ url('/') }}" class="font-black text-2xl" style="font-family:'Merriweather',Georgia,serif;color:var(--theme-primary,#1A5C38)">
            @if($logo) <img src="{{ Storage::url($logo) }}" class="h-12 w-auto" alt="{{ $siteName }}">
            @else {{ $siteName ?? 'MonetraPay' }} @endif
        </a>
        @if(!empty($ctaText))
        <a href="{{ $ctaUrl }}" class="hidden lg:inline-flex items-center gap-2 px-6 py-3 text-sm font-bold text-white transition-all"
           style="background:var(--theme-secondary,#8B4513);border:2px solid var(--theme-secondary,#8B4513)"
           onmouseover="this.style.background='transparent';this.style.color='var(--theme-secondary,#8B4513)'"
           onmouseout="this.style.background='var(--theme-secondary,#8B4513)';this.style.color='white'">
            {{ $ctaText }}
        </a>
        @endif
    </div>
</div>

{{-- Nav bar --}}
<header class="sticky top-0 z-50 transition-all" :class="scrolled ? 'shadow-md' : ''"
        style="background:var(--theme-primary,#1A5C38);border-bottom:3px solid var(--theme-secondary,#8B4513)">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
        <nav class="hidden lg:flex items-center">
            @foreach($primaryMenu as $item)
            @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
            <a href="{{ $item['url'] }}" class="px-5 py-3 text-xs font-bold uppercase tracking-wider transition-colors"
               style="{{ $active ? 'background:rgba(255,255,255,0.15);color:white' : 'color:rgba(255,255,255,0.75)' }}">
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>
        <button @click="mobileMenuOpen=true" class="lg:hidden p-3 text-white my-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
        <div class="absolute inset-0 bg-black/50" @click="mobileMenuOpen=false"></div>
        <div class="relative w-72 h-full overflow-y-auto" style="background:var(--theme-primary,#1A5C38)">
            <button @click="mobileMenuOpen=false" class="absolute top-4 right-4 p-2 text-white/70">✕</button>
            <div class="pt-14 pb-4">
                @foreach($primaryMenu as $item)
                <a href="{{ $item['url'] }}" @click="mobileMenuOpen=false" class="block px-6 py-2.5 text-xs font-bold uppercase tracking-wider border-b" style="color:rgba(255,255,255,0.75);border-color:rgba(255,255,255,0.1)">{{ $item['label'] }}</a>
                @endforeach
            </div>
        </div>
    </div>
</header>
