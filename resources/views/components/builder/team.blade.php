{{-- Team Members Component --}}
@php
    $heading = $content['heading'] ?? 'Meet Our Team';
    $members = $content['members'] ?? [];
    $columns = $settings['columns'] ?? 4;
    
    $gridClass = match($columns) {
        2 => 'md:grid-cols-2',
        3 => 'md:grid-cols-3',
        4 => 'md:grid-cols-2 lg:grid-cols-4',
        default => 'md:grid-cols-4',
    };
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        @if($heading)
        <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 text-gray-900">{{ $heading }}</h2>
        @endif
        
        <div class="grid grid-cols-1 {{ $gridClass }} gap-8">
            @foreach($members as $member)
            <div class="text-center group">
                <div class="relative mb-4 overflow-hidden rounded-2xl">
                    <img src="{{ $member['photo'] ?? '' }}" alt="{{ $member['name'] ?? '' }}" class="w-full aspect-square object-cover group-hover:scale-110 transition-transform duration-300">
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $member['name'] ?? '' }}</h3>
                <p class="text-primary font-semibold mb-2">{{ $member['position'] ?? '' }}</p>
                <p class="text-gray-600 text-sm mb-4">{{ $member['bio'] ?? '' }}</p>
                
                @if(!empty($member['social']))
                <div class="flex justify-center gap-3">
                    @foreach($member['social'] ?? [] as $platform => $url)
                    <a href="{{ $url }}" target="_blank" class="w-8 h-8 rounded-full bg-gray-200 hover:bg-primary hover:text-white flex items-center justify-center transition-all">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.879V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.989C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</section>
