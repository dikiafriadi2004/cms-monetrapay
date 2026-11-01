@extends('admin.layouts.app')

@section('title', 'Edit Halaman')
@section('uses-editor', true)

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Edit Halaman</h1>
        <a href="{{ route('admin.pages.index') }}"
           class="text-sm text-slate-600 hover:text-slate-800 font-medium">
            &larr; Kembali ke Daftar
        </a>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.pages.update', $page) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Main Content Column --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Title --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1">
                        Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}"
                           maxlength="200"
                           placeholder="Masukkan judul halaman..."
                           class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <label for="content" class="block text-sm font-medium text-slate-700 mb-1">
                        Konten
                    </label>
                    <textarea name="content" id="content" rows="20"
                              placeholder="Tulis konten halaman..."
                              class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 tinymce-editor @error('content') border-red-500 @enderror">{{ old('content', $page->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SEO Section --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">SEO</h2>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-slate-700 mb-1">
                            Slug <span class="text-xs text-slate-500">(opsional, auto-generate dari judul)</span>
                        </label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $page->slug) }}"
                               maxlength="200"
                               placeholder="custom-slug-halaman"
                               class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('slug') border-red-500 @enderror">
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-slate-700 mb-1">
                            Meta Title <span class="text-xs text-slate-500">(maks. 60 karakter)</span>
                        </label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $page->meta_title) }}"
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
                                  class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $page->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-slate-700 mb-1">
                            Meta Keywords <span class="text-xs text-slate-500">(pisahkan dengan koma)</span>
                        </label>
                        <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}"
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
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">Publikasi</h2>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                        <select name="status" id="status"
                                class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="draft" {{ old('status', $page->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $page->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit"
                                class="flex-1 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                            Perbarui
                        </button>
                        <a href="{{ route('admin.pages.index') }}"
                           class="flex-1 px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors text-center">
                            Batal
                        </a>
                    </div>
                </div>

                {{-- Parent Page --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">Halaman Parent</h2>

                    <div>
                        <select name="parent_id" id="parent_id"
                                class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('parent_id') border-red-500 @enderror">
                            <option value="">— Tidak ada (Root) —</option>
                            @foreach($parentPages as $parentPage)
                                @php
                                    $depth = 0;
                                    $current = $parentPage;
                                    while ($current->parent_id) {
                                        $depth++;
                                        $current = $current->parent;
                                        if ($depth > 5) break;
                                    }
                                    $indent = str_repeat('— ', $depth);
                                @endphp
                                <option value="{{ $parentPage->id }}" {{ old('parent_id', $page->parent_id) == $parentPage->id ? 'selected' : '' }}>
                                    {{ $indent }}{{ $parentPage->title }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-slate-500">Maksimal 3 level kedalaman.</p>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Visual Editor (GrapesJS) --}}
                <div class="bg-white rounded-lg shadow-sm border border-teal-200 p-6">
                    <h2 class="text-lg font-semibold text-slate-800 mb-3">🎨 Visual Editor</h2>
                    <p class="text-sm text-slate-500 mb-4">Edit tampilan halaman secara visual dengan drag & drop menggunakan GrapesJS.</p>
                    <a href="{{ route('admin.pages.grapesjs', $page) }}"
                       class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-teal-600 text-white text-sm font-semibold rounded-lg hover:bg-teal-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Buka Visual Editor
                    </a>
                </div>

                {{-- Template --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-4" id="template-section">
                    <h2 class="text-lg font-semibold text-slate-800">Template</h2>

                    <div>
                        <select name="template" id="template"
                                class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('template') border-red-500 @enderror">
                            @foreach($templates as $value => $label)
                                <option value="{{ $value }}" {{ old('template', $page->template ?? 'default') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('template')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">Featured Image</h2>

                    @if($page->featured_image)
                        <div class="relative">
                            <img src="{{ Storage::url($page->featured_image) }}" alt="Featured Image"
                                 class="w-full rounded-lg border border-slate-200">
                            <label class="mt-2 flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remove_featured_image" value="1"
                                       class="rounded border-slate-300 text-red-600 focus:ring-red-500">
                                <span class="text-sm text-red-600">Hapus gambar</span>
                            </label>
                        </div>
                    @endif

                    <div>
                        <input type="file" name="featured_image" id="featured_image"
                               accept="image/jpeg,image/png,image/webp"
                               class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               onchange="previewImage(event)">
                        <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, WebP. Maks. 2MB</p>
                        @error('featured_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="image-preview" class="hidden">
                        <img id="preview-img" src="" alt="Preview" class="w-full rounded-lg border border-slate-200">
                    </div>
                </div>

                {{-- Page Info --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-2">
                    <h2 class="text-lg font-semibold text-slate-800">Informasi</h2>
                    <div class="text-sm text-slate-600">
                        <p>Dibuat: {{ $page->created_at->format('d M Y, H:i') }}</p>
                        <p>Diperbarui: {{ $page->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(event) {
        const file = event.target.files[0];
        const preview = document.getElementById('image-preview');
        const img = document.getElementById('preview-img');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        } else {
            preview.classList.add('hidden');
            img.src = '';
        }
    }

    function togglePageBuilder() {
        const checkbox        = document.getElementById('use_page_builder');
        const contentLabel    = document.querySelector('label[for="content"]');
        const templateSection = document.getElementById('template-section');
        const pbActive        = document.getElementById('pb-active-section');
        const pbInactive      = document.getElementById('pb-inactive-section');

        if (!checkbox) return;

        const isChecked = checkbox.checked;

        // Dim TinyMCE editor when page builder is on
        if (contentLabel) {
            const contentBox = contentLabel.closest('.bg-white');
            if (contentBox) {
                contentBox.style.opacity       = isChecked ? '0.4' : '1';
                contentBox.style.pointerEvents = isChecked ? 'none' : 'auto';
            }
        }

        // Show/hide page builder status sections
        if (pbActive)    pbActive.classList.toggle('hidden', !isChecked);
        if (pbInactive)  pbInactive.classList.toggle('hidden',  isChecked);

        // Always show template section
        if (templateSection) templateSection.classList.remove('hidden');
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', function() {
        togglePageBuilder();
    });
</script>
@endsection
