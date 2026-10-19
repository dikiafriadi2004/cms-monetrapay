{{-- 2. HERO — asymmetric layout --}}
<section class="bg-[#F5F5F0] min-h-screen flex items-center">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-24 w-full">
        <div class="flex items-center gap-0">
            {{-- LEFT: 3/5 --}}
            <div class="w-3/5 pr-16">
                <div class="flex items-center mb-8">
                    <span class="inline-block w-1 bg-[#B7860D] h-32 mr-8 shrink-0"></span>
                    <div>
                        <p class="text-gray-500 text-sm uppercase tracking-widest mb-4">
                            Platform Transaksi Digital
                        </p>
                        <h1 class="text-6xl font-black text-black leading-none">
                            {{ $homepage['hero_title'] ?? 'Karya Digital Terbaik untuk Bisnis Anda' }}
                        </h1>
                    </div>
                </div>
                <p class="text-gray-500 italic text-xl leading-relaxed mb-8 ml-9">
                    {{ $homepage['hero_subtitle'] ?? 'Solusi lengkap pulsa, tagihan, game, dan e-wallet yang andal dan menguntungkan.' }}
                </p>
                <div class="ml-9">
                    <a href="{{ url('/layanan') }}"
                       class="text-[#B7860D] underline text-lg font-medium hover:text-[#8B6309] transition-colors">
                        Lihat Karya →
                    </a>
                </div>
            </div>
            {{-- RIGHT: 2/5 — decorative large number --}}
            <div class="w-2/5 flex justify-end items-center select-none">
                <span class="text-[12rem] font-black text-gray-200 leading-none">08</span>
            </div>
        </div>
    </div>
</section>

{{-- 3. SERVICES — masonry-inspired uneven grid --}}
<section class="py-24 bg-[#F5F5F0] border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-3xl font-black text-black mb-12">Keahlian</h2>
        <div class="grid grid-cols-2 gap-4">
            {{-- LEFT column: two tall boxes --}}
            <div class="flex flex-col gap-4">
                <div class="bg-white border border-gray-200 p-10 min-h-[280px] relative flex flex-col justify-between">
                    <span class="absolute top-6 right-8 text-[#B7860D] font-black text-2xl opacity-30">01</span>
                    <div>
                        <h3 class="font-black text-xl text-black mb-3">{{ $homepage['service_1_title'] ?? 'Pulsa All Operator' }}</h3>
                        <p class="text-gray-500 leading-relaxed">
                            {{ $homepage['service_1_description'] ?? 'Semua operator nasional tersedia. Proses kilat, gagal otomatis refund, margin kompetitif untuk agen.' }}
                        </p>
                    </div>
                    <span class="text-3xl mt-6">📱</span>
                </div>
                <div class="bg-white border border-gray-200 p-10 min-h-[280px] relative flex flex-col justify-between">
                    <span class="absolute top-6 right-8 text-[#B7860D] font-black text-2xl opacity-30">02</span>
                    <div>
                        <h3 class="font-black text-xl text-black mb-3">{{ $homepage['service_2_title'] ?? 'PPOB & Tagihan' }}</h3>
                        <p class="text-gray-500 leading-relaxed">
                            {{ $homepage['service_2_description'] ?? 'PLN, PDAM, BPJS, internet, TV kabel, dan ratusan tagihan lainnya. Satu platform untuk semua.' }}
                        </p>
                    </div>
                    <span class="text-3xl mt-6">🏠</span>
                </div>
            </div>
            {{-- RIGHT column: short + shorter --}}
            <div class="flex flex-col gap-4">
                <div class="bg-white border border-gray-200 p-8 min-h-[180px] relative flex flex-col justify-between">
                    <span class="absolute top-5 right-6 text-[#B7860D] font-black text-2xl opacity-30">03</span>
                    <div>
                        <h3 class="font-black text-xl text-black mb-3">{{ $homepage['service_3_title'] ?? 'Top-Up Game' }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">
                            {{ $homepage['service_3_description'] ?? 'Lebih dari 100 game populer. Mobile Legends, PUBG, Genshin Impact, Free Fire, dan banyak lagi.' }}
                        </p>
                    </div>
                    <span class="text-2xl mt-4">🎮</span>
                </div>
                <div class="bg-[#B7860D] text-white p-8 min-h-[140px] relative flex flex-col justify-between">
                    <span class="absolute top-5 right-6 font-black text-2xl opacity-20 text-white">04</span>
                    <div>
                        <h3 class="font-black text-xl text-white mb-2">{{ $homepage['service_4_title'] ?? 'E-Money & E-Wallet' }}</h3>
                        <p class="text-white/75 text-sm leading-relaxed">
                            {{ $homepage['service_4_description'] ?? 'GoPay, OVO, DANA, ShopeePay. Top-up instan dengan kurs terbaik.' }}
                        </p>
                    </div>
                    <span class="text-2xl mt-4">💳</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- 4. STATS — horizontal separator --}}
<div class="border-t border-gray-300 bg-[#F5F5F0]">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
        <div class="flex justify-around items-center flex-wrap gap-6">
            @foreach([
                ['1.000+', 'Pengguna Aktif'],
                ['500+', 'Produk Tersedia'],
                ['99.9%', 'Uptime Sistem'],
                ['24/7', 'Tim Support'],
            ] as $stat)
            <div class="text-center">
                <p class="font-bold text-2xl text-[#B7860D]">{{ $stat[0] }}</p>
                <p class="text-gray-500 text-sm mt-1">{{ $stat[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- 5. PROCESS — horizontal timeline --}}
<section class="py-20 bg-[#F5F5F0] border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <h2 class="text-3xl font-black text-black mb-14">Proses Kami</h2>
        @php
            $steps = [
                ['1', 'Pendaftaran', 'Isi formulir gratis. Akun aktif dalam hitungan menit.'],
                ['2', 'Verifikasi', 'Tim kami memverifikasi data Anda dengan cepat dan aman.'],
                ['3', 'Deposit', 'Top-up saldo minimal Rp10.000 via transfer bank atau e-wallet.'],
                ['4', 'Mulai Bertransaksi', 'Akses semua produk dan raih keuntungan dari setiap transaksi.'],
            ];
        @endphp
        <div class="flex items-start">
            @foreach($steps as $index => $step)
            <div class="flex flex-col items-center flex-1 {{ $index === 0 ? '' : '' }}">
                <div class="flex items-center w-full">
                    @if($index > 0)
                    <div class="flex-1 border-t border-gray-300 mt-0"></div>
                    @endif
                    <div class="w-10 h-10 rounded-full border-2 border-[#B7860D] text-[#B7860D] flex items-center justify-center font-bold text-sm shrink-0">
                        {{ $step[0] }}
                    </div>
                    @if($index < count($steps) - 1)
                    <div class="flex-1 border-t border-gray-300 mt-0"></div>
                    @endif
                </div>
                <div class="text-center mt-4 px-3">
                    <h3 class="font-bold text-black text-sm mb-1">{{ $step[1] }}</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">{{ $step[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 6. BLOG --}}
@if($latestBlogs->isNotEmpty())
<section class="bg-white py-16 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl font-black text-black">Artikel Terbaru</h2>
            <a href="{{ route('blog.index') }}" class="text-[#B7860D] text-sm font-medium hover:text-[#8B6309] transition-colors">
                Semua Artikel →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestBlogs as $blog)
            <a href="{{ route('blog.show', $blog->slug) }}"
               class="block border border-gray-200 p-6 hover:border-[#B7860D] transition-colors group">
                <p class="text-[#B7860D] text-xs uppercase tracking-widest mb-3 font-medium">
                    {{ $blog->published_at?->format('d M Y') }}
                </p>
                <h3 class="font-bold text-black text-base leading-snug mb-3 line-clamp-2 group-hover:text-[#B7860D] transition-colors">
                    {{ $blog->title }}
                </h3>
                <p class="text-gray-500 text-sm line-clamp-3 leading-relaxed">
                    {{ $blog->excerpt ?? Str::limit(strip_tags($blog->content ?? ''), 100) }}
                </p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 7. CTA --}}
<section class="bg-white border border-gray-200 py-20 border-t-0">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h2 class="text-4xl font-black text-black mb-4">
            {{ $homepage['cta_title'] ?? 'Siap Memulai Bisnis Digital?' }}
        </h2>
        <p class="text-gray-500 text-lg mb-8">
            {{ $homepage['cta_description'] ?? 'Bergabung sekarang dan mulai hasilkan keuntungan dari transaksi digital.' }}
        </p>
        <a href="{{ $homepage['cta_button_url'] ?? url('/kontak') }}"
           class="inline-block border-2 border-[#B7860D] text-[#B7860D] font-bold px-10 py-4 hover:bg-[#B7860D] hover:text-white transition-colors">
            {{ $homepage['cta_button_text'] ?? 'Daftar Sekarang' }}
        </a>
    </div>
</section>

{{-- 8. FOOTER --}}
