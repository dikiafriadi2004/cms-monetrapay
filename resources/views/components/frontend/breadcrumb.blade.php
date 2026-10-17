@props(['items' => []])

@if (!empty($items))
<nav aria-label="Breadcrumb" class="py-3">
    <ol class="flex items-center flex-wrap gap-1 text-sm text-slate-600">
        <li class="flex items-center">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition-colors duration-200">Home</a>
        </li>
        @foreach ($items as $index => $item)
            <li class="flex items-center">
                <svg class="w-4 h-4 mx-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                @if ($loop->last)
                    <span class="text-slate-800 font-medium" aria-current="page">{{ $item['label'] }}</span>
                @else
                    <a href="{{ $item['url'] }}" class="hover:text-blue-600 transition-colors duration-200">{{ $item['label'] }}</a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
