@extends('frontend.layouts.app')

@section('title', $page->meta_title ?: $page->title)

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
