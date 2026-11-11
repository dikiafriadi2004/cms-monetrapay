@php
    $sectionTitle    = $content['section_title']    ?? 'Fitur Unggulan';
    $sectionSubtitle = $content['section_subtitle'] ?? 'Semua yang Anda butuhkan dalam satu platform.';
    $columns         = (int)($content['columns'] ?? 3);
    $style           = $content['style'] ?? 'cards';
    $features        = is_array($content['features'] ?? null) ? $content['features'] : [];

    // Default demo features jika kosong
    if (empty($features)) {
        $features = [
            ['icon_emoji' => '⚡', 'title' => 'Cepat & Andal',       'description' => 'Transaksi selesai dalam hitungan detik tanpa gangguan.'],
            ['icon_emoji' => '🔒', 'title' => 'Keamanan Terjamin',   'description' => 'Enkripsi end-to-end melindungi setiap data Anda.'],
            ['icon_emoji' => '📊', 'title' => 'Laporan Real-time',   'description' => 'Monitor performa bisnis kapan saja dari mana saja.'],
            ['icon_emoji' => '🤝', 'title' => 'Support 24/7',        'description' => 'Tim kami siap membantu Anda setiap saat.'],
            ['icon_emoji' => '💰', 'title' => 'Harga Kompetitif',    'description' => 'Margin terbaik untuk setiap produk yang Anda jual.'],
            ['icon_emoji' => '🚀', 'title' => 'Mudah Digunakan',     'description' => 'Antarmuka intuitif, siap dipakai tanpa pelatihan khusus.'],
        ];
    }

    $colClass = match($columns) {
        2 => 'grid-cols-1 sm:grid-cols-2',
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="text-center max-w-3xl mx-auto mb-14">
            <span class="inline-block text-xs font-bold tracking-widest uppercase mb-3 px-3 py-1 rounded-full"
                  style="color:var(--theme-primary,#3D9E7A);background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 12%,white)">
                Fitur
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">{{ $sectionTitle }}</h2>
            @if($sectionSubtitle)
            <p class="text-lg text-slate-500 leading-relaxed">{{ $sectionSubtitle }}</p>
            @endif
        </div>

        {{-- Features grid --}}
        <div class="grid {{ $colClass }} gap-6 lg:gap-8">
            @foreach($features as $i => $feature)
            @php
                $icon  = $feature['icon_emoji'] ?? '✨';
                $ftitle = $feature['title']     ?? '';
                $fdesc  = $feature['description'] ?? '';
                $flink  = $feature['link_url']  ?? '';
                $flt    = $feature['link_text'] ?? '';
            @endphp

            @if($style === 'numbered')
            <div class="relative p-8 rounded-2xl border border-slate-100 bg-white hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                <span class="absolute -top-5 -left-2 text-8xl font-black select-none"
                      style="color:color-mix(in srgb,var(--theme-primary,#3D9E7A) 10%,white);line-height:1">
                    {{ $i+1 }}
                </span>
                <div class="relative">
                    <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $ftitle }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $fdesc }}</p>
                    @if($flink && $flt)
                    <a href="{{ $flink }}" class="inline-flex items-center gap-1 text-sm font-semibold mt-3"
                       style="color:var(--theme-primary,#3D9E7A)">
                        {{ $flt }} →
                    </a>
                    @endif
                </div>
            </div>

            @elseif($style === 'icons')
            <div class="flex gap-4 p-6">
                <div class="text-4xl shrink-0 leading-none">{{ $icon }}</div>
                <div>
                    <h3 class="text-base font-bold text-slate-800 mb-1">{{ $ftitle }}</h3>
                    <p class="text-sm text-slate-500 leading-relaxed">{{ $fdesc }}</p>
                </div>
            </div>

            @else {{-- cards (default) --}}
            <div class="group p-7 rounded-2xl border border-slate-100 bg-white shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1.5 hover:border-transparent"
                 style="--hover-color:var(--theme-primary,#3D9E7A)">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl mb-5 transition-transform group-hover:scale-110"
                     style="background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 12%,white)">
                    {{ $icon }}
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $ftitle }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $fdesc }}</p>
                @if($flink && $flt)
                <a href="{{ $flink }}" class="inline-flex items-center gap-1 text-sm font-semibold mt-4"
                   style="color:var(--theme-primary,#3D9E7A)">
                    {{ $flt }}
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @endif
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
