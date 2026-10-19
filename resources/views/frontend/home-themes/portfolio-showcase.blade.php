{{--
    Portfolio Showcase Home
    Layout: Full-screen dramatic black hero,
    gold accents, masonry-style showcase grid
--}}

{{-- FULL-SCREEN HERO: black, gold text --}}
<section class="min-h-screen flex items-end relative overflow-hidden" style="background:#000">
    {{-- Background texture --}}
    <div class="absolute inset-0" style="background:radial-gradient(ellipse at 30% 70%,rgba(255,215,0,0.08) 0%,transparent 60%)"></div>

    <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 pb-20 pt-32 w-full">
        <div class="max-w-3xl">
            <p class="font-bold uppercase tracking-widest text-xs mb-6" style="color:rgba(255,215,0,0.7)">Platform Transaksi Digital</p>
            <h1 class="leading-none mb-8 text-white" style="font-family:'Oswald',sans-serif;font-weight:700;font-size:clamp(3rem,8vw,8rem);text-transform:uppercase;letter-spacing:0.02em">
                {{ $homepage['hero_title'] ?? 'BISNIS DIGITAL TANPA BATAS' }}
            </h1>
            <div style="width:6rem;height:2px;background:#FFD700;margin-bottom:2rem"></div>
            <p class="text-lg mb-10 font-light" style="color:rgba(255,255,255,0.6);max-width:32rem">
                {{ $homepage['hero_subtitle']??'Pulsa, PPOB, Game & E-Wallet dengan transaksi real-time.' }}
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="{{ $homepage['hero_cta_url']??url('/kontak') }}"
                   class="px-8 py-4 font-bold uppercase tracking-wider text-sm transition-all"
                   style="border:1px solid #FFD700;color:#FFD700"
                   onmouseover="this.style.background='#FFD700';this.style.color='#000'"
                   onmouseout="this.style.background='transparent';this.style.color='#FFD700'">
                    {{ $homepage['hero_cta_text']??'Mulai Sekarang' }}
                </a>
                <a href="{{ url('/layanan') }}" class="px-8 py-4 font-bold uppercase tracking-wider text-sm" style="color:rgba(255,255,255,0.4)">
                    Pelajari Lebih →
                </a>
            </div>
        </div>
    </div>

    {{-- Stats bar --}}
    <div class="absolute bottom-0 left-0 right-0" style="background:rgba(255,215,0,0.08);border-top:1px solid rgba(255,215,0,0.15)">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-wrap gap-8">
            @foreach([['100K+','Pengguna'],['500+','Produk'],['99.9%','Uptime'],['24/7','Support']] as $s)
            <div>
                <span class="font-bold text-lg" style="font-family:'Oswald',sans-serif;color:#FFD700">{{ $s[0] }}</span>
                <span class="text-xs ml-2" style="color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.1em">{{ $s[1] }}</span>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- SERVICES: masonry-style dark grid --}}
<section style="background:#111">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-20">
        <div class="flex items-center gap-4 mb-12">
            <h2 class="text-3xl font-bold uppercase text-white" style="font-family:'Oswald',sans-serif">Layanan</h2>
            <div style="flex:1;height:1px;background:rgba(255,215,0,0.2)"></div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-px" style="background:rgba(255,215,0,0.1)">
            @foreach([
                [$homepage['service_1_title']??'Pulsa All Operator',$homepage['service_1_description']??'Semua operator, proses instan.','📱'],
                [$homepage['service_2_title']??'PPOB & Tagihan',$homepage['service_2_description']??'PLN, PDAM, BPJS dan lainnya.','🏠'],
                [$homepage['service_3_title']??'Top-Up Game',$homepage['service_3_description']??'100+ game populer tersedia.','🎮'],
                [$homepage['service_4_title']??'E-Money',$homepage['service_4_description']??'GoPay, OVO, DANA, ShopeePay.','💳'],
            ] as $s)
            <div class="p-8 group" style="background:#111">
                <div class="text-4xl mb-5">{{ $s[2] }}</div>
                <h3 class="text-xl font-bold uppercase text-white mb-3" style="font-family:'Oswald',sans-serif">{{ $s[0] }}</h3>
                <p class="text-sm font-light" style="color:rgba(255,255,255,0.5)">{{ $s[1] }}</p>
                <div class="mt-5 h-px opacity-0 group-hover:opacity-100 transition-opacity" style="background:#FFD700"></div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PROCESS: numbered dramatic --}}
<section class="py-20 px-4" style="background:#000">
    <div class="max-w-5xl mx-auto">
        <h2 class="text-3xl font-bold uppercase text-white text-center mb-16" style="font-family:'Oswald',sans-serif">Cara Bergabung</h2>
        @foreach([['01','Daftar','Registrasi gratis dalam 2 menit.'],['02','Deposit','Top-up saldo minimal Rp10.000.'],['03','Transaksi','Mulai jual dan raih profit.']] as $s)
        <div class="flex items-center gap-8 py-8" style="border-bottom:1px solid rgba(255,215,0,0.1)">
            <span class="font-black text-5xl shrink-0" style="font-family:'Oswald',sans-serif;color:rgba(255,215,0,0.25)">{{ $s[0] }}</span>
            <div>
                <h3 class="text-xl font-bold uppercase text-white mb-1" style="font-family:'Oswald',sans-serif">{{ $s[1] }}</h3>
                <p style="color:rgba(255,255,255,0.5)">{{ $s[2] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section class="py-20 px-4 text-center" style="background:#111;border-top:1px solid rgba(255,215,0,0.2)">
    <h2 class="text-4xl font-bold uppercase text-white mb-4" style="font-family:'Oswald',sans-serif">{{ $homepage['cta_title']??'BERGABUNG SEKARANG' }}</h2>
    <p class="text-lg mb-8" style="color:rgba(255,255,255,0.5)">{{ $homepage['cta_description']??'Mulai bisnis digital Anda.' }}</p>
    <a href="{{ $homepage['cta_button_url']??url('/kontak') }}"
       class="inline-block px-10 py-4 font-bold uppercase tracking-wider text-sm"
       style="border:1px solid #FFD700;color:#FFD700"
       onmouseover="this.style.background='#FFD700';this.style.color='#000'"
       onmouseout="this.style.background='transparent';this.style.color='#FFD700'">
        {{ $homepage['cta_button_text']??'Daftar Jadi Agen' }}
    </a>
</section>

@if($latestBlogs->isNotEmpty())
<section class="py-16 px-4" style="background:#000">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-4 mb-10">
            <h2 class="text-2xl font-bold uppercase text-white" style="font-family:'Oswald',sans-serif">Artikel</h2>
            <div style="flex:1;height:1px;background:rgba(255,215,0,0.15)"></div>
            <a href="{{ route('blog.index') }}" class="text-xs uppercase tracking-widest" style="color:#FFD700">Semua →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-px" style="background:rgba(255,215,0,0.08)">
            @foreach($latestBlogs as $blog)
            <a href="{{ route('blog.show',$blog->slug) }}" class="block p-6 group" style="background:#000">
                <p class="text-xs uppercase tracking-widest mb-3" style="color:rgba(255,215,0,0.5)">{{ $blog->published_at?->format('d M Y') }}</p>
                <h3 class="font-bold uppercase text-white line-clamp-2 group-hover:text-yellow-400 transition-colors" style="font-family:'Oswald',sans-serif">{{ $blog->title }}</h3>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
