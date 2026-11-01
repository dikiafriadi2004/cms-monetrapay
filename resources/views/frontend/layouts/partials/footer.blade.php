<footer role="contentinfo" class="mt-auto">
    <x-frontend.ad-slot position="footer" />

    {{-- Main Footer --}}
    <div class="bg-slate-900 relative overflow-hidden">
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-8">

                {{-- Col 1: Brand + Desc + Social --}}
                <div class="lg:col-span-4">
                    {{-- Logo --}}
                    @php $footerLogo = \App\Models\Setting::get('logo', 'general'); @endphp
                    @if($footerLogo)
                        <img src="{{ Storage::url($footerLogo) }}" alt="{{ $siteName ?? 'MonetraPay' }}"
                             class="h-16 w-auto mb-5 object-contain">
                    @elseif(file_exists(public_path('assets/images/logo.png')))
                        <img src="/assets/images/logo.png" alt="{{ $siteName ?? 'MonetraPay' }}"
                             class="h-16 w-auto mb-5 object-contain">
                    @else
                        <div class="mb-5">
                            <span class="text-2xl font-extrabold">
                                <span class="text-primary">Monetra</span><span class="text-secondary">Pay</span>
                            </span>
                        </div>
                    @endif

                    <p class="text-slate-400 text-sm leading-relaxed mb-6 max-w-sm">
                        {{ $footerSettings['tagline'] ?? 'Platform transaksi digital terlengkap untuk pulsa, PPOB, top-up game, dan e-money. Terpercaya oleh ratusan ribu agen di Indonesia.' }}
                    </p>

                    {{-- Social Icons --}}
                    @if(!empty($socialLinks))
                    <div class="flex items-center gap-2.5">
                        @if(!empty($socialLinks['facebook_url']))
                        <a href="{{ $socialLinks['facebook_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['instagram_url']))
                        <a href="{{ $socialLinks['instagram_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="Instagram">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['twitter_url']))
                        <a href="{{ $socialLinks['twitter_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-black hover:border-black hover:text-white transition-all duration-200" aria-label="X / Twitter">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['youtube_url']))
                        <a href="{{ $socialLinks['youtube_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-red-600 hover:border-red-600 hover:text-white transition-all duration-200" aria-label="YouTube">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zM9 16V8l8 4-8 4z"/></svg>
                        </a>
                        @endif
                        @if(!empty($socialLinks['tiktok_url']))
                        <a href="{{ $socialLinks['tiktok_url'] }}" target="_blank" rel="noopener noreferrer"
                           class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center text-slate-400 hover:bg-primary hover:border-primary hover:text-white transition-all duration-200" aria-label="TikTok">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.88 2.89 2.89 0 01-2.88-2.88 2.89 2.89 0 012.88-2.88c.28 0 .56.04.82.11v-3.5a6.37 6.37 0 00-.82-.05A6.34 6.34 0 003.15 15.7a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V9.41a8.16 8.16 0 004.76 1.52v-3.4a4.85 4.85 0 01-1-.84z"/></svg>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>

                {{-- Col 2: Navigasi --}}
                <div class="lg:col-span-2">
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-5">Navigasi</h4>
                    <ul class="space-y-3">
                        @forelse($footerMenuItems as $item)
                            <li>
                                <a href="{{ $item['url'] ?? '#' }}"
                                   class="text-sm text-slate-400 hover:text-white hover:translate-x-1 transition-all duration-200 inline-flex items-center gap-1.5">
                                    <span class="w-1 h-1 rounded-full bg-primary shrink-0"></span>
                                    {{ $item['label'] ?? '' }}
                                </a>
                            </li>
                        @empty
                            <li><a href="{{ url('/') }}" class="text-sm text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-primary shrink-0"></span>Beranda</a></li>
                            <li><a href="{{ url('/layanan') }}" class="text-sm text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-primary shrink-0"></span>Layanan</a></li>
                            <li><a href="{{ url('/blog') }}" class="text-sm text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-primary shrink-0"></span>Blog</a></li>
                            <li><a href="{{ url('/kontak') }}" class="text-sm text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-primary shrink-0"></span>Kontak</a></li>
                        @endforelse
                    </ul>
                </div>

                {{-- Col 3: Legal --}}
                <div class="lg:col-span-2">
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-5">Legal</h4>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/kebijakan-privasi') }}" class="text-sm text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary shrink-0"></span>Kebijakan Privasi</a></li>
                        <li><a href="{{ url('/syarat-ketentuan') }}" class="text-sm text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary shrink-0"></span>Syarat & Ketentuan</a></li>
                        <li><a href="{{ url('/faq') }}" class="text-sm text-slate-400 hover:text-white transition-colors inline-flex items-center gap-1.5"><span class="w-1 h-1 rounded-full bg-secondary shrink-0"></span>FAQ</a></li>
                    </ul>
                </div>

                {{-- Col 4: Kontak + Newsletter --}}
                <div class="lg:col-span-4">
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-5">Hubungi Kami</h4>
                    <ul class="space-y-3 mb-7">
                        @if(!empty($footerSettings['contact_email']))
                        <li>
                            <a href="mailto:{{ $footerSettings['contact_email'] }}"
                               class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                {{ $footerSettings['contact_email'] }}
                            </a>
                        </li>
                        @endif
                        @if(!empty($footerSettings['contact_phone']))
                        <li>
                            <a href="tel:{{ $footerSettings['contact_phone'] }}"
                               class="text-sm text-slate-400 hover:text-white transition-colors flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center shrink-0">
                                    <svg class="w-3.5 h-3.5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                {{ $footerSettings['contact_phone'] }}
                            </a>
                        </li>
                        @endif
                    </ul>

                    {{-- Newsletter --}}
                    <h4 class="text-xs font-bold text-white uppercase tracking-widest mb-3">Newsletter</h4>
                    <p class="text-xs text-slate-500 mb-3">Update artikel terbaru langsung ke email Anda.</p>
                    <x-frontend.newsletter-form />
                </div>

            </div>

            {{-- Divider --}}
            <div class="mt-12 pt-8 border-t border-white/8">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <p class="text-sm text-slate-500">
                        {{ $footerSettings['copyright_text'] ?? '© ' . date('Y') . ' ' . ($siteName ?? 'MonetraPay') . '. All rights reserved.' }}
                    </p>
                    @if(!empty($footerSettings['footer_extra_content']))
                    <p class="text-sm text-slate-500 flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        {{ $footerSettings['footer_extra_content'] }}
                    </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</footer>
