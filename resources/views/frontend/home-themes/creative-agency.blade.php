{{--
    Creative Agency Home
    Colors: Red #E53E3E, black accent. NO gradients anywhere.
    Layout: Split hero, horizontal scroll services, bold numbered layout.
--}}

{{-- NAV --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 flex items-center justify-between h-16">
        <span class="text-xl font-black text-gray-900 tracking-tight">{{ $siteName ?? 'MonetraPay' }}</span>
        <div class="flex items-center gap-8">
            <a href="{{ url('/') }}" class="text-sm font-semibold text-gray-700 hover:text-red-600 border-b-2 border-transparent hover:border-red-600 pb-0.5 transition-colors">Beranda</a>
            <a href="{{ url('/layanan') }}" class="text-sm font-semibold text-gray-700 hover:text-red-600 border-b-2 border-transparent hover:border-red-600 pb-0.5 transition-colors">Layanan</a>
            <a href="{{ url('/kontak') }}" class="text-sm font-semibold text-gray-700 hover:text-red-600 border-b-2 border-transparent hover:border-red-600 pb-0.5 transition-colors">Kontak</a>
        </div>
    </div>
</nav>

{{-- HERO: Split layout --}}
<section class="min-h-screen flex">
    {{-- LEFT HALF --}}
    <div class="relative flex-1 bg-white flex items-center px-12 lg:px-20 py-20 overflow-hidden">
        {{-- Decorative huge background number --}}
        <span class="absolute -left-6 top-1/2 -translate-y-1/2 text-9xl font-black text-gray-100 select-none pointer-events-none leading-none">01</span>
        <div class="relative z-10 max-w-lg">
            <p class="text-xs font-black uppercase tracking-widest text-red-600 mb-5">✦ Creative Agency</p>
            <h1 class="text-5xl font-black text-gray-900 leading-tight mb-6" style="letter-spacing:-0.03em">
                {{ $homepage['hero_title'] ?? 'Kami Bangun Brand yang Tak Terlupakan' }}
            </h1>
            <p class="text-lg text-gray-500 leading-relaxed mb-10">
                {{ $homepage['hero_subtitle'] ?? 'Strategi kreatif, desain visual, dan kampanye digital untuk bisnis yang ingin tampil beda.' }}
            </p>
            <a href="{{ $homepage['hero_cta_url'] ?? url('/kontak') }}"
               class="inline-block bg-red-600 text-white px-8 py-4 font-bold text-sm uppercase tracking-wider hover:bg-red-700 transition-colors">
                {{ $homepage['hero_cta_text'] ?? 'Mulai Proyek Anda' }}
            </a>
        </div>
    </div>
    {{-- RIGHT HALF --}}
    <div class="flex-1 bg-gray-50 flex items-center px-12 lg:px-16 py-20">
        <div class="w-full space-y-4">
            <p class="text-xs font-black uppercase tracking-widest text-gray-400 mb-8">Apa yang Kami Lakukan</p>
            {{-- Service card 1 --}}
            <div class="bg-white border-l-4 border-red-600 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base mb-1">Brand Strategy</h3>
                        <p class="text-sm text-gray-500">Membangun identitas brand yang kuat dan konsisten</p>
                    </div>
                    <span class="text-gray-400 text-lg ml-4">→</span>
                </div>
            </div>
            {{-- Service card 2 --}}
            <div class="bg-white border-l-4 border-red-600 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base mb-1">Visual Design</h3>
                        <p class="text-sm text-gray-500">Desain yang menarik perhatian dan menyampaikan pesan</p>
                    </div>
                    <span class="text-gray-400 text-lg ml-4">→</span>
                </div>
            </div>
            {{-- Service card 3 --}}
            <div class="bg-white border-l-4 border-red-600 p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base mb-1">Digital Campaign</h3>
                        <p class="text-sm text-gray-500">Kampanye digital terukur dengan hasil nyata</p>
                    </div>
                    <span class="text-gray-400 text-lg ml-4">→</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SERVICES: Horizontal scrolling numbered list --}}
<section class="bg-white py-20 px-6">
    <div class="max-w-7xl mx-auto">
        <p class="text-xs font-black uppercase tracking-widest text-red-600 mb-2">Portofolio</p>
        <h2 class="text-4xl font-black text-gray-900 mb-12" style="letter-spacing:-0.03em">Our Work</h2>
        <div class="overflow-x-auto pb-6">
            <div class="flex gap-12 flex-nowrap" style="min-width:max-content">
                @foreach([
                    ['01', $homepage['service_1_title'] ?? 'Brand Identity', $homepage['service_1_description'] ?? 'Menciptakan identitas visual yang kuat dan berkesan untuk bisnis Anda.'],
                    ['02', $homepage['service_2_title'] ?? 'UI/UX Design', $homepage['service_2_description'] ?? 'Antarmuka yang intuitif dan pengalaman pengguna yang menyenangkan.'],
                    ['03', $homepage['service_3_title'] ?? 'Digital Marketing', $homepage['service_3_description'] ?? 'Strategi pemasaran digital yang terukur dan efektif.'],
                    ['04', $homepage['service_4_title'] ?? 'Content Creation', $homepage['service_4_description'] ?? 'Konten kreatif yang menarik audiens dan mendorong konversi.'],
                ] as $item)
                <div class="w-64 shrink-0">
                    <p class="text-6xl font-black text-red-600 leading-none mb-4">{{ $item[0] }}</p>
                    <h3 class="font-black text-gray-900 text-lg mb-3">{{ $item[1] }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $item[2] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- STATS: Single row, black bg --}}
<section class="bg-black text-white py-10 px-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex divide-x divide-white/30">
            @foreach([['100K+','Pengguna Aktif'],['500+','Produk Digital'],['99.9%','Uptime Terjamin'],['24/7','Layanan Support']] as $stat)
            <div class="flex-1 text-center px-6">
                <p class="text-4xl font-black text-white mb-1">{{ $stat[0] }}</p>
                <p class="text-xs uppercase tracking-widest text-gray-400">{{ $stat[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- LATEST BLOGS --}}
@if($latestBlogs->isNotEmpty())
<section class="bg-gray-50 py-20 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-end justify-between mb-12">
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-red-600 mb-2">Wawasan</p>
                <h2 class="text-3xl font-black text-gray-900">Artikel Terbaru</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-bold text-red-600 hover:text-red-700 border-b border-red-600 pb-0.5">Lihat Semua →</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($latestBlogs as $blog)
            <article class="bg-white group">
                @if($blog->featured_image)
                    <img src="{{ Storage::url($blog->featured_image) }}" class="w-full h-52 object-cover" alt="{{ $blog->title }}">
                @else
                    <div class="w-full h-52 bg-red-600"></div>
                @endif
                <div class="p-6 border-t-4 border-red-600">
                    <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3">{{ $blog->published_at?->format('d M Y') }}</p>
                    <h3 class="font-black text-gray-900 text-base leading-snug mb-3 group-hover:text-red-600 transition-colors line-clamp-2">
                        {{ $blog->title }}
                    </h3>
                    @if($blog->excerpt)
                    <p class="text-sm text-gray-500 mb-4">{{ Str::limit($blog->excerpt, 80) }}</p>
                    @endif
                    <a href="{{ route('blog.show', $blog->slug) }}" class="text-xs font-black uppercase tracking-widest text-red-600 hover:text-red-700">Baca →</a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- CTA --}}
<section class="bg-black text-white py-20 px-6 text-center">
    <div class="max-w-2xl mx-auto">
        <p class="text-xs font-black uppercase tracking-widest text-red-500 mb-4">✦ Siap Berkolaborasi?</p>
        <h2 class="text-4xl font-black text-white mb-4" style="letter-spacing:-0.03em">
            {{ $homepage['cta_title'] ?? 'Mari Wujudkan Ide Anda' }}
        </h2>
        <p class="text-gray-400 text-lg mb-10">
            {{ $homepage['cta_description'] ?? 'Ceritakan proyek Anda dan kami akan membantu mewujudkannya.' }}
        </p>
        <a href="{{ $homepage['cta_button_url'] ?? url('/kontak') }}"
           class="inline-block bg-red-600 text-white px-10 py-4 font-bold text-sm uppercase tracking-wider hover:bg-red-700 transition-colors">
            {{ $homepage['cta_button_text'] ?? 'Hubungi Kami' }}
        </a>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-gray-900 text-white py-10 px-6">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
            <p class="font-black text-xl text-white mb-3">{{ $siteName ?? 'MonetraPay' }}</p>
            <p class="text-sm text-gray-400 leading-relaxed">{{ $footerSettings['tagline'] ?? 'Solusi transaksi digital terpercaya untuk bisnis modern.' }}</p>
        </div>
        <div>
            <h4 class="text-xs font-black uppercase tracking-widest text-gray-500 mb-4">Navigasi</h4>
            <ul class="space-y-2">
                <li><a href="{{ url('/') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Beranda</a></li>
                <li><a href="{{ url('/layanan') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Layanan</a></li>
                <li><a href="{{ route('blog.index') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Blog</a></li>
                <li><a href="{{ url('/kontak') }}" class="text-sm text-gray-400 hover:text-white transition-colors">Kontak</a></li>
            </ul>
        </div>
        <div>
            <h4 class="text-xs font-black uppercase tracking-widest text-gray-500 mb-4">Kontak</h4>
            @if(!empty($footerSettings['contact_email']))
            <p class="text-sm text-gray-400 mb-2">✉ {{ $footerSettings['contact_email'] }}</p>
            @endif
            @if(!empty($footerSettings['contact_phone']))
            <p class="text-sm text-gray-400">📞 {{ $footerSettings['contact_phone'] }}</p>
            @endif
        </div>
    </div>
    <div class="max-w-7xl mx-auto border-t border-white/10 mt-8 pt-6">
        <p class="text-xs text-gray-600 text-center">{{ $footerSettings['copyright_text'] ?? '© ' . date('Y') . ' ' . ($siteName ?? 'MonetraPay') . '. All rights reserved.' }}</p>
    </div>
</footer>
