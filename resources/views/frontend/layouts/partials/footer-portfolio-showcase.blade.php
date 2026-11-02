{{-- Portfolio Showcase Footer: full black, gold details --}}
<footer>
    <x-frontend.ad-slot position="footer" />
    <div style="background:#000;border-top:1px solid rgba(255,215,0,0.2)">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div>
                    <p class="font-bold text-xl mb-3" style="font-family:'Oswald',sans-serif;letter-spacing:0.08em;color:#FFD700">{{ $siteName ?? config('app.name') }}</p>
                    <p class="text-sm font-light" style="color:rgba(255,255,255,0.35)">{{ $footerSettings['tagline'] ?? '' }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#FFD700">Navigasi</h4>
                    <ul class="space-y-2">
                        @forelse($footerMenuItems as $item)
                        <li><a href="{{ $item['url'] }}" class="text-xs font-light uppercase tracking-wider" style="color:rgba(255,255,255,0.35)">{{ $item['label'] }}</a></li>
                        @empty <li><a href="{{ url('/') }}" class="text-xs" style="color:rgba(255,255,255,0.35)">Beranda</a></li> @endforelse
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest mb-4" style="color:#FFD700">Kontak</h4>
                    @if(!empty($footerSettings['contact_email']))<p class="text-xs font-light" style="color:rgba(255,255,255,0.35)">{{ $footerSettings['contact_email'] }}</p>@endif
                </div>
            </div>
            <p class="text-xs text-center" style="border-top:1px solid rgba(255,215,0,0.1);padding-top:1rem;color:rgba(255,255,255,0.2)">
                {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.(??config('app.name')) }}
            </p>
        </div>
    </div>
</footer>
