@extends('admin.layouts.app')
@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="space-y-5 max-w-3xl">

    {{-- Header --}}
    <div>
        <h1 class="text-xl font-bold text-slate-800">Profil Saya</h1>
        <p class="text-sm text-slate-500 mt-0.5">Kelola informasi, foto, dan password akun Anda</p>
    </div>

    {{-- Profile Info --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-1 h-4 bg-primary rounded-full"></span>
                    Informasi Akun
                </h2>
            </div>
            <div class="p-6 space-y-5">
                {{-- Avatar --}}
                <div class="flex items-start gap-5 pb-5 border-b border-slate-100">
                    {{-- Current avatar --}}
                    <div class="shrink-0">
                        @if($user->profile_photo)
                            <img id="avatar-preview"
                                 src="{{ Storage::url($user->profile_photo) }}"
                                 alt="{{ $user->name }}"
                                 class="w-20 h-20 rounded-2xl object-cover shadow-lg border-2 border-white ring-2 ring-primary/20">
                        @else
                            <div id="avatar-initials"
                                 class="w-20 h-20 rounded-2xl bg-gradient-to-br from-primary to-primary-dark flex items-center justify-center text-3xl font-bold text-white shadow-lg shadow-primary/20">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <img id="avatar-preview" src="" alt="" class="w-20 h-20 rounded-2xl object-cover shadow-lg border-2 border-white ring-2 ring-primary/20 hidden">
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-800">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500">{{ $user->email }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5 mb-3">
                            {{ $user->roles->first()?->name ?? 'No role' }}
                            · Bergabung {{ $user->created_at->translatedFormat('d M Y') }}
                        </p>

                        {{-- Photo upload --}}
                        <div class="flex items-center gap-3 flex-wrap">
                            <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 bg-primary-light text-primary text-xs font-bold rounded-lg hover:bg-primary hover:text-white transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Upload Foto
                                <input type="file" name="profile_photo" id="profile_photo"
                                       accept="image/jpeg,image/png,image/webp"
                                       class="hidden" onchange="previewAvatar(event)">
                            </label>
                            @if($user->profile_photo)
                            <label class="cursor-pointer inline-flex items-center gap-1.5 text-xs text-red-500 hover:text-red-700 font-semibold">
                                <input type="checkbox" name="remove_photo" value="1" class="sr-only" id="remove_photo_cb" onchange="toggleRemovePhoto(this)">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                Hapus Foto
                            </label>
                            @endif
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1.5">JPG, PNG, WebP · Maks. 2MB</p>
                        @error('profile_photo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required maxlength="255">
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required maxlength="255">
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <form method="POST" action="{{ route('admin.profile.update-password') }}">
            @csrf
            @method('PUT')
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-1 h-4 bg-secondary rounded-full"></span>
                    Ubah Password
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="p-3 bg-amber-50 border border-amber-100 rounded-lg flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <p class="text-xs text-amber-800">Password harus minimal 8 karakter, mengandung huruf besar, huruf kecil, dan angka.</p>
                </div>
                <div>
                    <label for="current_password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password Saat Ini</label>
                    <input type="password" name="current_password" id="current_password" required autocomplete="current-password">
                    @error('current_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password Baru</label>
                        <input type="password" name="password" id="password" required autocomplete="new-password" minlength="8">
                        @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" minlength="8">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-end gap-3">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 bg-secondary text-white text-sm font-semibold rounded-lg hover:bg-secondary-dark transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Ubah Password
                </button>
            </div>
        </form>
    </div>

    {{-- 2FA Security Card --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg {{ $user->hasTwoFactorEnabled() ? 'bg-green-100' : 'bg-slate-100' }} flex items-center justify-center">
                    <svg class="w-4 h-4 {{ $user->hasTwoFactorEnabled() ? 'text-green-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Autentikasi Dua Faktor (2FA)</h2>
                    <p class="text-xs {{ $user->hasTwoFactorEnabled() ? 'text-green-600' : 'text-slate-500' }}">
                        {{ $user->hasTwoFactorEnabled() ? '✓ Aktif — akun Anda terlindungi' : 'Belum diaktifkan' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.two-factor.setup') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold rounded-lg transition-colors
                      {{ $user->hasTwoFactorEnabled() ? 'bg-red-50 text-red-700 hover:bg-red-100' : 'bg-primary text-white hover:bg-primary-dark' }}">
                {{ $user->hasTwoFactorEnabled() ? 'Nonaktifkan' : 'Aktifkan 2FA' }}
            </a>
        </div>
        <div class="px-6 py-4">
            <p class="text-xs text-slate-600 leading-relaxed">
                2FA menambah lapisan keamanan ekstra. Setiap login akan meminta kode 6 digit dari aplikasi <strong>Google Authenticator</strong> atau <strong>Authy</strong> di smartphone Anda.
            </p>
        </div>
    </div>
</div>

<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const preview = document.getElementById('avatar-preview');
        const initials = document.getElementById('avatar-initials');
        preview.src = e.target.result;
        preview.classList.remove('hidden');
        if (initials) initials.classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

function toggleRemovePhoto(checkbox) {
    const preview = document.getElementById('avatar-preview');
    const initials = document.getElementById('avatar-initials');
    if (checkbox.checked) {
        if (preview) preview.style.opacity = '0.3';
    } else {
        if (preview) preview.style.opacity = '1';
    }
}
</script>
@endsection
