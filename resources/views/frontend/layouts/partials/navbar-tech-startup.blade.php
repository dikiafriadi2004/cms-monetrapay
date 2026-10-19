{{-- Tech Startup Navbar: gradient border-bottom, Space Grotesk --}}
@php $logo = \App\Models\Setting::get('logo','general'); @endphp
<header class="sticky top-0 z-50 transition-all duration-300"
        :style="scrolled ? 'box-shadow:0 2px 24px rgba(102,126,234,0.12)' : ''"
        style="background:rgba(248,250,255,0.92);backdrop-filter:blur(12px);border-bottom:2px solid transparent;border-image:linear-gradient(90deg,var(--theme-primary,#667EEA),var(--theme-secondary,#764BA2)) 1">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <a href="{{ url('/') }}" class="font-bold text-lg shrink-0" style="font-family:'Space Grotesk',sans-serif">
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-9 w-auto" alt="{{ $siteName }}">
                @else <span style="background:linear-gradient(135deg,var(--theme-primary,#667EEA),var(--theme-secondary,#764BA2));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">{{ $siteName ?? 'MonetraPay' }}</span> @endif
            </a>
            <nav class="hidden lg:flex items-center gap-1">
                @foreach($primaryMenu as $item)
                @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-all"
                   style="{{ $active ? 'background:rgba(102,126,234,0.1);color:var(--theme-primary,#667EEA);font-weight:600' : 'color:#4B5563' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            @if(!empty($ctaText))
            <a href="{{ $ctaUrl }}" class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white"
               style="background:linear-gradient(135deg,var(--theme-primary,#667EEA),var(--theme-secondary,#764BA2));box-shadow:0 4px 24px rgba(102,126,234,0.35)">
                {{ $ctaText }} ✦
            </a>
            @endif
            <button @click="mobileMenuOpen=true" class="lg:hidden p-2" style="color:var(--theme-primary,#667EEA)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
        <div class="absolute inset-0 bg-black/40" @click="mobileMenuOpen=false"></div>
        <div class="relative bg-white w-72 h-full p-6 space-y-1 overflow-y-auto shadow-2xl">
            <button @click="mobileMenuOpen=false" class="absolute top-4 right-4 p-2 text-slate-400">✕</button>
            @foreach($primaryMenu as $item)
            <a href="{{ $item['url'] }}" @click="mobileMenuOpen=false" class="block px-4 py-2.5 rounded-xl text-sm font-medium" style="color:#4B5563">{{ $item['label'] }}</a>
            @endforeach
            @if(!empty($ctaText))
            <a href="{{ $ctaUrl }}" class="block mt-3 px-4 py-3 rounded-xl text-sm font-bold text-white text-center"
               style="background:linear-gradient(135deg,var(--theme-primary,#667EEA),var(--theme-secondary,#764BA2))">{{ $ctaText }}</a>
            @endif
        </div>
    </div>
</header>
