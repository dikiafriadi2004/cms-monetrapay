@props(['menuItems' => [], 'siteName' => 'MonetraPay'])

<nav aria-label="Navigasi utama" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo / Site Name --}}
            <a href="{{ route('home') }}" class="text-xl font-bold text-slate-800 hover:text-blue-600 transition-colors duration-200">
                {{ $siteName }}
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden lg:flex items-center gap-1">
                @foreach ($menuItems as $item)
                    @if (!empty($item['children']))
                        {{-- Level 1 with dropdown --}}
                        <div class="relative" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false">
                            <button
                                @click="open = !open"
                                class="px-3 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50 rounded-md transition-colors duration-200 flex items-center gap-1"
                                :aria-expanded="open"
                                aria-haspopup="true"
                            >
                                {{ $item['label'] }}
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div
                                x-show="open"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-1"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 translate-y-1"
                                class="absolute left-0 top-full mt-1 w-56 bg-white rounded-md shadow-lg border border-slate-200 py-1 z-50"
                                x-cloak
                            >
                                @foreach ($item['children'] as $child)
                                    @if (!empty($child['children']))
                                        {{-- Level 2 with sub-dropdown --}}
                                        <div class="relative" x-data="{ subOpen: false }" @mouseenter="subOpen = true" @mouseleave="subOpen = false">
                                            <button
                                                @click="subOpen = !subOpen"
                                                class="w-full text-left px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 flex items-center justify-between"
                                                :aria-expanded="subOpen"
                                                aria-haspopup="true"
                                            >
                                                {{ $child['label'] }}
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </button>
                                            <div
                                                x-show="subOpen"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 translate-x-1"
                                                x-transition:enter-end="opacity-100 translate-x-0"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100 translate-x-0"
                                                x-transition:leave-end="opacity-0 translate-x-1"
                                                class="absolute left-full top-0 ml-1 w-52 bg-white rounded-md shadow-lg border border-slate-200 py-1 z-50"
                                                x-cloak
                                            >
                                                @foreach ($child['children'] as $grandchild)
                                                    {{-- Level 3 --}}
                                                    <a href="{{ $grandchild['url'] ?? '#' }}" target="{{ $grandchild['target'] ?? '_self' }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                                                        {{ $grandchild['label'] }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        {{-- Level 2 without children --}}
                                        <a href="{{ $child['url'] ?? '#' }}" target="{{ $child['target'] ?? '_self' }}" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600">
                                            {{ $child['label'] }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @else
                        {{-- Level 1 without dropdown --}}
                        <a href="{{ $item['url'] ?? '#' }}" target="{{ $item['target'] ?? '_self' }}" class="px-3 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50 rounded-md transition-colors duration-200">
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Mobile hamburger button --}}
            <button
                @click="mobileOpen = !mobileOpen"
                class="lg:hidden p-2 rounded-md text-slate-600 hover:text-slate-900 hover:bg-slate-100"
                :aria-expanded="mobileOpen"
                aria-label="Toggle navigasi"
            >
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div
        x-show="mobileOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="lg:hidden border-t border-slate-200 bg-white"
        x-cloak
    >
        <div class="px-4 py-3 space-y-1">
            @foreach ($menuItems as $item)
                @if (!empty($item['children']))
                    <div x-data="{ expanded: false }">
                        <button
                            @click="expanded = !expanded"
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50 rounded-md"
                            :aria-expanded="expanded"
                        >
                            {{ $item['label'] }}
                            <svg class="w-4 h-4 transition-transform duration-200" :class="expanded && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="expanded" x-collapse class="pl-4 space-y-1">
                            @foreach ($item['children'] as $child)
                                @if (!empty($child['children']))
                                    <div x-data="{ subExpanded: false }">
                                        <button
                                            @click="subExpanded = !subExpanded"
                                            class="w-full flex items-center justify-between px-3 py-2 text-sm text-slate-600 hover:text-blue-600 hover:bg-slate-50 rounded-md"
                                            :aria-expanded="subExpanded"
                                        >
                                            {{ $child['label'] }}
                                            <svg class="w-4 h-4 transition-transform duration-200" :class="subExpanded && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </button>
                                        <div x-show="subExpanded" x-collapse class="pl-4 space-y-1">
                                            @foreach ($child['children'] as $grandchild)
                                                <a href="{{ $grandchild['url'] ?? '#' }}" target="{{ $grandchild['target'] ?? '_self' }}" class="block px-3 py-2 text-sm text-slate-500 hover:text-blue-600 hover:bg-slate-50 rounded-md">
                                                    {{ $grandchild['label'] }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ $child['url'] ?? '#' }}" target="{{ $child['target'] ?? '_self' }}" class="block px-3 py-2 text-sm text-slate-600 hover:text-blue-600 hover:bg-slate-50 rounded-md">
                                        {{ $child['label'] }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item['url'] ?? '#' }}" target="{{ $item['target'] ?? '_self' }}" class="block px-3 py-2 text-sm font-medium text-slate-700 hover:text-blue-600 hover:bg-slate-50 rounded-md">
                        {{ $item['label'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</nav>
