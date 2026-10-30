{{-- Dark Mode Elegant Navbar: full glassmorphism, dark bg --}}
@php $logo = \App\Models\Setting::get('logo','general'); @endphp
<header class="sticky top-0 z-50 transition-all duration-300"
        :style="scrolled ? 'background:rgba(8,8,16,0.95);box-shadow:0 4px 32px rgba(0,0,0,0.6)' : 'background:rgba(8,8,16,0.7)'"
        style="backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);border-bottom:1px solid rgba(255,255,255,0.07)">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="font-bold text-lg shrink-0" style="font-family:'Poppins',sans-serif">
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-9 w-auto" alt="{{ $siteName }}" style="filter:brightness(1.3)">
                @else <span style="color:var(--theme-primary,#9F7AEA)">{{ $siteName ?? 'MonetraPay' }}</span> @endif
            </a>
            <nav class="hidden lg:flex items-center gap-1">
                @foreach($primaryMenu as $item)
                @php $cp=request()->path();$p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/');$p=ltrim($p,'/');$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all"
                   style="{{ $active ? 'background:rgba(159,122,234,0.15);color:var(--theme-primary,#9F7AEA)' : 'color:rgba(226,232,240,0.7)' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            @if(!empty($ctaText))
            <a href="{{ $ctaUrl }}" class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white"
               style="background:var(--theme-primary,#9F7AEA);box-shadow:0 0 24px rgba(159,122,234,0.4)">
                {{ $ctaText }}
            </a>
            @endif
            <button @click="mobileMenuOpen=true" class="lg:hidden p-2 rounded-lg" style="color:rgba(255,255,255,0.7)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
        <div class="absolute inset-0 bg-black/70" @click="mobileMenuOpen=false"></div>
        <div class="relative w-72 h-full p-6 space-y-1 overflow-y-auto" style="background:#0e0e1a;border-right:1px solid rgba(255,255,255,0.07)">
            <button @click="mobileMenuOpen=false" class="absolute top-4 right-4 p-2" style="color:rgba(255,255,255,0.5)">✕</button>
            @foreach($primaryMenu as $item)
            <a href="{{ $item['url'] }}" @click="mobileMenuOpen=false" class="block px-4 py-2.5 rounded-xl text-sm font-medium" style="color:rgba(226,232,240,0.7)">{{ $item['label'] }}</a>
            @endforeach
            @if(!empty($ctaText))
            <a href="{{ $ctaUrl }}" class="block mt-3 px-4 py-3 rounded-xl text-sm font-bold text-white text-center"
               style="background:var(--theme-primary,#9F7AEA)">{{ $ctaText }}</a>
            @endif
        </div>
    </div>
</header>
