@extends('admin.layouts.app')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-primary hover:text-primary-dark">&larr; Kembali ke daftar pengguna</a>
    <h1 class="text-2xl font-bold text-slate-800 mt-2">Tambah Pengguna Baru</h1>
</div>

<form method="POST" action="{{ route('admin.users.store') }}">
    @csrf

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        {{-- Name --}}
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                Nama <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                maxlength="255"
                class="w-full max-w-md px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                placeholder="Nama lengkap"
            >
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-slate-700 mb-1.5">
                Email <span class="text-red-500">*</span>
            </label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
                maxlength="255"
                class="w-full max-w-md px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('email') border-red-500 @enderror"
                placeholder="email@example.com"
            >
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                Password <span class="text-red-500">*</span>
            </label>
            <input
                type="password"
                id="password"
                name="password"
                required
                minlength="8"
                class="w-full max-w-md px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('password') border-red-500 @enderror"
                placeholder="Minimal 8 karakter"
            >
            <p class="mt-1 text-xs text-slate-500">Minimal 8 karakter.</p>
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Confirmation --}}
        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">
                Konfirmasi Password <span class="text-red-500">*</span>
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
                minlength="8"
                class="w-full max-w-md px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                placeholder="Ulangi password"
            >
        </div>

        {{-- Role --}}
        <div class="mb-6">
            <label for="role" class="block text-sm font-medium text-slate-700 mb-1.5">
                Role <span class="text-red-500">*</span>
            </label>
            <select
                id="role"
                name="role"
                required
                class="w-full max-w-md px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('role') border-red-500 @enderror"
            >
                <option value="">-- Pilih Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-3">
        <button type="submit"
                class="px-6 py-2.5 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2">
            Simpan Pengguna
        </button>
        <a href="{{ route('admin.users.index') }}"
           class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">
            Batal
        </a>
    </div>
</form>
@endsection
