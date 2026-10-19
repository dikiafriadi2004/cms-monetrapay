{{--
    E-Commerce Style Home — Product Catalog / Marketplace Style
    Colors: White bg, Green #15803D, Orange #D97706
    NO gradients. All sections self-contained.
--}}

{{-- ═══════════════════════════════════════════════════════════
     1. ANNOUNCEMENT BAR — bg-green-700 text-white text-center
     ═══════════════════════════════════════════════════════════ --}}
<div class="bg-green-700 text-white text-center text-sm py-2 px-4">
    🎉 Selamat datang di <strong>{{ $siteName ?? config('app.name') }}</strong> — Layanan Terbaik untuk Anda
</div>

{{-- ═══════════════════════════════════════════════════════════
     2. NAV — White bg border-b, site name bold green, orange CTA
     ═══════════════════════════════════════════════════════════ --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between py-4">
            {{-- Site Name --}}
            <a href="{{ url('/') }}" class="text-xl font-bold text-green-700">
                {{ $siteName ?? config('app.name') }}
            </a>

            {{-- Nav links --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-sm font-semibold text-gray-700 hover:text-green-700">Beranda</a>
                <a href="{{ url('/layanan') }}" class="text-sm font-semibold text-gray-700 hover:text-green-700">Layanan</a>
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold text-gray-700 hover:text-green-700">Artikel</a>
                <a href="{{ url('/kontak') }}" class="text-sm font-semibold text-gray-700 hover:text-green-700">Kontak</a>
            </div>

            {{-- Orange CTA --}}
            <a href="{{ url('/kontak') }}" class="bg-orange-600 text-white font-bold px-5 py-2 text-sm hover:bg-orange-700">
                🛒 Daftar Agen
            </a>
        </div>
    </div>
</nav>

{{-- ═══════════════════════════════════════════════════════════
     3. HERO — py-16, two-column layout
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-white py-16 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-4 gap-10 items-center">

            {{-- LEFT col-span-2: heading + subtitle + green CTA --}}
            <div class="col-span-4 md:col-span-2">
                <span class="inline-block text-xs font-bold uppercase tracking-widest bg-green-100 text-green-700 px-3 py-1 mb-4">
                    🌟 Platform Digital #1
                </span>
                <h1 class="text-5xl font-black text-gray-900 leading-tight mb-6">
                    {{ $homepage['hero_title'] ?? 'Transaksi Digital Mudah, Cepat & Menguntungkan' }}
                </h1>
                <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                    {{ $homepage['hero_subtitle'] ?? 'Bergabung bersama ratusan ribu agen sukses di seluruh Indonesia. Dapatkan margin terbaik untuk pulsa, PPOB, game, dan e-money.' }}
                </p>
                <a href="{{ $homepage['hero_cta_url'] ?? url('/kontak') }}"
                   class="inline-block bg-green-700 text-white font-bold px-8 py-4 text-sm uppercase tracking-wider hover:bg-green-800">
                    {{ $homepage['hero_cta_text'] ?? 'Daftar Jadi Agen — Gratis' }} &rarr;
                </a>
            </div>

            {{-- RIGHT col-span-2: 2x2 product category boxes (decorative, solid colors, no gradient) --}}
            <div class="col-span-4 md:col-span-2 grid grid-cols-2 gap-3">
                {{-- Box 1: green --}}
                <div class="bg-green-700 text-white p-6 flex flex-col items-center justify-center text-center aspect-square">
                    <span class="text-4xl mb-3">📱</span>
                    <p class="font-bold text-base">Pulsa &amp; Data</p>
                    <p class="text-xs text-green-200 mt-1">All Operator</p>
                </div>
                {{-- Box 2: orange --}}
                <div class="bg-orange-600 text-white p-6 flex flex-col items-center justify-center text-center aspect-square">
                    <span class="text-4xl mb-3">🏠</span>
                    <p class="font-bold text-base">PPOB</p>
                    <p class="text-xs text-orange-200 mt-1">Bayar Tagihan</p>
                </div>
                {{-- Box 3: dark green --}}
                <div class="bg-green-900 text-white p-6 flex flex-col items-center justify-center text-center aspect-square">
                    <span class="text-4xl mb-3">🎮</span>
                    <p class="font-bold text-base">Top-Up Game</p>
                    <p class="text-xs text-green-300 mt-1">100+ Game</p>
                </div>
                {{-- Box 4: dark orange --}}
                <div class="bg-orange-800 text-white p-6 flex flex-col items-center justify-center text-center aspect-square">
                    <span class="text-4xl mb-3">💳</span>
                    <p class="font-bold text-base">E-Money</p>
                    <p class="text-xs text-orange-200 mt-1">Dompet Digital</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     4. SERVICES / PRODUCT GRID — py-20, 2x2 grid
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-gray-50 py-20">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-xs font-bold uppercase tracking-widest text-green-700 mb-2">Produk Unggulan</p>
            <h2 class="text-3xl font-black text-gray-900">Layanan Unggulan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Card 1: green top strip --}}
            <div class="bg-white shadow-sm hover:shadow-md transition-shadow">
                <div class="h-2 bg-green-700"></div>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-3xl">📱</span>
                        <h3 class="text-lg font-bold text-gray-900">{{ $homepage['service_1_title'] ?? 'Pulsa All Operator' }}</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">{{ $homepage['service_1_description'] ?? 'Tersedia untuk semua operator nasional. Harga terjangkau dengan margin keuntungan yang menarik untuk setiap agen.' }}</p>
                    <span class="text-sm font-bold text-green-700">Keuntungan kompetitif ✓</span>
                    <div class="mt-4">
                        <a href="{{ url('/layanan') }}" class="text-sm font-semibold text-green-700 hover:text-green-800 border-b border-green-700">Lihat Detail &rarr;</a>
                    </div>
                </div>
            </div>

            {{-- Card 2: orange top strip --}}
            <div class="bg-white shadow-sm hover:shadow-md transition-shadow">
                <div class="h-2 bg-orange-600"></div>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-3xl">🏠</span>
                        <h3 class="text-lg font-bold text-gray-900">{{ $homepage['service_2_title'] ?? 'PPOB & Tagihan' }}</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">{{ $homepage['service_2_description'] ?? 'Bayar listrik, air, BPJS, telepon, dan ratusan tagihan lainnya. Proses cepat dengan komisi menarik setiap transaksi.' }}</p>
                    <span class="text-sm font-bold text-orange-600">Komisi setiap transaksi ✓</span>
                    <div class="mt-4">
                        <a href="{{ url('/layanan') }}" class="text-sm font-semibold text-green-700 hover:text-green-800 border-b border-green-700">Lihat Detail &rarr;</a>
                    </div>
                </div>
            </div>

            {{-- Card 3: dark green top strip --}}
            <div class="bg-white shadow-sm hover:shadow-md transition-shadow">
                <div class="h-2 bg-green-900"></div>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-3xl">🎮</span>
                        <h3 class="text-lg font-bold text-gray-900">{{ $homepage['service_3_title'] ?? 'Top-Up Game' }}</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">{{ $homepage['service_3_description'] ?? 'Top-up lebih dari 100 game populer dengan harga terbaik. Proses instan untuk kepuasan pelanggan Anda.' }}</p>
                    <span class="text-sm font-bold text-green-900">100+ game tersedia ✓</span>
                    <div class="mt-4">
                        <a href="{{ url('/layanan') }}" class="text-sm font-semibold text-green-700 hover:text-green-800 border-b border-green-700">Lihat Detail &rarr;</a>
                    </div>
                </div>
            </div>

            {{-- Card 4: dark orange top strip --}}
            <div class="bg-white shadow-sm hover:shadow-md transition-shadow">
                <div class="h-2 bg-orange-800"></div>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-3xl">💳</span>
                        <h3 class="text-lg font-bold text-gray-900">{{ $homepage['service_4_title'] ?? 'E-Money & E-Wallet' }}</h3>
                    </div>
                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">{{ $homepage['service_4_description'] ?? 'Isi saldo GoPay, OVO, DANA, ShopeePay, dan berbagai dompet digital lainnya dengan sistem handal dan terpercaya.' }}</p>
                    <span class="text-sm font-bold text-orange-800">Multi e-wallet ✓</span>
                    <div class="mt-4">
                        <a href="{{ url('/layanan') }}" class="text-sm font-semibold text-green-700 hover:text-green-800 border-b border-green-700">Lihat Detail &rarr;</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     5. STATS — "Mengapa Kami?" 3-column WHY US section
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-white py-20 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="text-center mb-12">
            <p class="text-xs font-bold uppercase tracking-widest text-green-700 mb-2">Keunggulan Kami</p>
            <h2 class="text-3xl font-black text-gray-900">Mengapa Kami?</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Column 1 --}}
            <div>
                <div class="w-12 h-12 bg-green-700 flex items-center justify-center mb-4">
                    <span class="text-2xl text-white">🏆</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-4 text-lg">{{ $homepage['service_1_title'] ?? 'Harga Terbaik' }}</h3>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-700 font-bold">✓</span>
                        Margin keuntungan kompetitif
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-700 font-bold">✓</span>
                        Tidak ada biaya pendaftaran
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-700 font-bold">✓</span>
                        Bonus member aktif bulanan
                    </li>
                </ul>
            </div>

            {{-- Column 2 --}}
            <div>
                <div class="w-12 h-12 bg-orange-600 flex items-center justify-center mb-4">
                    <span class="text-2xl text-white">⚡</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-4 text-lg">{{ $homepage['service_2_title'] ?? 'Proses Cepat' }}</h3>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-700 font-bold">✓</span>
                        Transaksi real-time 24 jam
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-700 font-bold">✓</span>
                        Uptime server 99.9%
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-700 font-bold">✓</span>
                        Antrian transaksi tanpa delay
                    </li>
                </ul>
            </div>

            {{-- Column 3 --}}
            <div>
                <div class="w-12 h-12 bg-green-900 flex items-center justify-center mb-4">
                    <span class="text-2xl text-white">🛡️</span>
                </div>
                <h3 class="font-bold text-gray-900 mb-4 text-lg">{{ $homepage['service_3_title'] ?? 'Terpercaya &amp; Aman' }}</h3>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-700 font-bold">✓</span>
                        Sistem keamanan berlapis
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-700 font-bold">✓</span>
                        Data terenkripsi penuh
                    </li>
                    <li class="flex items-center gap-2 text-sm text-gray-600">
                        <span class="text-green-700 font-bold">✓</span>
                        Support 24/7 siap membantu
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     6. BLOG — Grid 3 articles, solid green placeholder
     ═══════════════════════════════════════════════════════════ --}}
