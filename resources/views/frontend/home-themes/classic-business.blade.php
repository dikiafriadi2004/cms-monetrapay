{{-- 2. HERO — traditional 3-row structure --}}
<section class="bg-[#FAFAF7] py-24">
    <div class="max-w-4xl mx-auto px-6 text-center">
        {{-- ROW 1: Small tagline --}}
        <p class="text-[#7C2D12] uppercase tracking-widest text-sm font-semibold mb-6">
            {{ $homepage['hero_subtitle'] ?? 'Platform Transaksi Digital Terpercaya di Indonesia' }}
        </p>

        {{-- ROW 2: Large heading --}}
        <h1 class="text-5xl font-black text-[#14532D] leading-tight mb-8">
            {{ $homepage['hero_title'] ?? 'Mitra Terpercaya untuk Bisnis Digital Anda' }}
        </h1>

        {{-- Decorative divider --}}
        <div class="w-16 h-1 bg-[#7C2D12] mx-auto mb-8"></div>

        <p class="text-gray-600 text-lg leading-relaxed max-w-2xl mx-auto mb-10">
            Mulai bisnis pulsa, PPOB, game, dan e-wallet dengan modal kecil. Lebih dari 1.000 agen aktif
            telah mempercayai kami sebagai platform transaksi digital andalan mereka.
        </p>

        {{-- ROW 3: Button row --}}
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ $homepage['hero_cta_url'] ?? url('/kontak') }}"
               class="bg-[#14532D] text-white px-8 py-3 font-medium text-sm hover:bg-[#0F3D1F] transition-colors">
                {{ $homepage['hero_cta_text'] ?? 'Daftar Menjadi Agen' }}
            </a>
            <a href="{{ url('/kontak') }}"
               class="border-2 border-[#14532D] text-[#14532D] px-8 py-3 font-medium text-sm bg-transparent hover:bg-[#14532D] hover:text-white transition-colors">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>

{{-- 3. SERVICES — 4-column icon grid --}}
<section class="py-20 bg-[#FAFAF7] border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-black text-[#14532D] mb-3">Layanan Profesional</h2>
            <div class="w-12 h-1 bg-[#7C2D12] mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            @foreach([
                ['📱', $homepage['service_1_title'] ?? 'Pulsa All Operator', $homepage['service_1_description'] ?? 'Semua operator nasional tersedia. Proses instan, tanpa antrian, refund otomatis jika gagal.'],
                ['🏠', $homepage['service_2_title'] ?? 'PPOB & Tagihan', $homepage['service_2_description'] ?? 'PLN, PDAM, BPJS, internet, TV kabel, dan ratusan tagihan lainnya dalam satu platform.'],
                ['🎮', $homepage['service_3_title'] ?? 'Top-Up Game', $homepage['service_3_description'] ?? 'Lebih dari 100 game populer. Mobile Legends, PUBG, Genshin Impact, Free Fire, dan lainnya.'],
                ['💳', $homepage['service_4_title'] ?? 'E-Money & E-Wallet', $homepage['service_4_description'] ?? 'Top-up GoPay, OVO, DANA, ShopeePay dengan kurs terbaik dan proses instan.'],
            ] as $service)
            <div class="text-center">
                <div class="w-12 h-12 rounded-full flex items-center justify-center bg-[#14532D] text-white text-xl mx-auto mb-4">
                    {{ $service[0] }}
                </div>
                <h3 class="font-bold text-[#14532D] text-base mb-2">{{ $service[1] }}</h3>
                <p class="text-gray-600 text-sm leading-relaxed">{{ $service[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 4. TESTIMONIALS --}}
<section class="py-20 bg-white border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-3xl font-black text-[#14532D] mb-3">Apa Kata Klien</h2>
            <div class="w-12 h-1 bg-[#7C2D12] mx-auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['"Platform ini benar-benar membantu bisnis saya berkembang. Transaksi selalu lancar dan margin keuntungannya sangat kompetitif."', 'Ahmad Reza', 'Agen Jakarta Selatan'],
                ['"Saya sudah coba beberapa platform lain, tapi yang ini yang terbaik. Support 24 jam sangat membantu saat ada kendala."', 'Siti Nurhaliza', 'Agen Bandung'],
                ['"Produk top-up game lengkap sekali. Pelanggan saya puas karena prosesnya cepat dan tidak pernah gagal."', 'Budi Santoso', 'Agen Surabaya'],
            ] as $testimonial)
            <div class="border border-gray-200 p-8">
                <p class="italic text-gray-600 leading-relaxed mb-6 text-sm">
                    {{ $testimonial[0] }}
                </p>
                <div class="border-t border-gray-100 pt-4">
                    <p class="font-bold text-[#14532D] text-sm">{{ $testimonial[1] }}</p>
                    <p class="text-gray-500 text-xs mt-1">{{ $testimonial[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 5. STATS --}}
<section class="bg-[#14532D] text-white py-16">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            @foreach([
                ['1.000+', 'Agen Aktif'],
                ['500+', 'Produk Digital'],
                ['99.9%', 'Uptime Sistem'],
                ['24/7', 'Layanan Support'],
            ] as $stat)
            <div>
                <p class="text-4xl font-black text-white mb-2">{{ $stat[0] }}</p>
                <p class="text-white/70 text-sm uppercase tracking-wide">{{ $stat[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- BLOG --}}
@if($latestBlogs->isNotEmpty())
<section class="py-16 bg-[#FAFAF7] border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-2xl font-black text-[#14532D]">Berita & Artikel</h2>
                <div class="w-10 h-1 bg-[#7C2D12] mt-2"></div>
            </div>
            <a href="{{ route('blog.index') }}" class="text-[#14532D] font-medium text-sm hover:text-[#0F3D1F] transition-colors">
                Selengkapnya →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestBlogs as $blog)
            <a href="{{ route('blog.show', $blog->slug) }}" class="block border border-gray-200 bg-white hover:border-[#14532D] transition-colors group">
                @if($blog->featured_image)
                <img src="{{ Storage::url($blog->featured_image) }}" class="w-full h-44 object-cover" alt="{{ $blog->title }}">
                @else
                <div class="w-full h-44 bg-gray-100 flex items-center justify-center">
                    <span class="text-4xl text-gray-300">📰</span>
                </div>
                @endif
                <div class="p-6 border-t-4 border-[#14532D]">
                    <p class="text-[#7C2D12] text-xs uppercase tracking-widest mb-2 font-medium">
                        {{ $blog->published_at?->format('d F Y') }}
                    </p>
                    <h3 class="font-bold text-gray-900 line-clamp-2 group-hover:text-[#14532D] transition-colors text-sm leading-snug">
                        {{ $blog->title }}
                    </h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 6. CTA --}}
<section class="bg-[#14532D] text-white py-24">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="text-5xl font-black text-white leading-tight mb-4">
            {{ $homepage['cta_title'] ?? 'Bergabung Bersama Kami' }}
        </h2>
        <p class="text-white/70 text-lg mb-10">
            {{ $homepage['cta_description'] ?? 'Lebih dari 1.000 mitra telah mempercayai kami. Daftar gratis dan mulai hasilkan dari transaksi digital.' }}
        </p>
        <a href="{{ $homepage['cta_button_url'] ?? url('/kontak') }}"
           class="inline-block border-2 border-white text-white bg-transparent px-10 py-4 font-bold text-sm uppercase tracking-wider hover:bg-white hover:text-[#14532D] transition-colors">
            {{ $homepage['cta_button_text'] ?? 'Daftar Menjadi Mitra' }}
        </a>
    </div>
</section>

{{-- 7. FOOTER --}}
