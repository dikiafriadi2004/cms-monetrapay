@extends('admin.layouts.app')

@section('title', 'Tambah Artikel Baru')
@section('uses-editor', true)

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Tambah Artikel Baru</h1>
        <a href="{{ route('admin.blogs.index') }}"
           class="text-sm text-slate-600 hover:text-slate-800 font-medium">
            &larr; Kembali ke Daftar
        </a>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.blogs.store') }}" enctype="multipart/form-data" class="admin-form">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Title --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           maxlength="200"
                           placeholder="Masukkan judul artikel..."
                           class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <label for="content" class="block text-sm font-medium text-slate-700 mb-1">
                        Konten <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" id="content" rows="20"
                              placeholder="Tulis konten artikel..."
                              class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-500 @enderror">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Excerpt --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <label for="excerpt" class="block text-sm font-medium text-slate-700 mb-1">Ringkasan (Excerpt)</label>
                    <textarea name="excerpt" id="excerpt" rows="3"
                              placeholder="Ringkasan singkat artikel (opsional)..."
                              class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('excerpt') border-red-500 @enderror">{{ old('excerpt') }}</textarea>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SEO Section --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">SEO</h2>

                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-slate-700 mb-1">
                            Meta Title <span class="text-xs text-slate-500">(maks. 60 karakter)</span>
                        </label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                               maxlength="60"
                               placeholder="Meta title untuk SEO..."
                               class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('meta_title') border-red-500 @enderror">
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-slate-700 mb-1">
                            Meta Description <span class="text-xs text-slate-500">(maks. 160 karakter)</span>
                        </label>
                        <textarea name="meta_description" id="meta_description" rows="2"
                                  maxlength="160"
                                  placeholder="Deskripsi singkat untuk mesin pencari..."
                                  class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('meta_description') border-red-500 @enderror">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-slate-700 mb-1">
                            Meta Keywords <span class="text-xs text-slate-500">(pisahkan dengan koma)</span>
                        </label>
                        <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                               placeholder="keyword1, keyword2, keyword3..."
                               class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('meta_keywords') border-red-500 @enderror">
                        @error('meta_keywords')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="space-y-6">
                {{-- Publish Settings --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">Publikasi</h2>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" id="status"
                                class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                                onchange="toggleSchedule(this.value)">
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Scheduled Publish (only shown when status = draft) --}}
                    <div id="schedule-field" class="{{ old('status', 'draft') === 'published' ? 'hidden' : '' }}">
                        <label for="scheduled_at" class="block text-sm font-medium text-slate-700 mb-1">
                            Jadwalkan Publish
                            <span class="text-xs text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                               value="{{ old('scheduled_at') }}"
                               min="{{ now()->addMinutes(1)->format('Y-m-d\TH:i') }}"
                               class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('scheduled_at') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-slate-400">Artikel akan otomatis dipublikasi pada waktu yang ditentukan.</p>
                        @error('scheduled_at')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                            Simpan
                        </button>
                        <a href="{{ route('admin.blogs.index') }}"
                           class="flex-1 px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors text-center">
                            Batal
                        </a>
                    </div>
                </div>

                {{-- Categories --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Kategori <span class="text-red-500">*</span>
                        <span class="text-xs text-slate-500 font-normal">(min. 1, maks. 5)</span>
                    </h2>

                    <div class="max-h-48 overflow-y-auto space-y-2">
                        @foreach($categories as $category)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                       {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}
                                       class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="text-sm text-slate-700">{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    @error('categories')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('categories.*')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tags --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Tags
                        <span class="text-xs text-slate-500 font-normal">(maks. 10, pisahkan dengan koma)</span>
                    </h2>

                    <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                           placeholder="tag1, tag2, tag3..."
                           class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('tags') border-red-500 @enderror">
                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('tags.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Featured Image --}}
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 space-y-4">
                    <h2 class="text-base font-bold text-slate-800">Featured Image</h2>

                    {{-- Drop zone / preview --}}
                    <div id="image-drop-zone"
                         class="relative w-full rounded-xl border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden cursor-pointer hover:border-primary hover:bg-primary-light/20 transition-all duration-200"
                         style="min-height: 160px;"
                         onclick="document.getElementById('featured_image').click()">
                        {{-- Placeholder --}}
                        <div id="image-placeholder" class="flex flex-col items-center justify-center py-10 gap-3">
                            <div class="w-12 h-12 rounded-xl bg-slate-200 flex items-center justify-center">
                                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <div class="text-center">
                                <p class="text-sm font-semibold text-slate-600">Klik untuk upload gambar</p>
                                <p class="text-xs text-slate-400 mt-0.5">JPG, PNG, WebP — Maks. 2MB</p>
                            </div>
                        </div>
                        {{-- Preview --}}
                        <img id="preview-img" src="" alt="Preview"
                             class="hidden w-full h-48 object-cover rounded-xl">
                    </div>

                    <input type="file" name="featured_image" id="featured_image"
                           accept="image/jpeg,image/png,image/webp"
                           class="hidden"
                           onchange="previewFeaturedImage(event)">

                    <div id="remove-image-btn" class="hidden">
                        <button type="button" onclick="removeFeaturedImage()"
                                class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-600 hover:text-red-700">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            Hapus gambar
                        </button>
                    </div>

                    @error('featured_image')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function toggleSchedule(status) {
        const field = document.getElementById('schedule-field');
        if (status === 'draft') {
            field.classList.remove('hidden');
        } else {
            field.classList.add('hidden');
            document.getElementById('scheduled_at').value = '';
        }
    }

    function previewFeaturedImage(event) {
        const file = event.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('preview-img');
            const placeholder = document.getElementById('image-placeholder');
            const removeBtn = document.getElementById('remove-image-btn');
            img.src = e.target.result;
            img.classList.remove('hidden');
            placeholder.classList.add('hidden');
            removeBtn.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }

    function removeFeaturedImage() {
        const input = document.getElementById('featured_image');
        const img = document.getElementById('preview-img');
        const placeholder = document.getElementById('image-placeholder');
        const removeBtn = document.getElementById('remove-image-btn');
        input.value = '';
        img.src = '';
        img.classList.add('hidden');
        placeholder.classList.remove('hidden');
        removeBtn.classList.add('hidden');
    }
</script>
@endsection
