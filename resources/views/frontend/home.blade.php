@extends('frontend.layouts.app')

@section('title', ($siteName ?? config('app.name')) . ' - Pulsa, PPOB, Top-Up Game & E-Money')

@section('seo')
@php
    $homeTitle = ($siteName ?? config('app.name')) . ' - Pulsa, PPOB, Top-Up Game & E-Money';
    $homeDesc = $homepage['hero_subtitle'] ?? \App\Models\Setting::get('meta_description_default', 'seo') ?? 'MonetraPay - Aplikasi penjualan pulsa, PPOB, top-up game, dan e-money terpercaya.';
    $homeUrl = url('/');
    $homeImage = !empty($homepage['hero_image'])
        ? url(Storage::url($homepage['hero_image']))
        : (!empty($logo) ? url(Storage::url($logo)) : '');
@endphp
<meta name="description" content="{{ $homeDesc }}">
<link rel="canonical" href="{{ $homeUrl }}">
<meta name="robots" content="index, follow">
<meta property="og:title" content="{{ $homeTitle }}">
<meta property="og:description" content="{{ $homeDesc }}">
<meta property="og:url" content="{{ $homeUrl }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName ?? config('app.name') }}">
@if($homeImage)
<meta property="og:image" content="{{ $homeImage }}">
<meta name="twitter:image" content="{{ $homeImage }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $homeTitle }}">
<meta name="twitter:description" content="{{ $homeDesc }}">
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => $siteName ?? config('app.name'),
    'url' => $homeUrl,
    'description' => $homeDesc,
    'potentialAction' => [
        '@type' => 'SearchAction',
        'target' => $homeUrl . '/search?q={search_term_string}',
        'query-input' => 'required name=search_term_string',
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endsection

@section('content')

{{-- ═══════════════ HERO SECTION ═══════════════ --}}
<section class="relative overflow-hidden bg-white">
    {{-- Subtle background shapes --}}
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary/8 rounded-full blur-3xl -translate-y-1/3 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-secondary/8 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3 pointer-events-none"></div>
    {{-- Dot pattern --}}
    <div class="absolute inset-0 pointer-events-none"
         style="background-image: radial-gradient(circle, rgba(61,158,122,0.12) 1px, transparent 1px); background-size: 32px 32px;"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 lg:py-0 lg:min-h-[calc(100vh-56px)] flex items-center">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center w-full">

            {{-- LEFT: Text --}}
            <div class="animate-on-scroll order-1">
                {{-- Badge --}}
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary/10 border border-primary/20 mb-7">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    <span class="text-primary text-sm font-semibold">Platform Transaksi Digital untuk Agen</span>
                </div>

                {{-- Headline --}}
                <h1 class="text-4xl md:text-5xl lg:text-5xl xl:text-6xl font-extrabold leading-[1.1] tracking-tight text-slate-800 mb-5">
                    @php
                        $title = $homepage['hero_title'] ?? 'All-in-One Payment Solution untuk Bisnis Digital';
                        $words = explode(' ', $title);
                        $last = array_pop($words);
                    @endphp
                    {{ implode(' ', $words) }}
                    <span class="text-secondary"> {{ $last }}</span>
                </h1>

                {{-- Subtitle --}}
                <p class="text-lg text-slate-600 leading-relaxed mb-9 max-w-lg">
                    {{ $homepage['hero_subtitle'] ?? 'Platform terlengkap untuk penjualan pulsa, pembayaran PPOB, top-up game, dan e-money. Mulai bisnis digital Anda sekarang.' }}
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-wrap items-center gap-4 mb-10">
                    {{-- Google Play --}}
                    <a href="{{ $homepage['hero_cta_url'] ?? '#' }}"
                       class="group inline-flex items-center gap-3 px-5 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        {{-- Google Play icon --}}
                        <svg class="w-7 h-7 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M3.18 23.76c.32.18.68.22 1.04.12l11.2-11.2-2.83-2.83-9.41 13.91z" fill="#EA4335"/>
                            <path d="M20.82 10.45l-2.99-1.73-3.18 3.16 3.18 3.18 2.99-1.73a1.78 1.78 0 000-2.88z" fill="#FBBC04"/>
                            <path d="M4.22.12a1.78 1.78 0 00-1.04 1.6v20.56c0 .67.37 1.27.97 1.56L15.59 12 4.22.12z" fill="#4285F4"/>
                            <path d="M4.22.12L15.59 12l-3.18-3.18L4.22.12z" fill="#34A853"/>
                        </svg>
                        <div class="text-left leading-tight">
                            <p class="text-[10px] font-medium text-white/70 uppercase tracking-widest">Unduh di</p>
                            <p class="text-sm font-bold">Google Play</p>
                        </div>
                    </a>

                    {{-- App Store --}}
                    <a href="{{ $homepage['hero_cta_secondary_url'] ?? '#' }}"
                       class="group inline-flex items-center gap-3 px-5 py-3 bg-slate-800 hover:bg-slate-700 text-white rounded-2xl transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                        {{-- Apple icon --}}
                        <svg class="w-7 h-7 shrink-0" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
                        </svg>
                        <div class="text-left leading-tight">
                            <p class="text-[10px] font-medium text-white/70 uppercase tracking-widest">Unduh di</p>
                            <p class="text-sm font-bold">App Store</p>
                        </div>
                    </a>

                    {{-- Lihat Layanan --}}
                    <a href="{{ url('/layanan') }}"
                       class="inline-flex items-center gap-2 px-6 py-3.5 text-primary font-semibold text-sm rounded-2xl border-2 border-primary/30 hover:border-primary hover:bg-primary/5 transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        Lihat Layanan
                    </a>
                </div>

                {{-- Stats --}}
                <div class="flex flex-wrap items-center gap-0 pt-6 border-t border-slate-200">
                    <div class="pr-6 mr-6 border-r border-slate-200">
                        <p class="text-2xl font-extrabold text-slate-800">100K<span class="text-primary">+</span></p>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Pengguna Aktif</p>
                    </div>
                    <div class="pr-6 mr-6 border-r border-slate-200">
                        <p class="text-2xl font-extrabold text-slate-800">500<span class="text-primary">+</span></p>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Produk Digital</p>
                    </div>
                    <div class="pr-6 mr-6 border-r border-slate-200">
                        <p class="text-2xl font-extrabold text-slate-800">99.9<span class="text-primary">%</span></p>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Uptime Server</p>
                    </div>
                    <div>
                        <p class="text-2xl font-extrabold text-slate-800">24<span class="text-secondary">/7</span></p>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Support</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: Phone Mockup --}}
            <div class="order-2 flex justify-center lg:justify-end animate-on-scroll delay-200">
                <div class="relative">
                    {{-- Glow behind phone --}}
                    <div class="absolute inset-0 bg-primary/15 rounded-full blur-3xl scale-90"></div>

                    {{-- Floating badges --}}
                    <div class="absolute -left-6 top-12 z-20 hidden sm:block">
                        <div class="flex items-center gap-2.5 bg-white rounded-2xl px-4 py-2.5 shadow-lg shadow-slate-200/60 border border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-green-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </div>
                            <div><p class="text-slate-800 text-xs font-bold leading-none">Pulsa</p><p class="text-green-600 text-xs font-semibold mt-0.5">All Operator</p></div>
                        </div>
                    </div>
                    <div class="absolute -right-4 top-24 z-20 hidden sm:block">
                        <div class="flex items-center gap-2.5 bg-white rounded-2xl px-4 py-2.5 shadow-lg shadow-slate-200/60 border border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-blue-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                            <div><p class="text-slate-800 text-xs font-bold leading-none">PPOB</p><p class="text-blue-600 text-xs font-semibold mt-0.5">Tagihan Online</p></div>
                        </div>
                    </div>
                    <div class="absolute -left-8 bottom-28 z-20 hidden sm:block">
                        <div class="flex items-center gap-2.5 bg-white rounded-2xl px-4 py-2.5 shadow-lg shadow-slate-200/60 border border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-purple-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            </div>
                            <div><p class="text-slate-800 text-xs font-bold leading-none">Top-Up Game</p><p class="text-purple-600 text-xs font-semibold mt-0.5">100+ Game</p></div>
                        </div>
                    </div>
                    <div class="absolute -right-2 bottom-16 z-20 hidden sm:block">
                        <div class="flex items-center gap-2.5 bg-white rounded-2xl px-4 py-2.5 shadow-lg shadow-slate-200/60 border border-slate-100">
                            <div class="w-8 h-8 rounded-xl bg-orange-100 flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div><p class="text-slate-800 text-xs font-bold leading-none">E-Money</p><p class="text-orange-600 text-xs font-semibold mt-0.5">GoPay, OVO, DANA</p></div>
                        </div>
                    </div>

                    {{-- Phone image --}}
                    <div class="relative z-10">
                        @if(!empty($homepage['hero_image']))
                            <img src="{{ Storage::url($homepage['hero_image']) }}" alt="MonetraPay App"
                                 class="w-72 xl:w-96 object-contain drop-shadow-2xl">
                        @elseif(file_exists(public_path('assets/images/hero.png')))
                            <img src="/assets/images/hero.png" alt="MonetraPay App"
                                 class="w-72 xl:w-96 object-contain drop-shadow-2xl">
                        @endif
                    </div>
                    <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 w-48 h-8 bg-primary/20 blur-2xl rounded-full"></div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ═══════════════ MARQUEE PRODUK ═══════════════ --}}
