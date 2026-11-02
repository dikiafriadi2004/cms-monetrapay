{{-- Minimalist Blog Navbar: ultra minimal, thin border, no decoration --}}
@php $logo = \App\Models\Setting::get('logo','general'); @endphp
<header class="sticky top-0 z-50" style="background:#fffef9;border-bottom:1px solid #e8e0d0">
    <div class="max-w-3xl mx-auto px-6">
        <div class="flex items-center justify-between py-4">
            <a href="{{ url('/') }}" class="font-bold text-lg" style="font-family:'Lora',Georgia,serif;color:#1a1a1a">
                @if($logo) <img src="{{ Storage::url($logo) }}" class="h-8 w-auto" alt="{{ $siteName }}">
                @else {{ $siteName ?? config('app.name') }} @endif
            </a>
            <nav class="hidden lg:flex items-center gap-6">
                @foreach($primaryMenu as $item)
                @php $cp=request()->path();$p=ltrim(parse_url($item['url']??'/',PHP_URL_PATH)??'/','/');$p=ltrim($p,'/');$active=($p===''&&$cp==='/')||($p!==''&&str_starts_with($cp,$p)); @endphp
                <a href="{{ $item['url'] }}" class="text-sm transition-colors" style="color:{{ $active ? 'var(--theme-secondary,#E74C3C)' : '#555' }};font-weight:{{ $active ? '600' : '400' }}">{{ $item['label'] }}</a>
                @endforeach
            </nav>
            <button @click="mobileMenuOpen=true" class="lg:hidden p-2" style="color:var(--theme-primary,#2C3E50)">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>
    <div x-show="mobileMenuOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
        <div class="absolute inset-0 bg-black/40" @click="mobileMenuOpen=false"></div>
        <div class="relative max-w-xs w-full h-full p-6 overflow-y-auto" style="background:#fffef9;border-right:1px solid #e8e0d0">
            <button @click="mobileMenuOpen=false" class="absolute top-4 right-4 p-2 text-slate-400">✕</button>
            @foreach($primaryMenu as $item)
            <a href="{{ $item['url'] }}" @click="mobileMenuOpen=false" class="block py-2.5 text-sm border-b" style="color:var(--theme-primary,#2C3E50);border-color:#e8e0d0">{{ $item['label'] }}</a>
            @endforeach
        </div>
    </div>
</header>
