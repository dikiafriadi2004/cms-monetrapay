@extends('frontend.layouts.app')
@section('title', 'Layanan - ' . ($siteName ?? 'MonetraPay'))
@section('seo')
<x-seo-meta :title="$metaTags['title']" :description="$metaTags['description']" :keywords="$metaTags['keywords']" :canonicalUrl="$canonicalUrl" :ogTags="$ogTags" :twitterTags="$twitterTags" :structuredData="$structuredData"/>
@endsection

@section('content')
{{-- Page Hero --}}
<section class="relative bg-primary overflow-hidden py-20 md:py-28">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 28px 28px;"></div>
    <div class="absolute top-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 left-0 w-80 h-80 bg-secondary/10 rounded-full blur-3xl -translate-x-1/2 translate-y-1/2"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block text-xs font-bold text-white/75 bg-white/10 border border-white/20 px-4 py-1.5 rounded-full mb-5 uppercase tracking-widest">Layanan Kami</span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-5 leading-tight">Semua Kebutuhan Digital<br class="hidden md:block"> dalam Satu Platform</h1>
        <p class="text-white/80 text-lg max-w-2xl mx-auto">Pulsa, PPOB, top-up game, dan e-wallet untuk agen yang ingin transaksi cepat, margin jelas, dan operasional lebih mudah.</p>
        <div class="mt-8 flex flex-wrap items-center justify-center gap-3 text-sm text-white/80">
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 border border-white/15">✓ Deposit ringan</span>
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 border border-white/15">✓ Produk lengkap</span>
            <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-4 py-2 border border-white/15">✓ Support 24/7</span>
        </div>
    </div>
</section>