<section class="py-16 bg-white border-y border-slate-100">
    <div class="max-w-3xl mx-auto px-4 text-center mb-12">
        <h2 class="text-2xl font-bold text-slate-800">Produk Terlengkap & Terpercaya</h2>
        <p class="text-base text-slate-400 mt-2">Kami mendukung semua provider dan e-wallet populer di Indonesia.</p>
    </div>

    {{-- Marquee wrapper --}}
    @php
    $brands = [
        ['name'=>'Telkomsel', 'logo'=>asset('assets/images/brands/telkomsel.png'), 'bg'=>null],
        ['name'=>'Indosat',   'logo'=>asset('assets/images/brands/indosat.jpg'),   'bg'=>null],
        ['name'=>'XL',        'logo'=>asset('assets/images/brands/xl.svg'),        'bg'=>null],
        ['name'=>'DANA',      'logo'=>asset('assets/images/brands/dana.svg'),      'bg'=>null],
        ['name'=>'GoPay',     'logo'=>asset('assets/images/brands/gopay.webp'),    'bg'=>null],
        ['name'=>'OVO',       'logo'=>asset('assets/images/brands/ovo.svg'),       'bg'=>null],
        ['name'=>'ShopeePay', 'logo'=>asset('assets/images/brands/shopee.svg'),    'bg'=>null],
        ['name'=>'LinkAja',   'logo'=>asset('assets/images/brands/linkaja.svg'),   'bg'=>null],
        ['name'=>'Tri',       'logo'=>asset('assets/images/brands/tri.png'),       'bg'=>'#0099CC'],
    ];
    $items = array_merge($brands, $brands, $brands, $brands);
    @endphp

    <div class="relative overflow-hidden">
        <div class="absolute left-0 top-0 bottom-0 w-40 z-10 pointer-events-none" style="background:linear-gradient(to right,white 60%,transparent);"></div>
        <div class="absolute right-0 top-0 bottom-0 w-40 z-10 pointer-events-none" style="background:linear-gradient(to left,white 60%,transparent);"></div>

        <div class="marquee-track flex items-center">
            @foreach($items as $brand)
            <div class="shrink-0 flex items-center justify-center" style="height:72px; padding:0 28px;">
                @if($brand['bg'])
                    {{-- Logo putih: beri background warna brand, grayscale bersama container --}}
                    <div style="background:{{ $brand['bg'] }}; border-radius:10px; padding:6px 14px; filter:grayscale(1) opacity(0.45); transition:all 0.3s; cursor:default;"
                         onmouseover="this.style.filter='grayscale(0) opacity(1)';this.style.transform='scale(1.08)'"
                         onmouseout="this.style.filter='grayscale(1) opacity(0.45)';this.style.transform='scale(1)'">
                        <img src="{{ $brand['logo'] }}" alt="{{ $brand['name'] }}"
                             style="height:34px; width:auto; max-width:140px; object-fit:contain; display:block;">
                    </div>
                @else
                    <img src="{{ $brand['logo'] }}"
                         alt="{{ $brand['name'] }}"
                         class="object-contain transition-all duration-300"
                         style="height:38px; width:auto; max-width:160px; filter:grayscale(1) opacity(0.45);"
                         onmouseover="this.style.filter='grayscale(0) opacity(1)';this.style.transform='scale(1.08)'"
                         onmouseout="this.style.filter='grayscale(1) opacity(0.45)';this.style.transform='scale(1)'">
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>

