@php
    $title       = $content['title']        ?? '';
    $address     = $content['address']      ?? '';
    $embedUrl    = $content['embed_url']    ?? '';
    $height      = (int)($content['height'] ?? 400);
    $showInfo    = !empty($content['show_info_card']);
    $height      = in_array($height,[300,400,500,600]) ? $height : 400;
@endphp

<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($title)
        <div class="text-center mb-10">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ $title }}</h2>
        </div>
        @endif

        <div class="rounded-2xl overflow-hidden shadow-lg border border-slate-100 {{ $showInfo && $address ? 'grid grid-cols-1 lg:grid-cols-3' : '' }}">
            @if($showInfo && $address)
            <div class="p-8 flex flex-col justify-center bg-white lg:col-span-1">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4"
                     style="background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 12%,white)">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         style="color:var(--theme-primary,#3D9E7A)">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800 mb-2">Lokasi Kami</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $address }}</p>
            </div>
            <div class="lg:col-span-2">
            @endif

            @if($embedUrl)
                <iframe src="{{ $embedUrl }}"
                        width="100%"
                        height="{{ $height }}"
                        style="border:0;display:block"
                        allowfullscreen
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            @else
                <div class="w-full flex items-center justify-center bg-slate-100 text-slate-400"
                     style="height:{{ $height }}px">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <p class="text-sm">Masukkan Google Maps embed URL</p>
                    </div>
                </div>
            @endif

            @if($showInfo && $address) </div> @endif
        </div>

        @if(!$showInfo && $address)
        <p class="text-sm text-slate-500 text-center mt-4 flex items-center justify-center gap-1.5">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--theme-primary,#3D9E7A)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
            {{ $address }}
        </p>
        @endif
    </div>
</section>
