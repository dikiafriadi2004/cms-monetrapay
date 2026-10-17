@props([
    'title' => '',
    'description' => '',
    'keywords' => '',
    'canonicalUrl' => '',
    'ogTags' => [],
    'twitterTags' => [],
    'structuredData' => [],
])

@if ($title)
<title>{{ $title }}</title>
@endif

@if ($description)
<meta name="description" content="{{ $description }}">
@endif

@if ($keywords)
<meta name="keywords" content="{{ $keywords }}">
@endif

@if ($canonicalUrl)
<link rel="canonical" href="{{ $canonicalUrl }}">
@endif

{{-- Open Graph Tags --}}
@if (!empty($ogTags))
@foreach ($ogTags as $property => $content)
@if ($content)
<meta property="{{ $property }}" content="{{ $content }}">
@endif
@endforeach
@endif

{{-- Twitter Card Tags --}}
@if (!empty($twitterTags))
@foreach ($twitterTags as $name => $content)
@if ($content)
<meta name="{{ $name }}" content="{{ $content }}">
@endif
@endforeach
@endif

{{-- JSON-LD Structured Data --}}
@if (!empty($structuredData))
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif
