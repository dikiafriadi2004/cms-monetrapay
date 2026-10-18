{{-- Features Grid Component --}}
@php
    $heading = $content['heading'] ?? 'Our Features';
    $features = $content['features'] ?? [];
    $columns = $settings['columns'] ?? 3; // 2, 3, 4
    
    $gridClass = match($columns) {
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-3',
        4 => 'md:grid-cols-2 lg:grid-cols-4',
        default => 'md:grid-cols-3',
    };
@endphp

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($heading)
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">
            {{ $heading }}
        </h2>
        @endif
        
        <div class="grid grid-cols-1 {{ $gridClass }} gap-8">
            @foreach($features as $feature)
            <div class="text-center p-6 bg-white rounded-xl border-2 border-gray-200 hover:border-primary transition-all hover:shadow-lg">
                @if($feature['icon'] ?? '')
                <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center text-2xl" style="background-color: var(--theme-primary, #3D9E7A); color: white;">
                    {{ $feature['icon'] }}
                </div>
                @endif
                
                <h3 class="text-xl font-bold mb-3 text-gray-900">{{ $feature['title'] ?? '' }}</h3>
                <p class="text-gray-600">{{ $feature['description'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
