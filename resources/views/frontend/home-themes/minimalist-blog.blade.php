{{-- HERO --}}
<section class="max-w-xl mx-auto px-6 py-24">
    <h1 class="text-5xl font-bold leading-tight tracking-tight text-gray-900 mb-0" style="font-family:Georgia,serif">
        {{ $homepage['hero_title'] ?? 'Bisnis Digital yang Lebih Sederhana' }}
    </h1>
    <div class="border-t border-gray-300 my-6"></div>
    <p class="text-lg text-gray-600 leading-relaxed">
        {{ $homepage['hero_subtitle'] ?? 'Satu platform untuk semua kebutuhan transaksi digital. Pulsa, PPOB, game, dan e-wallet.' }}
    </p>
    <div class="mt-8 flex items-center gap-6">
        <a href="{{ $homepage['hero_cta_url'] ?? url('/kontak') }}" class="text-sm font-semibold text-black underline underline-offset-4 hover:text-gray-600 transition-colors">
            {{ $homepage['hero_cta_text'] ?? 'Mulai Sekarang' }} →
        </a>
        <a href="{{ url('/layanan') }}" class="text-sm font-semibold text-gray-500 underline underline-offset-4 hover:text-gray-900 transition-colors">Lihat Layanan</a>
    </div>
</section>

{{-- BLOG SECTION (prominent, before services) --}}
@if($latestBlogs->isNotEmpty())
<section class="max-w-xl mx-auto px-6 pb-16">
    <div class="border-t border-gray-200 pt-10">
        <p class="text-xs font-black uppercase tracking-widest text-gray-400 mb-6">Terbaru</p>
        <div class="space-y-0">
            @foreach($latestBlogs as $loop_blog)
            <article class="py-5 border-t border-gray-100 first:border-t-0">
                <div class="flex items-baseline gap-4">
                    <time class="text-gray-400 text-sm shrink-0 w-24">{{ $loop_blog->published_at?->format('d M Y') }}</time>
                    <a href="{{ route('blog.show', $loop_blog->slug) }}" class="font-bold text-gray-900 hover:text-gray-600 leading-snug transition-colors">
                        {{ $loop_blog->title }}
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        <div class="mt-6 border-t border-gray-100 pt-6">
            <a href="{{ route('blog.index') }}" class="text-sm text-gray-500 underline underline-offset-4 hover:text-gray-900 transition-colors">Semua artikel →</a>
        </div>
    </div>
</section>
@endif

{{-- SERVICES --}}
<section class="max-w-xl mx-auto px-6 py-16 border-t border-gray-200">
    <p class="text-xs font-black uppercase tracking-widest text-gray-400 mb-6">Layanan</p>
    <dl class="space-y-0">
        @foreach([
            [$homepage['service_1_title'] ?? 'Pulsa All Operator', $homepage['service_1_description'] ?? 'Semua operator tersedia dengan harga terbaik dan proses instan.'],
            [$homepage['service_2_title'] ?? 'PPOB & Tagihan', $homepage['service_2_description'] ?? 'PLN, PDAM, BPJS, dan berbagai tagihan lainnya.'],
            [$homepage['service_3_title'] ?? 'Top-Up Game', $homepage['service_3_description'] ?? 'Lebih dari 100 game populer tersedia di platform kami.'],
            [$homepage['service_4_title'] ?? 'E-Money & E-Wallet', $homepage['service_4_description'] ?? 'GoPay, OVO, DANA, dan ShopeePay dengan transaksi mudah.'],
        ] as $svc)
        <div class="flex gap-6 py-5 border-t border-gray-100">
            <dt class="w-1/3 font-bold text-gray-900 text-sm">{{ $svc[0] }}</dt>
            <dd class="w-2/3 text-sm text-gray-600 leading-relaxed">{{ $svc[1] }}</dd>
        </div>
        @endforeach
    </dl>
</section>

{{-- STATS --}}
<section class="max-w-xl mx-auto px-6 py-10 border-t border-gray-200">
    <p class="text-gray-500 italic text-sm leading-relaxed">
        Lebih dari 100K pengguna aktif, 500+ produk digital tersedia, dengan uptime 99.9% — platform terpercaya untuk bisnis digital Anda.
    </p>
</section>

{{-- FOOTER --}}
