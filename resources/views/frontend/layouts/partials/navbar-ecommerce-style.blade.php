{{-- E-commerce Navbar: topbar + compact nav tabs --}}
@php $logo = \App\Models\Setting::get('logo','general'); @endphp
{{-- Promo topbar --}}
<div style="background:var(--theme-primary,#27AE60)">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between py-1.5 text-xs text-white/80">
        <span>🚀 Transaksi Real-time • Support 24/7</span>
        @if(!empty($footerSettings['contact_phone']))<span>📞 {{ $footerSettings['contact_phone'] }}</span>@endif
    </div>
</div>
{{-- Main navbar --}}
<header class="sticky top-0 z-50 bg-white transition-all" :class="scrolled ? 'shadow-md' : ''" style="border-bottom:2px solid var(--theme-primary,#27AE60)">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-14 gap-4">
            <a href="{{ url('/') }}" class="font-black text-xl shrink-0" style="color:var(--theme-primary,#27AE60);font-family:'Roboto',sans-serif">
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-8 w-auto" alt="{{ $siteName }}">
                @else {{ $siteName ?? 'MonetraPay' }} @endif
            </a>
            {{-- Tab-style nav --}}
            <nav class="hidden lg:flex items-center gap-0 border rounded-lg overflow-hidden" style="border-color:#e5e7eb">
                @foreach($primaryMenu as $item)
                @php $cp=request()->path();$p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/');$p=ltrim($p,'/');$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}" class="px-4 py-2 text-xs font-bold uppercase tracking-wide border-r last:border-r-0 transition-colors"
                   style="{{ $active ? 'background:var(--theme-primary,#27AE60);color:white' : 'color:#4B5563;border-color:#e5e7eb' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            @if(!empty($ctaText))
            <a href="{{ $ctaUrl }}" class="hidden lg:inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-bold text-white shrink-0"
               style="background:var(--theme-secondary,#F39C12)">
                {{ $ctaText }}
            </a>
            @endif
            <button @click="mobileMenuOpen=true" class="lg:hidden p-2" style="color:var(--theme-primary,#27AE60)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
        <div class="absolute inset-0 bg-black/40" @click="mobileMenuOpen=false"></div>
        <div class="relative bg-white w-72 h-full overflow-y-auto shadow-2xl">
            <button @click="mobileMenuOpen=false" class="absolute top-4 right-4 p-2 text-slate-400">✕</button>
            <div class="p-4 border-b" style="background:var(--theme-primary,#27AE60)">
                <span class="font-black text-white">Menu</span>
            </div>
            @foreach($primaryMenu as $item)
            <a href="{{ $item['url'] }}" @click="mobileMenuOpen=false" class="block py-2.5 px-6 text-sm font-bold border-b" style="color:#1f2937;border-color:#f3f4f6">{{ $item['label'] }}</a>
            @endforeach
        </div>
    </div>
</header>
