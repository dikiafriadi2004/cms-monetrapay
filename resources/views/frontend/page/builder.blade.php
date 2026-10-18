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

    {{-- Apply active theme styles --}}
    @php
        $themeService = app(\App\Services\ThemeService::class);
        $themeStyles = $themeService->applyThemeStyles();
    @endphp
    @if($themeStyles)
    <style>{!! $themeStyles !!}</style>
    @endif
@endpush

@section('content')
{{-- Page Builder Components Rendering --}}
<div class="page-builder-content">
    @foreach($page->visibleComponents as $component)
        @include("components.builder.{$component->component_type}", [
            'content' => $component->content,
            'settings' => $component->settings ?? []
        ])
    @endforeach
</div>
@endsection
