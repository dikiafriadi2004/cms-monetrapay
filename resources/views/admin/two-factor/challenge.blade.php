<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex,nofollow">
    <title>Verifikasi 2FA — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
    @php $twofaFavicon = \App\Models\Setting::get('favicon', 'general'); @endphp
    @if($twofaFavicon)
        <link rel="icon" href="{{ Storage::url($twofaFavicon) }}" type="image/png">
    @else
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
    @endif
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-light via-white to-secondary-light flex items-center justify-center px-4 font-sans antialiased">

    <div class="w-full max-w-md">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-primary-dark px-6 py-8 text-center">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h1 class="text-xl font-extrabold text-white">Verifikasi 2FA</h1>
                <p class="text-white/80 text-sm mt-1">Masukkan kode dari aplikasi authenticator Anda</p>
            </div>

            <form method="POST" action="{{ route('two-factor.verify') }}" class="p-6 space-y-5">
                @csrf

                @if($errors->any())
                <div class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
                @endif

                <div x-data="{ useRecovery: false }">
                    <div x-show="!useRecovery">
                        <label for="code" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Kode OTP (6 digit)</label>
                        <input type="text" id="code" name="code"
                               inputmode="numeric" pattern="[0-9]*" maxlength="6" autocomplete="one-time-code"
                               placeholder="000000" autofocus
                               class="w-full px-4 py-4 text-2xl text-center font-mono tracking-[0.5em] rounded-xl border-2 border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all">
                        <p class="mt-2 text-xs text-slate-500 text-center">Buka Google Authenticator atau Authy, lalu masukkan kode 6 digit yang terlihat.</p>
                    </div>

                    <div x-show="useRecovery" x-cloak>
                        <label for="recovery_code" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Recovery Code</label>
                        <input type="text" id="recovery_code" name="code"
                               maxlength="9" placeholder="XXXX-XXXX"
                               class="w-full px-4 py-3 text-center font-mono tracking-widest rounded-xl border-2 border-slate-200 bg-white text-slate-800 focus:outline-none focus:border-secondary focus:ring-4 focus:ring-secondary/15 transition-all uppercase">
                        <p class="mt-2 text-xs text-slate-500 text-center">Masukkan salah satu recovery code yang Anda simpan saat mengaktifkan 2FA.</p>
                    </div>

                    <button type="button" @click="useRecovery = !useRecovery"
                            class="mt-3 w-full text-xs text-primary hover:text-primary-dark font-semibold text-center transition-colors"
                            x-text="useRecovery ? '← Kembali ke kode OTP' : 'Gunakan recovery code →'">
                    </button>
                </div>

                <button type="submit"
                        class="w-full py-3.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-lg shadow-primary/20">
                    Verifikasi & Masuk
                </button>

                <div class="text-center">
                    <a href="{{ route('login') }}" class="text-xs text-slate-500 hover:text-slate-700">← Kembali ke login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
