@php
    $title       = $content['title']        ?? '';
    $htmlContent = $content['html_content'] ?? '';
    $cssContent  = $content['css_content']  ?? '';
    $wrapperClass= $content['wrapper_class'] ?? '';
    $blockId     = 'custom-html-' . substr(md5($htmlContent), 0, 8);
@endphp

@if($cssContent)
<style>
    #{{ $blockId }} { {{ $cssContent }} }
</style>
@endif

<section class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($title)
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-slate-800">{{ $title }}</h2>
        </div>
        @endif
        <div id="{{ $blockId }}" class="{{ $wrapperClass }}">
            {!! $htmlContent !!}
        </div>
    </div>
</section>
