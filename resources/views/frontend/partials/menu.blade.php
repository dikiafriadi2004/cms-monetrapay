{{-- Dynamic Menu Renderer --}}
@php
    $menuService = app(\App\Services\MenuService::class);
@endphp

@if(!empty($menu))
    @foreach($menu as $item)
        <a href="{{ $item['url'] }}" 
           class="text-gray-700 dark:text-gray-300 hover:text-primary dark:hover:text-primary font-medium transition-colors {{ $menuService->isActive($item['url']) ? 'text-primary' : '' }} {{ $item['css_class'] ?? '' }}"
           @if(($item['target'] ?? '_self') !== '_self') target="{{ $item['target'] }}" @endif>
            @if($item['icon'] ?? '')
                <i class="{{ $item['icon'] }} mr-1"></i>
            @endif
            {{ $item['label'] }}
        </a>
        
        @if(!empty($item['children']))
            {{-- Submenu rendering (untuk future enhancement) --}}
            <div class="submenu hidden">
                @foreach($item['children'] as $child)
                    <a href="{{ $child['url'] }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        {{ $child['label'] }}
                    </a>
                @endforeach
            </div>
        @endif
    @endforeach
@else
    {{-- Fallback menu jika tidak ada di database --}}
    <a href="{{ url('/') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary font-medium {{ request()->is('/') ? 'text-primary' : '' }}">Beranda</a>
    <a href="{{ url('/blog') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary font-medium {{ request()->is('blog*') ? 'text-primary' : '' }}">Blog</a>
    <a href="{{ url('/tentang-kami') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary font-medium {{ request()->is('tentang-kami') ? 'text-primary' : '' }}">Tentang Kami</a>
    <a href="{{ url('/layanan') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary font-medium {{ request()->is('layanan') ? 'text-primary' : '' }}">Layanan</a>
    <a href="{{ url('/kontak') }}" class="text-gray-700 dark:text-gray-300 hover:text-primary font-medium {{ request()->is('kontak') ? 'text-primary' : '' }}">Kontak</a>
@endif
