@php
    $title       = $content['title']       ?? '';
    $subtitle    = $content['subtitle']    ?? '';
    $videoUrl    = $content['video_url']   ?? '';
    $thumbUrl    = $content['thumbnail_url'] ?? '';
    $caption     = $content['caption']    ?? '';

    // Extract YouTube ID
    $videoId = '';
    $embedUrl = '';
    if (preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $videoUrl, $m)) {
        $videoId  = $m[1];
        $embedUrl = "https://www.youtube.com/embed/{$videoId}?autoplay=1&rel=0";
        if (!$thumbUrl) $thumbUrl = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
    } elseif (preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $m)) {
        $embedUrl = "https://player.vimeo.com/video/{$m[1]}?autoplay=1";
    } else {
        $embedUrl = $videoUrl;
    }
@endphp

<section class="py-16 md:py-24 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($title || $subtitle)
        <div class="text-center mb-10">
            @if($title) <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-3">{{ $title }}</h2> @endif
            @if($subtitle) <p class="text-lg text-slate-500">{{ $subtitle }}</p> @endif
        </div>
        @endif

        @if($embedUrl)
        <div x-data="{ playing: false }" class="relative rounded-2xl overflow-hidden shadow-2xl shadow-slate-200 aspect-video bg-slate-900">
            {{-- Thumbnail + play button --}}
            <div x-show="!playing" @click="playing = true" class="absolute inset-0 cursor-pointer group">
                @if($thumbUrl)
                    <img src="{{ $thumbUrl }}" alt="{{ $title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center"
                         style="background:var(--theme-primary,#3D9E7A)">
                    </div>
                @endif
                <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors flex items-center justify-center">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"
                             style="color:var(--theme-primary,#3D9E7A)">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
            {{-- Iframe --}}
            <iframe x-show="playing" x-cloak
                    src="{{ $embedUrl }}"
                    class="absolute inset-0 w-full h-full"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
            </iframe>
        </div>
        @else
        <div class="rounded-2xl bg-slate-100 aspect-video flex items-center justify-center text-slate-400">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                <p class="text-sm">Masukkan URL video YouTube atau Vimeo</p>
            </div>
        </div>
        @endif

        @if($caption)
        <p class="text-center text-sm text-slate-500 mt-4">{{ $caption }}</p>
        @endif
    </div>
</section>
