@extends('frontend.layouts.app')

@section('title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@push('head')
    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ $canonicalUrl }}">

    {{-- OpenGraph --}}
    @foreach($ogTags as $property => $content)
    <meta property="{{ $property }}" content="{{ $content }}">
    @endforeach

    {{-- Twitter Card --}}
    @foreach($twitterTags as $name => $content)
    <meta name="{{ $name }}" content="{{ $content }}">
    @endforeach

    {{-- Structured Data --}}
    <script type="application/ld+json">{!! json_encode($structuredData) !!}</script>
@endpush

@section('content')
<div class="page-builder-content">
    @foreach($page->visibleComponents as $component)
        @if($component->component_type === 'row')
            {{-- New format: Row → Column → Widget --}}
            @php
                $columns   = $component->content['columns'] ?? [];
                $layout    = $component->settings['layout']  ?? '100';

                $gridClass = match($layout) {
                    '50/50'    => 'grid-cols-2',
                    '30/70',
                    '70/30',
                    '33/33/33' => 'grid-cols-3',
                    '25/75'    => 'grid-cols-4',
                    default    => 'grid-cols-1',
                };
            @endphp
            <div class="page-builder-row grid {{ $gridClass }} gap-6">
                @foreach($columns as $colIdx => $column)
                    @php
                        $colSpan = '';
                        if ($layout === '30/70') $colSpan = $colIdx === 0 ? 'col-span-1' : 'col-span-2';
                        if ($layout === '70/30') $colSpan = $colIdx === 0 ? 'col-span-2' : 'col-span-1';
                        if ($layout === '25/75') $colSpan = $colIdx === 0 ? 'col-span-1' : 'col-span-3';
                    @endphp
                    <div class="page-builder-column {{ $colSpan }}">
                        @foreach($column['widgets'] ?? [] as $widget)
                            @if($widget['is_visible'] ?? true)
                                @php
                                    $wContent  = $widget['content']  ?? [];
                                    $wSettings = $widget['settings'] ?? [];
                                    $wType     = $widget['type']     ?? '';
                                @endphp
                                @if($wType && View::exists("components.builder.{$wType}"))
                                    @include("components.builder.{$wType}", [
                                        'content'  => $wContent,
                                        'settings' => $wSettings,
                                    ])
                                @endif
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>
        @else
            {{-- Legacy format: flat component --}}
            @if(View::exists("components.builder.{$component->component_type}"))
                @include("components.builder.{$component->component_type}", [
                    'content'  => $component->content,
                    'settings' => $component->settings ?? [],
                ])
            @endif
        @endif
    @endforeach
</div>
@endsection
