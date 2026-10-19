{{-- Creative Agency Footer: gradient dark, 3-col --}}
<footer>
    <x-frontend.ad-slot position="footer" />
    <div style="background:linear-gradient(135deg,#1a1a2e,#16213e)">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
                <div>
                    <div class="font-black text-2xl mb-3" style="font-family:'Montserrat',sans-serif;color:var(--theme-primary,#FF6B6B)">{{ $siteName ?? 'MonetraPay' }}</div>
                    <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.55)">{{ $footerSettings['tagline'] ?? '' }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--theme-secondary,#4ECDC4)">Navigasi</h4>
                    <ul class="space-y-2">
                        @forelse($footerMenuItems as $item)
                        <li><a href="{{ $item['url'] }}" class="text-sm" style="color:rgba(255,255,255,0.5)">{{ $item['label'] }}</a></li>
                        @empty <li><a href="{{ url('/') }}" class="text-sm" style="color:rgba(255,255,255,0.5)">Beranda</a></li> @endforelse
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--theme-secondary,#4ECDC4)">Kontak</h4>
                    @if(!empty($footerSettings['contact_email']))<p class="text-sm mb-1" style="color:rgba(255,255,255,0.5)">{{ $footerSettings['contact_email'] }}</p>@endif
                    @if(!empty($footerSettings['contact_phone']))<p class="text-sm" style="color:rgba(255,255,255,0.5)">{{ $footerSettings['contact_phone'] }}</p>@endif
                </div>
            </div>
            <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.08);padding-top:1.5rem;color:rgba(255,255,255,0.25)">
                {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
            </p>
        </div>
    </div>
</footer>
