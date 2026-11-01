@extends('frontend.layouts.app')
@section('title', $page->meta_title ?: 'Tentang Kami - ' . ($siteName ?? 'MonetraPay'))
@section('seo')
<x-seo-meta :title="$metaTags['title']" :description="$metaTags['description']" :keywords="$metaTags['keywords']" :canonicalUrl="$canonicalUrl" :ogTags="$ogTags" :twitterTags="$twitterTags" :structuredData="$structuredData"/>
@endsection

@section('content')

{{-- Jika page builder aktif dan ada komponen, render dari page builder --}}
@if($page->use_page_builder && $page->visibleComponents->isNotEmpty())
    @foreach($page->visibleComponents as $component)
        @includeIf("components.builder.{$component->component_type}", [
            'content'  => $component->content,
            'settings' => $component->settings ?? [],
        ])
    @endforeach

{{-- Fallback: template statis profesional --}}
@else

{{-- ═══ HERO ═══ --}}
<section class="bg-white pt-0 pb-16 md:pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-frontend.breadcrumb :items="$breadcrumbs ?? []" />
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center mt-8">
            <div class="animate-on-scroll">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 leading-tight mb-6">
                    Membuat pembayaran digital terasa <span class="text-primary">mudah untuk semua orang.</span>
                </h1>
                <p class="text-lg text-slate-500 leading-relaxed mb-8">
                    MonetraPay hadir untuk mempermudah masyarakat Indonesia dalam bertransaksi digital. Kami percaya bahwa layanan keuangan digital seharusnya dapat diakses oleh siapa saja, di mana saja, kapan saja.
                </p>
                <div class="flex flex-wrap gap-6">
                    <div class="text-center">
                        <p class="text-3xl font-extrabold text-primary">100K<span class="text-secondary">+</span></p>
                        <p class="text-sm text-slate-500 mt-1">Pengguna Aktif</p>
                    </div>
                    <div class="w-px bg-slate-200 self-stretch hidden sm:block"></div>
                    <div class="text-center">
                        <p class="text-3xl font-extrabold text-primary">500<span class="text-secondary">+</span></p>
                        <p class="text-sm text-slate-500 mt-1">Produk Digital</p>
                    </div>
                    <div class="w-px bg-slate-200 self-stretch hidden sm:block"></div>
                    <div class="text-center">
                        <p class="text-3xl font-extrabold text-primary">99.9<span class="text-secondary">%</span></p>
                        <p class="text-sm text-slate-500 mt-1">Uptime Server</p>
                    </div>
                    <div class="w-px bg-slate-200 self-stretch hidden sm:block"></div>
                    <div class="text-center">
                        <p class="text-3xl font-extrabold text-primary">2019</p>
                        <p class="text-sm text-slate-500 mt-1">Tahun Berdiri</p>
                    </div>
                </div>
            </div>
            <div class="animate-on-scroll delay-200 flex justify-center">
                <div class="relative">
                    <div class="w-72 h-72 md:w-96 md:h-96 bg-primary/8 rounded-3xl flex items-center justify-center">
                        @php $heroLogo = \App\Models\Setting::get('logo', 'general'); @endphp
                        @if($heroLogo)
                            <img src="{{ Storage::url($heroLogo) }}" alt="{{ $siteName ?? 'MonetraPay' }}" class="w-48 md:w-64 object-contain drop-shadow-xl">
                        @elseif(file_exists(public_path('assets/images/logo.png')))
                            <img src="/assets/images/logo.png" alt="{{ $siteName ?? 'MonetraPay' }}" class="w-48 md:w-64 object-contain drop-shadow-xl">
                        @else
                            <span class="text-4xl font-extrabold"><span class="text-primary">Monetra</span><span class="text-secondary">Pay</span></span>
                        @endif
                    </div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-secondary/10 rounded-2xl"></div>
                    <div class="absolute -bottom-4 -left-4 w-14 h-14 bg-primary/10 rounded-xl"></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ TENTANG SINGKAT ═══ --}}
