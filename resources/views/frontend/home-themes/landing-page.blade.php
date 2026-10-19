{{--
    Landing Page Home
    Layout: Single-page conversion focused,
    full-bleed sections, strong CTA everywhere
--}}

{{-- HERO: Full viewport gradient, no split --}}
<section class="min-h-screen flex items-center justify-center relative overflow-hidden"
         style="background:linear-gradient(135deg,#FF5722 0%,#FF8A65 40%,#00BCD4 100%)">
    <div class="absolute inset-0" style="background:radial-gradient(circle at 30% 50%,rgba(255,255,255,0.15),transparent 60%)"></div>
    <div class="absolute bottom-0 left-0 right-0 h-32" style="background:linear-gradient(to top,rgba(255,87,34,0.3),transparent)"></div>

    <div class="relative z-10 text-center px-4 max-w-4xl mx-auto">
        <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full mb-8 text-sm font-bold text-white"
             style="background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.35)">
            🔥 Lebih dari 100.000 Agen Bergabung
        </div>
        <h1 class="font-black leading-none text-white mb-6"
            style="font-family:'Raleway',sans-serif;font-size:clamp(3rem,8vw,7rem)">
            {{ $homepage['hero_title'] ?? 'Bisnis Pulsa Lebih Mudah & Menguntungkan' }}
        </h1>
        <p class="text-xl md:text-2xl text-white/85 mb-10 max-w-2xl mx-auto leading-relaxed">
            {{ $homepage['hero_subtitle']??'Mulai dari Rp10.000, raih penghasilan tanpa batas.' }}
        </p>
        <div class="flex flex-wrap justify-center gap-4 mb-16">
            <a href="{{ $homepage['hero_cta_url']??url('/kontak') }}"
               class="px-10 py-5 rounded-full font-black text-lg transition-all hover:scale-105 hover:shadow-2xl"
               style="background:white;color:#FF5722">
                {{ $homepage['hero_cta_text']??'Daftar Gratis Sekarang' }} 🚀
            </a>
        </div>
        {{-- Social proof --}}
        <div class="flex flex-wrap justify-center gap-8">
            @foreach([['100K+','Agen Aktif'],['500+','Produk'],['Rp0','Biaya Daftar'],['24/7','Support']] as $s)
            <div class="text-center">
                <p class="font-black text-2xl text-white">{{ $s[0] }}</p>
                <p class="text-xs text-white/70 uppercase tracking-wider">{{ $s[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PROBLEM-SOLUTION --}}
<section class="py-20 px-4 bg-white">
    <div class="max-w-5xl mx-auto text-center">
        <h2 class="font-black text-4xl mb-4" style="font-family:'Raleway',sans-serif;color:#1f2937">Apa Yang Anda Dapatkan?</h2>
        <p class="text-xl mb-14 text-gray-500">Semua yang Anda butuhkan untuk membangun bisnis digital</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach([
                ['✅','Produk Lengkap',$homepage['service_1_title']??'Pulsa'.' · '.$homepage['service_2_title']??'PPOB'.' · '.'Game · E-Wallet'],
                ['⚡','Transaksi Instan','Proses otomatis dalam hitungan detik'],
                ['💰','Margin Kompetitif','Keuntungan jelas di setiap transaksi'],
                ['🎯','Support 24/7','Tim siap membantu kapanpun Anda butuhkan'],
            ] as $s)
            <div class="flex gap-4 p-6 rounded-2xl text-left" style="background:#fff7f5;border:1px solid rgba(255,87,34,0.15)">
                <span class="text-2xl shrink-0">{{ $s[0] }}</span>
                <div>
                    <h3 class="font-black mb-1" style="font-family:'Raleway',sans-serif">{{ $s[1] }}</h3>
                    <p class="text-sm text-gray-500">{{ $s[2] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- HOW IT WORKS --}}
<section class="py-20 px-4" style="background:linear-gradient(135deg,#FF5722,#00BCD4)">
    <div class="max-w-5xl mx-auto text-center">
        <h2 class="font-black text-4xl mb-14 text-white" style="font-family:'Raleway',sans-serif">Mulai dalam 3 Menit</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([['1','📝','Daftar','Isi form dan akun langsung aktif.'],['2','💵','Deposit','Top-up minimal Rp10.000.'],['3','💸','Cuan','Mulai bertransaksi & raih profit.']] as $s)
            <div class="p-8 rounded-2xl" style="background:rgba(255,255,255,0.15);border:1px solid rgba(255,255,255,0.25)">
                <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center font-black text-lg mx-auto mb-4" style="color:#FF5722">{{ $s[0] }}</div>
                <div class="text-4xl mb-3">{{ $s[1] }}</div>
                <h3 class="text-xl font-black text-white mb-2" style="font-family:'Raleway',sans-serif">{{ $s[2] }}</h3>
                <p class="text-white/75 text-sm">{{ $s[3] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- TESTIMONIALS STRIP --}}
<section class="py-16 px-4 bg-white">
    <div class="max-w-5xl mx-auto">
        <h2 class="font-black text-3xl text-center mb-10" style="font-family:'Raleway',sans-serif;color:#1f2937">Kata Mereka</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([['Ahmad R.','Agen Jakarta','Omzet naik 3x setelah pakai MonetraPay!'],['Siti N.','Agen Bandung','Deposit cepat, transaksi lancar 24 jam.'],['Budi S.','Agen Surabaya','Game top-up lengkap, pelanggan puas!']] as $t)
            <div class="p-6 rounded-2xl" style="background:#fff7f5;border:1px solid rgba(255,87,34,0.12)">
                <p class="text-sm italic mb-4 text-gray-600">"{{ $t[2] }}"</p>
                <p class="font-black text-sm" style="color:#FF5722">{{ $t[0] }}</p>
                <p class="text-xs text-gray-400">{{ $t[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FINAL CTA --}}
<section class="py-24 px-4 text-center" style="background:#0f172a">
    <div class="max-w-3xl mx-auto">
        <h2 class="font-black text-5xl text-white mb-4" style="font-family:'Raleway',sans-serif">{{ $homepage['cta_title']??'Daftar Sekarang, GRATIS!' }}</h2>
        <p class="text-xl mb-10" style="color:rgba(255,255,255,0.6)">{{ $homepage['cta_description']??'Tidak ada biaya pendaftaran. Mulai kapanpun.' }}</p>
        <a href="{{ $homepage['cta_button_url']??url('/kontak') }}"
           class="inline-block px-12 py-5 rounded-full font-black text-xl text-white transition-all hover:scale-105"
           style="background:linear-gradient(135deg,#FF5722,#00BCD4)">
            {{ $homepage['cta_button_text']??'Daftar Sekarang' }} 🚀
        </a>
        <p class="mt-4 text-xs" style="color:rgba(255,255,255,0.3)">Gratis · Tanpa kartu kredit · Aktif langsung</p>
    </div>
</section>
