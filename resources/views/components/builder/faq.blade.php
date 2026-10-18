{{-- FAQ Accordion Component --}}
@php
    $heading = $content['heading'] ?? 'Frequently Asked Questions';
    $faqs = $content['faqs'] ?? [];
@endphp

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        @if($heading)
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">{{ $heading }}</h2>
        @endif
        
        <div class="max-w-3xl mx-auto space-y-4" x-data="{ open: 0 }">
            @foreach($faqs as $index => $faq)
            <div class="bg-white rounded-xl border-2 border-gray-200 overflow-hidden">
                <button @click="open = open === {{ $index }} ? null : {{ $index }}" class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <span class="font-bold text-gray-900">{{ $faq['question'] ?? '' }}</span>
                    <svg class="w-5 h-5 transition-transform" :class="{'rotate-180': open === {{ $index }}}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="open === {{ $index }}" x-collapse class="px-6 pb-4">
                    <p class="text-gray-700">{{ $faq['answer'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