<style>
.marquee-track {
    display: flex;
    width: max-content;
    animation: marquee-scroll 35s linear infinite;
}
.marquee-track:hover { animation-play-state: paused; }
@keyframes marquee-scroll {
    0%   { transform: translateX(0); }
    100% { transform: translateX(-33.333%); }
}
</style>

{{-- Services Section --}}
<section id="layanan" class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="inline-block text-sm font-semibold text-primary bg-primary-light px-4 py-1.5 rounded-full mb-4">Layanan Kami</span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-slate-800 mb-4">Semua Kebutuhan Digital dalam Satu Platform</h2>
            <p class="text-slate-500 max-w-2xl mx-auto text-lg">Transaksi cepat, aman, dan terpercaya untuk berbagai layanan digital.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
            {{-- Service 1: Pulsa --}}
            <article class="animate-on-scroll delay-100 service-card group bg-white rounded-2xl p-8 border border-slate-100 hover:border-primary/20 shadow-sm transition-all duration-300">
                <div class="w-14 h-14 bg-green-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-green-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-3">{{ $homepage['service_1_title'] ?? 'Pulsa All Operator' }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $homepage['service_1_description'] ?? 'Jual pulsa semua operator dengan harga kompetitif.' }}</p>
            </article>
            {{-- Service 2: PPOB --}}
            <article class="animate-on-scroll delay-200 service-card group bg-white rounded-2xl p-8 border border-slate-100 hover:border-blue-200 shadow-sm transition-all duration-300">
                <div class="w-14 h-14 bg-blue-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-blue-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-3">{{ $homepage['service_2_title'] ?? 'PPOB & Tagihan' }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $homepage['service_2_description'] ?? 'Bayar tagihan PLN, PDAM, BPJS, dan berbagai tagihan lainnya.' }}</p>
            </article>
            {{-- Service 3: Top-Up Game --}}
            <article class="animate-on-scroll delay-300 service-card group bg-white rounded-2xl p-8 border border-slate-100 hover:border-purple-200 shadow-sm transition-all duration-300">
                <div class="w-14 h-14 bg-purple-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-purple-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-3">{{ $homepage['service_3_title'] ?? 'Top-Up Game' }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $homepage['service_3_description'] ?? 'Top-up diamond, UC, dan voucher 100+ game populer.' }}</p>
            </article>
            {{-- Service 4: E-Money --}}
            <article class="animate-on-scroll delay-400 service-card group bg-white rounded-2xl p-8 border border-slate-100 hover:border-orange-200 shadow-sm transition-all duration-300">
                <div class="w-14 h-14 bg-orange-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg shadow-orange-500/20">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-3">{{ $homepage['service_4_title'] ?? 'E-Money & E-Wallet' }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $homepage['service_4_description'] ?? 'Isi saldo GoPay, OVO, DANA, ShopeePay, dan lainnya.' }}</p>
            </article>
        </div>
    </div>
