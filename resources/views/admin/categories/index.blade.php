@extends('admin.layouts.app')
@section('title', 'Kategori')
@section('page-title', 'Kelola Kategori')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '', deleteItemName: '' }">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Kelola Kategori</h1>
            <p class="text-sm text-slate-500 mt-0.5">Buat dan kelola kategori untuk artikel blog</p>
        </div>
    </div>

    {{-- Split Panel --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

        {{-- LEFT: Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 sticky top-24">
                @if($editCategory)
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h2 class="text-base font-bold text-slate-800">Edit Kategori</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Ubah data kategori yang dipilih</p>
                        </div>
                        <a href="{{ route('admin.categories.index') }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-slate-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </a>
                    </div>
                    <div class="h-px bg-slate-100 mb-5"></div>
                    <form method="POST" action="{{ route('admin.categories.update', $editCategory) }}" class="space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $editCategory->name) }}" required maxlength="100"
                                   placeholder="Contoh: Teknologi"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-primary focus:bg-white transition-colors @error('name') border-red-400 @enderror">
                            @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Slug <span class="text-slate-400 font-normal normal-case">(opsional)</span></label>
                            <input type="text" name="slug" value="{{ old('slug', $editCategory->slug) }}" maxlength="120"
                                   placeholder="otomatis-dari-nama"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-primary focus:bg-white transition-colors @error('slug') border-red-400 @enderror">
                            @error('slug')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Deskripsi <span class="text-slate-400 font-normal normal-case">(opsional)</span></label>
                            <textarea name="description" rows="3" maxlength="500"
                                      placeholder="Deskripsi singkat kategori..."
                                      class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-primary focus:bg-white transition-colors resize-none @error('description') border-red-400 @enderror">{{ old('description', $editCategory->description) }}</textarea>
                            @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex gap-3 pt-1">
                            <button type="submit"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-dark transition-colors shadow-sm shadow-primary/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.categories.index') }}"
                               class="inline-flex items-center justify-center px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                                Batal
                            </a>
                        </div>
                    </form>
                @else
                    <div class="mb-5">
                        <h2 class="text-base font-bold text-slate-800">Tambah Kategori Baru</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Isi form untuk membuat kategori baru</p>
                    </div>
                    <div class="h-px bg-slate-100 mb-5"></div>
                    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Nama Kategori <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required maxlength="100" autofocus
                                   placeholder="Contoh: Teknologi"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-primary focus:bg-white transition-colors @error('name') border-red-400 @enderror">
                            @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Slug <span class="text-slate-400 font-normal normal-case">(opsional)</span></label>
                            <input type="text" name="slug" value="{{ old('slug') }}" maxlength="120"
                                   placeholder="otomatis-dari-nama"
                                   class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-primary focus:bg-white transition-colors @error('slug') border-red-400 @enderror">
                            <p class="text-xs text-slate-400 mt-1">Kosongkan untuk generate otomatis dari nama</p>
                            @error('slug')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase tracking-wide mb-1.5">Deskripsi <span class="text-slate-400 font-normal normal-case">(opsional)</span></label>
                            <textarea name="description" rows="3" maxlength="500"
                                      placeholder="Deskripsi singkat kategori..."
                                      class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-primary focus:bg-white transition-colors resize-none @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                            @error('description')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-dark transition-colors shadow-sm shadow-primary/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Kategori
                        </button>
                    </form>
                @endif
            </div>
        </div>

        {{-- RIGHT: Table --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-slate-800">Daftar Kategori</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $categories->count() }} kategori tersedia</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Slug</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Artikel</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($categories as $category)
                            <tr class="hover:bg-slate-50/70 transition-colors {{ $editCategory?->id === $category->id ? 'bg-primary-light/30' : '' }}">
                                <td class="px-5 py-3.5">
                                    <div class="font-semibold text-slate-800 text-sm">{{ $category->name }}</div>
                                    @if($category->description)
                                    <div class="text-xs text-slate-400 mt-0.5 truncate max-w-[160px]">{{ $category->description }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    <code class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-md font-mono">{{ $category->slug }}</code>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $category->blogs_count > 0 ? 'bg-blue-50 text-blue-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $category->blogs_count }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.categories.index', ['edit' => $category->id]) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-primary bg-primary-light rounded-lg hover:bg-primary hover:text-white transition-all duration-150">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            Edit
                                        </a>
                                        <button type="button"
                                                @click="deleteUrl = '{{ route('admin.categories.destroy', $category) }}'; deleteItemName = '{{ addslashes($category->name) }}'; showDeleteModal = true"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-150 border border-red-100">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-5 py-14 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                        </div>
                                        <p class="text-sm text-slate-500 font-medium">Belum ada kategori</p>
                                        <p class="text-xs text-slate-400">Buat kategori pertama menggunakan form di sebelah kiri</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('components.admin.delete-modal')
</div>
@endsection
