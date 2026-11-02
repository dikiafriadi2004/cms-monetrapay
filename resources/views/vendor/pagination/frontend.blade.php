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
<nav aria-label="Pagination" class="flex flex-col items-center gap-4 py-8">

    {{-- Page numbers row --}}
    <div class="flex items-center gap-2">

        {{-- Prev --}}
        @if ($paginator->onFirstPage())
            <span class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 text-slate-300 cursor-not-allowed bg-white select-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
               class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 text-slate-500 bg-white hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </a>
        @endif

        {{-- Numbers --}}
        @foreach ($elements as $element)
            @if ($element === '...')
                <span class="w-10 h-10 flex items-center justify-center text-slate-400 text-sm select-none">···</span>
            @elseif (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-10 h-10 flex items-center justify-center rounded-full bg-primary text-white text-sm font-bold shadow-md shadow-primary/40 select-none ring-4 ring-primary/20">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}"
                           class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 text-slate-600 text-sm font-medium bg-white hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-200 shadow-sm">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next"
               class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 text-slate-500 bg-white hover:border-primary hover:text-primary hover:bg-primary/5 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </a>
        @else
            <span class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-200 text-slate-300 cursor-not-allowed bg-white select-none">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </span>
        @endif

    </div>

    {{-- Info text --}}
    <p class="text-xs text-slate-400">
        Halaman <span class="font-semibold text-slate-600">{{ $paginator->currentPage() }}</span>
        dari <span class="font-semibold text-slate-600">{{ $paginator->lastPage() }}</span>
        &nbsp;·&nbsp;
        <span class="font-semibold text-slate-600">{{ $paginator->total() }}</span> artikel
    </p>

</nav>
@endif
