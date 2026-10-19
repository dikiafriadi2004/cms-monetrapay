<footer role="contentinfo" class="mt-auto">
    <x-frontend.ad-slot position="footer" />
    <div class="bg-dark relative overflow-hidden">
        <div class="tech-grid-pattern absolute inset-0"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14 lg:py-20">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-12">
                <div class="md:col-span-12 lg:col-span-5">
                    @php $footerLogo = \App\Models\Setting::get('logo', 'general'); @endphp
                    @if($footerLogo)
                        <img src="{{ Storage::url($footerLogo) }}" alt="{{ $siteName ?? 'MonetraPay' }}" class="h-12 w-auto mb-5">
                    @elseif(file_exists(public_path('assets/images/logo.png')))
                        <img src="/assets/images/logo.png" alt="{{ $siteName ?? 'MonetraPay' }}" class="h-12 w-auto mb-5">
                    @else
                        <span class="text-xl font-extrabold text-white block mb-5"><span class="text-primary">Monetra</span><span class="text-secondary">Pay</span></span>
                    @endif
                    <p class="text-slate-400 text-sm leading-relaxed max-w-md mb-5">
                        {{ $footerSettings['tagline'] ?? 'Solusi lengkap untuk kebutuhan transaksi digital Anda. Pulsa, PPOB, Top-Up Game, dan E-Money dalam satu platform.' }}
                    </p>

                    {{-- Newsletter inline --}}
                    <div class="mb-6">
                        <h4 class="text-xs font-semibold text-white/80 uppercase tracking-wider mb-2">Newsletter</h4>
                        <p class="text-slate-400 text-xs mb-3">Update artikel terbaru langsung ke email Anda.</p>
                        <x-frontend.newsletter-form />
                    </div>

                    @if(!empty($socialLinks))
                    <div class="flex items-center gap-3">
                        @if(!empty($socialLinks['facebook_url']))
                        <a href="{{ $socialLinks['facebook_url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['instagram_url']))
                        <a href="{{ $socialLinks['instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['twitter_url']))
                        <a href="{{ $socialLinks['twitter_url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-black hover:border-black hover:text-white transition-all duration-200" aria-label="X">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['youtube_url']))
                        <a href="{{ $socialLinks['youtube_url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="YouTube">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zM9 16V8l8 4-8 4z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['tiktok_url']))
                        <a href="{{ $socialLinks['tiktok_url'] }}" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="TikTok">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.88 2.89 2.89 0 01-2.88-2.88 2.89 2.89 0 012.88-2.88c.28 0 .56.04.82.11v-3.5a6.37 6.37 0 00-.82-.05A6.34 6.34 0 003.15 15.7a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V9.41a8.16 8.16 0 004.76 1.52v-3.4a4.85 4.85 0 01-1-.84z"/></svg>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="md:col-span-6 lg:col-span-3">
                    <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-5">Kontak</h4>
                    <ul class="space-y-4">
                        @if(!empty($footerSettings['contact_email']))
                        <li><a href="mailto:{{ $footerSettings['contact_email'] }}" class="text-sm text-slate-400 hover:text-primary transition-colors flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            {{ $footerSettings['contact_email'] }}</a></li>
                        @endif
                        @if(!empty($footerSettings['contact_phone']))
                        <li><a href="tel:{{ $footerSettings['contact_phone'] }}" class="text-sm text-slate-400 hover:text-primary transition-colors flex items-center gap-3">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            {{ $footerSettings['contact_phone'] }}</a></li>
                        @endif
                    </ul>
                </div>
                <div class="md:col-span-6 lg:col-span-4">
                    @php
                        // Merge any extra footer items from database that aren't already shown
                        $shownFooterPaths = ['kebijakan-privasi', 'syarat-ketentuan', 'faq'];
                    @endphp
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-5">Navigasi</h4>
                            <ul class="space-y-3">
                                @forelse($footerMenuItems as $item)
                                    <li><a href="{{ $item['url'] ?? '#' }}" class="text-sm text-slate-400 hover:text-primary transition-colors">{{ $item['label'] ?? '' }}</a></li>
                                @empty
                                    {{-- Minimal fallback if footer menu empty in DB --}}
                                    <li><a href="{{ url('/') }}" class="text-sm text-slate-400 hover:text-primary transition-colors">Beranda</a></li>
                                    <li><a href="{{ url('/blog') }}" class="text-sm text-slate-400 hover:text-primary transition-colors">Blog</a></li>
                                @endforelse
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-white uppercase tracking-wider mb-5">Legal & Bantuan</h4>
                            <ul class="space-y-3">
                                <li><a href="{{ url('/kebijakan-privasi') }}" class="text-sm text-slate-400 hover:text-primary transition-colors">Kebijakan Privasi</a></li>
                                <li><a href="{{ url('/syarat-ketentuan') }}" class="text-sm text-slate-400 hover:text-primary transition-colors">Syarat & Ketentuan</a></li>
                                <li><a href="{{ url('/faq') }}" class="text-sm text-slate-400 hover:text-primary transition-colors">FAQ</a></li>
                            </ul>
                            <div class="mt-6 rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                <p class="text-xs font-semibold text-white/80 uppercase tracking-wide">Keamanan Transaksi</p>
                                <p class="mt-2 text-xs leading-relaxed text-slate-400">Dipantau 24/7, mendukung verifikasi admin, dan siap untuk proses audit operasional.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom bg-[#0a1120] border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
            <p class="text-sm text-slate-500">{{ $footerSettings['copyright_text'] ?? '© ' . date('Y') . ' ' . ($siteName ?? 'MonetraPay') . '. All rights reserved.' }}</p>
            @if(!empty($footerSettings['footer_extra_content']))
            <p class="text-sm text-slate-500">{{ $footerSettings['footer_extra_content'] }}</p>
            @endif
        </div>
    </div>
</footer>