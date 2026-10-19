{{-- E-commerce Footer: dark, orange top border --}}
<footer>
    <x-frontend.ad-slot position="footer" />
    <div style="background:#1f2937;border-top:4px solid var(--theme-secondary,#F39C12)">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-8">
                <div class="col-span-2">
                    <p class="font-black text-lg mb-2 text-white">{{ $siteName ?? 'MonetraPay' }}</p>
                    <p class="text-xs leading-relaxed" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['tagline'] ?? '' }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--theme-primary,#27AE60)">Menu</h4>
                    <ul class="space-y-1.5">
                        @forelse($footerMenuItems as $item)
                        <li><a href="{{ $item['url'] }}" class="text-xs" style="color:rgba(255,255,255,0.45)">{{ $item['label'] }}</a></li>
                        @empty <li><a href="{{ url('/') }}" class="text-xs" style="color:rgba(255,255,255,0.45)">Beranda</a></li> @endforelse
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-3" style="color:var(--theme-secondary,#F39C12)">Kontak</h4>
                    @if(!empty($footerSettings['contact_email']))<p class="text-xs mb-1" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_email'] }}</p>@endif
                    @if(!empty($footerSettings['contact_phone']))<p class="text-xs" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_phone'] }}</p>@endif
                </div>
            </div>
            <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.08);padding-top:1rem;color:rgba(255,255,255,0.25)">
                {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
            </p>
        </div>
    </div>
</footer>
