@extends('admin.layouts.app')

@section('title', 'Tambah Menu')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.menus.index') }}" class="text-slate-500 hover:text-slate-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Tambah Menu</h1>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 max-w-lg">
        <form method="POST" action="{{ route('admin.menus.store') }}" class="space-y-4">
            @csrf

            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Menu</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       placeholder="Contoh: Menu Utama"
                       class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                       required maxlength="100">
                @error('name')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Location --}}
            <div>
                <label for="location" class="block text-sm font-medium text-slate-700 mb-1">Lokasi</label>
                <select name="location" id="location"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('location') border-red-500 @enderror"
                        required>
                    <option value="">Pilih Lokasi</option>
                    <option value="header" {{ old('location') === 'header' ? 'selected' : '' }}>Header</option>
                    <option value="footer" {{ old('location') === 'footer' ? 'selected' : '' }}>Footer</option>
                    <option value="sidebar" {{ old('location') === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                </select>
                @error('location')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Actions --}}
            <div class="flex items-center gap-3 pt-4">
                <button type="submit"
                        class="px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                    Simpan Menu
                </button>
                <a href="{{ route('admin.menus.index') }}"
                   class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