</section>

{{-- Stats Section --}}
<section class="py-20 md:py-24 bg-slate-800 relative overflow-hidden">
    <div class="tech-grid-pattern absolute inset-0"></div>
    <div class="absolute top-0 left-1/2 w-96 h-96 bg-primary/5 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 lg:gap-12">
            <div class="text-center animate-on-scroll">
                <p class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-2" data-count="100000">100.000</p>
                <p class="text-sm text-slate-400 font-medium">Pengguna Aktif</p>
            </div>
            <div class="text-center animate-on-scroll delay-100">
                <p class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-2" data-count="500">500</p>
                <p class="text-sm text-slate-400 font-medium">Produk Digital</p>
            </div>
            <div class="text-center animate-on-scroll delay-200">
                <p class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-primary mb-2">99.9%</p>
                <p class="text-sm text-slate-400 font-medium">Uptime Server</p>
            </div>
            <div class="text-center animate-on-scroll delay-300">
                <p class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-secondary mb-2">24/7</p>
                <p class="text-sm text-slate-400 font-medium">Customer Support</p>
            </div>
        </div>
    </div>
</section>

{{-- How It Works Section --}}
<section class="py-20 md:py-28 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="inline-block text-sm font-semibold text-primary bg-primary-light px-4 py-1.5 rounded-full mb-4">Cara Kerja</span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-slate-800 mb-4">Mulai dalam 3 Langkah Mudah</h2>
            <p class="text-slate-500 max-w-2xl mx-auto text-lg">Proses pendaftaran cepat dan langsung bisa bertransaksi.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12 relative">
            {{-- Connecting line (desktop only) — top-8 = setengah tinggi circle w-16 --}}
            <div class="hidden md:block absolute top-8 left-[calc(16.67%+32px)] right-[calc(16.67%+32px)] h-0.5 bg-primary/20"></div>

            {{-- Step 1 --}}
            <div class="text-center animate-on-scroll relative">
                <div class="relative inline-flex items-center justify-center w-16 h-16 bg-primary rounded-full text-white text-xl font-bold mb-6 shadow-lg shadow-primary/30">
                    1
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-3">Daftar Akun</h3>
                <p class="text-sm text-slate-500 leading-relaxed max-w-xs mx-auto">Buat akun gratis hanya dengan email dan nomor telepon. Proses kurang dari 2 menit.</p>
            </div>

            {{-- Step 2 --}}
            <div class="text-center animate-on-scroll delay-200 relative">
                <div class="relative inline-flex items-center justify-center w-16 h-16 bg-primary rounded-full text-white text-xl font-bold mb-6 shadow-lg shadow-primary/30">
                    2
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-3">Deposit Saldo</h3>
                <p class="text-sm text-slate-500 leading-relaxed max-w-xs mx-auto">Top-up saldo mulai dari Rp 10.000 via transfer bank, e-wallet, atau QRIS.</p>
            </div>

            {{-- Step 3 --}}
            <div class="text-center animate-on-scroll delay-400 relative">
                <div class="relative inline-flex items-center justify-center w-16 h-16 bg-secondary rounded-full text-white text-xl font-bold mb-6 shadow-lg shadow-secondary/30">
                    3
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-3">Mulai Transaksi</h3>
                <p class="text-sm text-slate-500 leading-relaxed max-w-xs mx-auto">Langsung jual pulsa, bayar tagihan, top-up game, dan isi e-money dengan harga terbaik.</p>
            </div>
        </div>
    </div>
