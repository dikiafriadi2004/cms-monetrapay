{{--
    Tech Startup Home — SaaS / B2B Platform Style
    Colors: White bg, Navy #1E3A5F, Blue accent #3B82F6
    NO gradients. All sections self-contained.
--}}

{{-- ═══════════════════════════════════════════════════════════
     1. NAV — White bg shadow-sm, navy text, blue CTA button
     ═══════════════════════════════════════════════════════════ --}}
<nav class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between py-4">
            {{-- Logo / Site Name --}}
            <a href="{{ url('/') }}" class="text-xl font-bold text-[#1E3A5F]">
                {{ $siteName ?? config('app.name') }}
            </a>

            {{-- Nav links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-sm font-semibold text-[#1E3A5F] hover:text-blue-600">Beranda</a>
                <a href="{{ url('/layanan') }}" class="text-sm font-semibold text-[#1E3A5F] hover:text-blue-600">Layanan</a>
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-[#1E3A5F] hover:text-blue-600">Artikel</a>
                <a href="{{ url('/kontak') }}" class="text-sm font-semibold text-[#1E3A5F] hover:text-blue-600">Tentang</a>
            </div>

            {{-- CTA Nav Button --}}
            <a href="{{ url('/kontak') }}" class="bg-blue-600 text-white px-4 py-2 text-sm font-semibold hover:bg-blue-700">
                Mulai Sekarang
            </a>
        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════════════════════════════
     2. HERO — min-h-screen, two-column layout
     ═══════════════════════════════════════════════════════════ --}}
<section class="min-h-screen flex items-center bg-white">
    <div class="max-w-7xl mx-auto px-4 lg:px-8 py-20 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- LEFT: label + heading + subtitle + CTA --}}
            <div>
                <span class="inline-block text-xs font-bold uppercase tracking-widest text-blue-600 border border-blue-600 px-3 py-1 mb-6">
                    🚀 Platform Terpercaya
                </span>
                <h1 class="text-5xl font-bold text-[#1E3A5F] leading-tight mb-6">
                    {{ $homepage['hero_title'] ?? 'Satu Platform untuk Semua Transaksi Digital' }}
                </h1>
                <p class="text-lg text-gray-500 mb-8 leading-relaxed">
                    {{ $homepage['hero_subtitle'] ?? 'Kelola pulsa, PPOB, top-up game, dan dompet digital dengan dashboard analitik terintegrasi untuk agen modern.' }}
                </p>
                <a href="{{ $homepage['hero_cta_url'] ?? url('/kontak') }}"
                   class="inline-block bg-blue-600 text-white font-semibold px-8 py-4 text-sm uppercase tracking-wide hover:bg-blue-700">
                    {{ $homepage['hero_cta_text'] ?? 'Daftar Gratis Sekarang' }}
                </a>
            </div>

            {{-- RIGHT: Feature checklist items --}}
            <div class="space-y-6">
                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-2">Keunggulan Platform</p>

                {{-- Checklist item 1 --}}
                <div class="flex items-start gap-4 p-5 border border-gray-100 shadow-sm bg-white">
                    <span class="text-blue-600 font-bold text-xl shrink-0 mt-0.5">✓</span>
                    <div>
                        <p class="font-bold text-[#1E3A5F] mb-1">{{ $homepage['service_1_title'] ?? 'Pulsa & Data Semua Operator' }}</p>
                        <p class="text-sm text-gray-500">{{ $homepage['service_1_description'] ?? 'Tersedia untuk seluruh operator nasional dengan harga terbaik dan proses transaksi real-time tanpa gangguan.' }}</p>
                    </div>
                </div>

                {{-- Checklist item 2 --}}
                <div class="flex items-start gap-4 p-5 border border-gray-100 shadow-sm bg-white">
                    <span class="text-blue-600 font-bold text-xl shrink-0 mt-0.5">✓</span>
                    <div>
                        <p class="font-bold text-[#1E3A5F] mb-1">{{ $homepage['service_2_title'] ?? 'PPOB & Pembayaran Tagihan' }}</p>
                        <p class="text-sm text-gray-500">{{ $homepage['service_2_description'] ?? 'Bayar listrik, PDAM, BPJS, internet, dan ratusan tagihan lainnya dengan mudah dan cepat.' }}</p>
                    </div>
                </div>

                {{-- Checklist item 3 --}}
                <div class="flex items-start gap-4 p-5 border border-gray-100 shadow-sm bg-white">
                    <span class="text-blue-600 font-bold text-xl shrink-0 mt-0.5">✓</span>
                    <div>
                        <p class="font-bold text-[#1E3A5F] mb-1">{{ $homepage['service_3_title'] ?? 'Top-Up Game & E-Wallet' }}</p>
                        <p class="text-sm text-gray-500">{{ $homepage['service_3_description'] ?? 'Isi ulang saldo game favorit dan dompet digital seperti GoPay, OVO, DANA dengan margin keuntungan kompetitif.' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     3. FEATURES STRIP — Full-width bg-blue-600, 4 features
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-blue-600 text-white py-6">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="flex flex-wrap items-center justify-center md:justify-between gap-4">
            <div class="flex items-center gap-3 px-4">
                <span class="text-2xl">⚡</span>
                <span class="text-sm font-semibold">Transaksi Real-Time</span>
            </div>
            <div class="hidden md:block w-px h-8 bg-white opacity-30"></div>
            <div class="flex items-center gap-3 px-4">
                <span class="text-2xl">🔒</span>
                <span class="text-sm font-semibold">Keamanan Berlapis</span>
            </div>
            <div class="hidden md:block w-px h-8 bg-white opacity-30"></div>
            <div class="flex items-center gap-3 px-4">
                <span class="text-2xl">📊</span>
                <span class="text-sm font-semibold">Dashboard Analitik</span>
            </div>
            <div class="hidden md:block w-px h-8 bg-white opacity-30"></div>
            <div class="flex items-center gap-3 px-4">
                <span class="text-2xl">🎧</span>
                <span class="text-sm font-semibold">Support 24/7</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     4. SERVICES — py-20, 2-column grid, left blue border
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-2">Apa yang Kami Tawarkan</p>
            <h2 class="text-3xl font-bold text-[#1E3A5F]">Solusi Kami</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach([
                [$homepage['service_1_title'] ?? 'Pulsa All Operator', $homepage['service_1_description'] ?? 'Layanan pengisian pulsa untuk semua operator nasional dengan harga kompetitif, proses cepat, dan margin keuntungan yang menarik bagi setiap agen.', '📱'],
                [$homepage['service_2_title'] ?? 'PPOB & Tagihan', $homepage['service_2_description'] ?? 'Pembayaran tagihan listrik, air, BPJS, telepon, internet, dan berbagai layanan publik lainnya dalam satu platform terintegrasi.', '🏠'],
                [$homepage['service_3_title'] ?? 'Top-Up Game', $homepage['service_3_description'] ?? 'Isi ulang lebih dari 100 game populer di Indonesia dengan harga terbaik dan proses yang sangat cepat untuk kepuasan pelanggan.', '🎮'],
                [$homepage['service_4_title'] ?? 'E-Money & E-Wallet', $homepage['service_4_description'] ?? 'Pengisian saldo GoPay, OVO, DANA, ShopeePay, dan dompet digital lainnya dengan sistem yang handal dan terpercaya.', '💳'],
            ] as $svc)
            <div class="border-l-4 border-blue-600 bg-white shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4">
                    <span class="text-3xl shrink-0">{{ $svc[2] }}</span>
                    <div>
                        <h3 class="font-bold text-[#1E3A5F] mb-2">{{ $svc[0] }}</h3>
                        <p class="text-sm text-gray-500 leading-relaxed">{{ $svc[1] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     5. STATS — 4-column grid, bg-[#1E3A5F] text-white
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach([
                ['100K+', 'Pengguna Aktif', '👥'],
                ['500+', 'Produk Tersedia', '📦'],
                ['99.9%', 'Uptime Server', '⚙️'],
                ['50+', 'Partner Resmi', '🤝'],
            ] as $stat)
            <div class="bg-[#1E3A5F] text-white p-8 text-center">
                <p class="text-3xl mb-1">{{ $stat[2] }}</p>
                <p class="text-4xl font-bold text-white mb-2">{{ $stat[0] }}</p>
                <p class="text-sm text-blue-200 uppercase tracking-wider">{{ $stat[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     6. BLOG — 3 cards, top blue border
     ═══════════════════════════════════════════════════════════ --}}
@if($latestBlogs->isNotEmpty())
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-blue-600 mb-1">Insight &amp; Update</p>
                <h2 class="text-2xl font-bold text-[#1E3A5F]">Artikel Terbaru</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700 border border-blue-600 px-4 py-2">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestBlogs as $blog)
            <article class="bg-white border-t-4 border-blue-600 shadow-sm hover:shadow-md transition-shadow">
                @if($blog->thumbnail)
                <img src="{{ \Storage::url($blog->thumbnail) }}" alt="{{ $blog->title }}"
                     class="w-full h-44 object-cover">
                @else
                <div class="w-full h-44 bg-blue-50 flex items-center justify-center">
                    <span class="text-5xl">📄</span>
                </div>
                @endif
                <div class="p-6">
                    <p class="text-xs font-semibold text-blue-600 uppercase tracking-wider mb-2">
                        {{ $blog->published_at?->format('d M Y') }}
                    </p>
                    <h3 class="font-bold text-[#1E3A5F] mb-3 line-clamp-2 leading-snug">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-blue-600">{{ $blog->title }}</a>
                    </h3>
                    @if($blog->excerpt)
                    <p class="text-sm text-gray-500 line-clamp-2">{{ $blog->excerpt }}</p>
                    @endif
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════
     7. CTA — bg-[#1E3A5F], text-white, solid blue button
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-[#1E3A5F] text-white py-20">
    <div class="max-w-4xl mx-auto px-4 lg:px-8 text-center">
        <p class="text-xs font-bold uppercase tracking-widest text-blue-300 mb-4">Bergabung Bersama Kami</p>
        <h2 class="text-4xl font-bold text-white mb-6">
            {{ $homepage['cta_title'] ?? 'Siap Memulai Bisnis Digital?' }}
        </h2>
        <p class="text-lg text-blue-200 mb-10 max-w-2xl mx-auto leading-relaxed">
            {{ $homepage['cta_description'] ?? 'Lebih dari 100.000 agen telah bergabung dan merasakan manfaat platform kami. Daftarkan diri Anda sekarang dan mulai perjalanan bisnis digital Anda.' }}
        </p>
        <a href="{{ $homepage['cta_button_url'] ?? url('/kontak') }}"
           class="inline-block bg-blue-600 text-white font-bold px-10 py-4 text-sm uppercase tracking-wider hover:bg-blue-500">
            {{ $homepage['cta_button_text'] ?? 'Daftar Sekarang — Gratis' }}
        </a>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     8. FOOTER — bg-[#1E3A5F], white text, 3-column
     ═══════════════════════════════════════════════════════════ --}}
<footer class="bg-[#1E3A5F] text-white py-10 border-t border-blue-800">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            {{-- Col 1: Brand --}}
            <div>
                <p class="text-lg font-bold text-white mb-3">{{ $siteName ?? config('app.name') }}</p>
                <p class="text-sm text-blue-200 leading-relaxed">
                    Platform transaksi digital terpercaya dengan teknologi real-time untuk semua kebutuhan agen digital Anda.
                </p>
            </div>

            {{-- Col 2: Layanan --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-blue-300 mb-4">Layanan</p>
                <ul class="space-y-2">
                    <li><a href="{{ url('/layanan') }}" class="text-sm text-blue-200 hover:text-white">Pulsa & Data</a></li>
                    <li><a href="{{ url('/layanan') }}" class="text-sm text-blue-200 hover:text-white">PPOB & Tagihan</a></li>
                    <li><a href="{{ url('/layanan') }}" class="text-sm text-blue-200 hover:text-white">Top-Up Game</a></li>
                    <li><a href="{{ url('/layanan') }}" class="text-sm text-blue-200 hover:text-white">E-Money</a></li>
                </ul>
            </div>

            {{-- Col 3: Kontak --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-blue-300 mb-4">Kontak</p>
                <ul class="space-y-2 text-sm text-blue-200">
                    <li>📧 {{ $homepage['contact_email'] ?? 'info@example.com' }}</li>
                    <li>📞 {{ $homepage['contact_phone'] ?? '+62 800 0000 0000' }}</li>
                    <li>📍 {{ $homepage['contact_address'] ?? 'Indonesia' }}</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-blue-800 pt-6 text-center">
            <p class="text-xs text-blue-400">&copy; {{ date('Y') }} {{ $siteName ?? config('app.name') }}. Hak cipta dilindungi.</p>
        </div>
    </div>
</footer>
