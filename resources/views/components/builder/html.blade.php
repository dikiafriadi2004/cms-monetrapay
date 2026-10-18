{{-- Custom HTML Component --}}
@php
    $html = $content['html'] ?? '';
    $container = $settings['container'] ?? true;
    $padding = $settings['padding'] ?? 'py-16';
@endphp

<section class="{{ $padding }}">
    @if($container)
    <div class="container mx-auto px-4">
        {!! $html !!}
    </div>
    @else
        {!! $html !!}
    @endif
</section>
