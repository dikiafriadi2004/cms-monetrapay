{{-- Social Media Component --}}
@php
    $heading = $content['heading'] ?? 'Follow Us';
    $text = $content['text'] ?? 'Stay connected on social media';
    $socialLinks = $content['social_links'] ?? [];
@endphp

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4 text-center">
        @if($heading)
        <h2 class="text-3xl md:text-4xl font-bold mb-4 text-gray-900">{{ $heading }}</h2>
        @endif
        
        @if($text)
        <p class="text-lg text-gray-600 mb-8">{{ $text }}</p>
        @endif
        
        <div class="flex justify-center gap-4 flex-wrap">
            @foreach($socialLinks as $social)
            <a href="{{ $social['url'] ?? '#' }}" target="_blank" class="group relative w-14 h-14 rounded-full flex items-center justify-center transition-all hover:scale-110" style="background-color: {{ $social['color'] ?? 'var(--theme-primary, #3D9E7A)' }};">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    {!! $social['icon_svg'] ?? '<path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/>' !!}
                </svg>
                <span class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 bg-gray-900 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                    {{ $social['name'] ?? 'Social Media' }}
                </span>
            </a>
            @endforeach
        </div>
    </div>
</section>
