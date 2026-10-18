{{-- Map Location Component --}}
@php
    $heading = $content['heading'] ?? 'Visit Us';
    $address = $content['address'] ?? '';
    $lat = $content['latitude'] ?? '-6.200000';
    $lng = $content['longitude'] ?? '106.816666';
    $zoom = $settings['zoom'] ?? 15;
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        @if($heading)
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">{{ $heading }}</h2>
        @endif
        
        <div class="max-w-4xl mx-auto">
            @if($address)
            <p class="text-center text-gray-700 mb-6">{{ $address }}</p>
            @endif
            
            <div class="rounded-2xl overflow-hidden shadow-lg" style="height: 400px;">
                <iframe src="https://www.google.com/maps?q={{ $lat }},{{ $lng }}&hl=id&z={{ $zoom }}&output=embed" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
    </div>
</section>
