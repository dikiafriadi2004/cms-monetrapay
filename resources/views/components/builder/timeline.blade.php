@php
    $sectionLabel    = $content['section_label']    ?? 'Perjalanan Kami';
    $sectionTitle    = $content['section_title']    ?? 'Tumbuh selangkah demi selangkah';
    $sectionSubtitle = $content['section_subtitle'] ?? 'Dari sebuah ide sederhana, kami berkembang menjadi platform yang dipercaya jutaan pengguna.';
    $items = $content['items'] ?? [
        ['year'=>'2019','color'=>'#0D9488','title'=>'Didirikan','desc'=>'MonetraPay lahir dengan visi menyederhanakan transaksi digital di Indonesia.'],
        ['year'=>'2020','color'=>'#F59E0B','title'=>'Ekspansi Produk','desc'=>'Menambahkan layanan PPOB dan pembayaran tagihan untuk memperluas jangkauan.'],
        ['year'=>'2021','color'=>'#8B5CF6','title'=>'Jaringan Agen','desc'=>'Membangun jaringan agen pertama dengan lebih dari 1.000 mitra aktif.'],
        ['year'=>'2022','color'=>'#3B82F6','title'=>'Perizinan Resmi','desc'=>'Mendapatkan izin resmi dari Bank Indonesia sebagai penyelenggara PPOB.'],
        ['year'=>'2024','color'=>'#0D9488','title'=>'Platform Digital','desc'=>'Meluncurkan platform digital baru dengan 500+ produk dan 100K+ pengguna aktif.'],
    ];
@endphp

<section class="py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

            {{-- Left: Heading --}}
            <div class="animate-on-scroll">
                <span class="inline-block text-xs font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">{{ $sectionLabel }}</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 leading-tight mb-4">{{ $sectionTitle }}</h2>
                <p class="text-slate-500 leading-relaxed text-base">{{ $sectionSubtitle }}</p>
            </div>

            {{-- Right: Timeline --}}
            <div class="animate-on-scroll delay-200">
                <div class="space-y-0">
                    @foreach($items as $i => $item)
                    @php
                        $color = $item['color'] ?? '#0D9488';
                        $isLast = $i === count($items) - 1;
                    @endphp
                    <div class="flex gap-4 group">
                        <div class="flex flex-col items-center">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-xs font-bold shrink-0 shadow-lg group-hover:scale-110 transition-transform"
                                 style="background:{{ $color }}">
                                {{ substr($item['year'] ?? '00', 2) }}
                            </div>
                            @if(!$isLast)
                            <div class="w-0.5 flex-1 bg-slate-200 mt-1 mb-1 min-h-[2rem]"></div>
                            @endif
                        </div>
                        <div class="pb-7">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-bold text-slate-400">{{ $item['year'] ?? '' }}</span>
                                <span class="text-sm font-bold text-slate-800">{{ $item['title'] ?? '' }}</span>
                            </div>
                            <p class="text-sm text-slate-500 leading-relaxed">{{ $item['desc'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
