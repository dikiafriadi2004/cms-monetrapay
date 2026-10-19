{{-- ═══════════════════════════════════════════════════════════
     2. HERO — Full-width newspaper front page style
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-white py-12 border-t-4 border-red-700">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">

        {{-- Edition label --}}
        <div class="mb-4">
            <span class="text-xs font-black uppercase tracking-widest text-red-700" style="font-variant:small-caps;letter-spacing:0.15em">&#9632; Edisi Terkini &nbsp;|&nbsp; {{ date('d F Y') }}</span>
        </div>

        {{-- Full-width bold headline --}}
        <h1 class="text-5xl font-black leading-tight text-gray-900 mb-6 pb-6 border-b-2 border-gray-900" style="font-family:Georgia,'Times New Roman',serif">
            {{ $homepage['hero_title'] ?? 'Headline Utama Platform Digital' }}
        </h1>

        {{-- 2-column split: col-span-2 + col-span-1 --}}
        <div class="grid grid-cols-3 gap-8 pt-4">
            {{-- Left: lead paragraph --}}
            <div class="col-span-2 border-r border-gray-300 pr-8">
                <p class="text-lg leading-relaxed text-gray-700 mb-6" style="font-family:Georgia,serif">
                    {{ $homepage['hero_subtitle'] ?? 'Platform transaksi digital terpercaya hadir untuk memudahkan ribuan agen di seluruh Indonesia. Kelola pulsa, PPOB, top-up game, dan dompet digital dalam satu dashboard terintegrasi dengan teknologi terkini.' }}
                </p>
                <a href="{{ $homepage['hero_cta_url'] ?? url('/kontak') }}"
                   class="inline-block bg-red-700 text-white font-bold px-6 py-3 text-sm uppercase tracking-wider hover:bg-red-800">
                    {{ $homepage['hero_cta_text'] ?? 'Mulai Sekarang' }} &rarr;
                </a>
            </div>

            {{-- Right: latest article titles --}}
            <div class="col-span-1 pl-4">
                <p class="text-xs font-black uppercase tracking-widest text-red-700 mb-3 pb-2 border-b border-red-700">Artikel Terbaru</p>
                @if($latestBlogs->isNotEmpty())
                    <ul class="space-y-3">
                        @foreach($latestBlogs->take(3) as $blog)
                        <li class="flex items-start gap-2">
                            <span class="text-red-700 font-black shrink-0 mt-0.5">&#9679;</span>
                            <a href="{{ route('blog.show', $blog->slug) }}"
                               class="text-sm font-semibold text-gray-800 hover:text-red-700 leading-snug line-clamp-2" style="font-family:Georgia,serif">
                                {{ $blog->title }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                @else
                    <ul class="space-y-3">
                        <li class="flex items-start gap-2"><span class="text-red-700 font-black shrink-0">&#9679;</span><span class="text-sm text-gray-500">Belum ada artikel terbaru.</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-700 font-black shrink-0">&#9679;</span><span class="text-sm text-gray-500">Artikel akan segera ditambahkan.</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-700 font-black shrink-0">&#9679;</span><span class="text-sm text-gray-500">Kunjungi kembali halaman ini.</span></li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     3. SERVICES — Horizontal pills + compact 2-column table
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-white py-12 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">

        <div class="flex items-center gap-4 mb-6">
            <h2 class="text-xl font-black uppercase tracking-widest text-gray-900 whitespace-nowrap" style="font-family:Georgia,serif">Layanan Kami</h2>
            <div class="flex-1 h-px bg-gray-300"></div>
        </div>

        {{-- Horizontal pills row --}}
        <div class="flex flex-wrap gap-2 mb-8">
            @foreach([
                $homepage['service_1_title'] ?? 'Pulsa All Operator',
                $homepage['service_2_title'] ?? 'PPOB & Tagihan',
                $homepage['service_3_title'] ?? 'Top-Up Game',
                $homepage['service_4_title'] ?? 'E-Money & Dompet Digital',
            ] as $pill)
            <span class="border border-gray-400 rounded-full px-4 py-1 text-sm inline-block text-gray-700 hover:border-red-700 hover:text-red-700 cursor-default">
                {{ $pill }}
            </span>
            @endforeach
        </div>

        {{-- Compact 2-column table --}}
        <div class="border border-gray-200">
            @foreach([
                [$homepage['service_1_title'] ?? 'Pulsa All Operator', $homepage['service_1_description'] ?? 'Tersedia untuk semua operator nasional dengan harga kompetitif dan proses real-time.'],
                [$homepage['service_2_title'] ?? 'PPOB & Tagihan', $homepage['service_2_description'] ?? 'Bayar listrik, air, BPJS, telepon, dan berbagai tagihan lainnya dalam satu platform.'],
                [$homepage['service_3_title'] ?? 'Top-Up Game', $homepage['service_3_description'] ?? 'Top-up lebih dari 100 game populer dengan harga terbaik dan proses instan.'],
                [$homepage['service_4_title'] ?? 'E-Money & Dompet Digital', $homepage['service_4_description'] ?? 'Isi saldo GoPay, OVO, DANA, dan dompet digital lainnya dengan mudah.'],
            ] as $i => $svc)
            <div class="grid grid-cols-2 border-b border-gray-200 {{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                <div class="px-5 py-4 border-r border-gray-200">
                    <p class="font-bold text-gray-900 text-sm">{{ $svc[0] }}</p>
                </div>
                <div class="px-5 py-4">
                    <p class="text-sm text-gray-600">{{ $svc[1] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     4. STATS — Full-width light gray marquee-style
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-gray-100 py-4 border-t border-b border-gray-300">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="overflow-hidden">
            <div class="flex items-center gap-8 text-sm font-bold text-gray-700 uppercase tracking-widest whitespace-nowrap">
                <span class="text-red-700">&#9658;</span>
                <span>100K+ Pengguna</span>
                <span class="text-red-700">&#9658;</span>
                <span>500+ Produk</span>
                <span class="text-red-700">&#9658;</span>
                <span>99.9% Uptime</span>
                <span class="text-red-700">&#9658;</span>
                <span>50+ Partner</span>
                <span class="text-red-700">&#9658;</span>
                <span>Transaksi Real-Time</span>
                <span class="text-red-700">&#9658;</span>
                <span>Support 24/7</span>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     5. BLOG GRID — 3-column editorial article cards
     ═══════════════════════════════════════════════════════════ --}}
@if($latestBlogs->isNotEmpty())
<section class="bg-white py-14 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <h2 class="text-xl font-black uppercase tracking-widest text-gray-900 whitespace-nowrap" style="font-family:Georgia,serif">Artikel &amp; Berita</h2>
                <div class="w-24 h-px bg-gray-300"></div>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-bold text-red-700 hover:text-red-800 uppercase tracking-wide">Selengkapnya &rarr;</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-0 border border-gray-200">
            @foreach($latestBlogs as $blog)
            <article class="border-r border-gray-200 last:border-r-0">
                {{-- Image or solid red placeholder --}}
                @if($blog->featured_image)
                <img src="{{ \Storage::url($blog->featured_image) }}" alt="{{ $blog->title }}"
                     class="w-full h-44 object-cover">
                @else
                <div class="w-full h-44 bg-red-700 flex items-center justify-center">
                    <span class="text-4xl">📰</span>
                </div>
                @endif

                <div class="p-5">
                    {{-- Category tag --}}
                    @if($blog->category)
                    <span class="text-xs font-black uppercase tracking-widest text-red-700">{{ $blog->category->name }}</span>
                    @else
                    <span class="text-xs font-black uppercase tracking-widest text-red-700">Artikel</span>
                    @endif

                    <h3 class="font-black text-base mt-2 mb-3 text-gray-900 leading-snug line-clamp-3" style="font-family:Georgia,serif">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="hover:text-red-700">{{ $blog->title }}</a>
                    </h3>

                    @if($blog->excerpt)
                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $blog->excerpt }}</p>
                    @endif

                    <p class="text-xs text-gray-400 uppercase tracking-wider">{{ $blog->published_at?->format('d M Y') }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════════════════
     6. CTA — Left-aligned, large red heading
     ═══════════════════════════════════════════════════════════ --}}
