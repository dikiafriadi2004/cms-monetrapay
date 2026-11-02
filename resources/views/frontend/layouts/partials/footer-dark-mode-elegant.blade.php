{{-- Dark Mode Elegant Footer: ultra dark, purple glow accents --}}
<footer>
    <x-frontend.ad-slot position="footer" />
    <div style="background:#050508;border-top:1px solid rgba(159,122,234,0.2)">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10">
                <div class="md:col-span-2">
                    <div class="font-bold text-xl mb-3" style="font-family:'Poppins',sans-serif;color:var(--theme-primary,#9F7AEA)">{{ $siteName ?? config('app.name') }}</div>
                    <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['tagline'] ?? '' }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--theme-primary,#9F7AEA)">Navigasi</h4>
                    <ul class="space-y-2">
                        @forelse($footerMenuItems as $item)
                        <li><a href="{{ $item['url'] }}" class="text-sm transition-colors" style="color:rgba(255,255,255,0.4)">{{ $item['label'] }}</a></li>
                        @empty <li><a href="{{ url('/') }}" class="text-sm" style="color:rgba(255,255,255,0.4)">Beranda</a></li> @endforelse
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:var(--theme-secondary,#63B3ED)">Kontak</h4>
                    @if(!empty($footerSettings['contact_email']))<p class="text-sm mb-1" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_email'] }}</p>@endif
                    @if(!empty($footerSettings['contact_phone']))<p class="text-sm" style="color:rgba(255,255,255,0.4)">{{ $footerSettings['contact_phone'] }}</p>@endif
                </div>
            </div>
            <p class="text-xs text-center" style="border-top:1px solid rgba(255,255,255,0.05);padding-top:1.5rem;color:rgba(255,255,255,0.2)">
                {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.(??config('app.name')) }}
            </p>
        </div>
    </div>
</footer>
