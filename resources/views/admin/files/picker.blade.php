<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pilih Gambar - File Manager</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100">
    <div x-data="imagePicker()" class="p-4 min-h-screen">
        {{-- Drag & Drop Overlay --}}
        <div
            x-show="isDragging"
            x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center pointer-events-none"
            style="background:rgba(13,148,136,0.15);border:3px dashed #0D9488;"
        >
            <div class="bg-white rounded-xl shadow-2xl px-12 py-8 text-center">
                <svg class="mx-auto w-16 h-16 text-primary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <p class="text-lg font-semibold text-slate-700">Lepas file untuk mengupload</p>
                <p class="text-sm text-slate-500 mt-1">Gambar akan langsung diupload</p>
            </div>
        </div>

        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-slate-800">Pilih Gambar</h2>
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-1 mt-1 text-xs text-slate-500">
                    <a href="{{ route('admin.files.picker') }}" class="hover:text-slate-700">Home</a>
                    @foreach($breadcrumbs as $crumb)
                        <span>/</span>
                        <a href="{{ route('admin.files.picker', ['folder_id' => $crumb->id]) }}" class="hover:text-slate-700">
                            {{ $crumb->name }}
                        </a>
                    @endforeach
                </nav>
            </div>
            <div class="flex items-center gap-2">
                {{-- Search --}}
                <div class="relative">
                    <input
                        type="text"
                        x-model="searchQuery"
                        @input.debounce.300ms="performSearch()"
                        placeholder="Cari gambar..."
                        class="w-48 pl-8 pr-3 py-1.5 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                    <svg class="absolute left-2.5 top-2 w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                {{-- Upload --}}
                <label class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-primary rounded-lg cursor-pointer hover:bg-primary-dark">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Upload
                    <input
                        type="file"
                        class="hidden"
                        accept=".jpg,.jpeg,.png,.gif,.webp,.svg"
                        @change="uploadImage($event.target.files[0])"
                    >
                </label>
            </div>
        </div>

        {{-- Search Results --}}
        <div x-show="searchQuery.length >= 2 && searchResults !== null" x-cloak class="mb-4">
            <div x-show="searchResults && searchResults.length === 0" class="text-sm text-slate-500 text-center py-8">
                Tidak ada gambar ditemukan.
            </div>
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                <template x-for="file in searchResults" :key="file.id">
                    <div
                        @click="selectImage(file.url)"
                        class="cursor-pointer group relative aspect-square rounded-lg border-2 border-slate-200 overflow-hidden hover:border-blue-500 transition"
                    >
                        <img :src="file.thumbnail_url || file.url" :alt="file.original_name" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/20 transition flex items-center justify-center">
                            <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- Folders --}}
        <div x-show="searchQuery.length < 2">
            @if($folders->isNotEmpty())
            <div class="mb-4">
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                    @foreach($folders as $folder)
                    <a href="{{ route('admin.files.picker', ['folder_id' => $folder->id]) }}"
                       class="flex flex-col items-center gap-1 p-3 rounded-lg border border-slate-200 hover:border-blue-300 hover:bg-blue-50 transition">
                        <svg class="w-8 h-8 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 6a2 2 0 012-2h5l2 2h9a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        <span class="text-xs text-slate-700 text-center truncate w-full">{{ $folder->name }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Recently uploaded (shown immediately after upload, before page reload) --}}
            <div x-show="recentUploads.length > 0" class="mb-3">
                <p class="text-xs text-slate-500 mb-2">Baru diupload:</p>
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                    <template x-for="file in recentUploads" :key="file.id">
                        <div
                            @click="selectImage(file.url)"
                            class="cursor-pointer group relative aspect-square rounded-lg border-2 border-emerald-400 overflow-hidden hover:border-blue-500 hover:shadow-md transition"
                        >
                            <img :src="file.thumbnail_url || file.url" :alt="file.original_name" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/25 transition flex items-center justify-center">
                                <svg class="w-7 h-7 text-white opacity-0 group-hover:opacity-100 drop-shadow transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 bg-black/60 px-1.5 py-1 opacity-0 group-hover:opacity-100 transition">
                                <p class="text-[10px] text-white truncate text-center font-semibold">Klik untuk pilih</p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Images Grid --}}
            @if($files->isNotEmpty())
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-3">
                @foreach($files as $file)
                <div
                    @click="selectImage('{{ Storage::url($file->path) }}')"
                    class="cursor-pointer group relative aspect-square rounded-lg border-2 border-slate-200 overflow-hidden hover:border-blue-500 transition"
                >
                    @if($file->thumbnail_path)
                        <img src="{{ Storage::url($file->thumbnail_path) }}" alt="{{ $file->original_name }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ Storage::url($file->path) }}" alt="{{ $file->original_name }}" class="w-full h-full object-cover">
                    @endif
                    <div class="absolute inset-0 bg-primary/0 group-hover:bg-primary/20 transition flex items-center justify-center">
                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="absolute bottom-0 left-0 right-0 bg-black/50 px-2 py-1 opacity-0 group-hover:opacity-100 transition">
                        <p class="text-xs text-white truncate">{{ $file->original_name }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $files->links() }}
            </div>
            @endif

            @if($folders->isEmpty() && $files->isEmpty())
            <div class="text-center py-12">
                <svg class="mx-auto w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <p class="mt-3 text-sm text-slate-500">Tidak ada gambar di folder ini.</p>
            </div>
            @endif
        </div>

        {{-- Upload Progress --}}
        <div x-show="uploading" x-cloak class="fixed bottom-4 right-4 bg-white rounded-lg shadow-lg border border-slate-200 p-4 w-64">
            <div class="flex items-center justify-between mb-1">
                <span class="text-sm text-slate-600">Mengupload...</span>
                <span class="text-sm text-slate-600" x-text="uploadProgress + '%'"></span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2">
                <div class="bg-primary h-2 rounded-full transition-all" :style="'width: ' + uploadProgress + '%'"></div>
            </div>
        </div>
    </div>

    <script>
    function imagePicker() {
        return {
            searchQuery: '',
            searchResults: null,
            uploading: false,
            uploadProgress: 0,
            isDragging: false,
            _dragCounter: 0,
            recentUploads: [],

            init() {
                // Drag & drop on entire document
                document.addEventListener('dragenter', (e) => {
                    e.preventDefault();
                    this._dragCounter++;
                    this.isDragging = true;
                });
                document.addEventListener('dragleave', (e) => {
                    this._dragCounter--;
                    if (this._dragCounter <= 0) {
                        this._dragCounter = 0;
                        this.isDragging = false;
                    }
                });
                document.addEventListener('dragover', (e) => {
                    e.preventDefault();
                });
                document.addEventListener('drop', (e) => {
                    e.preventDefault();
                    this._dragCounter = 0;
                    this.isDragging = false;
                    const files = e.dataTransfer.files;
                    if (files.length) {
                        this.uploadImage(files[0]);
                    }
                });
            },

            selectImage(url) {
                // Send selected image URL back to parent window (TinyMCE)
                if (window.parent && window.parent.postMessage) {
                    window.parent.postMessage({ type: 'file-manager-select', url: url }, '*');
                }
                // Also try callback approach for TinyMCE
                if (typeof window.parent.fileManagerCallback === 'function') {
                    window.parent.fileManagerCallback(url);
                }
            },

            async performSearch() {
                if (this.searchQuery.length < 2) {
                    this.searchResults = null;
                    return;
                }

                try {
                    const response = await fetch(`{{route('admin.files.search')}}?q=${encodeURIComponent(this.searchQuery)}`);
                    const data = await response.json();
                    // Filter only images
                    this.searchResults = data.files.filter(f => f.mime_type.startsWith('image/'));
                } catch (error) {
                    console.error('Search failed:', error);
                }
            },

            async uploadImage(file) {
                if (!file) return;

                // Validate image type client-side
                if (!file.type.startsWith('image/')) {
                    alert('Hanya file gambar yang diizinkan.');
                    return;
                }

                this.uploading = true;
                this.uploadProgress = 0;

                const formData = new FormData();
                formData.append('file', file);
                formData.append('folder_id', '{{$currentFolder?->id ?? ''}}');

                try {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{route("admin.files.upload")}}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
                    xhr.setRequestHeader('Accept', 'application/json');

                    xhr.upload.onprogress = (e) => {
                        if (e.lengthComputable) {
                            this.uploadProgress = Math.round((e.loaded / e.total) * 100);
                        }
                    };

                    xhr.onload = () => {
                        this.uploading = false;
                        if (xhr.status === 200) {
                            try {
                                const json = JSON.parse(xhr.responseText);
                                if (json.success && json.file) {
                                    // Use server URL so selectImage works properly
                                    this.recentUploads.unshift({
                                        id: Date.now(),
                                        url: json.file.url,
                                        thumbnail_url: json.file.thumbnail_url || json.file.url,
                                        original_name: json.file.original_name || file.name,
                                    });
                                    return; // Don't reload — image is clickable immediately
                                }
                            } catch (e) {}
                            // Fallback: reload to refresh grid
                            window.location.reload();
                        } else {
                            alert('Upload gagal. Pastikan file adalah gambar dengan ukuran maksimal 10MB.');
                        }
                    };

                    xhr.onerror = () => {
                        this.uploading = false;
                        alert('Upload gagal. Silakan coba lagi.');
                    };

                    xhr.send(formData);
                } catch (error) {
                    this.uploading = false;
                    console.error('Upload failed:', error);
                }
            }
        };
    }
    </script>
</body>
</html>
