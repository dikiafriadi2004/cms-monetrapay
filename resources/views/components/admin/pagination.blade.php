@if ($paginator->hasPages())
@php
    $window = \Illuminate\Pagination\UrlWindow::make($paginator);
    $elements = array_filter([
        $window['first'],
        is_array($window['slider']) ? '...' : null,
        $window['slider'],
        is_array($window['last']) ? '...' : null,
        $window['last'],
    ]);
@endphp
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-4 px-1">

    {{-- Info --}}
    <p class="text-xs text-slate-400 order-2 sm:order-1">
        Menampilkan
        <span class="font-semibold text-slate-600">{{ $paginator->firstItem() }}</span>–<span class="font-semibold text-slate-600">{{ $paginator->lastItem() }}</span>
        dari <span class="font-semibold text-slate-600">{{ $paginator->total() }}</span> data
    </p>

    {{-- Buttons --}}
    <nav class="flex items-center gap-1.5 order-1 sm:order-2" aria-label="Pagination">

        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 text-slate-300 cursor-not-allowed bg-white select-none">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 text-slate-500 bg-white hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-150 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if ($element === '...')
                <span class="w-8 h-8 flex items-center justify-center text-xs text-slate-400 select-none">···</span>
            @elseif (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-8 h-8 flex items-center justify-center rounded-full bg-primary text-white text-xs font-bold shadow-sm shadow-primary/30 ring-2 ring-primary/20 select-none">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}"
                           class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 text-slate-600 text-xs font-medium bg-white hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-150 shadow-sm">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 text-slate-500 bg-white hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-150 shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="w-8 h-8 flex items-center justify-center rounded-full border border-slate-200 text-slate-300 cursor-not-allowed bg-white select-none">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif

    </nav>
</div>
@endif
