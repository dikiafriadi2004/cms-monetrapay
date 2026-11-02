{{-- Minimalist Blog Footer: simple 2-col, warm dark --}}
<footer>
    <x-frontend.ad-slot position="footer" />
    <div style="background:#1a1a1a;border-top:3px solid var(--theme-secondary,#E74C3C)">
        <div class="max-w-3xl mx-auto px-6 py-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mb-8">
                <div>
                    <p class="font-bold mb-2" style="font-family:'Lora',serif;color:white">{{ $siteName ?? config('app.name') }}</p>
                    <p class="text-sm leading-relaxed" style="color:rgba(255,255,255,0.5)">{{ $footerSettings['tagline'] ?? '' }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold uppercase tracking-widest mb-3" style="color:var(--theme-secondary,#E74C3C)">Halaman</h4>
                    <ul class="space-y-1.5">
                        @forelse($footerMenuItems as $item)
                        <li><a href="{{ $item['url'] }}" class="text-sm" style="color:rgba(255,255,255,0.55)">{{ $item['label'] }}</a></li>
                        @empty <li><a href="{{ url('/') }}" class="text-sm" style="color:rgba(255,255,255,0.55)">Beranda</a></li> @endforelse
                    </ul>
                </div>
            </div>
            <p class="text-xs text-center" style="color:rgba(255,255,255,0.25);border-top:1px solid rgba(255,255,255,0.08);padding-top:1.5rem">
                {{ $footerSettings['copyright_text'] ?? '© '.date('Y').' '.(??config('app.name')) }}
            </p>
        </div>
    </div>
</footer>
