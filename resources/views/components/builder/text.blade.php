@php
    $heading      = $content['heading']       ?? '';
    $headingLevel = $content['heading_level'] ?? 'h2';
    $text         = $content['text']          ?? '';
    $textAlign    = $content['text_align']    ?? 'left';
    $columns      = (int)($content['columns'] ?? 1);
    $highlight    = $content['highlight_text'] ?? '';
    $bgColor      = $settings['bg_color']     ?? 'white';
    $maxWidth     = $settings['max_width']    ?? 'medium';

    $bgClass = match($bgColor) {
        'light' => 'bg-slate-50',
        'dark'  => 'bg-slate-900',
        default => 'bg-white',
    };
    $textColor = $bgColor === 'dark' ? 'text-white' : 'text-slate-700';
    $headingColor = $bgColor === 'dark' ? 'text-white' : 'text-slate-800';

    $maxClass = match($maxWidth) {
        'narrow' => 'max-w-2xl',
        'wide'   => 'max-w-6xl',
        'full'   => 'max-w-full',
        default  => 'max-w-4xl',
    };
@endphp

<section class="py-12 md:py-16 {{ $bgClass }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="{{ $maxClass }} mx-auto text-{{ $textAlign }}">

            @if($heading)
            <{{ $headingLevel }} class="font-extrabold {{ $headingColor }} leading-tight mb-6
                {{ $headingLevel === 'h2' ? 'text-3xl md:text-4xl' : ($headingLevel === 'h3' ? 'text-2xl md:text-3xl' : 'text-xl md:text-2xl') }}">
                {{ $heading }}
            </{{ $headingLevel }}>
            @endif

            @if($highlight)
            <div class="p-4 rounded-xl border-l-4 mb-6 text-sm font-medium"
                 style="background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 10%,white);border-color:var(--theme-primary,#3D9E7A);color:var(--theme-primary,#3D9E7A)">
                {{ $highlight }}
            </div>
            @endif

            @if($text)
                @if($columns === 2)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 prose prose-slate max-w-none {{ $textColor }}">
                    <div>{!! $text !!}</div>
                </div>
                @else
                <div class="prose prose-slate max-w-none {{ $textColor }}" style="font-size:1.0625rem;line-height:1.8">
                    {!! $text !!}
                </div>
                @endif
            @endif
        </div>
    </div>
</section>
