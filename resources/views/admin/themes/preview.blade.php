<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $theme->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family={{ urlencode($theme->config['font_heading'] ?? 'Plus Jakarta Sans') }}:wght@300;400;600;700&family={{ urlencode($theme->config['font_body'] ?? 'Inter') }}:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        :root {
            --theme-primary: {{ $theme->config['primary_color'] ?? '#3D9E7A' }};
            --theme-secondary: {{ $theme->config['secondary_color'] ?? '#FF6B35' }};
            --theme-font-heading: {{ $theme->config['font_heading'] ?? 'Plus Jakarta Sans' }}, sans-serif;
            --theme-font-body: {{ $theme->config['font_body'] ?? 'Inter' }}, sans-serif;
        }
        body {
            font-family: var(--theme-font-body);
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: var(--theme-font-heading);
        }
    </style>
</head>
<body class="bg-gray-50">

    {{-- Preview Banner --}}
    <div class="fixed top-0 left-0 right-0 z-50 bg-black text-white px-6 py-3 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <span class="font-semibold">Preview Mode: {{ $theme->name }}</span>
        </div>
        <a href="{{ route('admin.themes.index') }}" class="px-4 py-1.5 bg-white text-black rounded-lg text-sm font-semibold hover:bg-gray-100">
            Close Preview
        </a>
    </div>

    <div class="pt-16">
        {{-- Sample Header --}}
        <header class="bg-white border-b border-gray-200 py-4">
            <div class="container mx-auto px-4 flex items-center justify-between">
                <div class="text-2xl font-bold" style="color: var(--theme-primary);">MonetraPay</div>
                <nav class="hidden md:flex gap-6">
                    <a href="#" class="text-gray-700 hover:text-primary font-medium">Home</a>
                    <a href="#" class="text-gray-700 hover:text-primary font-medium">About</a>
                    <a href="#" class="text-gray-700 hover:text-primary font-medium">Services</a>
                    <a href="#" class="text-gray-700 hover:text-primary font-medium">Contact</a>
                </nav>
                <button class="px-6 py-2 rounded-lg font-semibold text-white" style="background-color: var(--theme-primary);">
                    Get Started
                </button>
            </div>
        </header>

        {{-- Sample Hero --}}
        <section class="py-20" style="background: linear-gradient(135deg, var(--theme-primary) 0%, var(--theme-secondary) 100%);">
            <div class="container mx-auto px-4 text-center text-white">
                <h1 class="text-5xl font-bold mb-4">Welcome to {{ $theme->name }}</h1>
                <p class="text-xl mb-8 opacity-90">This is a preview of how your site will look with this theme</p>
                <div class="flex justify-center gap-4">
                    <button class="px-8 py-3 bg-white text-gray-900 rounded-lg font-semibold">Primary Button</button>
                    <button class="px-8 py-3 border-2 border-white rounded-lg font-semibold">Secondary Button</button>
                </div>
            </div>
        </section>

        {{-- Sample Content --}}
        <section class="py-16 bg-white">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center p-6 rounded-xl border-2 border-gray-200">
                        <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: var(--theme-primary); color: white;">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Fast Performance</h3>
                        <p class="text-gray-600">Lightning-fast load times and optimal performance</p>
                    </div>
                    <div class="text-center p-6 rounded-xl border-2 border-gray-200">
                        <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: var(--theme-secondary); color: white;">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Customizable</h3>
                        <p class="text-gray-600">Easy to customize colors and fonts</p>
                    </div>
                    <div class="text-center p-6 rounded-xl border-2 border-gray-200">
                        <div class="w-16 h-16 rounded-full mx-auto mb-4 flex items-center justify-center" style="background-color: var(--theme-primary); color: white;">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Responsive</h3>
                        <p class="text-gray-600">Perfect on all devices and screen sizes</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Sample Footer --}}
        <footer class="py-12 bg-gray-900 text-white">
            <div class="container mx-auto px-4 text-center">
                <p class="text-lg font-semibold mb-4">{{ $theme->name }}</p>
                <p class="text-gray-400 text-sm">© 2024 MonetraPay. All rights reserved.</p>
            </div>
        </footer>
    </div>

</body>
</html>
