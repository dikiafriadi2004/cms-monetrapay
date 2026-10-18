@php
    $sectionTitle = $content['section_title'] ?? '';
    $images       = is_array($content['images'] ?? null) ? $content['images'] : [];
    $columns      = (int)($content['columns'] ?? 3);

    if (empty($images)) {
        $images = array_fill(0, 6, ['url'=>'','caption'=>'','alt'=>'Gallery Image']);
    }

    $colClass = match($columns) {
        2 => 'grid-cols-1 sm:grid-cols-2',
        4 => 'grid-cols-2 sm:grid-cols-3 lg:grid-cols-4',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($sectionTitle)
        <div class="text-center mb-12">
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ $sectionTitle }}</h2>
        </div>
        @endif

        <div class="grid {{ $colClass }} gap-4">
            @foreach($images as $img)
            @php $url = $img['url'] ?? ''; $caption = $img['caption'] ?? ''; $alt = $img['alt'] ?? ''; @endphp
            <div class="group relative overflow-hidden rounded-2xl aspect-square cursor-pointer"
                 x-data="{ show: false }">
                @if($url)
                    <img src="{{ $url }}" alt="{{ $alt }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                @else
                    <div class="w-full h-full flex items-center justify-center text-4xl"
                         style="background:linear-gradient(135deg,color-mix(in srgb,var(--theme-primary,#3D9E7A) 20%,white),color-mix(in srgb,var(--theme-secondary,#FF6B35) 20%,white))">
                        🖼️
                    </div>
                @endif
                @if($caption)
                <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-4">
                    <p class="text-white text-sm font-medium">{{ $caption }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
