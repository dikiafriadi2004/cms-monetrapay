{{-- Video Embed Component --}}
@php
    $heading = $content['heading'] ?? '';
    $videoUrl = $content['video_url'] ?? '';
    $videoType = $content['video_type'] ?? 'youtube'; // youtube, vimeo, direct
    $autoplay = $settings['autoplay'] ?? false;
    $aspectRatio = $settings['aspect_ratio'] ?? '16/9'; // 16/9, 4/3, 21/9
    
    // Extract video ID for YouTube/Vimeo
    if ($videoType === 'youtube' && preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&]+)/', $videoUrl, $matches)) {
        $embedUrl = "https://www.youtube.com/embed/{$matches[1]}" . ($autoplay ? '?autoplay=1' : '');
    } elseif ($videoType === 'vimeo' && preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $matches)) {
        $embedUrl = "https://player.vimeo.com/video/{$matches[1]}" . ($autoplay ? '?autoplay=1' : '');
    } else {
        $embedUrl = $videoUrl;
    }
@endphp

<section class="py-16">
    <div class="container mx-auto px-4">
        @if($heading)
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">{{ $heading }}</h2>
        @endif
        
        <div class="max-w-4xl mx-auto">
            <div class="relative rounded-2xl overflow-hidden shadow-2xl" style="aspect-ratio: {{ $aspectRatio }};">
                <iframe src="{{ $embedUrl }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="absolute inset-0 w-full h-full"></iframe>
            </div>
        </div>
    </div>
</section>
