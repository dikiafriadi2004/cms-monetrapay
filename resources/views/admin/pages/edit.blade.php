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

                {{-- Page Builder Toggle --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">Page Builder</h2>

                    <div class="flex items-center justify-between">
                        <label for="use_page_builder" class="flex items-center cursor-pointer">
                            <div class="relative">
                                <input type="checkbox" name="use_page_builder" id="use_page_builder" value="1"
                                       {{ old('use_page_builder', $page->use_page_builder) ? 'checked' : '' }}
                                       class="sr-only peer"
                                       onchange="togglePageBuilder()">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            </div>
                            <span class="ms-3 text-sm font-medium text-slate-700">Gunakan Page Builder</span>
                        </label>
                    </div>

                    @if($page->use_page_builder)
                        <div class="p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-800">
                                <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                Page Builder aktif. 
                                <a href="{{ route('admin.page-builder.edit', $page) }}" class="font-semibold underline">Edit Komponen →</a>
                            </p>
                        </div>
                    @endif

                    <p class="text-xs text-slate-500">Aktifkan untuk membuat halaman dengan drag & drop components. Jika diaktifkan, konten di editor TinyMCE akan diabaikan.</p>
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
        const checkbox = document.getElementById('use_page_builder');
        const contentSection = document.querySelector('[for="content"]').closest('.bg-white');
        const templateSection = document.getElementById('template-section');

        if (checkbox.checked) {
            // Dim content editor when page builder is enabled
            contentSection.style.opacity = '0.5';
            contentSection.style.pointerEvents = 'none';
            templateSection.classList.remove('hidden');
        } else {
            // Enable content editor when page builder is disabled
            contentSection.style.opacity = '1';
            contentSection.style.pointerEvents = 'auto';
            templateSection.classList.remove('hidden');
        }
    }

    // Run on page load
    document.addEventListener('DOMContentLoaded', function() {
        togglePageBuilder();
    });
</script>
@endsection
