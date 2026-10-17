@props([
    'media' => null,         // App\Models\Media|null — preferred source
    'src' => null,           // fallback path string
    'webp' => null,          // optional explicit webp path
    'alt' => '',
    'width' => null,
    'height' => null,
    'loading' => 'lazy',
    'class' => '',
])

@php
    $useStorage = fn (?string $p) => $p ? \Illuminate\Support\Facades\Storage::disk('public')->url($p) : null;

    if ($media) {
        $imgUrl = $media->url();
        $webpUrl = $media->webpUrl();
        $alt = $alt !== '' ? $alt : ($media->original_name ?? '');
    } else {
        $imgUrl = $useStorage($src) ?? $src;
        $webpUrl = $useStorage($webp) ?? $webp;
    }
@endphp

@if($webpUrl)
<picture>
    <source srcset="{{ $webpUrl }}" type="image/webp">
    <img src="{{ $imgUrl }}" alt="{{ $alt }}"
         @if($width) width="{{ $width }}" @endif
         @if($height) height="{{ $height }}" @endif
         loading="{{ $loading }}" decoding="async"
         class="{{ $class }}">
</picture>
@else
<img src="{{ $imgUrl }}" alt="{{ $alt }}"
     @if($width) width="{{ $width }}" @endif
     @if($height) height="{{ $height }}" @endif
     loading="{{ $loading }}" decoding="async"
     class="{{ $class }}">
@endif
