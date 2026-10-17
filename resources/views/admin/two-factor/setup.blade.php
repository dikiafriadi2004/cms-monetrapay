@extends('admin.layouts.app')
@section('title', 'Setup 2FA')
@section('page-title', 'Keamanan 2FA')

@section('content')
<div class="max-w-2xl space-y-5">

    <div>
        <h1 class="text-xl font-bold text-slate-800">Autentikasi Dua Faktor (2FA)</h1>
        <p class="text-sm text-slate-500 mt-0.5">Tambah lapisan keamanan ekstra ke akun Anda dengan TOTP authenticator.</p>
    </div>

    @if($user->hasTwoFactorEnabled())
    {{-- 2FA already active --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-green-100 flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <div>
                <h2 class="text-sm font-bold text-slate-800">2FA Aktif</h2>
                <p class="text-xs text-green-600">Akun Anda terlindungi dengan autentikasi dua faktor</p>
            </div>
        </div>
        <div class="p-6">
            <p class="text-sm text-slate-600 mb-6">Untuk menonaktifkan 2FA, masukkan password Anda sebagai konfirmasi.</p>
            <form method="POST" action="{{ route('admin.two-factor.disable') }}">
                @csrf
                <div class="space-y-3">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password Saat Ini</label>
                        <input type="password" name="password" id="password" required autocomplete="current-password" placeholder="Masukkan password Anda">
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" onclick="return confirm('Yakin ingin menonaktifkan 2FA? Akun Anda akan kurang aman.')"
                            class="inline-flex items-center gap-2 px-5 py-2 bg-red-600 text-white text-sm font-semibold rounded-lg hover:bg-red-700 transition-colors">
                        Nonaktifkan 2FA
                    </button>
                </div>
            </form>
        </div>
    </div>
    @else
    {{-- Setup 2FA --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <span class="w-1 h-4 bg-primary rounded-full"></span>
                Langkah 1 — Scan QR Code
            </h2>
        </div>
        <div class="p-6 space-y-4">
            <p class="text-sm text-slate-600">Install <strong>Google Authenticator</strong> atau <strong>Authy</strong> di smartphone Anda, lalu scan QR code ini:</p>

            {{-- QR Code via Google Charts API --}}
            <div class="flex flex-col sm:flex-row items-start gap-6">
                <div class="p-3 bg-white border-2 border-slate-200 rounded-xl inline-block shrink-0">
                    <img src="https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl={{ urlencode($qrCodeUrl) }}"
                         alt="QR Code 2FA"
                         width="200" height="200"
                         class="block rounded">
                </div>
                <div class="space-y-3">
                    <div class="p-3 bg-slate-50 rounded-lg border border-slate-100">
                        <p class="text-xs font-semibold text-slate-600 mb-1">Tidak bisa scan? Masukkan kode manual:</p>
                        <code class="text-sm font-mono font-bold text-primary tracking-widest">{{ $secret }}</code>
                    </div>
                    <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <p class="text-xs text-blue-800"><strong>Tips:</strong> Setelah scan, aplikasi akan menampilkan kode 6 digit yang berganti setiap 30 detik. Masukkan kode itu di bawah untuk mengaktifkan 2FA.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                <span class="w-1 h-4 bg-secondary rounded-full"></span>
                Langkah 2 — Verifikasi & Aktifkan
            </h2>
        </div>
        <div class="p-6">
            @if($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                {{ $errors->first() }}
            </div>
            @endif
            <form method="POST" action="{{ route('admin.two-factor.enable') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="code" class="block text-xs font-semibold text-slate-700 mb-1.5">Kode OTP dari Aplikasi Authenticator</label>
                    <input type="text" name="code" id="code" required
                           inputmode="numeric" pattern="[0-9]*" maxlength="6"
                           autocomplete="one-time-code" autofocus
                           placeholder="000000"
                           class="w-full sm:max-w-[200px] text-center font-mono text-xl tracking-[0.5em] px-4 py-3 rounded-xl border-2 border-slate-200 bg-white focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/15">
                    <p class="mt-1 text-[11px] text-slate-500">Masukkan kode 6 digit yang ditampilkan di aplikasi Anda sekarang.</p>
                </div>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    Aktifkan 2FA
                </button>
            </form>
        </div>
    </div>
    @endif

    <a href="{{ route('admin.profile.edit') }}" class="inline-flex items-center gap-1.5 text-sm text-primary hover:text-primary-dark font-semibold">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Kembali ke Profil
    </a>
</div>
@endsection
