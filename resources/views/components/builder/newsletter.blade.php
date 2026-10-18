{{-- Newsletter Signup Component --}}
@php
    $heading = $content['heading'] ?? 'Subscribe to Our Newsletter';
    $text = $content['text'] ?? 'Get the latest updates delivered to your inbox';
    $buttonText = $content['button_text'] ?? 'Subscribe';
    $placeholder = $content['placeholder'] ?? 'Enter your email';
@endphp

<section class="py-16" style="background: linear-gradient(135deg, var(--theme-primary, #3D9E7A) 0%, var(--theme-secondary, #FF6B35) 100%);">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">{{ $heading }}</h2>
            <p class="text-lg mb-8 opacity-90">{{ $text }}</p>
            
            <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto">
                @csrf
                <input type="email" name="email" required placeholder="{{ $placeholder }}" class="flex-1 px-6 py-4 rounded-lg text-gray-900 focus:outline-none focus:ring-4 focus:ring-white/30">
                <button type="submit" class="px-8 py-4 bg-white text-gray-900 rounded-lg font-semibold hover:bg-gray-100 transition-all whitespace-nowrap">
                    {{ $buttonText }}
                </button>
            </form>
        </div>
    </div>
</section>
