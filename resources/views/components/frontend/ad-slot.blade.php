@props(['position'])

@php
    // Map alias positions to database ENUM values
    $positionMap = [
        'sidebar_bottom' => 'in_content',
    ];
    $dbPosition = $positionMap[$position] ?? $position;

    $ads = \App\Models\Ad::where('position', $dbPosition)
        ->where('is_active', true)
        ->where(function ($query) {
            $query->whereNull('start_date')
                ->orWhere('start_date', '<=', today());
        })
        ->where(function ($query) {
            $query->whereNull('end_date')
                ->orWhere('end_date', '>=', today());
        })
        ->orderBy('created_at')
        ->get()
        // Filter out ads with no actual content to avoid empty wrappers
        ->filter(fn($ad) => !empty(trim($ad->code ?? '')) || !empty(trim($ad->image ?? '')));
@endphp

@if ($ads->isNotEmpty())
    <div class="ad-container ad-{{ $position }}">
        @foreach ($ads as $ad)
            <div class="ad-slot" data-ad-id="{{ $ad->id }}">
                @if (!empty($ad->code))
                    {!! $ad->code !!}
                @elseif (!empty($ad->image))
                    <img src="{{ Storage::url($ad->image) }}" alt="{{ $ad->name }}" loading="lazy">
                @endif
            </div>
        @endforeach
    </div>
@endif