</section>

{{-- Testimonials Section --}}
<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 animate-on-scroll">
            <span class="inline-block text-sm font-semibold text-primary bg-primary-light px-4 py-1.5 rounded-full mb-4">Testimoni</span>
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-slate-800 mb-4">Dipercaya Ribuan Agen di Indonesia</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Testimonial 1 --}}
            <div class="animate-on-scroll bg-slate-50 rounded-2xl p-8 border border-slate-100 relative">
                <div class="absolute top-6 right-6 text-primary/20">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151C7.546 6.068 5.983 8.789 5.983 11h4v10H0z"/></svg>
                </div>
                <p class="text-slate-600 text-sm leading-relaxed mb-6">"MonetraPay sangat membantu bisnis saya. Transaksi pulsa dan PPOB lancar 24 jam, margin kompetitif, dan saldo top-up langsung masuk. Omzet konter saya naik signifikan dalam 2 bulan!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-primary flex items-center justify-center text-white font-bold text-sm">A</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Ahmad Fauzi</p>
                        <p class="text-xs text-slate-500">Agen Pulsa, Surabaya</p>
                    </div>
                </div>
            </div>

            {{-- Testimonial 2 --}}
            <div class="animate-on-scroll delay-200 bg-slate-50 rounded-2xl p-8 border border-slate-100 relative">
                <div class="absolute top-6 right-6 text-primary/20">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151C7.546 6.068 5.983 8.789 5.983 11h4v10H0z"/></svg>
                </div>
                <p class="text-slate-600 text-sm leading-relaxed mb-6">"Margin yang diberikan MonetraPay sangat kompetitif dibanding provider lain. Pelanggan saya senang karena harga jual saya tetap bisa bersaing. Proses deposit juga sangat cepat!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-secondary flex items-center justify-center text-white font-bold text-sm">S</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Siti Rahayu</p>
                        <p class="text-xs text-slate-500">Reseller PPOB, Bandung</p>
                    </div>
                </div>
            </div>

            {{-- Testimonial 3 --}}
            <div class="animate-on-scroll delay-400 bg-slate-50 rounded-2xl p-8 border border-slate-100 relative">
                <div class="absolute top-6 right-6 text-primary/20">
                    <svg class="w-10 h-10" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151C7.546 6.068 5.983 8.789 5.983 11h4v10H0z"/></svg>
                </div>
                <p class="text-slate-600 text-sm leading-relaxed mb-6">"Support 24/7 membuat saya merasa aman berjualan. Kalau ada kendala transaksi, tim MonetraPay langsung respons dan selesaikan masalah dengan cepat. Sangat profesional!"</p>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold text-sm">B</div>
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Budi Santoso</p>
                        <p class="text-xs text-slate-500">Agen PPOB, Medan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA Section --}}
