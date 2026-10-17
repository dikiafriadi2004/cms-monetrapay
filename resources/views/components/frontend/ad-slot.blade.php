@props(['position'])

@php
    $ads = \App\Models\Ad::where('position', $position)
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
        ->get();
@endphp

@if ($ads->isNotEmpty())
    <div class="ad-container ad-{{ $position }}">
        @foreach ($ads as $ad)
            <div class="ad-slot" data-ad-id="{{ $ad->id }}">
                @if (!empty($ad->code))
                    {!! $ad->code !!}
                @elseif (!empty($ad->image))
                    <img src="{{ $ad->image }}" alt="{{ $ad->name }}" loading="lazy">
                @endif
            </div>
        @endforeach
    </div>
@endif