<section class="py-16 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="animate-on-scroll">
                <span class="inline-block text-xs font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">Cerita Kami</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 leading-tight mb-5">
                    Mitra pembayaran digital yang dekat dengan keseharian Anda
                </h2>
                <p class="text-slate-500 leading-relaxed mb-4">
                    MonetraPay didirikan dengan satu tujuan: membuat transaksi digital lebih mudah, lebih cepat, dan lebih terjangkau bagi semua orang di Indonesia.
                </p>
                <p class="text-slate-500 leading-relaxed mb-4">
                    Dari pulsa hingga tagihan listrik, dari top-up game hingga e-money, kami menyediakan ratusan produk digital dalam satu platform yang mudah digunakan.
                </p>
                <p class="text-slate-500 leading-relaxed">
                    Kami bekerja sama dengan ribuan agen di seluruh Indonesia untuk memastikan layanan kami dapat diakses oleh semua lapisan masyarakat, bahkan di daerah terpencil sekalipun.
                </p>
            </div>
            <div class="animate-on-scroll delay-200 grid grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-primary/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Cepat & Andal</h3>
                    <p class="text-sm text-slate-500">Transaksi selesai dalam hitungan detik dengan uptime 99.9%</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm mt-6">
                    <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-secondary/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Aman & Terpercaya</h3>
                    <p class="text-sm text-slate-500">Sistem keamanan berlapis dan dipantau 24/7</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-purple-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Komunitas Agen</h3>
                    <p class="text-sm text-slate-500">Jaringan ribuan agen aktif di seluruh Indonesia</p>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm mt-6">
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-4 shadow-lg shadow-blue-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-slate-800 mb-2">Support 24/7</h3>
                    <p class="text-sm text-slate-500">Tim dukungan siap membantu kapan pun dibutuhkan</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ VISI MISI ═══ --}}
<section class="py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Visi --}}
            <div class="animate-on-scroll bg-primary rounded-3xl p-8 md:p-10 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-xl font-extrabold mb-4">Visi</h3>
                    <p class="text-white/85 leading-relaxed text-base">
                        Menjadi platform transaksi digital terdepan yang menghubungkan jutaan masyarakat Indonesia dengan layanan keuangan digital yang mudah, cepat, dan terjangkau.
                    </p>
                </div>
            </div>
            {{-- Misi --}}
            <div class="animate-on-scroll delay-200 bg-slate-50 rounded-3xl p-8 md:p-10 border border-slate-100">
                <div class="w-12 h-12 bg-secondary/15 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                </div>
                <h3 class="text-xl font-extrabold text-slate-800 mb-4">Misi</h3>
                <ul class="space-y-3">
                    @foreach(['Menyediakan akses layanan digital yang mudah dan terjangkau bagi semua kalangan.', 'Membangun ekosistem agen yang kuat dan merata di seluruh Indonesia.', 'Berinovasi terus-menerus untuk menghadirkan produk digital terbaik.', 'Menjaga kepercayaan pengguna dengan sistem yang aman dan transparan.'] as $misi)
                    <li class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-primary/15 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $misi }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- ═══ NILAI-NILAI ═══ --}}
<section class="py-16 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 animate-on-scroll">
            <span class="inline-block text-xs font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">Nilai Kami</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800">Prinsip yang kami pegang</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['icon' => '🤝', 'title' => 'Transparansi', 'desc' => 'Kami percaya pada keterbukaan informasi dan komunikasi yang jujur kepada seluruh pemangku kepentingan.'],
                ['icon' => '🛡️', 'title' => 'Dapat Dipercaya', 'desc' => 'Keamanan dan kepercayaan pengguna adalah prioritas utama dalam setiap keputusan yang kami buat.'],
                ['icon' => '💡', 'title' => 'Fokus Pengguna', 'desc' => 'Setiap fitur dan layanan kami dirancang dengan mengutamakan kemudahan dan kebutuhan pengguna.'],
                ['icon' => '🌱', 'title' => 'Terus Berkembang', 'desc' => 'Kami terus belajar, berinovasi, dan berkembang untuk memberikan layanan terbaik bagi Indonesia.'],
            ] as $nilai)
            <div class="animate-on-scroll bg-white rounded-2xl p-7 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="text-3xl mb-4">{{ $nilai['icon'] }}</div>
                <h3 class="text-base font-bold text-slate-800 mb-2">{{ $nilai['title'] }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $nilai['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ PERJALANAN ═══ --}}
<section class="py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
            <div class="animate-on-scroll">
                <span class="inline-block text-xs font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">Perjalanan Kami</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 leading-tight mb-4">
                    Tumbuh selangkah demi selangkah
                </h2>
                <p class="text-slate-500 leading-relaxed">
                    Dari sebuah ide sederhana di tahun 2019, MonetraPay telah berkembang menjadi platform transaksi digital yang dipercaya oleh ratusan ribu pengguna di seluruh Indonesia.
                </p>
            </div>
            <div class="animate-on-scroll delay-200">
                <div class="space-y-0">
                    @foreach([
                        ['year' => '2019', 'color' => 'bg-primary', 'title' => 'Didirikan', 'desc' => 'MonetraPay lahir dengan visi menyederhanakan transaksi digital di Indonesia.'],
                        ['year' => '2020', 'color' => 'bg-secondary', 'title' => 'Ekspansi Produk', 'desc' => 'Menambahkan layanan PPOB dan pembayaran tagihan untuk memperluas jangkauan.'],
                        ['year' => '2021', 'color' => 'bg-purple-500', 'title' => 'Jaringan Agen', 'desc' => 'Membangun jaringan agen pertama dengan lebih dari 1.000 mitra aktif.'],
                        ['year' => '2022', 'color' => 'bg-blue-500', 'title' => 'Perizinan Resmi', 'desc' => 'Mendapatkan izin resmi dari Bank Indonesia sebagai penyelenggara PPOB.'],
                        ['year' => '2024', 'color' => 'bg-primary', 'title' => 'Platform Digital', 'desc' => 'Meluncurkan platform digital baru dengan 500+ produk dan 100K+ pengguna aktif.'],
                    ] as $i => $item)
                    <div class="flex gap-4 group">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 {{ $item['color'] }} rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-lg group-hover:scale-110 transition-transform">
                                {{ substr($item['year'], 2) }}
                            </div>
                            @if($i < 4)
                            <div class="w-0.5 h-8 bg-slate-200 mt-1"></div>
                            @endif
                        </div>
                        <div class="pb-8">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-slate-400">{{ $item['year'] }}</span>
                                <span class="text-sm font-bold text-slate-800">{{ $item['title'] }}</span>
                            </div>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ LEGALITAS ═══ --}}
