{{--
    Dark Mode Elegant Home
    Layout: Dark bg throughout, glassmorphism cards,
    purple/blue glow effects, luxury feel
--}}

{{-- HERO: Split layout, dark with glow --}}
<section class="min-h-screen flex items-center relative overflow-hidden" style="background:#080810">
    <div class="absolute top-0 left-0 w-96 h-96 rounded-full blur-3xl opacity-30" style="background:#9F7AEA"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 rounded-full blur-3xl opacity-20" style="background:#63B3ED"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 py-20 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-6 text-sm font-semibold"
                     style="background:rgba(159,122,234,0.15);border:1px solid rgba(159,122,234,0.3);color:#9F7AEA">
                    <span class="w-2 h-2 rounded-full animate-pulse" style="background:#9F7AEA"></span>
                    Platform Terpercaya
                </div>
                <h1 class="font-bold leading-tight mb-6" style="font-family:'Poppins',sans-serif;font-size:clamp(2.5rem,5vw,4.5rem);color:#f0f4ff">
                    {{ $homepage['hero_title'] ?? 'Solusi Transaksi Digital Terlengkap' }}
                </h1>
                <p class="text-lg mb-8 leading-relaxed" style="color:rgba(226,232,240,0.7)">
                    {{ $homepage['hero_subtitle'] ?? 'Pulsa, PPOB, Game & E-Wallet dengan transaksi real-time.' }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ $homepage['hero_cta_url']??url('/kontak') }}"
                       class="px-8 py-4 rounded-xl font-bold text-white transition-all hover:-translate-y-1"
                       style="background:linear-gradient(135deg,#9F7AEA,#63B3ED);box-shadow:0 0 32px rgba(159,122,234,0.4)">
                        {{ $homepage['hero_cta_text']??'Mulai Sekarang' }}
                    </a>
                    <a href="{{ url('/layanan') }}"
                       class="px-8 py-4 rounded-xl font-bold transition-all"
                       style="border:1px solid rgba(255,255,255,0.15);color:rgba(255,255,255,0.75)">
                        Pelajari Lebih
                    </a>
                </div>
            </div>
            {{-- Stats cards --}}
            <div class="grid grid-cols-2 gap-4">
                @foreach([['100K+','Pengguna','#9F7AEA'],['500+','Produk','#63B3ED'],['99.9%','Uptime','#9F7AEA'],['24/7','Support','#63B3ED']] as $s)
                <div class="p-6 rounded-2xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
                    <p class="text-3xl font-bold mb-1" style="color:{{ $s[2] }}">{{ $s[0] }}</p>
                    <p class="text-sm" style="color:rgba(255,255,255,0.5)">{{ $s[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- SERVICES: glassmorphism cards on dark --}}
<section class="py-24 px-4" style="background:#0e0e1a">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4" style="font-family:'Poppins',sans-serif;color:#f0f4ff">Layanan Kami</h2>
            <p style="color:rgba(226,232,240,0.55)">Semua kebutuhan transaksi digital dalam satu tempat</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([
                ['📱',$homepage['service_1_title']??'Pulsa',$homepage['service_1_description']??'All operator','rgba(159,122,234,0.8)'],
                ['🏠',$homepage['service_2_title']??'PPOB',$homepage['service_2_description']??'Bayar tagihan','rgba(99,179,237,0.8)'],
                ['🎮',$homepage['service_3_title']??'Top-Up Game',$homepage['service_3_description']??'100+ game','rgba(159,122,234,0.8)'],
                ['💳',$homepage['service_4_title']??'E-Wallet',$homepage['service_4_description']??'GoPay OVO DANA','rgba(99,179,237,0.8)'],
            ] as $s)
            <div class="p-6 rounded-2xl group hover:-translate-y-2 transition-all duration-300" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
                <div class="text-4xl mb-4">{{ $s[0] }}</div>
                <h3 class="text-base font-bold mb-2" style="color:#f0f4ff">{{ $s[1] }}</h3>
                <p class="text-sm" style="color:rgba(226,232,240,0.5)">{{ $s[2] }}</p>
                <div class="mt-4 h-0.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity" style="background:linear-gradient(90deg,{{ $s[3] }},transparent)"></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="py-24 px-4" style="background:#080810">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-4xl font-bold text-center mb-16" style="font-family:'Poppins',sans-serif;color:#f0f4ff">Cara Kerja</h2>
        <div class="space-y-6">
            @foreach([['01','Daftar Akun','Buat akun gratis dengan email dan nomor telepon.'],['02','Isi Saldo','Top-up via transfer bank, e-wallet, atau QRIS.'],['03','Bertransaksi','Jual pulsa, bayar tagihan, top-up game sekarang.']] as $s)
            <div class="flex items-start gap-6 p-6 rounded-2xl" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06)">
                <span class="text-4xl font-black shrink-0" style="font-family:'Poppins',sans-serif;background:linear-gradient(135deg,#9F7AEA,#63B3ED);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">{{ $s[0] }}</span>
                <div>
                    <h3 class="text-lg font-bold mb-1" style="color:#f0f4ff">{{ $s[1] }}</h3>
                    <p style="color:rgba(226,232,240,0.5)">{{ $s[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 px-4" style="background:#0e0e1a;border-top:1px solid rgba(159,122,234,0.2)">
    <div class="max-w-3xl mx-auto text-center">
        <h2 class="text-4xl font-bold mb-4" style="font-family:'Poppins',sans-serif;color:#f0f4ff">{{ $homepage['cta_title']??'Siap Bergabung?' }}</h2>
        <p class="text-lg mb-8" style="color:rgba(226,232,240,0.6)">{{ $homepage['cta_description']??'Mulai bisnis digital Anda hari ini.' }}</p>
        <a href="{{ $homepage['cta_button_url']??url('/kontak') }}"
           class="inline-block px-10 py-4 rounded-xl font-bold text-white"
           style="background:linear-gradient(135deg,#9F7AEA,#63B3ED);box-shadow:0 0 40px rgba(159,122,234,0.4)">
            {{ $homepage['cta_button_text']??'Daftar Sekarang' }}
        </a>
    </div>
</section>

@if($latestBlogs->isNotEmpty())
<section class="py-20 px-4" style="background:#0e0e1a">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl font-bold" style="color:#f0f4ff">Artikel Terbaru</h2>
            <a href="{{ route('blog.index') }}" class="text-sm font-semibold" style="color:#9F7AEA">Lihat Semua →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestBlogs as $blog)
            <a href="{{ route('blog.show',$blog->slug) }}" class="block rounded-2xl overflow-hidden group" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08)">
                @if($blog->featured_image)
                <img src="{{ Storage::url($blog->featured_image) }}" class="w-full h-44 object-cover" alt="{{ $blog->title }}">
                @else
                <div class="w-full h-44" style="background:linear-gradient(135deg,rgba(159,122,234,0.3),rgba(99,179,237,0.3))"></div>
                @endif
                <div class="p-5">
                    <p class="text-xs mb-2" style="color:#9F7AEA">{{ $blog->published_at?->format('d M Y') }}</p>
                    <h3 class="font-bold line-clamp-2 group-hover:text-purple-400 transition-colors" style="color:#f0f4ff">{{ $blog->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
