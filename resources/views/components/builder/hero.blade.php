{{-- Hero Section Component --}}
@php
    $title = $content['title'] ?? 'Welcome to Our Website';
    $subtitle = $content['subtitle'] ?? 'Discover amazing features and services';
    $buttonText = $content['button_text'] ?? 'Get Started';
    $buttonLink = $content['button_link'] ?? '#';
    $backgroundImage = $content['background_image'] ?? '';
    $textAlign = $settings['text_align'] ?? 'center';
    $height = $settings['height'] ?? 'large'; // small, medium, large
    
    $heightClass = match($height) {
        'small' => 'py-12 md:py-16',
        'medium' => 'py-16 md:py-24',
        'large' => 'py-24 md:py-32',
        default => 'py-20 md:py-28',
    };
@endphp

<section class="relative overflow-hidden {{ $heightClass }}" style="@if($backgroundImage) background-image: url('{{ $backgroundImage }}'); background-size: cover; background-position: center; @else background: linear-gradient(135deg, var(--theme-primary, #3D9E7A) 0%, var(--theme-secondary, #FF6B35) 100%); @endif">
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/40"></div>
    
    {{-- Content --}}
    <div class="container mx-auto px-4 relative z-10 text-white text-{{ $textAlign }}">
        <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-4 animate-fade-in">
            {{ $title }}
        </h1>
        <p class="text-lg md:text-xl mb-8 max-w-2xl {{ $textAlign === 'center' ? 'mx-auto' : '' }} opacity-90">
            {{ $subtitle }}
        </p>
        @if($buttonText)
        <a href="{{ $buttonLink }}" class="inline-block px-8 py-4 bg-white text-gray-900 rounded-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105">
            {{ $buttonText }}
        </a>
        @endif
    </div>
</section>