<section class="py-20 md:py-28 bg-slate-800 relative overflow-hidden">
    <div class="hero-pattern absolute inset-0"></div>
    <div class="tech-grid-pattern absolute inset-0"></div>
    {{-- Decorative elements --}}
    <div class="absolute top-0 left-0 w-72 h-72 bg-primary/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-3xl translate-x-1/3 translate-y-1/3"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="max-w-3xl mx-auto animate-on-scroll">
            <h2 class="text-2xl md:text-3xl lg:text-5xl font-extrabold text-white mb-6">
                {{ $homepage['cta_title'] ?? 'Siap Mengembangkan Bisnis Pulsa & PPOB?' }}
            </h2>
            <p class="text-lg md:text-xl text-slate-400 mb-10 leading-relaxed">
                {{ $homepage['cta_description'] ?? 'Bergabung sebagai agen MonetraPay, kelola transaksi digital dalam satu dashboard, dan mulai dari deposit ringan.' }}
            </p>
            <a href="{{ $homepage['cta_button_url'] ?? '#daftar' }}"
               class="btn-scale inline-flex items-center px-10 py-4 bg-secondary text-white font-bold rounded-xl hover:bg-secondary-dark transition-colors duration-200 shadow-lg shadow-secondary/30 text-lg">
                {{ $homepage['cta_button_text'] ?? 'Daftar Jadi Agen' }}
                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
            </a>
        </div>
    </div>
</section>

{{-- Latest Blog Posts --}}
@if ($latestBlogs->isNotEmpty())
<section class="py-20 md:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between mb-14 animate-on-scroll">
            <div>
                <span class="inline-block text-sm font-semibold text-primary bg-primary-light px-4 py-1.5 rounded-full mb-4">Blog</span>
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-slate-800">Artikel Terbaru</h2>
                <p class="text-slate-500 mt-3 text-lg">Informasi dan tips seputar bisnis digital</p>
            </div>
            <a href="{{ route('blog.index') }}" class="hidden sm:inline-flex items-center gap-2 text-primary hover:text-primary-dark font-semibold text-sm transition-colors duration-200 px-5 py-2.5 border border-primary/20 rounded-xl hover:bg-primary-light">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($latestBlogs as $index => $blog)
            <article class="animate-on-scroll delay-{{ ($index % 3 + 1) * 100 }} hover-lift group bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg transition-all duration-300 flex flex-col">
                {{-- Thumbnail --}}
                <a href="{{ route('blog.show', $blog->slug) }}" class="block overflow-hidden shrink-0">
                    @if ($blog->featured_image)
                    <div class="img-zoom">
                        <img src="{{ Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}"
                             loading="lazy" width="400" height="225"
                             class="w-full h-52 object-cover">
                    </div>
                    @else
                    <div class="w-full h-52 bg-slate-100 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-14 h-14 bg-white/60 rounded-2xl flex items-center justify-center mx-auto mb-2 shadow-sm">
                                <svg class="w-7 h-7 text-primary/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                            <span class="text-xs text-slate-400 font-medium">MonetraPay Blog</span>
                        </div>
                    </div>
                    @endif
                </a>
                <div class="p-6 flex flex-col flex-1">
                    {{-- Category badges --}}
                    @if($blog->categories->isNotEmpty())
                    <div class="flex flex-wrap gap-1.5 mb-3">
                        @foreach($blog->categories->take(2) as $cat)
                        <span class="text-xs font-semibold text-primary bg-primary-light px-2.5 py-1 rounded-full">{{ $cat->name }}</span>
                        @endforeach
                    </div>
                    @endif
                    <time datetime="{{ $blog->published_at?->toIso8601String() }}" class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                        {{ $blog->published_at?->translatedFormat('d F Y') }}
                    </time>
                    <h3 class="text-lg font-bold text-slate-800 mt-2 mb-3 line-clamp-2 group-hover:text-primary transition-colors duration-200 flex-1">
                        <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
                    </h3>
                    @if ($blog->excerpt)
                    <p class="text-sm text-slate-500 line-clamp-2 leading-relaxed mb-4">{{ $blog->excerpt }}</p>
                    @endif
                    <a href="{{ route('blog.show', $blog->slug) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-primary mt-auto hover:gap-2 transition-all duration-200">
                        Baca Selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        <div class="text-center mt-12 sm:hidden">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-primary hover:text-primary-dark font-semibold text-sm transition-colors duration-200 px-5 py-2.5 border border-primary/20 rounded-xl hover:bg-primary-light">
                Lihat Semua Artikel
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
    </div>
</section>
@endif
@endsection
