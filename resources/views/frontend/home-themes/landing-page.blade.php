{{-- 2. HERO --}}
<section class="bg-black min-h-screen flex items-center">
    <div class="max-w-5xl mx-auto px-6 lg:px-8 py-24 w-full">
        <div class="mb-4">
            <span class="text-yellow-400 text-xs uppercase tracking-widest font-bold border border-yellow-400/40 px-3 py-1">
                Platform Transaksi Digital Terpercaya
            </span>
        </div>

        <h1 class="text-7xl lg:text-8xl font-black leading-none text-white mt-8 mb-6">
            @php
                $heroTitle = $homepage['hero_title'] ?? 'Solusi Terbaik Bisnis Digital Anda';
                $words = explode(' ', $heroTitle);
                $highlighted = implode(' ', array_slice($words, 0, 2));
                $rest = implode(' ', array_slice($words, 2));
            @endphp
            <span class="text-yellow-400">{{ $highlighted }}</span>
            @if($rest)
                <br>{{ $rest }}
            @endif
        </h1>

        <p class="text-gray-400 text-xl mt-6 max-w-2xl leading-relaxed">
            {{ $homepage['hero_subtitle'] ?? 'Bergabung dengan ribuan agen sukses. Mulai dari Rp10.000, raih penghasilan tanpa batas bersama platform kami.' }}
        </p>

        <div class="mt-10 mb-12">
            <a href="{{ $homepage['hero_cta_url'] ?? url('/kontak') }}"
               class="inline-block bg-yellow-400 text-black font-black text-lg px-10 py-5 hover:bg-yellow-300 transition-colors">
                {{ $homepage['hero_cta_text'] ?? 'Daftar Gratis Sekarang' }}
            </a>
        </div>

        {{-- Social Proof Bar --}}
        <div class="flex flex-wrap items-center gap-0">
            <div class="pr-8 text-gray-500 text-sm">
                <span class="text-white font-bold text-base">1.000+</span> Pengguna Aktif
            </div>
            <div class="border-l border-white/10 pl-8 pr-8 text-gray-500 text-sm">
                <span class="text-white font-bold text-base">50+</span> Partner Resmi
            </div>
            <div class="border-l border-white/10 pl-8 text-gray-500 text-sm">
                <span class="text-white font-bold text-base">5★</span> Rating Pengguna
            </div>
        </div>
    </div>
</section>

{{-- 3. PROBLEM / SOLUTION --}}
<section class="bg-black py-20 border-t border-white/10">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-4xl font-black text-white">Kenapa Harus Kami?</h2>
            <div class="w-16 h-1 bg-yellow-400 mx-auto mt-4"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            {{-- LEFT: Pain points --}}
            <div>
                <h3 class="text-red-500 font-black text-2xl mb-6">❌ Masalah Anda</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3 text-red-400">
                        <span class="mt-1 shrink-0">✗</span>
                        <span>{{ $homepage['service_1_description'] ?? 'Sulitnya mencari produk digital lengkap dalam satu platform.' }}</span>
                    </li>
                    <li class="flex items-start gap-3 text-red-400">
                        <span class="mt-1 shrink-0">✗</span>
                        <span>{{ $homepage['service_2_description'] ?? 'Transaksi lambat dan sering gagal di platform lain.' }}</span>
                    </li>
                    <li class="flex items-start gap-3 text-red-400">
                        <span class="mt-1 shrink-0">✗</span>
                        <span>Margin keuntungan tipis yang tidak sepadan dengan kerja keras Anda.</span>
                    </li>
                    <li class="flex items-start gap-3 text-red-400">
                        <span class="mt-1 shrink-0">✗</span>
                        <span>Tidak ada dukungan teknis saat transaksi bermasalah tengah malam.</span>
                    </li>
                </ul>
            </div>
            {{-- RIGHT: Solutions --}}
            <div>
                <h3 class="text-green-500 font-black text-2xl mb-6">✅ Solusi Kami</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3 text-green-400">
                        <span class="mt-1 shrink-0">✓</span>
                        <span>{{ $homepage['service_1_title'] ?? 'Pulsa & Data' }} — ribuan produk digital tersedia dalam satu dashboard.</span>
                    </li>
                    <li class="flex items-start gap-3 text-green-400">
                        <span class="mt-1 shrink-0">✓</span>
                        <span>{{ $homepage['service_2_title'] ?? 'PPOB & Tagihan' }} — proses otomatis dalam hitungan detik, 99.9% berhasil.</span>
                    </li>
                    <li class="flex items-start gap-3 text-green-400">
                        <span class="mt-1 shrink-0">✓</span>
                        <span>Margin kompetitif dan bonus referral yang nyata untuk setiap transaksi.</span>
                    </li>
                    <li class="flex items-start gap-3 text-green-400">
                        <span class="mt-1 shrink-0">✓</span>
                        <span>Tim support aktif 24 jam, 7 hari seminggu via live chat dan WhatsApp.</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- 4. SERVICES — numbered full-width rows --}}
