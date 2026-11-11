<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    @php
        try {
            $siteName = \App\Models\Setting::get('site_name', 'general') ?? config('app.name');
            $logo = \App\Models\Setting::get('logo', 'general');
        } catch (\Throwable $e) {
            $siteName = config('app.name');
            $logo = null;
        }
    @endphp
    <title>Lupa Password — {{ $siteName }}</title>
    @vite(['resources/css/app.css'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="min-h-screen bg-[#0f172a] flex items-center justify-center px-4 font-sans antialiased"
      style="background-image: linear-gradient(rgba(255,255,255,0.02) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.02) 1px, transparent 1px); background-size: 50px 50px;">
    <div class="absolute top-0 left-1/2 w-[500px] h-[500px] bg-primary/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>

    <div class="relative w-full max-w-md">
        <div class="text-center mb-8">
            @if($logo)
                <img src="{{ Storage::url($logo) }}" alt="{{ $siteName }}" class="h-10 w-auto mx-auto mb-4">
            @else
                <span class="text-2xl font-extrabold text-white inline-block mb-4">{{ $siteName }}</span>
            @endif
            <h1 class="text-xl font-extrabold text-white">Lupa Password</h1>
            <p class="text-slate-400 text-sm mt-1">Masukkan email Anda untuk menerima link reset</p>
        </div>

        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="h-1 bg-gradient-to-r from-primary to-secondary"></div>
            <div class="p-8">
                @if(session('status'))
                <div class="mb-5 p-3.5 rounded-xl bg-green-50 border border-green-200 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-green-700">{{ session('status') }}</p>
                </div>
                @endif

                @if($errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-red-50 border border-red-200 flex items-start gap-2.5">
                    <svg class="w-4 h-4 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-1.5">Email Akun</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="admin@monetrapay.com"
                               class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 text-slate-900 placeholder-slate-400 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all text-sm">
                    </div>
                    <button type="submit"
                            class="w-full py-3.5 bg-primary hover:bg-primary-dark text-white font-bold rounded-xl transition-colors shadow-lg shadow-primary/25 text-sm">
                        Kirim Link Reset Password
                    </button>
                </form>

                <div class="text-center mt-5">
                    <a href="{{ route('login') }}" class="text-xs text-primary hover:text-primary-dark font-semibold">← Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
