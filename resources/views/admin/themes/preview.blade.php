<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $theme->name }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50">

    {{-- Preview Banner - Sticky di top --}}
    <div class="fixed top-0 left-0 right-0 z-[9999] bg-gradient-to-r from-gray-900 to-gray-800 text-white px-6 py-3 shadow-xl">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <span class="font-semibold">Preview Mode</span>
                </div>
                <span class="hidden sm:inline text-sm text-gray-300">{{ $theme->name }}</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.themes.customize', $theme) }}" 
                   class="px-4 py-1.5 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-semibold transition-colors">
                    Customize
                </a>
                <form method="POST" action="{{ route('admin.themes.activate', $theme) }}" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-1.5 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-semibold transition-colors">
                        Activate Theme
                    </button>
                </form>
                <a href="{{ route('admin.themes.index') }}" 
                   class="px-4 py-1.5 bg-white text-gray-900 hover:bg-gray-100 rounded-lg text-sm font-semibold transition-colors">
                    ✕ Close
                </a>
            </div>
        </div>
    </div>

    {{-- Preview Content - Real frontend preview --}}
    <div class="pt-12">
        <iframe src="{{ url('/?theme_preview=' . $theme->id) }}" 
                class="w-full border-0" 
                style="height: calc(100vh - 3rem);"
                sandbox="allow-same-origin allow-scripts allow-popups allow-forms">
        </iframe>
    </div>

</body>
</html>
