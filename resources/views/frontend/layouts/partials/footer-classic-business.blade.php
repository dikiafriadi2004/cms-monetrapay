{{-- Classic Business Footer: green bg, brown accent border --}}
<footer>
    <x-frontend.ad-slot position="footer" />
    <div style="background:var(--theme-primary,#1A5C38);border-top:4px solid var(--theme-secondary,#8B4513)">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <p class="font-black text-xl mb-3 text-white" style="font-family:'Merriweather',serif">{{ $siteName ?? config('app.name') }}</p>
                    <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.5)">{{ $footerSettings['tagline'] ?? '' }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--theme-secondary,#8B4513)">Navigasi</h4>
                    <ul class="space-y-2">
                        @forelse($footerMenuItems as $item)
                        <li><a href="{{ $item['url'] }}" class="text-sm transition-colors" style="color:rgba(255,255,255,0.55)">{{ $item['label'] }}</a></li>
                        @empty <li><a href="{{ url('/') }}" class="text-sm" style="color:rgba(255,255,255,0.55)">Beranda</a></li> @endforelse
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--theme-secondary,#8B4513)">Kontak</h4>
                    @if(!empty($footerSettings['contact_email']))<p class="text-sm mb-1" style="color:rgba(255,255,255,0.55)">{{ $footerSettings['contact_email'] }}</p>@endif
                    @if(!empty($footerSettings['contact_phone']))<p class="text-sm" style="color:rgba(255,255,255,0.55)">{{ $footerSettings['contact_phone'] }}</p>@endif
                </div>
            </div>
            <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.15);padding-top:1rem;color:rgba(255,255,255,0.3)">
                {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.(??config('app.name')) }}
            </p>
        </div>
    </div>
</footer>