<section class="py-20 bg-[#111111]">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        <div class="text-center mb-14">
            <h2 class="text-4xl font-black text-white">Layanan Unggulan</h2>
            <div class="w-16 h-1 bg-yellow-400 mx-auto mt-4"></div>
        </div>
        @php
            $services = [
                ['num' => '01', 'icon' => '📱', 'title' => $homepage['service_1_title'] ?? 'Pulsa All Operator', 'desc' => $homepage['service_1_description'] ?? 'Semua operator nasional tersedia. Proses instan tanpa antrian.', 'bg' => 'bg-[#111111]'],
                ['num' => '02', 'icon' => '🏠', 'title' => $homepage['service_2_title'] ?? 'PPOB & Tagihan', 'desc' => $homepage['service_2_description'] ?? 'Bayar PLN, PDAM, BPJS, internet, dan tagihan lainnya dengan mudah.', 'bg' => 'bg-[#0A0A0A]'],
                ['num' => '03', 'icon' => '🎮', 'title' => $homepage['service_3_title'] ?? 'Top-Up Game', 'desc' => $homepage['service_3_description'] ?? 'Lebih dari 100 game populer. Mobile Legends, PUBG, Genshin, dan banyak lagi.', 'bg' => 'bg-[#111111]'],
                ['num' => '04', 'icon' => '💳', 'title' => $homepage['service_4_title'] ?? 'E-Money & E-Wallet', 'desc' => $homepage['service_4_description'] ?? 'Top-up GoPay, OVO, DANA, ShopeePay dalam satu klik.', 'bg' => 'bg-[#0A0A0A]'],
            ];
        @endphp
        <div class="border border-white/10">
            @foreach($services as $service)
            <div class="{{ $service['bg'] }} px-8 py-8 flex items-start gap-8 border-b border-white/10 last:border-b-0">
                <span class="text-4xl font-black text-yellow-400 shrink-0 w-14">{{ $service['num'] }}</span>
                <span class="text-3xl shrink-0 mt-1">{{ $service['icon'] }}</span>
                <div>
                    <h3 class="text-white font-bold text-xl mb-2">{{ $service['title'] }}</h3>
                    <p class="text-gray-400 leading-relaxed">{{ $service['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- 5. BLOG --}}
@if($latestBlogs->isNotEmpty())
<section class="bg-black py-16 border-t border-white/10">
    <div class="max-w-5xl mx-auto px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-white">Artikel Terbaru</h2>
                <div class="w-12 h-1 bg-yellow-400 mt-3"></div>
            </div>
            <a href="{{ route('blog.index') }}" class="text-yellow-400 text-sm font-bold hover:text-yellow-300 transition-colors">
                Semua Artikel →
            </a>
        </div>
        <div class="space-y-0 border border-white/10">
            @foreach($latestBlogs as $blog)
            <div class="flex items-start gap-6 px-8 py-6 border-b border-white/10 last:border-b-0 hover:bg-white/5 transition-colors">
                <div class="flex-1 min-w-0">
                    <p class="text-gray-600 text-xs uppercase tracking-widest mb-2">
                        {{ $blog->published_at?->format('d M Y') }}
                    </p>
                    <h3 class="text-white font-bold text-lg mb-2 line-clamp-1">{{ $blog->title }}</h3>
                    <p class="text-gray-500 text-sm line-clamp-2 leading-relaxed">
                        {{ $blog->excerpt ?? Str::limit(strip_tags($blog->content ?? ''), 120) }}
                    </p>
                </div>
                <a href="{{ route('blog.show', $blog->slug) }}"
                   class="text-yellow-400 font-black text-xl shrink-0 hover:text-yellow-300 transition-colors mt-1">
                    →
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- 6. CTA — full-width yellow, NO gradient --}}
<section class="bg-yellow-400 py-24">
    <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
        <h2 class="text-5xl font-black text-black leading-tight mb-4">
            {{ $homepage['cta_title'] ?? 'Mulai Bisnis Digital Anda Hari Ini' }}
        </h2>
        <p class="text-black/70 text-xl mb-10">
            {{ $homepage['cta_description'] ?? 'Gratis daftar. Tanpa biaya bulanan. Aktif langsung.' }}
        </p>
        <a href="{{ $homepage['cta_button_url'] ?? url('/kontak') }}"
           class="inline-block bg-black text-yellow-400 font-black text-lg px-12 py-5 hover:bg-gray-900 transition-colors">
            {{ $homepage['cta_button_text'] ?? 'Daftar Sekarang — Gratis' }}
        </a>
        <p class="text-black/50 text-sm mt-6">Bergabung dengan 1.000+ agen aktif di seluruh Indonesia</p>
    </div>
</section>

{{-- 7. FOOTER --}}