<section class="py-16 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 animate-on-scroll">
            <span class="inline-block text-xs font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">Legalitas</span>
            <h2 class="text-3xl font-extrabold text-slate-800 mb-3">Resmi dan terdaftar</h2>
            <p class="text-slate-500 max-w-xl mx-auto">Kami beroperasi dengan izin resmi dan diawasi oleh otoritas terpercaya di Indonesia.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach([
                ['icon' => '🏛️', 'label' => 'Terdaftar di', 'value' => 'PT Monetra Payment Indonesia', 'sub' => 'Badan Hukum Resmi'],
                ['icon' => '🏦', 'label' => 'Diawasi oleh', 'value' => 'OJK & Bank Indonesia', 'sub' => 'Otoritas Jasa Keuangan'],
                ['icon' => '✅', 'label' => 'Didukung oleh', 'value' => 'Telkom Indonesia', 'sub' => 'Strategic Partner'],
            ] as $legal)
            <div class="animate-on-scroll bg-white rounded-2xl p-8 border border-slate-100 shadow-sm text-center">
                <div class="text-4xl mb-4">{{ $legal['icon'] }}</div>
                <p class="text-xs text-slate-400 uppercase tracking-widest font-semibold mb-1">{{ $legal['label'] }}</p>
                <p class="text-base font-extrabold text-slate-800">{{ $legal['value'] }}</p>
                <p class="text-xs text-primary mt-1 font-semibold">{{ $legal['sub'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ TIM ═══ --}}
<section class="py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 animate-on-scroll">
            <span class="inline-block text-xs font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">Tim Kami</span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800">Orang-orang di balik MonetraPay</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['initial' => 'A', 'name' => 'Ahmad Fauzi', 'role' => 'CEO & Co-Founder', 'color' => 'bg-primary'],
                ['initial' => 'B', 'name' => 'Budi Santoso', 'role' => 'CTO & Co-Founder', 'color' => 'bg-secondary'],
                ['initial' => 'C', 'name' => 'Citra Dewi', 'role' => 'Head of Product', 'color' => 'bg-purple-500'],
                ['initial' => 'D', 'name' => 'Dian Pratama', 'role' => 'Head of Operations', 'color' => 'bg-blue-500'],
            ] as $member)
            <div class="animate-on-scroll text-center group">
                <div class="w-20 h-20 md:w-24 md:h-24 {{ $member['color'] }} rounded-2xl flex items-center justify-center text-white text-2xl font-extrabold mx-auto mb-4 group-hover:scale-105 transition-transform shadow-lg">
                    {{ $member['initial'] }}
                </div>
                <h3 class="text-sm font-bold text-slate-800">{{ $member['name'] }}</h3>
                <p class="text-xs text-slate-500 mt-0.5">{{ $member['role'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="py-16 md:py-20 bg-primary">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-on-scroll">
        <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">
            Ingin bekerja sama atau sekadar bertanya?
        </h2>
        <p class="text-white/75 text-lg mb-8 max-w-2xl mx-auto">
            Tim kami siap membantu Anda. Hubungi kami sekarang dan mulai perjalanan digital Anda bersama MonetraPay.
        </p>
        <a href="{{ url('/kontak') }}"
           class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary font-bold rounded-2xl hover:bg-slate-50 transition-colors shadow-xl text-base">
            Hubungi Kami
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>

@endif

@endsection
