{{-- Image Gallery Component --}}
@php
    $heading = $content['heading'] ?? '';
    $images = $content['images'] ?? [];
    $layout = $settings['layout'] ?? 'grid'; // grid, masonry, slider
    $columns = $settings['columns'] ?? 3; // 2, 3, 4
    
    $gridClass = match($columns) {
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-3',
        4 => 'md:grid-cols-2 lg:grid-cols-4',
        default => 'md:grid-cols-3',
    };
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        @if($heading)
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">{{ $heading }}</h2>
        @endif
        
        <div class="grid grid-cols-1 {{ $gridClass }} gap-6">
            @foreach($images as $image)
            <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-shadow">
                <img src="{{ $image['url'] ?? '' }}" alt="{{ $image['alt'] ?? '' }}" class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-300">
                @if($image['caption'] ?? '')
                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                    <p class="text-white text-sm">{{ $image['caption'] }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
