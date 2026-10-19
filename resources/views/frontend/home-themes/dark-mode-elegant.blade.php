{{-- HERO --}}
<section class="bg-[#0A0A0A] min-h-screen flex items-center justify-center text-center px-6">
    <div class="max-w-3xl">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-8">Platform Transaksi Digital</p>
        <h1 class="text-6xl font-bold text-white leading-tight mb-0" style="letter-spacing:-0.04em">
            {{ $homepage['hero_title'] ?? 'Transaksi Digital Tanpa Batas' }}
        </h1>
        <div class="border-t border-gray-600 w-24 mx-auto my-8"></div>
        <p class="text-gray-400 text-xl leading-relaxed mb-12 max-w-xl mx-auto">
            {{ $homepage['hero_subtitle'] ?? 'Pulsa, PPOB, game, dan e-wallet dalam satu platform yang andal dan efisien.' }}
        </p>
        <div class="flex items-center justify-center gap-10">
            <a href="{{ url('/layanan') }}" class="text-white underline underline-offset-4 text-sm font-medium hover:text-gray-300 transition-colors">→ Lihat Layanan</a>
            <a href="{{ url('/kontak') }}" class="text-white underline underline-offset-4 text-sm font-medium hover:text-gray-300 transition-colors">→ Hubungi Kami</a>
        </div>
    </div>
</section>

{{-- SERVICES: Single column list --}}
<section class="bg-[#111111] py-20 px-6">
    <div class="max-w-4xl mx-auto">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-2">Yang Kami Tawarkan</p>
        <h2 class="text-3xl font-bold text-white mb-12">Layanan Kami</h2>
        <div class="space-y-0">
            @foreach([
                ['📱', $homepage['service_1_title'] ?? 'Pulsa All Operator', $homepage['service_1_description'] ?? 'Semua operator tersedia dengan harga kompetitif dan proses instan tanpa hambatan.'],
                ['🏠', $homepage['service_2_title'] ?? 'PPOB & Tagihan', $homepage['service_2_description'] ?? 'Bayar listrik, air, BPJS, internet, dan berbagai tagihan lain dalam satu tempat.'],
                ['🎮', $homepage['service_3_title'] ?? 'Top-Up Game', $homepage['service_3_description'] ?? 'Lebih dari 100 game populer tersedia. Top-up instan untuk pengalaman bermain terbaik.'],
                ['💳', $homepage['service_4_title'] ?? 'E-Money & E-Wallet', $homepage['service_4_description'] ?? 'Isi saldo GoPay, OVO, DANA, dan ShopeePay dengan mudah dan aman.'],
            ] as $svc)
            <div class="flex items-start gap-6 py-7 border-b border-white/10">
                <span class="text-2xl shrink-0 mt-0.5">{{ $svc[0] }}</span>
                <div>
                    <h3 class="font-bold text-white text-base mb-1">{{ $svc[1] }}</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">{{ $svc[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- STATS: 2x2 grid --}}
<section class="bg-[#0D0D0D] py-16 px-6">
    <div class="max-w-4xl mx-auto">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-10">Angka yang Berbicara</p>
        <div class="grid grid-cols-2 gap-4">
            @foreach([['100K+','Pengguna Aktif'],['500+','Produk Digital'],['99.9%','Uptime Terjamin'],['24/7','Layanan Support']] as $stat)
            <div class="bg-[#1A1A1A] px-8 py-10">
                <p class="text-4xl font-bold text-white mb-2">{{ $stat[0] }}</p>
                <p class="text-sm text-gray-400 uppercase tracking-widest">{{ $stat[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- LATEST BLOGS --}}
@if($latestBlogs->isNotEmpty())
<section class="bg-[#0A0A0A] py-16 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-10">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-2">Wawasan</p>
                <h2 class="text-2xl font-bold text-white">Artikel Terbaru</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm text-white underline underline-offset-4 hover:text-gray-300 transition-colors">Lihat Semua →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($latestBlogs as $blog)
            <article class="bg-[#111111] border border-white/10 overflow-hidden group">
                @if($blog->featured_image)
                    <img src="{{ Storage::url($blog->featured_image) }}" class="w-full h-48 object-cover" alt="{{ $blog->title }}">
                @else
                    <div class="w-full h-48 bg-[#1A1A1A] flex items-center justify-center">
                        <span class="text-4xl">📄</span>
                    </div>
                @endif
                <div class="p-6">
                    <p class="text-xs text-gray-500 mb-3">{{ $blog->published_at?->format('d M Y') }}</p>
                    <h3 class="font-bold text-white text-sm leading-snug mb-3 line-clamp-2 group-hover:text-gray-300 transition-colors">
                        {{ $blog->title }}
                    </h3>
                    @if($blog->excerpt)
                    <p class="text-xs text-gray-500 leading-relaxed mb-4">{{ Str::limit($blog->excerpt, 80) }}</p>
                    @endif
                    <a href="{{ route('blog.show', $blog->slug) }}" class="text-xs text-white underline underline-offset-2 hover:text-gray-400 transition-colors">Baca selengkapnya →</a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="bg-[#111111] border border-white/10 py-20 px-6 text-center">
    <div class="max-w-2xl mx-auto">
        <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 mb-6">Bergabung Bersama Kami</p>
        <h2 class="text-4xl font-bold text-white mb-4" style="letter-spacing:-0.03em">
            {{ $homepage['cta_title'] ?? 'Siap Memulai?' }}
        </h2>
        <p class="text-gray-400 text-lg mb-10">
            {{ $homepage['cta_description'] ?? 'Mulai bisnis digital Anda hari ini bersama ribuan mitra terpercaya kami.' }}
        </p>
        <a href="{{ $homepage['cta_button_url'] ?? url('/kontak') }}"
           class="inline-block border border-white text-white px-10 py-4 text-sm font-semibold uppercase tracking-wider hover:bg-white hover:text-black transition-colors">
            {{ $homepage['cta_button_text'] ?? 'Daftar Sekarang' }}
        </a>
    </div>
</section>

{{-- FOOTER --}}