@if($latestBlogs->isNotEmpty())
<section class="bg-gray-50 py-16 border-t border-gray-100">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-green-700 mb-1">Tips &amp; Update</p>
                <h2 class="text-2xl font-black text-gray-900">Artikel Terbaru</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-bold text-orange-600 hover:text-orange-700 border border-orange-600 px-4 py-2">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestBlogs as $blog)
            <article class="bg-white shadow-sm hover:shadow-md transition-shadow">
                {{-- Image or solid green placeholder --}}
                @if($blog->thumbnail)
                <img src="{{ \Storage::url($blog->thumbnail) }}" alt="{{ $blog->title }}"
                     class="w-full h-44 object-cover">
                @else
                <div class="w-full h-44 bg-green-700 flex items-center justify-center">
                    <span class="text-5xl">📰</span>
                </div>
                @endif

                <div class="p-5">
                    <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 leading-snug">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-green-700">{{ $blog->title }}</a>
                    </h3>
                    @if($blog->excerpt)
                    <p class="text-sm text-gray-500 line-clamp-2 mb-3">{{ $blog->excerpt }}</p>
                    @endif
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-400">{{ $blog->published_at?->format('d M Y') }}</span>
                        <a href="{{ route('blog.show', $blog->slug) }}"
                           class="text-sm font-bold text-orange-600 hover:text-orange-700">
                            Baca &rarr;
                        </a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════
     7. CTA — bg-green-700, text-white, orange button solid
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-green-700 text-white py-20">
    <div class="max-w-4xl mx-auto px-4 lg:px-8 text-center">
        <p class="text-xs font-bold uppercase tracking-widest text-green-300 mb-4">Bergabung Sekarang</p>
        <h2 class="text-4xl font-black text-white mb-6">
            {{ $homepage['cta_title'] ?? 'Mulai Hasilkan Pendapatan Hari Ini' }}
        </h2>
        <p class="text-lg text-green-200 mb-10 max-w-2xl mx-auto leading-relaxed">
            {{ $homepage['cta_description'] ?? 'Bergabung bersama lebih dari 100.000 agen sukses di seluruh Indonesia. Tidak ada biaya pendaftaran, tidak ada minimum deposit awal.' }}
        </p>
        <a href="{{ $homepage['cta_button_url'] ?? url('/kontak') }}"
           class="inline-block bg-orange-600 text-white font-bold px-10 py-4 text-sm uppercase tracking-wider hover:bg-orange-700">
            {{ $homepage['cta_button_text'] ?? 'Daftar Jadi Agen — Gratis' }}
        </a>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     8. FOOTER — Dark bg, 3-column grid, green accent links
     ═══════════════════════════════════════════════════════════ --}}
