{{-- Call to Action Component --}}
@php
    $heading = $content['heading'] ?? 'Ready to Get Started?';
    $text = $content['text'] ?? 'Join thousands of satisfied customers today';
    $buttonText = $content['button_text'] ?? 'Get Started Now';
    $buttonLink = $content['button_link'] ?? '#';
    $style = $settings['style'] ?? 'gradient'; // gradient, solid, outline
@endphp

<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="rounded-2xl p-12 text-center text-white relative overflow-hidden @if($style === 'gradient') @elseif($style === 'solid') bg-gray-900 @else border-4 border-primary text-gray-900 @endif" @if($style === 'gradient') style="background: linear-gradient(135deg, var(--theme-primary, #3D9E7A) 0%, var(--theme-secondary, #FF6B35) 100%);" @endif>
            <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ $heading }}</h2>
            <p class="text-lg mb-8 opacity-90 max-w-2xl mx-auto">{{ $text }}</p>
            <a href="{{ $buttonLink }}" class="inline-block px-8 py-4 @if($style === 'outline') bg-primary text-white @else bg-white text-gray-900 @endif rounded-lg font-semibold hover:opacity-90 transition-all transform hover:scale-105">
                {{ $buttonText }}
            </a>
        </div>
    </div>
</section>
