@php
    $sectionTitle    = $content['section_title']    ?? 'Pilih Paket Anda';
    $sectionSubtitle = $content['section_subtitle'] ?? 'Mulai gratis, upgrade kapan saja sesuai kebutuhan.';
    $currency        = $content['currency']         ?? 'Rp';
    $plans           = is_array($content['plans'] ?? null) ? $content['plans'] : [];

    if (empty($plans)) {
        $plans = [
            ['name'=>'Starter', 'price'=>'0', 'period'=>'bulan', 'description'=>'Cocok untuk pemula', 'is_popular'=>false, 'button_text'=>'Mulai Gratis', 'button_link'=>url('/kontak'), 'features'=>['Transaksi s/d 100x/bulan','Support via email','Dashboard dasar','1 pengguna']],
            ['name'=>'Growth',  'price'=>'149.000', 'period'=>'bulan', 'description'=>'Paling populer untuk UMKM', 'is_popular'=>true, 'button_text'=>'Pilih Growth', 'button_link'=>url('/kontak'), 'features'=>['Transaksi unlimited','Support prioritas 24/7','Dashboard lengkap','5 pengguna','Laporan harian']],
            ['name'=>'Enterprise','price'=>'399.000','period'=>'bulan','description'=>'Untuk bisnis skala besar', 'is_popular'=>false,'button_text'=>'Hubungi Kami','button_link'=>url('/kontak'),'features'=>['Semua fitur Growth','API akses penuh','Dedicated account manager','Unlimited pengguna','SLA 99.9%']],
        ];
    }
@endphp

<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-block text-xs font-bold tracking-widest uppercase mb-3 px-3 py-1 rounded-full"
                  style="color:var(--theme-primary,#3D9E7A);background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 12%,white)">
                Harga
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">{{ $sectionTitle }}</h2>
            @if($sectionSubtitle)
            <p class="text-lg text-slate-500">{{ $sectionSubtitle }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 items-start">
            @foreach($plans as $plan)
            @php
                $popular = !empty($plan['is_popular']);
                $features = is_array($plan['features'] ?? null) ? $plan['features'] : [];
            @endphp
            <div class="relative rounded-2xl border-2 {{ $popular ? 'border-primary shadow-2xl scale-105' : 'border-slate-100 shadow-sm' }} overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">

                @if($popular)
                <div class="text-center text-xs font-bold text-white py-2.5 tracking-wider"
                     style="background:var(--theme-primary,#3D9E7A)">
                    ⭐ PALING POPULER
                </div>
                @endif

                <div class="p-8">
                    <h3 class="text-xl font-extrabold text-slate-800 mb-1">{{ $plan['name'] }}</h3>
                    <p class="text-sm text-slate-500 mb-6">{{ $plan['description'] ?? '' }}</p>

                    <div class="flex items-end gap-1 mb-8">
                        @if(($plan['price'] ?? '0') === '0')
                            <span class="text-4xl font-extrabold text-slate-800">Gratis</span>
                        @else
                            <span class="text-2xl font-bold text-slate-500">{{ $currency }}</span>
                            <span class="text-4xl font-extrabold text-slate-800">{{ number_format((float)str_replace(['.','Rp',' '], '', $plan['price'] ?? '0'), 0, ',', '.') }}</span>
                            <span class="text-sm text-slate-400 mb-1">/{{ $plan['period'] ?? 'bulan' }}</span>
                        @endif
                    </div>

                    <a href="{{ $plan['button_link'] ?? url('/kontak') }}"
                       class="block w-full text-center py-3.5 rounded-xl font-bold text-sm transition-all duration-200 mb-8
                              {{ $popular ? 'text-white hover:opacity-90' : 'text-slate-700 bg-slate-100 hover:bg-slate-200' }}"
                       @if($popular) style="background:var(--theme-primary,#3D9E7A)" @endif>
                        {{ $plan['button_text'] ?? 'Pilih Paket' }}
                    </a>

                    <ul class="space-y-3">
                        @foreach($features as $feature)
                        <li class="flex items-start gap-2.5 text-sm text-slate-600">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 style="color:var(--theme-primary,#3D9E7A)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