<footer class="bg-gray-900 text-white py-14">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 mb-10">
            {{-- Col 1: Brand --}}
            <div>
                <p class="text-xl font-black text-white mb-3">{{ $siteName ?? config('app.name') }}</p>
                <p class="text-sm text-gray-400 leading-relaxed mb-4">
                    Platform transaksi digital terpercaya untuk agen se-Indonesia. Mulai bisnis Anda sekarang.
                </p>
                <div class="flex gap-3">
                    <span class="text-2xl cursor-pointer hover:opacity-75">📱</span>
                    <span class="text-2xl cursor-pointer hover:opacity-75">💬</span>
                    <span class="text-2xl cursor-pointer hover:opacity-75">📧</span>
                </div>
            </div>

            {{-- Col 2: Navigasi --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-green-500 mb-4 pb-2 border-b border-gray-800">Navigasi</p>
                <ul class="space-y-2">
                    <li><a href="{{ url('/') }}" class="text-sm text-gray-400 hover:text-green-400">Beranda</a></li>
                    <li><a href="{{ url('/layanan') }}" class="text-sm text-gray-400 hover:text-green-400">Layanan</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-sm text-gray-400 hover:text-green-400">Artikel</a></li>
                    <li><a href="{{ url('/kontak') }}" class="text-sm text-gray-400 hover:text-green-400">Kontak</a></li>
                </ul>
            </div>

            {{-- Col 3: Kontak --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-green-500 mb-4 pb-2 border-b border-gray-800">Hubungi Kami</p>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li>📧 {{ $homepage['contact_email'] ?? 'info@example.com' }}</li>
                    <li>📞 {{ $homepage['contact_phone'] ?? '+62 800 0000 0000' }}</li>
                    <li>📍 {{ $homepage['contact_address'] ?? 'Indonesia' }}</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-6 flex flex-col md:flex-row items-center justify-between gap-2">
            <p class="text-xs text-gray-500">&copy; {{ date('Y') }} {{ $siteName ?? config('app.name') }}. Hak cipta dilindungi.</p>
            <p class="text-xs text-gray-600">Dibuat dengan ❤️ untuk agen Indonesia</p>
        </div>
    </div>
</footer>