<section class="bg-white py-16 border-t-4 border-red-700">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">
        <div class="max-w-2xl">
            <p class="text-xs font-black uppercase tracking-widest text-red-700 mb-3">&#9632; Bergabung Sekarang</p>
            <h2 class="text-4xl font-black text-red-700 leading-tight mb-4" style="font-family:Georgia,serif">
                {{ $homepage['cta_title'] ?? 'Jadilah Agen Digital Terpercaya' }}
            </h2>
            <p class="text-lg text-gray-600 mb-8" style="font-family:Georgia,serif">
                {{ $homepage['cta_description'] ?? 'Ribuan agen sudah membuktikan kemudahan dan keuntungan bertransaksi bersama kami. Daftarkan diri Anda sekarang dan mulai hasilkan pendapatan dari bisnis digital.' }}
            </p>
            <a href="{{ $homepage['cta_button_url'] ?? url('/kontak') }}"
               class="inline-block bg-red-700 text-white font-bold px-8 py-4 text-sm uppercase tracking-wider hover:bg-red-800">
                {{ $homepage['cta_button_text'] ?? 'Daftar Jadi Agen' }} &rarr;
            </a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════════════════
     7. FOOTER — Black bg, white text, 3-column grid
     ═══════════════════════════════════════════════════════════ --}}
