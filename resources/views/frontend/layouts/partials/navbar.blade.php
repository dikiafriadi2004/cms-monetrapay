{{-- ===== NAVBAR ===== --}}
<header role="banner"
        :class="scrolled ? 'shadow-md shadow-slate-200/60' : 'shadow-sm'"
        class="header-sticky bg-white transition-all duration-300 z-50 border-b border-slate-100">
    <x-frontend.ad-slot position="header" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-12 lg:h-14">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center shrink-0 group">
                @php $logo = \App\Models\Setting::get('logo', 'general'); @endphp
                @if($logo)
                    <img src="{{ Storage::url($logo) }}" alt="{{ $siteName ?? config('app.name') }}"
                         class="h-9 lg:h-11 w-auto max-w-[220px] object-contain transition-transform group-hover:scale-105">
                @else
                    <span class="text-xl lg:text-2xl font-extrabold tracking-tight">
                        {{ $siteName ?? config('app.name') }}
                    </span>
                @endif
            </a>
            {{-- Desktop Nav - Use MenuService (dynamic from database) --}}
            <nav class="hidden lg:flex items-center gap-0.5" role="navigation" aria-label="Main navigation">
                @foreach($primaryMenu as $item)
                @php
                    $p = ltrim(parse_url($item['url'] ?? '/', PHP_URL_PATH) ?? '/', '/');
                    $active = ($p === '' && $currentPath === '/') || ($p !== '' && str_starts_with($currentPath, $p));
                @endphp
                <a href="{{ $item['url'] ?? '#' }}"
                   class="relative px-3.5 py-2 text-sm font-semibold rounded-lg transition-all duration-200 group
                          {{ $active ? 'text-primary' : 'text-slate-600 hover:text-primary hover:bg-primary-light/60' }}">
                    {{ $item['label'] ?? '' }}
                    <span class="absolute bottom-0 left-1/2 -translate-x-1/2 h-0.5 rounded-full bg-primary transition-all duration-300 {{ $active ? 'w-5' : 'w-0 group-hover:w-5' }}"></span>
                </a>
                @endforeach
            </nav>
            {{-- Right: Search + CTA + Hamburger --}}
            <div class="flex items-center gap-2">
                {{-- Dark mode toggle --}}
                <button x-data="{ dark: document.documentElement.classList.contains('dark') }"
                        @click="dark = !dark; document.documentElement.classList.toggle('dark', dark); localStorage.setItem('mp-theme', dark ? 'dark' : 'light')"
                        class="hidden sm:flex items-center justify-center w-9 h-9 text-slate-600 hover:text-primary rounded-xl hover:bg-slate-100 transition-colors dark:text-slate-300 dark:hover:bg-slate-800"
                        :aria-label="dark ? 'Switch to light mode' : 'Switch to dark mode'">
                    <svg x-show="!dark" class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="dark" x-cloak class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>
                {{-- Search (desktop) --}}
                <div class="hidden lg:flex items-center" x-data="{ open: false }">
                    <div class="relative flex items-center">
                        <button @click="open = !open; $nextTick(() => open && $refs.si.focus())"
                                class="p-2 text-slate-500 hover:text-primary rounded-lg hover:bg-slate-100 transition-colors" aria-label="Cari">
                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95 translate-x-4"
                             x-transition:enter-end="opacity-100 scale-100 translate-x-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100 translate-x-0"
                             x-transition:leave-end="opacity-0 scale-95 translate-x-4"
                             class="absolute right-0 top-1/2 -translate-y-1/2 z-10">
                            <form action="{{ route('search') }}" method="GET">
                                <input x-ref="si" type="text" name="q" placeholder="Cari artikel..."
                                       value="{{ request('q') }}" @keydown.escape="open = false"
                                       class="w-64 text-sm border border-slate-200 rounded-xl px-4 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary bg-white shadow-lg">
                                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- CTA --}}
                @if(!empty($ctaText))
                <a href="{{ $ctaUrl }}"
                   class="hidden lg:inline-flex items-center gap-2 px-4 py-2.5 bg-secondary text-white text-sm font-bold rounded-xl hover:bg-secondary-dark transition-all duration-200 shadow-md shadow-secondary/25 hover:-translate-y-0.5">
                    {{ $ctaText }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @endif
                {{-- Hamburger --}}
                <button @click="mobileMenuOpen = true"
                        class="lg:hidden flex items-center justify-center w-9 h-9 text-slate-600 hover:text-primary rounded-xl hover:bg-slate-100 transition-colors"
                        aria-label="Buka menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    {{-- Mobile Drawer Backdrop --}}
    <div x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         @click="mobileMenuOpen = false"
         class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>
    {{-- Mobile Drawer Panel --}}
    <nav x-show="mobileMenuOpen" x-cloak
         x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
         class="fixed top-0 left-0 bottom-0 z-50 w-[300px] bg-white shadow-2xl lg:hidden flex flex-col"
         role="navigation" aria-label="Mobile navigation">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            @if($logo)
                <img src="{{ Storage::url($logo) }}" alt="{{ $siteName ?? config('app.name') }}" class="h-9 w-auto max-w-[145px]">
            @else
                <span class="text-lg font-extrabold">{{ $siteName ?? config('app.name') }}</span>
            @endif
            <button @click="mobileMenuOpen = false" class="w-9 h-9 flex items-center justify-center text-slate-400 hover:text-slate-700 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="px-5 py-4 border-b border-slate-100">
            <form action="{{ route('search') }}" method="GET">
                <div class="relative">
                    <input type="text" name="q" placeholder="Cari artikel..." value="{{ request('q') }}"
                           class="w-full text-sm border border-slate-200 rounded-xl px-4 py-2.5 pl-10 focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary bg-slate-50">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </form>
        </div>
        <div class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
            @if(!empty($primaryMenu))
                @foreach($primaryMenu as $item)
                @php $p = ltrim(parse_url($item['url'] ?? '/', PHP_URL_PATH) ?? '/', '/'); $ma = ($p === '' && $currentPath === '/') || ($p !== '' && str_starts_with($currentPath, $p)); @endphp
                <a href="{{ $item['url'] ?? '#' }}" @click="mobileMenuOpen = false"
                   class="flex items-center gap-3 px-4 py-3 text-sm font-semibold rounded-xl transition-all duration-200 {{ $ma ? 'bg-primary-light text-primary' : 'text-slate-600 hover:bg-slate-50 hover:text-primary' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $ma ? 'bg-primary' : 'bg-slate-300' }} shrink-0"></span>
                    {{ $item['label'] ?? '' }}
                </a>
                @endforeach
            @endif
        </div>
        @if(!empty($ctaText))
        <div class="px-5 py-5 border-t border-slate-100">
            <a href="{{ $ctaUrl }}" @click="mobileMenuOpen = false"
               class="flex items-center justify-center gap-2 w-full px-5 py-3.5 bg-secondary text-white text-sm font-bold rounded-xl hover:bg-secondary-dark transition-all duration-200 shadow-md shadow-secondary/25">
                {{ $ctaText }}
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        @endif
    </nav>
</header>
