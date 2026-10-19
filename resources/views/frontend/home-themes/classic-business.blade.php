{{--
    Classic Business Home
    Layout: Traditional corporate, formal structure,
    serif typography, trustworthy feel
--}}

{{-- HERO: Traditional split, warm bg --}}
<section style="background:#fdfdf8;border-bottom:2px solid #e8e0d0">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
            <div>
                <p class="text-sm font-bold uppercase tracking-widest mb-4" style="color:#8B4513">Platform Transaksi Digital</p>
                <h1 class="leading-tight mb-6" style="font-family:'Merriweather',Georgia,serif;font-weight:900;font-size:clamp(2rem,4vw,3.25rem);color:#1A5C38">
                    {{ $homepage['hero_title'] ?? 'Mitra Terpercaya untuk Bisnis Digital Anda' }}
                </h1>
                <div style="width:4rem;height:3px;background:#8B4513;margin-bottom:1.5rem"></div>
                <p class="text-lg leading-relaxed mb-8" style="color:#555;font-family:'Lato',sans-serif;line-height:1.8">
                    {{ $homepage['hero_subtitle']??'Bergabung dengan pulsa, PPOB, game, dan e-wallet. Solusi lengkap untuk agen digital modern.' }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ $homepage['hero_cta_url']??url('/kontak') }}"
                       class="px-8 py-3 font-bold text-white text-sm uppercase tracking-wider transition-all"
                       style="background:#1A5C38;font-family:'Lato',sans-serif"
                       onmouseover="this.style.background='#145030'" onmouseout="this.style.background='#1A5C38'">
                        {{ $homepage['hero_cta_text']??'Daftar Menjadi Agen' }}
                    </a>
                    <a href="{{ url('/layanan') }}" class="px-8 py-3 font-bold text-sm uppercase tracking-wider transition-all"
                       style="border:2px solid #1A5C38;color:#1A5C38;font-family:'Lato',sans-serif">
                        Lihat Layanan
                    </a>
                </div>
            </div>
            {{-- Stats in formal boxes --}}
            <div class="grid grid-cols-2 gap-4">
                @foreach([['100K+','Mitra Aktif'],['500+','Produk Digital'],['99.9%','Tingkat Uptime'],['Sejak 2020','Berpengalaman']] as $s)
                <div class="p-6 text-center" style="border:2px solid #e8e0d0;background:white">
                    <p class="font-black text-2xl mb-1" style="font-family:'Merriweather',serif;color:#1A5C38">{{ $s[0] }}</p>
                    <p class="text-xs uppercase tracking-wider" style="color:#888;font-family:'Lato',sans-serif">{{ $s[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- SERVICES: formal cards --}}
<section class="py-20 px-4" style="background:#f5f2eb">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="mb-3" style="font-family:'Merriweather',serif;font-weight:900;font-size:2rem;color:#1A5C38">Layanan Kami</h2>
            <div style="width:3rem;height:2px;background:#8B4513;margin:0 auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach([
                [$homepage['service_1_title']??'Pulsa All Operator',$homepage['service_1_description']??'Semua operator, proses instan.','📱'],
                [$homepage['service_2_title']??'PPOB & Tagihan',$homepage['service_2_description']??'PLN, PDAM, BPJS dan lainnya.','🏠'],
                [$homepage['service_3_title']??'Top-Up Game',$homepage['service_3_description']??'100+ game populer tersedia.','🎮'],
                [$homepage['service_4_title']??'E-Money & E-Wallet',$homepage['service_4_description']??'GoPay, OVO, DANA.','💳'],
            ] as $s)
            <div class="p-6 bg-white" style="border-top:4px solid #1A5C38;border:1px solid #e8e0d0;border-top:4px solid #1A5C38">
                <div class="text-4xl mb-4">{{ $s[2] }}</div>
                <h3 class="font-bold mb-2" style="font-family:'Merriweather',serif;font-size:1rem;color:#1A5C38">{{ $s[0] }}</h3>
                <p class="text-sm leading-relaxed" style="color:#666;font-family:'Lato',sans-serif">{{ $s[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOW IT WORKS: formal numbered --}}
<section class="py-20 px-4 bg-white">
    <div class="max-w-5xl mx-auto">
        <div class="text-center mb-14">
            <h2 class="mb-3" style="font-family:'Merriweather',serif;font-weight:900;font-size:2rem;color:#1A5C38">Langkah Memulai</h2>
            <div style="width:3rem;height:2px;background:#8B4513;margin:0 auto"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([['I','Pendaftaran','Isi formulir pendaftaran secara gratis dan akun langsung aktif.'],['II','Pengisian Saldo','Transfer saldo minimal Rp10.000 melalui bank atau e-wallet.'],['III','Memulai Bisnis','Akses semua produk dan mulai bertransaksi menghasilkan keuntungan.']] as $s)
            <div class="text-center">
                <div class="w-14 h-14 rounded-full flex items-center justify-center text-white font-black text-xl mx-auto mb-5" style="background:#1A5C38;font-family:'Merriweather',serif">{{ $s[0] }}</div>
                <h3 class="font-bold text-lg mb-3" style="font-family:'Merriweather',serif;color:#1A5C38">{{ $s[1] }}</h3>
                <p class="text-sm leading-relaxed" style="color:#666;font-family:'Lato',sans-serif">{{ $s[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA: formal banner --}}
<section class="py-16 px-4" style="background:#1A5C38;border-top:4px solid #8B4513">
    <div class="max-w-4xl mx-auto text-center">
        <h2 class="text-3xl font-black text-white mb-4" style="font-family:'Merriweather',serif">{{ $homepage['cta_title']??'Bergabung Bersama Kami' }}</h2>
        <p class="text-lg mb-8" style="color:rgba(255,255,255,0.7);font-family:'Lato',sans-serif">{{ $homepage['cta_description']??'Lebih dari 100.000 mitra telah mempercayai kami.' }}</p>
        <a href="{{ $homepage['cta_button_url']??url('/kontak') }}"
           class="inline-block px-10 py-4 font-bold uppercase tracking-wider text-sm"
           style="background:#8B4513;color:white;font-family:'Lato',sans-serif"
           onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            {{ $homepage['cta_button_text']??'Daftar Menjadi Mitra' }}
        </a>
    </div>
</section>

@if($latestBlogs->isNotEmpty())
<section class="py-16 px-4" style="background:#f5f2eb">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="font-black text-2xl" style="font-family:'Merriweather',serif;color:#1A5C38">Berita & Artikel</h2>
                <div style="width:2.5rem;height:2px;background:#8B4513;margin-top:.5rem"></div>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-bold uppercase tracking-wider" style="color:#1A5C38">Selengkapnya →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestBlogs as $blog)
            <a href="{{ route('blog.show',$blog->slug) }}" class="block bg-white group" style="border:1px solid #e8e0d0">
                @if($blog->featured_image)
                <img src="{{ Storage::url($blog->featured_image) }}" class="w-full h-44 object-cover" alt="{{ $blog->title }}">
                @else
                <div class="w-full h-44" style="background:#e8e0d0"></div>
                @endif
                <div class="p-5" style="border-top:3px solid #1A5C38">
                    <p class="text-xs uppercase tracking-widest mb-2" style="color:#8B4513;font-family:'Lato',sans-serif">{{ $blog->published_at?->format('d F Y') }}</p>
                    <h3 class="font-bold line-clamp-2 group-hover:text-green-800 transition-colors" style="font-family:'Merriweather',serif;color:#1a1a1a">{{ $blog->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
