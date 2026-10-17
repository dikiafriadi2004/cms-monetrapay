@extends('admin.layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.categories.index') }}" class="text-sm text-blue-600 hover:text-blue-800">&larr; Kembali ke daftar kategori</a>
    <h1 class="text-2xl font-bold text-slate-800 mt-2">Edit Kategori: {{ $category->name }}</h1>
</div>

<form method="POST" action="{{ route('admin.categories.update', $category) }}">
    @csrf
    @method('PUT')

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        {{-- Category Name --}}
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                Nama Kategori <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name', $category->name) }}"
                required
                maxlength="100"
                class="w-full max-w-md px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                placeholder="Contoh: Teknologi"
            >
            <p class="mt-1 text-xs text-slate-500">Maksimal 100 karakter.</p>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Slug (optional) --}}
        <div class="mb-6">
            <label for="slug" class="block text-sm font-medium text-slate-700 mb-1.5">
                Slug
            </label>
            <input
                type="text"
                id="slug"
                name="slug"
                value="{{ old('slug', $category->slug) }}"
                maxlength="120"
                class="w-full max-w-md px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('slug') border-red-500 @enderror"
                placeholder="otomatis-dari-nama"
            >
            <p class="mt-1 text-xs text-slate-500">Opsional. Jika dikosongkan dan nama berubah, slug akan dibuat ulang otomatis. Maksimal 120 karakter.</p>
            @error('slug')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">
                Deskripsi
            </label>
            <textarea
                id="description"
                name="description"
                rows="3"
                maxlength="500"
                class="w-full max-w-lg px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('description') border-red-500 @enderror"
                placeholder="Deskripsi singkat tentang kategori ini..."
            >{{ old('description', $category->description) }}</textarea>
            <p class="mt-1 text-xs text-slate-500">Opsional. Maksimal 500 karakter.</p>
            @error('description')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-3">
        <button type="submit"
                class="px-6 py-2.5 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2">
            Perbarui Kategori
        </button>
        <a href="{{ route('admin.categories.index') }}"
           class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">
            Batal
        </a>
    </div>
</form>
@endsection
