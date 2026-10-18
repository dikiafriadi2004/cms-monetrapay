<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Login — MonetraPay CMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#0f172a] flex items-center justify-center px-4 font-sans antialiased"
      style="background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 50px 50px;">

    {{-- Decorative blobs --}}
    <div class="absolute top-0 left-1/2 w-[500px] h-[500px] bg-primary/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-secondary/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

    <div class="relative w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            @php $loginLogo = \App\Models\Setting::get('logo', 'general'); @endphp
            @if($loginLogo)
                <img src="{{ Storage::url($loginLogo) }}" alt="MonetraPay" class="h-12 w-auto mx-auto mb-4">
            @elseif(file_exists(public_path('assets/images/logo.png')))
                <img src="/assets/images/logo.png" alt="MonetraPay" class="h-12 w-auto mx-auto mb-4">
            @else
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-primary/20 border border-primary/30 mb-4">
                    <svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
            @endif
            <h1 class="text-xl font-extrabold text-white">MonetraPay CMS</h1>
            <p class="text-slate-400 text-sm mt-1">Masuk ke panel administrasi</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-primary to-secondary"></div>
            <div class="p-8">
                @if ($errors->any())
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

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-bold text-slate-700 mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                               required autofocus autocomplete="email"
                               placeholder="admin@monetrapay.com"
                               class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all text-sm">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                            <a href="{{ route('password.request') }}" class="text-xs text-primary hover:text-primary-dark font-semibold">Lupa password?</a>
                        </div>
                        <div class="relative">
                            <input type="password" id="password" name="password"
                                   required autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="w-full px-4 py-3 pr-12 rounded-xl border-2 border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all text-sm">
                            <button type="button" id="togglePassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-slate-400 hover:text-primary transition-colors"
                                    aria-label="Tampilkan password">
                                <span id="showText">Show</span>
                                <span id="hideText" class="hidden">Hide</span>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" id="remember" name="remember"
                               class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/30">
                        <label for="remember" class="text-sm text-slate-600">Ingat saya selama 30 hari</label>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-colors shadow-lg shadow-primary/25 text-sm">
                        Masuk ke Dashboard
                    </button>
                </form>
            </div>
        </div>

        <script>
            // Toggle password visibility
            document.addEventListener('DOMContentLoaded', function() {
                const togglePassword = document.getElementById('togglePassword');
                const passwordInput = document.getElementById('password');
                const showText = document.getElementById('showText');
                const hideText = document.getElementById('hideText');

                if (togglePassword && passwordInput) {
                    togglePassword.addEventListener('click', function(e) {
                        e.preventDefault();
                        
                        // Toggle the type attribute
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                        passwordInput.setAttribute('type', type);
                        
                        // Toggle the button text
                        if (type === 'text') {
                            showText.classList.add('hidden');
                            hideText.classList.remove('hidden');
                            togglePassword.setAttribute('aria-label', 'Sembunyikan password');
                        } else {
                            showText.classList.remove('hidden');
                            hideText.classList.add('hidden');
                            togglePassword.setAttribute('aria-label', 'Tampilkan password');
                        }
                    });
                }
            });
        </script>

        <div class="text-center mt-6 space-y-2">
            <a href="{{ url('/') }}" class="inline-flex items-center text-xs font-semibold text-primary hover:text-secondary transition-colors">← Kembali ke website</a>
            <p class="text-slate-400 text-xs">
                &copy; {{ date('Y') }} MonetraPay CMS. Versi {{ config('app.version', '1.0') }}.
            </p>
        </div>
    </div>
</body>
</html>
