{{-- Magazine Layout Navbar: 3-row (topbar + masthead + nav) --}}
@php $logo = \App\Models\Setting::get('logo','general'); @endphp

{{-- Top utility bar --}}
<div style="background:var(--theme-secondary,#2C3E50)">
    <div class="max-w-7xl mx-auto px-6 flex items-center justify-between py-1.5 text-xs" style="color:rgba(255,255,255,0.6)">
        <span>{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        @if(!empty($ctaText))<a href="{{ $ctaUrl }}" class="font-bold uppercase tracking-wider" style="color:white">{{ $ctaText }}</a>@endif
    </div>
</div>

{{-- Masthead --}}
<div class="bg-white" style="border-bottom:1px solid #e0e0e0">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">
        <a href="{{ url('/') }}" class="font-black text-2xl" style="font-family:'Playfair Display',Georgia,serif;color:#1a1a1a">
            @if($logo) <img src="{{ Storage::url($logo) }}" class="h-10 w-auto" alt="{{ $siteName }}">
            @else {{ $siteName ?? 'MonetraPay' }} @endif
        </a>
        <button @click="mobileMenuOpen=true" class="lg:hidden p-2" style="color:#1a1a1a">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
    </div>
</div>

{{-- Navigation bar --}}
<header class="sticky top-0 z-50 bg-white" style="border-top:4px solid var(--theme-primary,#C0392B);border-bottom:1px solid #e0e0e0">
    <div class="max-w-7xl mx-auto px-6">
        <nav class="hidden lg:flex items-center">
            @foreach($primaryMenu as $item)
            @php $cp=request()->path();$p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/');$p=ltrim($p,'/');$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
            <a href="{{ $item['url'] }}"
               class="px-5 py-3 text-xs font-bold uppercase tracking-wider border-r transition-colors"
               style="{{ $active ? 'background:var(--theme-primary,#C0392B);color:white' : 'color:#1a1a1a;border-color:#e0e0e0' }}">
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>
    </div>
    {{-- Mobile --}}
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
        <div class="absolute inset-0 bg-black/50" @click="mobileMenuOpen=false"></div>
        <div class="relative bg-white w-72 h-full py-6 overflow-y-auto">
            <button @click="mobileMenuOpen=false" class="absolute top-4 right-4 p-2 text-slate-500">✕</button>
            <div class="px-6 mb-4 font-black text-xl" style="font-family:'Playfair Display',serif;color:#1a1a1a">{{ $siteName }}</div>
            @foreach($primaryMenu as $item)
            <a href="{{ $item['url'] }}" @click="mobileMenuOpen=false" class="block px-6 py-2.5 text-xs font-bold uppercase tracking-wider border-b" style="color:#1a1a1a;border-color:#f0f0f0">{{ $item['label'] }}</a>
            @endforeach
        </div>
    </div>
</header>
