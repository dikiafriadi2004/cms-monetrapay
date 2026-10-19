{{-- Creative Agency Navbar: pill nav, gradient CTA, color border bottom --}}
@php $logo = \App\Models\Setting::get('logo','general'); @endphp
<header class="sticky top-0 z-50 bg-white" style="border-bottom:3px solid var(--theme-primary,#FF6B6B)">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="font-black text-xl shrink-0" style="font-family:'Montserrat',sans-serif;color:var(--theme-primary,#FF6B6B)">
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-10 w-auto" alt="{{ $siteName }}">
                @else {{ $siteName ?? 'MonetraPay' }} @endif
            </a>
            {{-- Pill nav --}}
            <nav class="hidden lg:flex items-center gap-1">
                @foreach($primaryMenu as $item)
                @php $p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/',$cp=request()->path());$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}"
                   class="px-4 py-2 rounded-full text-sm font-bold transition-all"
                   style="{{ $active ? 'background:var(--theme-primary,#FF6B6B);color:white' : 'color:#374151' }}">
                    {{ $item['label'] }}
                </a>
                @endforeach
            </nav>
            @if(!empty($ctaText))
            <a href="{{ $ctaUrl }}" class="hidden lg:inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-sm font-black text-white"
               style="background:linear-gradient(135deg,var(--theme-primary,#FF6B6B),var(--theme-secondary,#4ECDC4))">
                {{ $ctaText }} →
            </a>
            @endif
            <button @click="mobileMenuOpen = true" class="lg:hidden p-2" style="color:var(--theme-primary,#FF6B6B)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    {{-- Mobile --}}
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
        <div class="absolute inset-0 bg-black/50" @click="mobileMenuOpen=false"></div>
        <div class="relative bg-white w-72 h-full p-6 space-y-2 overflow-y-auto">
            <button @click="mobileMenuOpen=false" class="absolute top-4 right-4 p-2" style="color:var(--theme-primary,#FF6B6B)">✕</button>
            <div class="font-black text-xl mb-6" style="font-family:'Montserrat',sans-serif;color:var(--theme-primary,#FF6B6B)">{{ $siteName ?? 'MonetraPay' }}</div>
            @foreach($primaryMenu as $item)
            <a href="{{ $item['url'] }}" @click="mobileMenuOpen=false" class="block px-4 py-2.5 rounded-2xl text-sm font-bold" style="color:#374151">{{ $item['label'] }}</a>
            @endforeach
            @if(!empty($ctaText))
            <a href="{{ $ctaUrl }}" class="block mt-4 px-4 py-3 rounded-full text-sm font-black text-white text-center"
               style="background:linear-gradient(135deg,var(--theme-primary,#FF6B6B),var(--theme-secondary,#4ECDC4))">{{ $ctaText }}</a>
            @endif
        </div>
    </div>
</header>
