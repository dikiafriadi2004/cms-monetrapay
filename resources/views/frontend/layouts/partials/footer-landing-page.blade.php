{{-- Landing Page Footer: simple dark conversion footer --}}
<footer>
    <x-frontend.ad-slot position="footer" />
    {{-- CTA before footer --}}
    @if(!empty($ctaText))
    <div class="py-16 text-center px-4" style="background:var(--theme-primary,#FF5722)">
        <h2 class="text-2xl md:text-3xl font-black text-white mb-4" style="font-family:'Raleway',sans-serif">Siap Bergabung?</h2>
        <a href="{{ $ctaUrl }}" class="inline-flex items-center gap-2 px-10 py-4 rounded-full font-black text-base"
           style="background:white;color:var(--theme-primary,#FF5722)">{{ $ctaText }} 🚀</a>
    </div>
    @endif
    <div style="background:#0f172a">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <p class="font-black text-xl mb-2" style="font-family:'Raleway',sans-serif;color:var(--theme-primary,#FF5722)">{{ $siteName ?? 'MonetraPay' }}</p>
                    <p class="text-sm" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['tagline'] ?? '' }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--theme-primary,#FF5722)">Navigasi</h4>
                    <ul class="space-y-2">
                        @forelse($footerMenuItems as $item)
                        <li><a href="{{ $item['url'] }}" class="text-sm" style="color:rgba(255,255,255,0.45)">{{ $item['label'] }}</a></li>
                        @empty <li><a href="{{ url('/') }}" class="text-sm" style="color:rgba(255,255,255,0.45)">Beranda</a></li> @endforelse
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--theme-secondary,#00BCD4)">Kontak</h4>
                    @if(!empty($footerSettings['contact_email']))<p class="text-xs mb-1" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_email'] }}</p>@endif
                    @if(!empty($footerSettings['contact_phone']))<p class="text-xs" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_phone'] }}</p>@endif
                </div>
            </div>
            <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.06);padding-top:1rem;color:rgba(255,255,255,0.2)">
                {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
            </p>
        </div>
    </div>
</footer>
