{{--
    Creative Agency Home
    Layout: Full-screen hero with big bold text centered,
    rounded pill cards, alternating gradient sections
--}}

{{-- HERO: Full screen, centered, gradient bg --}}
<section class="min-h-screen flex items-center justify-center relative overflow-hidden"
         style="background:linear-gradient(135deg,#FF6B6B 0%,#4ECDC4 100%)">
    <div class="absolute inset-0" style="background:radial-gradient(ellipse at 20% 50%,rgba(255,255,255,0.15) 0%,transparent 60%)"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full opacity-20" style="background:#fff;blur:60px"></div>

    <div class="relative z-10 max-w-5xl mx-auto px-6 text-center">
        <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full mb-8 text-sm font-bold"
             style="background:rgba(255,255,255,0.2);color:white;border:1px solid rgba(255,255,255,0.4)">
            ✦ Platform Transaksi Digital #1
        </div>
        <h1 class="font-black leading-none mb-6 text-white"
            style="font-family:'Montserrat',sans-serif;font-size:clamp(3rem,8vw,7rem);letter-spacing:-0.04em">
            {{ $homepage['hero_title'] ?? 'Bisnis Digital Lebih Mudah' }}
        </h1>
        <p class="text-xl md:text-2xl mb-10 max-w-2xl mx-auto" style="color:rgba(255,255,255,0.85)">
            {{ $homepage['hero_subtitle'] ?? 'Pulsa, PPOB, Game & E-Wallet dalam satu platform.' }}
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ $homepage['hero_cta_url'] ?? url('/kontak') }}"
               class="px-10 py-4 rounded-full font-black text-lg transition-all hover:scale-105 hover:shadow-2xl"
               style="background:white;color:#FF6B6B">
                {{ $homepage['hero_cta_text'] ?? 'Mulai Sekarang' }}
            </a>
            <a href="{{ url('/layanan') }}"
               class="px-10 py-4 rounded-full font-black text-lg border-2 border-white text-white transition-all hover:bg-white hover:text-pink-500">
                Lihat Layanan
            </a>
        </div>
    </div>
</section>

{{-- SERVICES: 4 big rounded cards --}}
<section class="py-24 px-4" style="background:#fafafa">
    <div class="max-w-6xl mx-auto">
        <div class="text-center mb-16">
            <h2 class="font-black text-5xl mb-4" style="font-family:'Montserrat',sans-serif;color:#111;letter-spacing:-0.03em">Semua Ada Di Sini</h2>
            <p class="text-xl text-gray-500">Satu platform untuk semua kebutuhan digital Anda</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach([
                ['icon'=>'📱','title'=>$homepage['service_1_title']??'Pulsa All Operator','desc'=>$homepage['service_1_description']??'Semua operator, harga terbaik','bg'=>'from-pink-400 to-rose-500'],
                ['icon'=>'🏠','title'=>$homepage['service_2_title']??'PPOB & Tagihan','desc'=>$homepage['service_2_description']??'PLN, PDAM, BPJS dan lainnya','bg'=>'from-teal-400 to-cyan-500'],
                ['icon'=>'🎮','title'=>$homepage['service_3_title']??'Top-Up Game','desc'=>$homepage['service_3_description']??'100+ game populer tersedia','bg'=>'from-violet-400 to-purple-500'],
                ['icon'=>'💳','title'=>$homepage['service_4_title']??'E-Money','desc'=>$homepage['service_4_description']??'GoPay, OVO, DANA & lainnya','bg'=>'from-amber-400 to-orange-500'],
            ] as $s)
            <div class="group rounded-3xl p-8 text-white bg-gradient-to-br {{ $s['bg'] }} hover:scale-105 transition-all duration-300 cursor-default">
                <div class="text-5xl mb-5">{{ $s['icon'] }}</div>
                <h3 class="text-2xl font-black mb-3" style="font-family:'Montserrat',sans-serif">{{ $s['title'] }}</h3>
                <p class="text-white/80 text-lg">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- STATS: big numbers on dark bg --}}
<section class="py-20 px-4" style="background:#1a1a2e">
    <div class="max-w-5xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
        @foreach([['100K+','Pengguna Aktif'],['500+','Produk Digital'],['99.9%','Uptime'],['24/7','Support']] as $stat)
        <div>
            <p class="font-black mb-2" style="font-family:'Montserrat',sans-serif;font-size:3rem;background:linear-gradient(135deg,#FF6B6B,#4ECDC4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">{{ $stat[0] }}</p>
            <p class="text-sm font-medium" style="color:rgba(255,255,255,0.6)">{{ $stat[1] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- HOW IT WORKS: horizontal timeline --}}
<section class="py-24 px-4" style="background:#fafafa">
    <div class="max-w-5xl mx-auto text-center">
        <h2 class="font-black text-4xl mb-16" style="font-family:'Montserrat',sans-serif;color:#111">3 Langkah Mudah</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([['1','🖊️','Daftar','Buat akun gratis dalam 2 menit'],['2','💰','Deposit','Top-up saldo mulai Rp10.000'],['3','🚀','Transaksi','Langsung jual & raih keuntungan']] as $step)
            <div class="rounded-3xl p-8 text-white" style="background:linear-gradient(135deg,#FF6B6B,#4ECDC4)">
                <div class="w-12 h-12 rounded-full bg-white/30 flex items-center justify-center text-xl font-black mb-4 mx-auto">{{ $step[0] }}</div>
                <div class="text-4xl mb-3">{{ $step[1] }}</div>
                <h3 class="text-xl font-black mb-2" style="font-family:'Montserrat',sans-serif">{{ $step[2] }}</h3>
                <p class="text-white/80">{{ $step[3] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-20 px-4 text-center" style="background:linear-gradient(135deg,#FF6B6B,#4ECDC4)">
    <h2 class="font-black text-4xl text-white mb-4" style="font-family:'Montserrat',sans-serif">{{ $homepage['cta_title']??'Siap Bergabung?' }}</h2>
    <p class="text-white/80 text-xl mb-8">{{ $homepage['cta_description']??'Mulai bisnis digital Anda sekarang.' }}</p>
    <a href="{{ $homepage['cta_button_url']??url('/kontak') }}"
       class="inline-block px-10 py-4 rounded-full font-black text-lg bg-white hover:scale-105 transition-all"
       style="color:#FF6B6B">
        {{ $homepage['cta_button_text']??'Daftar Jadi Agen' }}
    </a>
</section>

@if($latestBlogs->isNotEmpty())
<section class="py-20 px-4" style="background:#fafafa">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-12">
            <h2 class="font-black text-3xl" style="font-family:'Montserrat',sans-serif;color:#111">Artikel Terbaru</h2>
            <a href="{{ route('blog.index') }}" class="font-bold text-sm px-5 py-2.5 rounded-full" style="background:#FF6B6B;color:white">Lihat Semua</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestBlogs as $blog)
            <a href="{{ route('blog.show',$blog->slug) }}" class="group block rounded-3xl overflow-hidden bg-white hover:shadow-xl transition-all">
                @if($blog->featured_image)
                <img src="{{ Storage::url($blog->featured_image) }}" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500" alt="{{ $blog->title }}">
                @else
                <div class="w-full h-48" style="background:linear-gradient(135deg,#FF6B6B,#4ECDC4)"></div>
                @endif
                <div class="p-6">
                    <p class="text-xs font-bold mb-2" style="color:#FF6B6B">{{ $blog->published_at?->format('d M Y') }}</p>
                    <h3 class="font-black text-lg text-gray-900 group-hover:text-pink-500 transition-colors line-clamp-2">{{ $blog->title }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