{{-- Services Grid --}}
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @php
                $services = [
                    ['title' => 'Pulsa All Operator', 'desc' => 'Jual pulsa Telkomsel, Indosat, XL, Tri, Smartfren, dan Axis dengan proses instan serta margin yang mudah dipantau.', 'tags' => ['Telkomsel','Indosat','XL','Tri','Smartfren','Axis'], 'color' => 'green', 'icon' => 'M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z'],
                    ['title' => 'PPOB & Tagihan', 'desc' => 'Bayar PLN, PDAM, BPJS, internet, TV kabel, dan cicilan dalam satu alur transaksi yang simpel untuk agen.', 'tags' => ['PLN','PDAM','BPJS','Internet','TV Kabel','Cicilan'], 'color' => 'blue', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                    ['title' => 'Top-Up Game', 'desc' => 'Top-up Mobile Legends, PUBG, Free Fire, Genshin Impact, Valorant, dan game populer lain dengan katalog yang siap berkembang.', 'tags' => ['Mobile Legends','PUBG','Free Fire','Genshin','Valorant','COD Mobile'], 'color' => 'purple', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
                    ['title' => 'E-Money & E-Wallet', 'desc' => 'Isi saldo GoPay, OVO, DANA, ShopeePay, LinkAja, dan berbagai dompet digital lain dengan pengalaman transaksi cepat.', 'tags' => ['GoPay','OVO','DANA','ShopeePay','LinkAja','Jenius'], 'color' => 'orange', 'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                ];
                $colorMap = [
                    'green' => ['box' => 'from-green-400 to-green-600 shadow-green-500/20', 'tag' => 'bg-green-50 text-green-700', 'hover' => 'hover:shadow-green-500/5 hover:border-green-200'],
                    'blue' => ['box' => 'from-blue-400 to-blue-600 shadow-blue-500/20', 'tag' => 'bg-blue-50 text-blue-700', 'hover' => 'hover:shadow-blue-500/5 hover:border-blue-200'],
                    'purple' => ['box' => 'from-purple-400 to-purple-600 shadow-purple-500/20', 'tag' => 'bg-purple-50 text-purple-700', 'hover' => 'hover:shadow-purple-500/5 hover:border-purple-200'],
                    'orange' => ['box' => 'from-orange-400 to-orange-600 shadow-orange-500/20', 'tag' => 'bg-orange-50 text-orange-700', 'hover' => 'hover:shadow-orange-500/5 hover:border-orange-200'],
                ];
            @endphp
            @foreach($services as $index => $service)
                @php $c = $colorMap[$service['color']]; @endphp
                <article class="animate-on-scroll delay-{{ min($index * 100, 300) }} group flex flex-col sm:flex-row gap-6 p-7 md:p-8 bg-white rounded-2xl border border-slate-100 shadow-sm hover:shadow-xl {{ $c['hover'] }} transition-all duration-300">
                    <div class="w-16 h-16 bg-gradient-to-br {{ $c['box'] }} rounded-2xl flex items-center justify-center shrink-0 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $service['icon'] }}"/></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 mb-2">{{ $service['title'] }}</h2>
                        <p class="text-slate-500 leading-relaxed mb-4">{{ $service['desc'] }}</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($service['tags'] as $tag)
                                <span class="text-xs font-semibold {{ $c['tag'] }} px-3 py-1 rounded-full">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- Benefits --}}
<section class="py-16 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-12 animate-on-scroll">
            <span class="inline-block text-sm font-semibold text-primary bg-primary-light px-4 py-1.5 rounded-full mb-4">Keunggulan</span>
            <h2 class="text-2xl md:text-4xl font-extrabold text-slate-800">Dibuat untuk Operasional Agen</h2>
            <p class="mt-3 text-slate-500">Fokus pada transaksi yang mudah dipantau, dukungan cepat, dan pengalaman yang jelas untuk tim operasional.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['title' => 'Margin Transparan', 'desc' => 'Pantau produk dan potensi keuntungan dengan struktur harga yang mudah dipahami.'],
                ['title' => 'Proses Cepat', 'desc' => 'Alur transaksi ringkas sehingga agen bisa melayani pelanggan tanpa proses berbelit.'],
                ['title' => 'Support Responsif', 'desc' => 'Kontak dukungan tersedia untuk membantu kendala transaksi dan operasional.'],
            ] as $benefit)
                <div class="animate-on-scroll bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-primary-light text-primary flex items-center justify-center mb-4 font-bold">✓</div>
                    <h3 class="text-lg font-bold text-slate-800">{{ $benefit['title'] }}</h3>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed">{{ $benefit['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FAQ + CTA --}}
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            <div class="animate-on-scroll">
                <span class="inline-block text-sm font-semibold text-primary bg-primary-light px-4 py-1.5 rounded-full mb-4">FAQ</span>
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800 mb-4">Pertanyaan yang Sering Ditanyakan</h2>
                <p class="text-slate-500">Gunakan bagian ini untuk menjelaskan deposit minimum, komisi, dan alur aktivasi agen agar calon mitra lebih yakin.</p>
            </div>
            <div class="space-y-3 animate-on-scroll delay-100">
                @foreach([
                    ['q' => 'Berapa modal awal yang dibutuhkan?', 'a' => 'Deposit dapat dibuat ringan dan fleksibel sesuai kebijakan bisnis Anda.'],
                    ['q' => 'Apakah semua layanan aktif 24 jam?', 'a' => 'Layanan digital didesain berjalan 24/7 dengan pemantauan transaksi.'],
                    ['q' => 'Bagaimana jika transaksi bermasalah?', 'a' => 'Agen dapat menghubungi support melalui kanal kontak resmi untuk pengecekan.'],
                ] as $item)
                    <details class="group rounded-2xl border border-slate-200 bg-slate-50 p-5 open:bg-white open:shadow-sm">
                        <summary class="cursor-pointer list-none flex items-center justify-between gap-4 font-bold text-slate-800">
                            {{ $item['q'] }}
                            <span class="text-primary group-open:rotate-45 transition-transform">+</span>
                        </summary>
                        <p class="mt-3 text-sm leading-relaxed text-slate-500">{{ $item['a'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="py-16 bg-primary-light">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-on-scroll">
        <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800 mb-4">Siap Mulai Bisnis Digital?</h2>
        <p class="text-slate-600 mb-8">Daftar sebagai agen MonetraPay dan kelola pulsa, PPOB, game, serta e-wallet dari satu platform.</p>
        <a href="{{ url('/kontak') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-secondary text-white font-bold rounded-2xl hover:bg-secondary-dark transition-all duration-200 shadow-lg shadow-secondary/30">
            Daftar Jadi Agen
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endsection
