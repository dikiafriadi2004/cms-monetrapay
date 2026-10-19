{{--
    E-commerce Style Home
    Layout: Product catalog style, compact cards,
    promo banners, category grid
--}}

{{-- HERO PROMO BANNER --}}
<section style="background:linear-gradient(90deg,#27AE60,#2ECC71)">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
                <div class="text-xs font-bold uppercase tracking-widest mb-3" style="color:rgba(255,255,255,0.7)">Promo Spesial</div>
                <h1 class="font-black leading-tight mb-4 text-white" style="font-family:'Roboto',sans-serif;font-size:clamp(1.75rem,4vw,3rem)">
                    {{ $homepage['hero_title'] ?? 'Transaksi Digital, Untung Berlipat' }}
                </h1>
                <p class="text-lg mb-6" style="color:rgba(255,255,255,0.85)">{{ $homepage['hero_subtitle']??'Bergabung dan nikmati margin terbaik di industri.' }}</p>
                <a href="{{ $homepage['hero_cta_url']??url('/kontak') }}"
                   class="inline-block px-8 py-3 font-black text-sm text-white rounded"
                   style="background:#F39C12;letter-spacing:0.05em">
                    {{ $homepage['hero_cta_text']??'DAFTAR SEKARANG' }}
                </a>
            </div>
            {{-- Stats as promo boxes --}}
            <div class="grid grid-cols-2 gap-3">
                @foreach([['100K+','Pengguna Aktif','bg-white/20'],['500+','Produk','bg-white/20'],['99.9%','Uptime','bg-white/20'],['24/7','Support','bg-white/20']] as $s)
                <div class="text-center p-4 rounded" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25)">
                    <p class="font-black text-2xl text-white">{{ $s[0] }}</p>
                    <p class="text-xs" style="color:rgba(255,255,255,0.75)">{{ $s[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- CATEGORY GRID --}}
<section class="py-10 px-4 bg-gray-100">
    <div class="max-w-7xl mx-auto">
        <h2 class="font-bold text-xl mb-5 text-gray-800">Kategori Produk</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @foreach([
                ['📱','Pulsa','All Operator',$homepage['service_1_title']??'Pulsa','from-green-400 to-green-600'],
                ['🏠','PPOB','Bayar Tagihan',$homepage['service_2_title']??'PPOB','from-blue-400 to-blue-600'],
                ['🎮','Game','Top-Up',$homepage['service_3_title']??'Top-Up Game','from-purple-400 to-purple-600'],
                ['💳','E-Money','Dompet Digital',$homepage['service_4_title']??'E-Money','from-orange-400 to-orange-600'],
            ] as $s)
            <a href="{{ url('/layanan') }}" class="flex items-center gap-3 p-4 bg-white rounded hover:shadow-md transition-all group">
                <div class="w-12 h-12 rounded flex items-center justify-center text-2xl bg-gradient-to-br {{ $s[4] }} shrink-0">
                    {{ $s[0] }}
                </div>
                <div>
                    <p class="font-bold text-sm text-gray-800 group-hover:text-green-600">{{ $s[3] }}</p>
                    <p class="text-xs text-gray-500">{{ $s[2] }}</p>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- PROMO BANNER ROW --}}
<section class="py-6 px-4 bg-gray-100">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-5 rounded text-white" style="background:linear-gradient(135deg,#27AE60,#2ECC71)">
            <p class="font-black text-lg">Deposit Ringan</p>
            <p class="text-sm mt-1" style="color:rgba(255,255,255,0.8)">Mulai dari Rp10.000</p>
        </div>
        <div class="p-5 rounded text-white" style="background:linear-gradient(135deg,#F39C12,#E67E22)">
            <p class="font-black text-lg">Margin Terbaik</p>
            <p class="text-sm mt-1" style="color:rgba(255,255,255,0.8)">Harga kompetitif</p>
        </div>
        <div class="p-5 rounded text-white" style="background:linear-gradient(135deg,#2C3E50,#34495E)">
            <p class="font-black text-lg">Support 24/7</p>
            <p class="text-sm mt-1" style="color:rgba(255,255,255,0.8)">Siap bantu kapanpun</p>
        </div>
    </div>
</section>

{{-- HOW TO ORDER --}}
<section class="py-12 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <h2 class="font-bold text-xl mb-6 text-gray-800">Cara Bergabung</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([['1','Daftar Akun','Registrasi gratis dengan email dan telepon.'],['2','Isi Saldo','Transfer minimal Rp10.000 via bank atau e-wallet.'],['3','Mulai Jualan','Akses semua produk dan langsung bertransaksi.']] as $s)
            <div class="flex gap-4 items-start">
                <div class="w-8 h-8 rounded flex items-center justify-center font-black text-sm text-white shrink-0" style="background:#27AE60">{{ $s[0] }}</div>
                <div>
                    <p class="font-bold text-gray-800 mb-1">{{ $s[1] }}</p>
                    <p class="text-sm text-gray-500">{{ $s[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA BANNER --}}
<section class="py-10 px-4" style="background:#1f2937">
    <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-6">
        <div>
            <h2 class="font-black text-2xl text-white mb-1">{{ $homepage['cta_title']??'Bergabung Sekarang' }}</h2>
            <p class="text-sm" style="color:rgba(255,255,255,0.6)">{{ $homepage['cta_description']??'Ribuan agen sudah bergabung.' }}</p>
        </div>
        <a href="{{ $homepage['cta_button_url']??url('/kontak') }}"
           class="shrink-0 px-8 py-3 font-black text-white rounded text-sm uppercase tracking-wider"
           style="background:#F39C12">
            {{ $homepage['cta_button_text']??'DAFTAR AGEN' }}
        </a>
    </div>
</section>

@if($latestBlogs->isNotEmpty())
<section class="py-12 px-4 bg-gray-100">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-5">
            <h2 class="font-bold text-xl text-gray-800">Artikel Terbaru</h2>
            <a href="{{ route('blog.index') }}" class="text-sm font-semibold" style="color:#27AE60">Lihat Semua →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($latestBlogs as $blog)
            <a href="{{ route('blog.show',$blog->slug) }}" class="bg-white p-5 rounded hover:shadow-md transition-all group block">
                <p class="text-xs text-gray-400 mb-2">{{ $blog->published_at?->format('d M Y') }}</p>
                <h3 class="font-bold text-gray-800 line-clamp-2 group-hover:text-green-600 text-sm">{{ $blog->title }}</h3>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
