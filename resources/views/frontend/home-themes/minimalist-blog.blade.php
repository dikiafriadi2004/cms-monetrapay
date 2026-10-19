{{--
    Minimalist Blog Home
    Layout: Narrow centered, generous whitespace,
    typography-focused, no decorative elements
--}}
<div style="max-width:46rem;margin:0 auto;padding:4rem 1.5rem">

    {{-- Hero: simple text, no images --}}
    <section style="padding:4rem 0 5rem;border-bottom:1px solid #e8e0d0">
        <p class="text-sm font-semibold uppercase tracking-widest mb-4" style="color:#E74C3C;font-family:'Source Sans 3',sans-serif">Platform Transaksi Digital</p>
        <h1 class="leading-tight mb-6" style="font-family:'Lora',Georgia,serif;font-weight:700;font-size:clamp(2rem,5vw,3.25rem);color:#1a1a1a">
            {{ $homepage['hero_title'] ?? 'Bisnis Digital yang Lebih Sederhana' }}
        </h1>
        <p class="text-xl leading-relaxed mb-8" style="color:#4a4a4a;font-family:'Source Sans 3',sans-serif;line-height:1.8">
            {{ $homepage['hero_subtitle'] ?? 'Satu platform untuk pulsa, PPOB, game, dan e-wallet. Mulai dengan deposit ringan.' }}
        </p>
        <div class="flex gap-4 flex-wrap">
            <a href="{{ $homepage['hero_cta_url']??url('/kontak') }}"
               class="px-6 py-3 font-semibold text-white"
               style="background:#2C3E50;font-family:'Source Sans 3',sans-serif">
                {{ $homepage['hero_cta_text']??'Mulai Sekarang' }}
            </a>
            <a href="{{ url('/layanan') }}" class="px-6 py-3 font-semibold" style="color:#2C3E50;border-bottom:2px solid #2C3E50">
                Lihat Layanan
            </a>
        </div>
    </section>

    {{-- Stats: simple inline --}}
    <section style="padding:3rem 0;border-bottom:1px solid #e8e0d0">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach([['100K+','Pengguna'],['500+','Produk'],['99.9%','Uptime'],['24/7','Support']] as $s)
            <div>
                <p class="font-bold text-2xl mb-0.5" style="font-family:'Lora',serif;color:#2C3E50">{{ $s[0] }}</p>
                <p class="text-sm" style="color:#888">{{ $s[1] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- Services: simple list --}}
    <section style="padding:4rem 0;border-bottom:1px solid #e8e0d0">
        <h2 class="mb-6" style="font-family:'Lora',serif;font-weight:700;font-size:1.75rem;color:#1a1a1a">Layanan Kami</h2>
        <div class="space-y-5">
            @foreach([
                [$homepage['service_1_title']??'Pulsa All Operator',$homepage['service_1_description']??'Semua operator dengan harga terbaik.'],
                [$homepage['service_2_title']??'PPOB & Tagihan',$homepage['service_2_description']??'PLN, PDAM, BPJS, dan tagihan lainnya.'],
                [$homepage['service_3_title']??'Top-Up Game',$homepage['service_3_description']??'100+ game populer tersedia.'],
                [$homepage['service_4_title']??'E-Money & E-Wallet',$homepage['service_4_description']??'GoPay, OVO, DANA, ShopeePay.'],
            ] as $i => $s)
            <div class="flex gap-5 py-5" style="{{ $i < 3 ? 'border-bottom:1px solid #f0ece4' : '' }}">
                <span class="text-xl font-black shrink-0 w-8 pt-0.5" style="font-family:'Lora',serif;color:#E74C3C">{{ sprintf('%02d', $i+1) }}</span>
                <div>
                    <h3 class="font-bold mb-1" style="font-family:'Lora',serif;font-size:1.1rem;color:#1a1a1a">{{ $s[0] }}</h3>
                    <p class="text-sm leading-relaxed" style="color:#666">{{ $s[1] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- How it works --}}
    <section style="padding:4rem 0;border-bottom:1px solid #e8e0d0">
        <h2 class="mb-8" style="font-family:'Lora',serif;font-weight:700;font-size:1.75rem;color:#1a1a1a">Cara Memulai</h2>
        <div class="space-y-6">
            @foreach([['Daftar akun gratis','Cukup email dan nomor telepon, selesai dalam 2 menit.'],['Isi saldo','Top-up via transfer bank atau e-wallet mulai Rp10.000.'],['Mulai bertransaksi','Jual pulsa, bayar tagihan, top-up game langsung dari dashboard.']] as $i => $s)
            <div class="flex gap-5">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0 mt-0.5" style="background:#2C3E50">{{ $i+1 }}</div>
                <div>
                    <h3 class="font-bold mb-1" style="color:#1a1a1a">{{ $s[0] }}</h3>
                    <p class="text-sm leading-relaxed" style="color:#666">{{ $s[1] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- CTA --}}
    <section style="padding:4rem 0;border-bottom:1px solid #e8e0d0;text-align:center">
        <h2 class="mb-3" style="font-family:'Lora',serif;font-weight:700;font-size:2rem;color:#1a1a1a">{{ $homepage['cta_title']??'Siap Bergabung?' }}</h2>
        <p class="mb-6 text-lg" style="color:#666">{{ $homepage['cta_description']??'Mulai bisnis digital Anda hari ini.' }}</p>
        <a href="{{ $homepage['cta_button_url']??url('/kontak') }}"
           class="inline-block px-8 py-3 font-semibold text-white"
           style="background:#2C3E50">
            {{ $homepage['cta_button_text']??'Daftar Jadi Agen' }}
        </a>
    </section>

    @if($latestBlogs->isNotEmpty())
    {{-- Blog: editorial list --}}
    <section style="padding:4rem 0">
        <div class="flex items-center justify-between mb-8">
            <h2 style="font-family:'Lora',serif;font-weight:700;font-size:1.75rem;color:#1a1a1a">Artikel Terbaru</h2>
            <a href="{{ route('blog.index') }}" class="text-sm" style="color:#E74C3C">Semua artikel →</a>
        </div>
        <div class="space-y-8">
            @foreach($latestBlogs as $blog)
            <article style="border-bottom:1px solid #e8e0d0;padding-bottom:2rem">
                <p class="text-xs mb-2" style="color:#999;font-family:'Source Sans 3',sans-serif">{{ $blog->published_at?->format('d F Y') }}</p>
                <h3 class="mb-2 leading-snug" style="font-family:'Lora',serif;font-weight:700;font-size:1.25rem">
                    <a href="{{ route('blog.show',$blog->slug) }}" style="color:#1a1a1a">{{ $blog->title }}</a>
                </h3>
                @if($blog->excerpt)<p class="text-sm leading-relaxed" style="color:#666">{{ Str::limit($blog->excerpt, 120) }}</p>@endif
            </article>
            @endforeach
        </div>
    </section>
    @endif

</div>
