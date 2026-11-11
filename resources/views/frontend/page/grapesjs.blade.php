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
<meta property="og:site_name" content="{{ $siteName ?? config('app.name') }}">
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
    // Replace @csrf literal dengan hidden input CSRF token yang benar
    $pageContent = str_replace(
        '@csrf',
        '<input type="hidden" name="_token" value="' . csrf_token() . '">',
        $pageContent
    );
@endphp

<div class="gjs-page-content">
    {!! $pageContent !!}
</div>

{{-- Set form timestamp untuk anti-spam, dan show success/error flash --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Set _t timestamp pada semua form di halaman (untuk time-check anti-bot)
    document.querySelectorAll('input[name="_t"]').forEach(function (el) {
        el.value = Math.floor(Date.now() / 1000);
    });
});
</script>

{{-- Flash messages untuk form kontak --}}
@if(session('contact_success'))
<div id="contact-flash-success"
     style="position:fixed;bottom:24px;right:24px;z-index:9999;background:#3D9E7A;color:#fff;padding:16px 24px;border-radius:12px;font-size:14px;font-weight:600;box-shadow:0 8px 32px rgba(61,158,122,0.35);max-width:360px;display:flex;align-items:center;gap:10px;">
    <svg style="width:20px;height:20px;shrink:0;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>{{ session('contact_success') }}</span>
</div>
<script>setTimeout(function(){var el=document.getElementById('contact-flash-success');if(el)el.remove();},6000);</script>
@endif

@if(session('contact_error'))
<div id="contact-flash-error"
     style="position:fixed;bottom:24px;right:24px;z-index:9999;background:#ef4444;color:#fff;padding:16px 24px;border-radius:12px;font-size:14px;font-weight:600;box-shadow:0 8px 32px rgba(239,68,68,0.35);max-width:360px;display:flex;align-items:center;gap:10px;">
    <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>{{ session('contact_error') }}</span>
</div>
<script>setTimeout(function(){var el=document.getElementById('contact-flash-error');if(el)el.remove();},6000);</script>
@endif

@if($errors->any())
<div id="contact-flash-validation"
     style="position:fixed;bottom:24px;right:24px;z-index:9999;background:#f97316;color:#fff;padding:16px 24px;border-radius:12px;font-size:14px;font-weight:600;box-shadow:0 8px 32px rgba(249,115,22,0.35);max-width:360px;">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
        <svg style="width:20px;height:20px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span style="font-weight:700;">Periksa kembali form:</span>
    </div>
    <ul style="margin:0;padding-left:18px;font-weight:400;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
<script>setTimeout(function(){var el=document.getElementById('contact-flash-validation');if(el)el.remove();},8000);</script>
@endif
@endsection
