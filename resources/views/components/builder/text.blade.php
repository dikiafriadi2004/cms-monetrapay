{{-- Text Block Component --}}
@php
    $heading = $content['heading'] ?? '';
    $text = $content['text'] ?? '';
    $textAlign = $settings['text_align'] ?? 'left';
    $maxWidth = $settings['max_width'] ?? 'full'; // narrow, medium, wide, full
    
    $widthClass = match($maxWidth) {
        'narrow' => 'max-w-2xl',
        'medium' => 'max-w-4xl',
        'wide' => 'max-w-6xl',
        'full' => 'max-w-full',
        default => 'max-w-4xl',
    };
@endphp

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="{{ $widthClass }} mx-auto text-{{ $textAlign }}">
            @if($heading)
            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900">
                {{ $heading }}
            </h2>
            @endif
            
            @if($text)
            <div class="prose prose-lg max-w-none text-gray-700">
                {!! nl2br(e($text)) !!}
            </div>
            @endif
        </div>
    </div>
</section>
