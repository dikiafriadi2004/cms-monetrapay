{{-- Pricing Tables Component --}}
@php
    $heading = $content['heading'] ?? 'Choose Your Plan';
    $plans = $content['plans'] ?? [];
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        @if($heading)
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">{{ $heading }}</h2>
        @endif
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
            @foreach($plans as $index => $plan)
            <div class="rounded-2xl border-2 {{ ($plan['featured'] ?? false) ? 'border-primary shadow-2xl scale-105' : 'border-gray-200' }} p-8 relative {{ ($plan['featured'] ?? false) ? 'bg-primary/5' : 'bg-white' }}">
                @if($plan['featured'] ?? false)
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 px-4 py-1 bg-primary text-white text-sm font-bold rounded-full">
                    POPULAR
                </div>
                @endif
                
                <h3 class="text-2xl font-bold mb-2 text-gray-900">{{ $plan['name'] ?? '' }}</h3>
                <p class="text-gray-600 mb-6">{{ $plan['description'] ?? '' }}</p>
                
                <div class="mb-6">
                    <span class="text-5xl font-bold" style="color: var(--theme-primary, #3D9E7A);">{{ $plan['currency'] ?? 'Rp' }}{{ $plan['price'] ?? '0' }}</span>
                    <span class="text-gray-600">/{{ $plan['period'] ?? 'month' }}</span>
                </div>
                
                <ul class="space-y-3 mb-8">
                    @foreach($plan['features'] ?? [] as $feature)
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span class="text-gray-700">{{ $feature }}</span>
                    </li>
                    @endforeach
                </ul>
                
                <a href="{{ $plan['button_link'] ?? '#' }}" class="block w-full px-6 py-3 text-center rounded-lg font-semibold transition-all {{ ($plan['featured'] ?? false) ? 'bg-primary text-white hover:bg-primary-dark' : 'border-2 border-primary text-primary hover:bg-primary hover:text-white' }}">
                    {{ $plan['button_text'] ?? 'Get Started' }}
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
