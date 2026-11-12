<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    @php
        try {
            $siteName      = \App\Models\Setting::get('site_name', 'general') ?? config('app.name');
            $logo          = \App\Models\Setting::get('logo', 'general');
            $primaryColor  = \App\Models\Setting::get('primary_color', 'general') ?? '#3D9E7A';
            $secondaryColor= \App\Models\Setting::get('secondary_color', 'general') ?? '#F59E0B';
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $primaryColor))    $primaryColor   = '#3D9E7A';
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $secondaryColor))  $secondaryColor = '#F59E0B';
        } catch (\Throwable $e) {
            $siteName = config('app.name');
            $logo = null;
            $primaryColor   = '#3D9E7A';
            $secondaryColor = '#F59E0B';
        }
    @endphp
    <title>Login — {{ $siteName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary:   {{ $primaryColor }};
            --color-secondary: {{ $secondaryColor }};
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex items-stretch">

    {{-- ══ LEFT PANEL — Branding ══════════════════════════════════════ --}}
    <div class="hidden lg:flex lg:w-1/2 xl:w-3/5 relative overflow-hidden flex-col items-center justify-center p-12"
         style="background-color: {{ $primaryColor }};">

        {{-- Pattern overlay --}}
        <div class="absolute inset-0 opacity-10"
             style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 28px 28px;"></div>

        {{-- Decorative circles --}}
        <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full opacity-10" style="background-color: #fff;"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full opacity-10" style="background-color: #fff;"></div>

        {{-- Content --}}
        <div class="relative z-10 text-center max-w-md">
            {{-- Logo --}}
            @if($logo)
                <img src="{{ Storage::url($logo) }}" alt="{{ $siteName }}"
                     class="h-16 w-auto mx-auto mb-8 object-contain drop-shadow-lg">
            @else
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-white/20 backdrop-blur mb-8">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif

            <h1 class="text-3xl xl:text-4xl font-extrabold text-white mb-4 leading-tight">
                Selamat Datang di<br>{{ $siteName }}
            </h1>
            <p class="text-white/75 text-base leading-relaxed mb-10">
                Panel administrasi untuk mengelola konten, pengguna, dan pengaturan website Anda.
            </p>

            {{-- Feature list --}}
            <div class="space-y-3 text-left">
                @foreach([
                    ['icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'text' => 'Kelola konten dengan visual editor'],
                    ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'text' => 'Manajemen pengguna & role'],
                    ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'text' => 'Analytics & laporan real-time'],
                ] as $f)
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-white/15 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/>
                        </svg>
                    </div>
                    <span class="text-white/90 text-sm font-medium">{{ $f['text'] }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Bottom copyright --}}
        <p class="absolute bottom-6 text-white/40 text-xs">
            &copy; {{ date('Y') }} {{ $siteName }}
        </p>
    </div>

    {{-- ══ RIGHT PANEL — Login Form ════════════════════════════════════ --}}
    <div class="w-full lg:w-1/2 xl:w-2/5 flex flex-col items-center justify-center px-6 py-12 sm:px-12">

        {{-- Mobile: logo --}}
        <div class="lg:hidden text-center mb-8">
            @if($logo)
                <img src="{{ Storage::url($logo) }}" alt="{{ $siteName }}" class="h-10 w-auto mx-auto mb-3">
            @endif
            <h1 class="text-xl font-extrabold text-slate-800">{{ $siteName }}</h1>
        </div>

        <div class="w-full max-w-sm">
            {{-- Heading --}}
            <div class="mb-8">
                <h2 class="text-2xl font-extrabold text-slate-800">Masuk ke Dashboard</h2>
                <p class="text-slate-500 text-sm mt-1.5">Gunakan akun admin Anda untuk melanjutkan.</p>
            </div>

            {{-- Error / Status --}}
            @if($errors->any())
            <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-red-700">{{ $errors->first() }}</p>
            </div>
            @endif

            @if(session('status'))
            <div class="mb-5 p-3.5 rounded-xl bg-green-50 border border-green-200 flex items-start gap-2.5">
                <svg class="w-4 h-4 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p class="text-sm text-green-700">{{ session('status') }}</p>
            </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Email
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               required autofocus autocomplete="email"
                               placeholder="admin@domain.com"
                               class="w-full pl-10 pr-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm @error('email') border-red-400 @enderror">
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                        <a href="{{ route('password.request') }}"
                           class="text-xs font-semibold transition-colors"
                           style="color: {{ $primaryColor }}">
                            Lupa password?
                        </a>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <input type="password" id="password" name="password"
                               required autocomplete="current-password"
                               placeholder="••••••••"
                               class="w-full pl-10 pr-12 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 placeholder-slate-400 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition-all text-sm">
                        <button type="button" id="togglePassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 p-1 text-slate-400 hover:text-slate-600 transition-colors"
                                aria-label="Toggle password">
                            <svg id="eyeShow" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eyeHide" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2.5">
                    <input type="checkbox" id="remember" name="remember"
                           class="w-4 h-4 rounded border-slate-300 focus:ring-2 transition-colors"
                           style="accent-color: {{ $primaryColor }}">
                    <label for="remember" class="text-sm text-slate-600 select-none cursor-pointer">
                        Ingat saya selama 30 hari
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full py-3.5 text-white font-bold rounded-xl transition-all text-sm shadow-lg hover:opacity-90 hover:-translate-y-0.5 active:translate-y-0"
                        style="background-color: {{ $primaryColor }}; box-shadow: 0 8px 24px {{ $primaryColor }}40;">
                    Masuk ke Dashboard
                    <svg class="w-4 h-4 inline ml-1.5 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>
            </form>

            {{-- Back to website --}}
            <div class="mt-8 pt-6 border-t border-slate-100 text-center">
                <a href="{{ url('/') }}"
                   class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Kembali ke Website
                </a>
            </div>
        </div>

        {{-- Mobile copyright --}}
        <p class="lg:hidden mt-8 text-slate-400 text-xs text-center">
            &copy; {{ date('Y') }} {{ $siteName }}
        </p>
    </div>

    <script>
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const input = document.getElementById('password');
        const eyeShow = document.getElementById('eyeShow');
        const eyeHide = document.getElementById('eyeHide');
        const isPassword = input.type === 'password';
        input.type = isPassword ? 'text' : 'password';
        eyeShow.classList.toggle('hidden', isPassword);
        eyeHide.classList.toggle('hidden', !isPassword);
    });
    </script>
</body>
</html>
