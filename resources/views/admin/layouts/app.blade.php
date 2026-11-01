<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - MonetraPay CMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="/assets/images/favico.png" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @hasSection('uses-editor')
    <meta name="file-picker-url" content="{{ route('admin.files.picker') }}">
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.4/tinymce.min.js"></script>
    @endif
    <style>
        /* ════ ADMIN GLOBAL FORM STYLING ════ */
        /* Apply to ALL inputs/selects/textareas inside admin pages */
        body input[type="text"],
        body input[type="email"],
        body input[type="password"],
        body input[type="number"],
        body input[type="date"],
        body input[type="datetime-local"],
        body input[type="url"],
        body input[type="tel"],
        body input[type="search"],
        body select,
        body textarea {
            display: block;
            width: 100%;
            padding: 0.625rem 0.875rem !important;
            font-size: 0.875rem;
            line-height: 1.5;
            color: #0f172a;
            background-color: #ffffff !important;
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 0.625rem !important;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            outline: none;
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            --tw-ring-shadow: 0 0 #0000;
            --tw-ring-offset-shadow: 0 0 #0000;
        }
        body input[type="text"]:hover,
        body input[type="email"]:hover,
        body input[type="password"]:hover,
        body input[type="number"]:hover,
        body input[type="date"]:hover,
        body input[type="datetime-local"]:hover,
        body input[type="url"]:hover,
        body input[type="tel"]:hover,
        body input[type="search"]:hover,
        body select:hover,
        body textarea:hover {
            border-color: #cbd5e1 !important;
        }
        body input[type="text"]:focus,
        body input[type="email"]:focus,
        body input[type="password"]:focus,
        body input[type="number"]:focus,
        body input[type="date"]:focus,
        body input[type="datetime-local"]:focus,
        body input[type="url"]:focus,
        body input[type="tel"]:focus,
        body input[type="search"]:focus,
        body select:focus,
        body textarea:focus {
            border-color: #3D9E7A !important;
            box-shadow: 0 0 0 3px rgba(61, 158, 122, 0.15) !important;
        }
        body input::placeholder,
        body textarea::placeholder {
            color: #94a3b8;
        }
        /* Error state */
        body input.border-red-500,
        body select.border-red-500,
        body textarea.border-red-500 {
            border-color: #ef4444 !important;
        }
        body input.border-red-500:focus,
        body select.border-red-500:focus,
        body textarea.border-red-500:focus {
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.15) !important;
        }
        body select {
            appearance: none;
            -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%2364748b'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important;
            background-position: right 0.75rem center !important;
            background-size: 1.125rem !important;
            padding-right: 2.75rem !important;
            cursor: pointer;
        }
        body select option {
            background: #ffffff;
            color: #0f172a;
            padding: 0.5rem;
        }
        body textarea {
            resize: vertical;
            min-height: 5rem;
        }
        /* Checkbox & Radio (override Tailwind preflight) */
        body input[type="checkbox"]:not(.sr-only),
        body input[type="radio"]:not(.sr-only) {
            width: 1.125rem !important;
            height: 1.125rem !important;
            border: 1.5px solid #cbd5e1 !important;
            background-color: #ffffff !important;
            cursor: pointer;
            transition: all 0.15s ease;
            box-shadow: none !important;
            padding: 0 !important;
        }
        body input[type="checkbox"]:not(.sr-only) {
            border-radius: 0.375rem !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            position: relative;
            background-image: none !important;
        }
        body input[type="checkbox"]:not(.sr-only):checked {
            background-color: #3D9E7A !important;
            border-color: #3D9E7A !important;
        }
        body input[type="checkbox"]:not(.sr-only):checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1px;
            width: 5px;
            height: 9px;
            border: solid white;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
        }
        body input[type="radio"]:not(.sr-only) {
            border-radius: 50% !important;
            appearance: none !important;
            -webkit-appearance: none !important;
            background-image: none !important;
        }
        body input[type="radio"]:not(.sr-only):checked {
            background-color: #3D9E7A !important;
            border-color: #3D9E7A !important;
            box-shadow: inset 0 0 0 3px white !important;
        }
        body input[type="checkbox"]:not(.sr-only):focus,
        body input[type="radio"]:not(.sr-only):focus {
            box-shadow: 0 0 0 3px rgba(61, 158, 122, 0.2) !important;
            outline: none;
        }
        /* sr-only checkboxes (Tailwind toggles) must stay hidden */
        body input[type="checkbox"].sr-only,
        body input[type="radio"].sr-only {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0,0,0,0) !important;
            white-space: nowrap !important;
            border-width: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }
        /* File input */
        body input[type="file"] {
            display: block;
            width: 100%;
            font-size: 0.875rem;
            color: #64748b;
            cursor: pointer;
            padding: 0 !important;
            border: none !important;
            background: transparent !important;
            box-shadow: none !important;
        }
        body input[type="file"]::file-selector-button {
            margin-right: 0.875rem;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            border: 0;
            background-color: #E8F5F0;
            color: #2D7A5C;
            font-weight: 600;
            font-size: 0.8125rem;
            cursor: pointer;
            transition: background-color 0.15s ease;
            font-family: inherit;
        }
        body input[type="file"]::file-selector-button:hover {
            background-color: #d1ebe0;
        }
        /* Disabled state */
        body input:disabled, body select:disabled, body textarea:disabled {
            background-color: #f8fafc !important;
            color: #94a3b8;
            cursor: not-allowed;
        }
        /* Reset Tailwind preflight conflicts inside admin */
        body button { font-family: inherit; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen bg-[#f1f5f9] font-sans antialiased" x-data="{ sidebarOpen: false }">

<div class="flex h-screen overflow-hidden">

{{-- Mobile overlay --}}
<div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-black/50 backdrop-blur-sm lg:hidden"></div>

{{-- ═══ SIDEBAR ═══ --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 w-[240px] bg-[#0f172a] text-white flex flex-col
              transform transition-transform duration-300 ease-in-out
              lg:relative lg:translate-x-0 lg:flex lg:flex-col lg:shrink-0">

    {{-- Logo --}}
    <div class="flex items-center justify-between h-20 px-4 border-b border-white/[0.06] shrink-0">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center min-w-0">
            @php
                $adminLogo = \App\Models\Setting::get('logo', 'general');
            @endphp
            @if($adminLogo)
                <img src="{{ Storage::url($adminLogo) }}" alt="MonetraPay" class="h-12 w-auto max-w-[180px] object-contain">
            @elseif(file_exists(public_path('assets/images/logo.png')))
                <img src="/assets/images/logo.png" alt="MonetraPay" class="h-12 w-auto max-w-[180px] object-contain">
            @else
                <span class="text-lg font-extrabold"><span class="text-primary">Monetra</span><span class="text-secondary">Pay</span></span>
            @endif
        </a>
        <button @click="sidebarOpen = false" class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:text-white hover:bg-white/10 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-0.5">
        @php
            $navItem = function(bool $active): string {
                return $active
                    ? 'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-semibold bg-primary/15 text-primary border-l-[3px] border-primary'
                    : 'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-slate-400 hover:bg-white/[0.06] hover:text-white transition-colors';
            };
        @endphp

        <a href="{{ route('admin.dashboard') }}" class="{{ $navItem(request()->routeIs('admin.dashboard')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>

        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Konten</p>

        @can('blog.view')
        <a href="{{ route('admin.blogs.index') }}" class="{{ $navItem(request()->routeIs('admin.blogs.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
            Blog
        </a>
        @endcan

        @can('blog.view')
        <a href="{{ route('admin.comments.index') }}" class="{{ $navItem(request()->routeIs('admin.comments.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <span class="flex-1">Komentar</span>
            @if(isset($notifications) && $notifications['pending_comments'] > 0)
                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-amber-500 rounded-full" data-notification-badge="pending_comments">{{ $notifications['pending_comments'] > 99 ? '99+' : $notifications['pending_comments'] }}</span>
            @endif
        </a>
        @endcan

        @can('page.view')
        <a href="{{ route('admin.pages.index') }}" class="{{ $navItem(request()->routeIs('admin.pages.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Halaman
        </a>
        @endcan

        @can('category.view')
        <a href="{{ route('admin.categories.index') }}" class="{{ $navItem(request()->routeIs('admin.categories.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            Kategori
        </a>
        @endcan

        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Media & Navigasi</p>

        @can('menu.view')
        <a href="{{ route('admin.menus.index') }}" class="{{ $navItem(request()->routeIs('admin.menus.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            Menu Builder
        </a>
        @endcan

        @can('file.view')
        <a href="{{ route('admin.files.index') }}" class="{{ $navItem(request()->routeIs('admin.files.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            File Manager
        </a>
        @endcan

        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Monetisasi</p>

        @can('ad.view')
        <a href="{{ route('admin.ads.index') }}" class="{{ $navItem(request()->routeIs('admin.ads.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            Iklan
        </a>
        @endcan

        @can('analytics.view')
        <a href="{{ route('admin.analytics.index') }}" class="{{ $navItem(request()->routeIs('admin.analytics.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Analytics
        </a>
        @endcan

        @can('setting.view')
        <a href="{{ route('admin.contacts.index') }}" class="{{ $navItem(request()->routeIs('admin.contacts.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span class="flex-1">Kontak</span>
            @if(isset($notifications) && $notifications['unread_contacts'] > 0)
                <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-secondary rounded-full" data-notification-badge="unread_contacts">{{ $notifications['unread_contacts'] > 99 ? '99+' : $notifications['unread_contacts'] }}</span>
            @endif
        </a>

        <a href="{{ route('admin.newsletter.index') }}" class="{{ $navItem(request()->routeIs('admin.newsletter.index') || request()->routeIs('admin.newsletter.destroy')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            Newsletter
        </a>
        <a href="{{ route('admin.newsletter.campaigns.index') }}" class="{{ request()->routeIs('admin.newsletter.campaigns.*')
            ? 'flex items-center gap-2.5 pl-8 pr-3 py-1.5 rounded-lg text-xs font-semibold text-primary bg-primary/10'
            : 'flex items-center gap-2.5 pl-8 pr-3 py-1.5 rounded-lg text-xs font-medium text-slate-400 hover:bg-white/[0.06] hover:text-white transition-colors' }}">
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Kirim Blast
        </a>
        @endcan

        @can('setting.view')
        <p class="px-3 pt-4 pb-1 text-[10px] font-bold text-slate-600 uppercase tracking-widest">Pengaturan</p>

        <a href="{{ route('admin.settings.edit', 'general') }}" class="{{ $navItem(request()->routeIs('admin.settings.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Settings
        </a>

        <a href="{{ route('admin.users.index') }}" class="{{ $navItem(request()->routeIs('admin.users.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            Users
        </a>

        <a href="{{ route('admin.roles.index') }}" class="{{ $navItem(request()->routeIs('admin.roles.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Roles
        </a>

        <a href="{{ route('admin.audit-logs.index') }}" class="{{ $navItem(request()->routeIs('admin.audit-logs.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Audit Log
        </a>

        <a href="{{ route('admin.tools.export-import') }}" class="{{ $navItem(request()->routeIs('admin.tools.*')) }}">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
            Export/Import
        </a>
        @endcan
    </nav>

    {{-- User footer --}}
    <div class="border-t border-white/[0.06] p-3 shrink-0">
        <div class="rounded-2xl bg-white/[0.04] border border-white/[0.06] p-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.profile.edit') }}" class="w-9 h-9 rounded-xl bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-xs font-bold text-white shrink-0 hover:opacity-90 transition-opacity overflow-hidden" title="Edit profil">
                    @if(auth()->user()->profile_photo)
                        <img src="{{ Storage::url(auth()->user()->profile_photo) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    @endif
                </a>
                <a href="{{ route('admin.profile.edit') }}" class="flex-1 min-w-0 hover:opacity-90 transition-opacity">
                    <p class="text-xs font-semibold text-white truncate" title="{{ auth()->user()->name }}">{{ auth()->user()->name }}</p>
                    <p class="text-[11px] text-slate-400 truncate" title="{{ auth()->user()->email }}">{{ auth()->user()->email }}</p>
                </a>
            </div>
            <div class="mt-3 grid grid-cols-2 gap-2">
                <a href="{{ route('admin.profile.edit') }}" class="text-center px-2 py-1.5 rounded-lg bg-white/[0.05] text-[11px] font-semibold text-slate-300 hover:text-white hover:bg-white/[0.08] transition-colors">Profil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full px-2 py-1.5 rounded-lg bg-red-500/10 text-[11px] font-semibold text-red-300 hover:text-white hover:bg-red-500 transition-colors">Logout</button>
                </form>
            </div>
        </div>
    </div>
</aside>

{{-- ═══ MAIN AREA ═══ --}}
<div class="flex-1 min-w-0 flex flex-col overflow-hidden">

    {{-- Topbar --}}
    <header class="bg-white border-b border-slate-200 h-14 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shrink-0">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="lg:hidden p-1.5 text-slate-500 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <div>
                <h1 class="text-sm font-bold text-slate-800 leading-none">@yield('page-title', 'Dashboard')</h1>
                <p class="text-[11px] text-slate-400 mt-0.5 hidden sm:block">Panel administrasi konten</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            {{-- Notification bell --}}
            @if(isset($notifications) && $notifications['total'] > 0)
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" @click.outside="open = false"
                        class="relative p-1.5 text-slate-500 hover:text-slate-800 rounded-lg hover:bg-slate-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 text-[10px] font-bold text-white bg-red-500 rounded-full flex items-center justify-center" data-notification-badge="total">{{ $notifications['total'] > 9 ? '9+' : $notifications['total'] }}</span>
                </button>
                <div x-show="open" x-cloak x-transition
                     class="absolute right-0 top-10 w-64 bg-white border border-slate-200 rounded-xl shadow-lg z-50 overflow-hidden">
                    <div class="px-4 py-3 border-b border-slate-100 bg-slate-50">
                        <p class="text-xs font-bold text-slate-700 uppercase tracking-wide">Notifikasi</p>
                    </div>
                    @if(isset($notifications) && $notifications['pending_comments'] > 0)
                    <a href="{{ route('admin.comments.index', ['status' => 'pending']) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors">
                        <div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-700">{{ $notifications['pending_comments'] }} komentar pending</p>
                            <p class="text-[11px] text-slate-400">Menunggu moderasi</p>
                        </div>
                    </a>
                    @endif
                    @if(isset($notifications) && $notifications['unread_contacts'] > 0)
                    <a href="{{ route('admin.contacts.index', ['filter' => 'unread']) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors">
                        <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-slate-700">{{ $notifications['unread_contacts'] }} pesan belum dibaca</p>
                            <p class="text-[11px] text-slate-400">Dari form kontak</p>
                        </div>
                    </a>
                    @endif
                </div>
            </div>
            @endif
            <a href="{{ url('/') }}" target="_blank"
               class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Lihat Website
            </a>
            <div class="flex items-center gap-2 pl-2 border-l border-slate-200">
                <a href="{{ route('admin.profile.edit') }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity" title="Edit profil">
                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-xs font-bold text-white overflow-hidden">
                        @if(auth()->user()->profile_photo)
                            <img src="{{ Storage::url(auth()->user()->profile_photo) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="hidden sm:block">
                        <p class="text-xs font-semibold text-slate-700 leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ auth()->user()->roles->first()?->name ?? 'Admin' }}</p>
                    </div>
                </a>
            </div>
        </div>
    </header>

    {{-- Maintenance mode banner (visible only to admins when maintenance is active) --}}
    @php
        $maintenanceActive = \App\Models\Setting::get('maintenance_mode', 'maintenance') === '1';
    @endphp
    @if($maintenanceActive)
    <div class="bg-amber-500 text-white text-xs font-semibold py-2 px-4 flex items-center justify-center gap-2 sticky top-0 z-[100]">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        <span>Maintenance Mode sedang AKTIF — Pengunjung publik melihat halaman Coming Soon.</span>
        <a href="{{ route('admin.settings.edit', 'maintenance') }}" class="underline underline-offset-2 hover:opacity-80 ml-1">Nonaktifkan →</a>
    </div>
    @endif

    {{-- Flash messages (toast) --}}
    @if(session('success') || session('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4500)" x-show="show" x-cloak
         x-transition:enter="transform ease-out duration-300 transition"
         x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
         x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed top-4 right-4 z-[60] max-w-sm w-full sm:w-auto">
        @if(session('success'))
        <div class="flex items-center gap-3 p-4 pr-5 rounded-xl bg-white border-l-4 border-green-500 shadow-lg ring-1 ring-slate-200">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm text-slate-700 font-medium flex-1">{{ session('success') }}</p>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif
        @if(session('error'))
        <div class="flex items-center gap-3 p-4 pr-5 rounded-xl bg-white border-l-4 border-red-500 shadow-lg ring-1 ring-slate-200">
            <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
            </div>
            <p class="text-sm text-slate-700 font-medium flex-1">{{ session('error') }}</p>
            <button @click="show = false" class="text-slate-400 hover:text-slate-600 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- Page content --}}
    <main class="flex-1 overflow-y-auto px-4 sm:px-6 py-5 pb-10">
        @yield('content')
    </main>

</div>{{-- /main area --}}
</div>{{-- /flex wrapper --}}

@yield('scripts')
<script src="{{ asset('js/seo-counter.js') }}"></script>
@hasSection('uses-editor')
<script src="{{ asset('js/editor.js') }}"></script>
@endif

{{-- Session timeout warning --}}
<script>
(function() {
    var sessionLifetimeMs = {{ config('session.lifetime', 120) * 60 * 1000 }};
    var warningBeforeMs = 2 * 60 * 1000; // warn 2 minutes before
    var warningAt = sessionLifetimeMs - warningBeforeMs;
    var warningShown = false;

    function showSessionWarning() {
        if (warningShown) return;
        warningShown = true;

        var div = document.createElement('div');
        div.id = 'session-warning';
        div.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:9999;background:#fff;border:2px solid #f59e0b;border-radius:12px;padding:16px 20px;max-width:340px;box-shadow:0 10px 25px rgba(0,0,0,0.15);font-family:inherit;';
        div.innerHTML = '<div style="display:flex;align-items:flex-start;gap:12px;">'
            + '<div style="width:36px;height:36px;background:#fef3c7;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">'
            + '<svg style="width:18px;height:18px;color:#d97706;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>'
            + '<div><p style="font-size:13px;font-weight:700;color:#92400e;margin:0 0 4px;">Sesi Hampir Berakhir</p>'
            + '<p style="font-size:12px;color:#78350f;margin:0 0 12px;">Sesi Anda akan berakhir dalam 2 menit. Simpan pekerjaan Anda.</p>'
            + '<div style="display:flex;gap:8px;">'
            + '<button onclick="extendSession()" style="padding:6px 14px;background:#3D9E7A;color:#fff;border:none;border-radius:8px;font-size:12px;font-weight:600;cursor:pointer;">Perpanjang Sesi</button>'
            + '<button onclick="document.getElementById(\'session-warning\').remove();warningShown=false;" style="padding:6px 14px;background:#f3f4f6;color:#374151;border:none;border-radius:8px;font-size:12px;cursor:pointer;">Tutup</button>'
            + '</div></div></div>';
        document.body.appendChild(div);
    }

    window.extendSession = function() {
        fetch('/admin/dashboard', { method: 'GET', credentials: 'same-origin' })
            .then(function() {
                var el = document.getElementById('session-warning');
                if (el) el.remove();
                warningShown = false;
                resetTimer();
            })
            .catch(function() {});
    };

    var warningTimer, expireTimer;

    function resetTimer() {
        clearTimeout(warningTimer);
        clearTimeout(expireTimer);
        warningShown = false;
        warningTimer = setTimeout(showSessionWarning, warningAt);
        expireTimer = setTimeout(function() {
            window.location.href = '{{ route("login") }}';
        }, sessionLifetimeMs);
    }

    // Reset on user activity
    ['click', 'keypress', 'mousemove', 'scroll', 'touchstart'].forEach(function(event) {
        document.addEventListener(event, function() {
            if (warningShown) return; // don't reset if already showing warning
            clearTimeout(warningTimer);
            clearTimeout(expireTimer);
            warningTimer = setTimeout(showSessionWarning, warningAt);
            expireTimer = setTimeout(function() {
                window.location.href = '{{ route("login") }}';
            }, sessionLifetimeMs);
        }, { passive: true });
    });

    resetTimer();
})();
</script>
</body>
</html>
