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
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-1 py-4">
    {{-- Info --}}
    <p class="text-sm text-slate-500 order-2 sm:order-1">
        Menampilkan
        <span class="font-semibold text-slate-700">{{ $paginator->firstItem() }}</span>–<span class="font-semibold text-slate-700">{{ $paginator->lastItem() }}</span>
        dari <span class="font-semibold text-slate-700">{{ $paginator->total() }}</span> data
    </p>

    {{-- Buttons --}}
    <nav class="flex items-center gap-1 order-1 sm:order-2" aria-label="Pagination">
        {{-- Previous --}}
        @if ($paginator->onFirstPage())
            <button disabled class="inline-flex items-center gap-1.5 px-3 h-9 rounded-lg text-xs font-semibold text-slate-400 bg-slate-100 cursor-not-allowed select-none">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Prev
            </button>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="inline-flex items-center gap-1.5 px-3 h-9 rounded-lg text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-primary hover:text-white hover:border-primary transition-all duration-150">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Prev
            </a>
        @endif

        {{-- Pages --}}
        @foreach ($elements as $element)
            @if ($element === '...')
                <span class="inline-flex items-center justify-center w-9 h-9 text-sm text-slate-400 select-none">···</span>
            @elseif (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-bold text-white bg-primary shadow-sm shadow-primary/30 select-none">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-medium text-slate-600 bg-white border border-slate-200 hover:bg-primary hover:text-white hover:border-primary transition-all duration-150">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="inline-flex items-center gap-1.5 px-3 h-9 rounded-lg text-xs font-semibold text-slate-600 bg-white border border-slate-200 hover:bg-primary hover:text-white hover:border-primary transition-all duration-150">
                Next
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <button disabled class="inline-flex items-center gap-1.5 px-3 h-9 rounded-lg text-xs font-semibold text-slate-400 bg-slate-100 cursor-not-allowed select-none">
                Next
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
        @endif
    </nav>
</div>
@endif
