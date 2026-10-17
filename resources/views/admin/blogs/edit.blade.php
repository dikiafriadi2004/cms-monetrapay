@extends('admin.layouts.app')

@section('title', 'Edit Artikel')
@section('uses-editor', true)

@section('content')
<div class="space-y-6" x-data="{ autosaveStatus: '' }">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Artikel</h1>
            <p x-show="autosaveStatus" x-cloak class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                <span x-text="autosaveStatus"></span>
            </p>
        </div>
        <a href="{{ route('admin.blogs.index') }}"
           class="text-sm text-slate-600 hover:text-slate-800 font-medium">
            &larr; Kembali ke Daftar
        </a>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.blogs.update', $blog) }}" enctype="multipart/form-data">
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
                    <input type="text" name="title" id="title" value="{{ old('title', $blog->title) }}"
                           maxlength="200"
                           placeholder="Masukkan judul artikel..."
                           class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Content --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <label for="content" class="block text-sm font-medium text-slate-700 mb-1">
                        Konten <span class="text-red-500">*</span>
                    </label>
                    <textarea name="content" id="content" rows="20"
                              placeholder="Tulis konten artikel..."
                              class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('content') border-red-500 @enderror">{{ old('content', $blog->content) }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Excerpt --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                    <label for="excerpt" class="block text-sm font-medium text-slate-700 mb-1">Ringkasan (Excerpt)</label>
                    <textarea name="excerpt" id="excerpt" rows="3"
                              placeholder="Ringkasan singkat artikel (opsional)..."
                              class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('excerpt') border-red-500 @enderror">{{ old('excerpt', $blog->excerpt) }}</textarea>
                    @error('excerpt')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- SEO Section --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">SEO</h2>

                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-slate-700 mb-1">
                            Meta Title <span class="text-xs text-slate-500">(maks. 60 karakter)</span>
                        </label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $blog->meta_title) }}"
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
                                  class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('meta_description') border-red-500 @enderror">{{ old('meta_description', $blog->meta_description) }}</textarea>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-slate-700 mb-1">
                            Meta Keywords <span class="text-xs text-slate-500">(pisahkan dengan koma)</span>
                        </label>
                        <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', $blog->meta_keywords) }}"
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
                                class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror"
                                onchange="toggleSchedule(this.value)">
                            <option value="draft" {{ old('status', $blog->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $blog->status) === 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Scheduled Publish (only shown when status = draft) --}}
                    @php
                        $currentScheduled = old('scheduled_at', $blog->scheduled_at?->format('Y-m-d\TH:i'));
                        $showSchedule = old('status', $blog->status) === 'draft';
                    @endphp
                    <div id="schedule-field" class="{{ $showSchedule ? '' : 'hidden' }}">
                        <label for="scheduled_at" class="block text-sm font-medium text-slate-700 mb-1">
                            Jadwalkan Publish
                            <span class="text-xs text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                               value="{{ $currentScheduled }}"
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
                            Perbarui
                        </button>
                        <a href="{{ route('admin.blogs.index') }}"
                           class="flex-1 px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors text-center">
                            Batal
                        </a>
                    </div>
                </div>

                {{-- Categories --}}
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Kategori <span class="text-red-500">*</span>
                        <span class="text-xs text-slate-500 font-normal">(min. 1, maks. 5)</span>
                    </h2>

                    @php
                        $selectedCategories = old('categories', $blog->categories->pluck('id')->toArray());
                    @endphp

                    <div class="max-h-48 overflow-y-auto space-y-2">
                        @foreach($categories as $category)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                       {{ in_array($category->id, $selectedCategories) ? 'checked' : '' }}
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
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Tags
                        <span class="text-xs text-slate-500 font-normal">(maks. 10, pisahkan dengan koma)</span>
                    </h2>

                    @php
                        $currentTags = old('tags', $blog->tags->pluck('name')->implode(', '));
                    @endphp

                    <input type="text" name="tags" id="tags" value="{{ $currentTags }}"
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
                <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-slate-800">Featured Image</h2>

                    {{-- Current Image --}}
                    @if($blog->featured_image)
                        <div id="current-image" class="space-y-2">
                            <img src="{{ Storage::url($blog->featured_image) }}" alt="Featured image"
                                 class="w-full rounded-lg border border-slate-200">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="remove_featured_image" value="1"
                                       class="rounded border-slate-300 text-red-600 focus:ring-red-500"
                                       onchange="toggleImageUpload(this)">
                                <span class="text-sm text-red-600">Hapus gambar saat ini</span>
                            </label>
                        </div>
                    @endif

                    <div id="image-upload-section">
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

    function toggleImageUpload(checkbox) {
        const currentImage = document.getElementById('current-image');
        if (checkbox.checked) {
            currentImage.querySelector('img').classList.add('opacity-50');
        } else {
            currentImage.querySelector('img').classList.remove('opacity-50');
        }
    }

    // ═══ AUTOSAVE (every 30s, only if status is draft) ═══
    (function() {
        const statusSelect = document.getElementById('status');
        const titleInput = document.getElementById('title');
        const contentTextarea = document.getElementById('content');
        const excerptTextarea = document.getElementById('excerpt');
        const url = '{{ route("admin.blogs.autosave", $blog) }}';
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        let lastPayload = null;
        let isSaving = false;

        async function autosave() {
            // Only autosave drafts
            if (!statusSelect || statusSelect.value !== 'draft') return;
            if (isSaving) return;

            // Get content from TinyMCE if available
            let content = contentTextarea?.value || '';
            if (window.tinymce && tinymce.get('content')) {
                content = tinymce.get('content').getContent();
            }

            const payload = {
                title: titleInput?.value || '',
                content: content,
                excerpt: excerptTextarea?.value || '',
            };

            // Skip if unchanged
            const serialized = JSON.stringify(payload);
            if (serialized === lastPayload) return;

            isSaving = true;
            try {
                const r = await fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json',
                    },
                    body: serialized,
                });
                if (r.ok) {
                    const data = await r.json();
                    lastPayload = serialized;
                    // Set Alpine status
                    const root = document.querySelector('[x-data*=autosaveStatus]');
                    if (root && root._x_dataStack) {
                        root._x_dataStack[0].autosaveStatus = data.message;
                    }
                }
            } catch (e) {
                console.warn('Autosave failed', e);
            }
            isSaving = false;
        }

        // Run every 30 seconds
        setInterval(autosave, 30000);
    })();
</script>
@endsection
