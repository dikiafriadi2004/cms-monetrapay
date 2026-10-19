{{--
    Tech Startup Home
    Layout: SaaS-style, gradient text headings,
    feature grid with icons, pricing teaser
--}}

{{-- HERO: centered with gradient text + decorative grid --}}
<section class="relative min-h-screen flex items-center overflow-hidden" style="background:#f8faff">
    {{-- Grid pattern --}}
    <div class="absolute inset-0 pointer-events-none" style="background-image:linear-gradient(rgba(102,126,234,0.06) 1px,transparent 1px),linear-gradient(90deg,rgba(102,126,234,0.06) 1px,transparent 1px);background-size:40px 40px"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center py-20">
        <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full mb-8 text-sm font-semibold"
             style="background:rgba(102,126,234,0.1);border:1px solid rgba(102,126,234,0.2);color:#667EEA">
            🚀 Platform Transaksi Digital Terdepan
        </div>
        <h1 class="font-bold leading-tight mb-6" style="font-family:'Space Grotesk',sans-serif;font-size:clamp(2.5rem,6vw,5rem);background:linear-gradient(135deg,#667EEA,#764BA2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">
            {{ $homepage['hero_title'] ?? 'Satu Dashboard untuk Semua Transaksi' }}
        </h1>
        <p class="text-xl mb-10 max-w-2xl mx-auto" style="color:#4B5563;line-height:1.7">
            {{ $homepage['hero_subtitle'] ?? 'Pulsa, PPOB, Game & E-Wallet dengan API real-time dan dashboard analitik lengkap.' }}
        </p>
        <div class="flex flex-wrap justify-center gap-4 mb-16">
            <a href="{{ $homepage['hero_cta_url']??url('/kontak') }}"
               class="px-8 py-4 rounded-xl font-bold text-white transition-all hover:-translate-y-1"
               style="background:linear-gradient(135deg,#667EEA,#764BA2);box-shadow:0 4px 24px rgba(102,126,234,0.35)">
                {{ $homepage['hero_cta_text']??'Mulai Gratis' }}
            </a>
            <a href="{{ url('/layanan') }}"
               class="px-8 py-4 rounded-xl font-bold transition-all border"
               style="border-color:rgba(102,126,234,0.3);color:#667EEA">
                Lihat Demo
            </a>
        </div>
        {{-- Trust badges --}}
        <div class="flex flex-wrap justify-center gap-6">
            @foreach([['100K+','Pengguna'],['500+','Produk'],['99.9%','Uptime'],['24/7','Support']] as $s)
            <div class="px-5 py-3 rounded-xl" style="background:rgba(102,126,234,0.07);border:1px solid rgba(102,126,234,0.12)">
                <p class="font-bold text-xl" style="font-family:'Space Grotesk',sans-serif;background:linear-gradient(135deg,#667EEA,#764BA2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">{{ $s[0] }}</p>
                <p class="text-xs" style="color:#6B7280">{{ $s[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FEATURES: icon grid --}}
<section class="py-24 px-4 bg-white">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold mb-4" style="font-family:'Space Grotesk',sans-serif;background:linear-gradient(135deg,#667EEA,#764BA2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Produk Lengkap</h2>
            <p class="text-lg" style="color:#6B7280">Semua dalam satu platform terintegrasi</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['📱',$homepage['service_1_title']??'Pulsa',$homepage['service_1_description']??'All operator','rgba(102,126,234,0.1)'],
                ['🏠',$homepage['service_2_title']??'PPOB',$homepage['service_2_description']??'Bayar tagihan','rgba(118,75,162,0.1)'],
                ['🎮',$homepage['service_3_title']??'Top-Up Game',$homepage['service_3_description']??'100+ game','rgba(102,126,234,0.1)'],
                ['💳',$homepage['service_4_title']??'E-Wallet',$homepage['service_4_description']??'GoPay OVO DANA','rgba(118,75,162,0.1)'],
            ] as $s)
            <div class="p-6 rounded-2xl group hover:-translate-y-1 transition-all" style="background:{{ $s[3] }};border:1px solid rgba(102,126,234,0.15)">
                <div class="text-4xl mb-4">{{ $s[0] }}</div>
                <h3 class="font-bold mb-2" style="font-family:'Space Grotesk',sans-serif;color:#1f2937">{{ $s[1] }}</h3>
                <p class="text-sm" style="color:#6B7280">{{ $s[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOW IT WORKS: horizontal steps --}}
<section class="py-24 px-4" style="background:linear-gradient(135deg,rgba(102,126,234,0.04),rgba(118,75,162,0.04))">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-4xl font-bold text-center mb-16" style="font-family:'Space Grotesk',sans-serif;background:linear-gradient(135deg,#667EEA,#764BA2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">
            Cara Kerja
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([['01','Daftar','Buat akun gratis dalam 2 menit.'],['02','Top-Up','Isi saldo via bank atau e-wallet.'],['03','Transaksi','Mulai jual dan raih keuntungan.']] as $s)
            <div class="text-center">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5 font-black text-2xl" style="background:linear-gradient(135deg,#667EEA,#764BA2);color:white;font-family:'Space Grotesk',sans-serif">{{ $s[0] }}</div>
                <h3 class="text-xl font-bold mb-2" style="font-family:'Space Grotesk',sans-serif;color:#1f2937">{{ $s[1] }}</h3>
                <p style="color:#6B7280">{{ $s[2] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 px-4 bg-white">
    <div class="max-w-4xl mx-auto text-center p-12 rounded-3xl" style="background:linear-gradient(135deg,rgba(102,126,234,0.08),rgba(118,75,162,0.08));border:1px solid rgba(102,126,234,0.15)">
        <h2 class="text-4xl font-bold mb-4" style="font-family:'Space Grotesk',sans-serif;background:linear-gradient(135deg,#667EEA,#764BA2);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">{{ $homepage['cta_title']??'Siap Bergabung?' }}</h2>
        <p class="text-lg mb-8" style="color:#6B7280">{{ $homepage['cta_description']??'Mulai bisnis digital Anda hari ini.' }}</p>
        <a href="{{ $homepage['cta_button_url']??url('/kontak') }}"
           class="inline-block px-10 py-4 rounded-xl font-bold text-white"
           style="background:linear-gradient(135deg,#667EEA,#764BA2);box-shadow:0 4px 24px rgba(102,126,234,0.35)">
            {{ $homepage['cta_button_text']??'Daftar Sekarang' }} ✦
        </a>
    </div>
</section>

@if($latestBlogs->isNotEmpty())
<section class="py-20 px-4" style="background:linear-gradient(135deg,rgba(102,126,234,0.04),rgba(118,75,162,0.04))">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-2xl font-bold" style="font-family:'Space Grotesk',sans-serif;color:#1f2937">Artikel Terbaru</h2>
            <a href="{{ route('blog.index') }}" class="text-sm font-semibold" style="color:#667EEA">Lihat Semua →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestBlogs as $blog)
            <a href="{{ route('blog.show',$blog->slug) }}" class="block p-6 rounded-2xl bg-white group hover:shadow-lg transition-all" style="border:1px solid rgba(102,126,234,0.12)">
                <p class="text-xs font-semibold mb-2" style="color:#667EEA">{{ $blog->published_at?->format('d M Y') }}</p>
                <h3 class="font-bold mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors" style="font-family:'Space Grotesk',sans-serif;color:#1f2937">{{ $blog->title }}</h3>
                @if($blog->excerpt)<p class="text-sm line-clamp-2" style="color:#6B7280">{{ $blog->excerpt }}</p>@endif
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
