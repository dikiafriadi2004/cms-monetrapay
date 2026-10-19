{{--
    Magazine Layout Home
    Layout: Editorial newspaper-style grid,
    3-column asymmetric, featured article style
--}}

{{-- MAGAZINE HEADER HERO: newspaper masthead style --}}
<section style="background:white;border-bottom:2px solid #e0e0e0">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12">
        {{-- Breaking news bar --}}
        <div class="flex items-center gap-4 px-4 py-2 mb-10 text-sm" style="background:#C0392B;color:white">
            <span class="font-black uppercase tracking-wider text-xs shrink-0">Terbaru</span>
            <span>Platform transaksi digital terpercaya untuk agen se-Indonesia</span>
        </div>

        {{-- 2-col hero split --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
            <div style="border-right:1px solid #e0e0e0;padding-right:2.5rem">
                <span class="text-xs font-bold uppercase tracking-widest" style="color:#C0392B">Platform Unggulan</span>
                <h1 class="mt-2 mb-4 leading-tight" style="font-family:'Playfair Display',Georgia,serif;font-weight:900;font-size:clamp(2rem,4vw,3.5rem);color:#1a1a1a">
                    {{ $homepage['hero_title'] ?? 'Solusi Transaksi Digital untuk Agen Modern' }}
                </h1>
                <p class="text-lg leading-relaxed mb-6" style="color:#555;font-family:'Source Sans 3',sans-serif">
                    {{ $homepage['hero_subtitle'] ?? 'Pulsa, PPOB, Top-Up Game & E-Money dalam satu platform terpercaya.' }}
                </p>
                <a href="{{ $homepage['hero_cta_url']??url('/kontak') }}"
                   class="inline-block px-8 py-3 font-bold text-white"
                   style="background:#C0392B">
                    {{ $homepage['hero_cta_text']??'Daftar Jadi Agen' }} →
                </a>
            </div>
            {{-- Stats in editorial boxes --}}
            <div class="grid grid-cols-2 gap-0" style="border:1px solid #e0e0e0">
                @foreach([['100K+','Agen Aktif'],['500+','Produk'],['99.9%','Uptime'],['24/7','Support']] as $stat)
                <div class="p-6 text-center" style="border:1px solid #e0e0e0">
                    <p class="font-black text-3xl mb-1" style="font-family:'Playfair Display',serif;color:#C0392B">{{ $stat[0] }}</p>
                    <p class="text-xs uppercase tracking-wider" style="color:#666">{{ $stat[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- SERVICES: editorial list style --}}
<section class="py-16 px-4" style="background:#f8f8f8;border-top:4px solid #C0392B">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-4 mb-10">
            <h2 class="font-black text-2xl" style="font-family:'Playfair Display',serif">Layanan Kami</h2>
            <div class="flex-1 h-px" style="background:#e0e0e0"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach([
                [$homepage['service_1_title']??'Pulsa All Operator',$homepage['service_1_description']??'Semua operator','📱'],
                [$homepage['service_2_title']??'PPOB & Tagihan',$homepage['service_2_description']??'PLN PDAM BPJS','🏠'],
                [$homepage['service_3_title']??'Top-Up Game',$homepage['service_3_description']??'100+ game','🎮'],
                [$homepage['service_4_title']??'E-Money',$homepage['service_4_description']??'GoPay OVO DANA','💳'],
            ] as $s)
            <div class="p-5 bg-white" style="border-top:3px solid #C0392B">
                <div class="text-3xl mb-3">{{ $s[2] }}</div>
                <h3 class="font-bold mb-2" style="font-family:'Playfair Display',serif;font-size:1.1rem">{{ $s[0] }}</h3>
                <p class="text-sm" style="color:#666;line-height:1.6">{{ $s[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOW IT WORKS: numbered editorial --}}
<section class="py-16 px-4 bg-white">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-4 mb-10">
            <h2 class="font-black text-2xl whitespace-nowrap" style="font-family:'Playfair Display',serif">Cara Kerja</h2>
            <div class="flex-1 h-px" style="background:#e0e0e0"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0" style="border:1px solid #e0e0e0">
            @foreach([['Langkah 01','Daftar Akun','Registrasi gratis dengan email dan telepon.'],['Langkah 02','Isi Saldo','Deposit via transfer bank atau e-wallet.'],['Langkah 03','Mulai Jualan','Transaksi real-time 24 jam non-stop.']] as $s)
            <div class="p-8" style="border-right:1px solid #e0e0e0">
                <p class="text-xs font-bold uppercase tracking-widest mb-3" style="color:#C0392B">{{ $s[0] }}</p>
                <h3 class="font-black text-xl mb-3" style="font-family:'Playfair Display',serif">{{ $s[1] }}</h3>
                <p class="text-sm leading-relaxed" style="color:#555">{{ $s[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA: editorial banner --}}
<section class="py-16 px-4" style="background:#1a1a1a">
    <div class="max-w-4xl mx-auto flex flex-col md:flex-row items-center justify-between gap-8">
        <div>
            <h2 class="text-3xl font-black mb-2 text-white" style="font-family:'Playfair Display',serif">{{ $homepage['cta_title']??'Bergabung Sekarang' }}</h2>
            <p style="color:rgba(255,255,255,0.6)">{{ $homepage['cta_description']??'Mulai bisnis digital Anda hari ini.' }}</p>
        </div>
        <a href="{{ $homepage['cta_button_url']??url('/kontak') }}"
           class="shrink-0 px-8 py-3 font-bold text-white whitespace-nowrap"
           style="background:#C0392B">
            {{ $homepage['cta_button_text']??'Daftar Sekarang' }} →
        </a>
    </div>
</section>

@if($latestBlogs->isNotEmpty())
<section class="py-16 px-4 bg-white" style="border-top:4px solid #C0392B">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="font-black text-2xl" style="font-family:'Playfair Display',serif">Artikel Terbaru</h2>
            <a href="{{ route('blog.index') }}" class="text-sm font-bold" style="color:#C0392B">Selengkapnya →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-0" style="border:1px solid #e0e0e0">
            @foreach($latestBlogs as $i => $blog)
            <a href="{{ route('blog.show',$blog->slug) }}" class="block p-6 group" style="border-right:1px solid #e0e0e0">
                <p class="text-xs uppercase tracking-widest mb-2" style="color:#C0392B">{{ $blog->published_at?->format('d M Y') }}</p>
                <h3 class="font-black text-lg mb-2 group-hover:text-red-700 transition-colors line-clamp-3" style="font-family:'Playfair Display',serif">{{ $blog->title }}</h3>
                @if($blog->excerpt)<p class="text-sm line-clamp-2" style="color:#666">{{ $blog->excerpt }}</p>@endif
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
