@php
    $title       = $content['title']       ?? 'Membuat pembayaran digital terasa <span class="text-primary">mudah untuk semua orang.</span>';
    $subtitle    = $content['subtitle']    ?? 'Kami hadir untuk memudahkan transaksi digital Anda, kapan saja dan di mana saja.';
    $breadcrumb  = $content['breadcrumb']  ?? true;
    $stats = $content['stats'] ?? [
        ['value'=>'100K+','label'=>'Pengguna Aktif'],
        ['value'=>'500+', 'label'=>'Produk Digital'],
        ['value'=>'99.9%','label'=>'Uptime Server'],
        ['value'=>'2019', 'label'=>'Tahun Berdiri'],
    ];
@endphp

<section class="bg-white pt-12 pb-16 md:pt-16 md:pb-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($breadcrumb ?? true)
        <x-frontend.breadcrumb :items="[]" />
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center mt-8">
            {{-- Left --}}
            <div class="animate-on-scroll">
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-800 leading-tight mb-6">
                    {!! $title !!}
                </h1>
                <p class="text-lg text-slate-500 leading-relaxed mb-10">{{ $subtitle }}</p>

                {{-- Stats --}}
                <div class="flex flex-wrap gap-6">
                    @foreach($stats as $i => $stat)
                        @if($i > 0)<div class="w-px bg-slate-200 self-stretch hidden sm:block"></div>@endif
                        <div class="text-center">
                            <p class="text-3xl font-extrabold text-primary">{{ $stat['value'] ?? '' }}</p>
                            <p class="text-sm text-slate-500 mt-1">{{ $stat['label'] ?? '' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Logo --}}
            <div class="animate-on-scroll delay-200 flex justify-center">
                <div class="relative">
                    <div class="w-72 h-72 md:w-96 md:h-96 bg-primary/8 rounded-3xl flex items-center justify-center">
                        @php
                            $logo = \App\Models\Setting::get('logo', 'general');
                            $siteName = \App\Models\Setting::get('site_name', 'general') ?? config('app.name');
                        @endphp
                        @if($logo)
                            <img src="{{ Storage::url($logo) }}" alt="{{ $siteName }}" class="w-48 md:w-64 object-contain drop-shadow-xl">
                        @else
                            <span class="text-4xl font-extrabold">{{ $siteName }}</span>
                        @endif
                    </div>
                    <div class="absolute -top-4 -right-4 w-20 h-20 bg-secondary/10 rounded-2xl pointer-events-none"></div>
                    <div class="absolute -bottom-4 -left-4 w-14 h-14 bg-primary/10 rounded-xl pointer-events-none"></div>
                </div>
            </div>
        </div>
    </div>
</section>
