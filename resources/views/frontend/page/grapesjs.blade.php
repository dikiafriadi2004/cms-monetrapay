@extends('frontend.layouts.app')

@section('title', $page->meta_title ?: $page->title)

@section('seo')
@php
    $pageTitle = $page->meta_title ?: $page->title;
    $pageDesc = $page->meta_description ?? '';
    $pageCanonical = $canonicalUrl ?? url('/' . $page->slug);
    $pageImage = !empty($page->og_image)
        ? url(Storage::url($page->og_image))
        : (!empty($logo ?? null) ? url(Storage::url($logo)) : '');
@endphp
<meta name="description" content="{{ $pageDesc }}">
<link rel="canonical" href="{{ $pageCanonical }}">
<meta name="robots" content="index, follow">
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDesc }}">
<meta property="og:url" content="{{ $pageCanonical }}">
<meta property="og:type" content="website">
<meta property="og:site_name" content="{{ $siteName ?? 'MonetraPay' }}">
@if($pageImage)
<meta property="og:image" content="{{ $pageImage }}">
<meta name="twitter:image" content="{{ $pageImage }}">
@endif
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDesc }}">
@endsection

@section('content')
@if($page->gjs_css)
<style>
{!! $page->gjs_css !!}
</style>
@endif

@php
    // Google Maps koordinat dari Settings DB — bisa diubah di admin/settings/general
    $mapsLat  = \App\Models\Setting::get('maps_lat',  'general') ?: '-6.2088';
    $mapsLng  = \App\Models\Setting::get('maps_lng',  'general') ?: '106.8456';
    $mapsZoom = \App\Models\Setting::get('maps_zoom', 'general') ?: '15';
    // Replace placeholder koordinat di HTML yang tersimpan
    $pageContent = str_replace(
        'https://maps.google.com/maps?q=-6.2088,106.8456&z=15&output=embed',
        'https://maps.google.com/maps?q=' . $mapsLat . ',' . $mapsLng . '&z=' . $mapsZoom . '&output=embed',
        $page->content ?? ''
    );
@endphp

<div class="gjs-page-content">
    {!! $pageContent !!}
</div>
@endsection
