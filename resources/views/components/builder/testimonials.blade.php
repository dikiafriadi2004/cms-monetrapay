{{-- Testimonials Component --}}
@php
    $heading = $content['heading'] ?? 'What Our Clients Say';
    $testimonials = $content['testimonials'] ?? [];
    $layout = $settings['layout'] ?? 'grid'; // grid, slider
    $columns = $settings['columns'] ?? 3;
    
    $gridClass = match($columns) {
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-3',
        default => 'md:grid-cols-3',
    };
@endphp

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($heading)
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">{{ $heading }}</h2>
        @endif
        
        <div class="grid grid-cols-1 {{ $gridClass }} gap-8">
            @foreach($testimonials as $testimonial)
            <div class="bg-white p-8 rounded-2xl shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <svg class="w-5 h-5 {{ $i < ($testimonial['rating'] ?? 5) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-gray-700 mb-6 italic">"{{ $testimonial['text'] ?? '' }}"</p>
                <div class="flex items-center gap-3">
                    @if($testimonial['photo'] ?? '')
                    <img src="{{ $testimonial['photo'] }}" alt="{{ $testimonial['name'] ?? '' }}" class="w-12 h-12 rounded-full object-cover">
                    @else
                    <div class="w-12 h-12 rounded-full bg-primary text-white flex items-center justify-center font-bold">
                        {{ substr($testimonial['name'] ?? 'U', 0, 1) }}
                    </div>
                    @endif
                    <div>
                        <p class="font-bold text-gray-900">{{ $testimonial['name'] ?? '' }}</p>
                        <p class="text-sm text-gray-600">{{ $testimonial['position'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
