{{-- Magazine Footer: dark, red accent top border, editorial style --}}
<footer>
    <x-frontend.ad-slot position="footer" />
    <div style="background:#1a1a1a;border-top:4px solid var(--theme-primary,#C0392B)">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="md:col-span-2">
                    <div class="font-black text-2xl mb-2" style="font-family:'Playfair Display',serif;color:white">{{ $siteName ?? 'MonetraPay' }}</div>
                    <div style="width:3rem;height:3px;background:var(--theme-primary,#C0392B);margin:.5rem 0 .75rem"></div>
                    <p class="text-sm" style="color:rgba(255,255,255,0.5)">{{ $footerSettings['tagline'] ?? '' }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--theme-primary,#C0392B)">Navigasi</h4>
                    <ul class="space-y-2">
                        @forelse($footerMenuItems as $item)
                        <li><a href="{{ $item['url'] }}" class="text-xs font-bold uppercase tracking-wide" style="color:rgba(255,255,255,0.45)">{{ $item['label'] }}</a></li>
                        @empty <li><a href="{{ url('/') }}" class="text-xs" style="color:rgba(255,255,255,0.45)">Beranda</a></li> @endforelse
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--theme-primary,#C0392B)">Kontak</h4>
                    @if(!empty($footerSettings['contact_email']))<p class="text-xs mb-1" style="color:rgba(255,255,255,0.45)">{{ $footerSettings['contact_email'] }}</p>@endif
                    @if(!empty($footerSettings['contact_phone']))<p class="text-xs" style="color:rgba(255,255,255,0.45)">{{ $footerSettings['contact_phone'] }}</p>@endif
                </div>
            </div>
            <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.08);padding-top:1rem;color:rgba(255,255,255,0.2)">
                {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.($siteName??'MonetraPay') }}
            </p>
        </div>
    </div>
</footer>
