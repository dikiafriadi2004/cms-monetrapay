@extends('admin.layouts.app')
@section('title', 'File Manager')
@section('page-title', 'File Manager')

@section('content')
<div x-data="fileManager()" class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl font-bold text-slate-800">File Manager</h1>
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 mt-1 text-sm text-slate-500">
                <a href="{{ route('admin.files.index') }}" class="hover:text-primary transition-colors flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                    Root
                </a>
                @foreach($breadcrumbs as $crumb)
                    <svg class="w-3.5 h-3.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <a href="{{ route('admin.files.index', ['folder_id' => $crumb->id]) }}" class="hover:text-primary transition-colors">{{ $crumb->name }}</a>
                @endforeach
            </nav>
        </div>
        <div class="flex items-center gap-2">
            {{-- Search --}}
            <div class="relative">
                <input type="text" x-model="searchQuery" @input.debounce.300ms="performSearch()"
                       placeholder="Cari file..."
                       class="w-56 pl-9 pr-4 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-primary focus:bg-white transition-colors">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            {{-- New Folder --}}
            <button @click="showFolderModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border-2 border-slate-200 rounded-xl hover:border-slate-300 hover:bg-slate-50 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                Folder Baru
            </button>
            {{-- Upload --}}
            <button @click="showUploadModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary rounded-xl hover:bg-primary-dark transition-all shadow-sm shadow-primary/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Upload File
            </button>
        </div>
    </div>

    {{-- Search Results --}}
    <div x-show="searchQuery.length >= 2 && searchResults !== null" x-cloak
         class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <h3 class="text-sm font-bold text-slate-700 mb-4">Hasil: "<span x-text="searchQuery" class="text-primary"></span>"</h3>
        <div x-show="searchResults && searchResults.length === 0" class="text-sm text-slate-500 text-center py-6">Tidak ada file ditemukan.</div>
        <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-7 lg:grid-cols-9 gap-3">
            <template x-for="file in searchResults" :key="file.id">
                <div class="group relative bg-slate-50 rounded-xl border border-slate-200 p-2.5 hover:border-primary/40 hover:shadow-md transition-all cursor-pointer">
                    <div class="aspect-square flex items-center justify-center mb-2 overflow-hidden rounded-lg bg-white">
                        <template x-if="file.thumbnail_url">
                            <img :src="file.thumbnail_url" :alt="file.original_name" class="w-full h-full object-cover rounded-lg">
                        </template>
                        <template x-if="!file.thumbnail_url">
                            <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                        </template>
                    </div>
                    <p class="text-xs text-slate-600 truncate font-medium" x-text="file.original_name"></p>
                    <p class="text-xs text-slate-400" x-text="formatSize(file.size)"></p>
                </div>
            </template>
        </div>
    </div>

    {{-- Main Content --}}
    <div x-show="searchQuery.length < 2" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

        {{-- Folders --}}
        @if($folders->isNotEmpty())
        <div class="p-5 border-b border-slate-100">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Folder ({{ $folders->count() }})</h3>
            <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                @foreach($folders as $folder)
                <div class="group relative">
                    {{-- Delete folder button --}}
                    <button @click="deleteFolder({{ $folder->id }}, '{{ addslashes($folder->name) }}')"
                            class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-sm hover:bg-red-600 z-10"
                            title="Hapus folder">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <a href="{{ route('admin.files.index', ['folder_id' => $folder->id]) }}"
                       class="flex flex-col items-center gap-2 p-3 rounded-xl border-2 border-slate-100 hover:border-yellow-300 hover:bg-yellow-50/50 transition-all">
                        <svg class="w-10 h-10 text-yellow-400 group-hover:text-yellow-500 transition-colors" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2 6a2 2 0 012-2h5l2 2h9a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        <span class="text-xs text-slate-700 font-medium text-center truncate w-full">{{ $folder->name }}</span>
                    </a>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Files --}}
        @if($files->isNotEmpty())
        <div class="p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">File ({{ $files->total() }})</h3>
            <div class="grid grid-cols-3 sm:grid-cols-5 md:grid-cols-7 lg:grid-cols-9 gap-3">
                @foreach($files as $file)
                <div class="group relative bg-slate-50 rounded-xl border-2 border-slate-100 p-2.5 hover:border-primary/30 hover:shadow-md transition-all">
                    {{-- Delete button --}}
                    <button @click="deleteFile({{ $file->id }}, '{{ addslashes($file->original_name) }}')"
                            class="absolute -top-1.5 -right-1.5 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all shadow-sm hover:bg-red-600 z-10"
                            title="Hapus">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    {{-- Thumbnail --}}
                    <div class="aspect-square flex items-center justify-center mb-2 overflow-hidden rounded-lg bg-white border border-slate-100">
                        @if($file->thumbnail_path)
                            <img src="{{ Storage::url($file->thumbnail_path) }}" alt="{{ $file->original_name }}" class="w-full h-full object-cover rounded-lg">
                        @elseif(str_starts_with($file->mime_type, 'image/'))
                            <img src="{{ Storage::url($file->path) }}" alt="{{ $file->original_name }}" class="w-full h-full object-cover rounded-lg">
                        @elseif(str_contains($file->mime_type, 'pdf'))
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-8 h-8 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6zm-1 1.5L18.5 9H13V3.5zM8 17v-1h8v1H8zm0-3v-1h8v1H8zm0-3V10h5v1H8z"/></svg>
                                <span class="text-[10px] font-bold text-red-500">PDF</span>
                            </div>
                        @else
                            <div class="flex flex-col items-center gap-1">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span class="text-[10px] font-bold text-slate-400 uppercase">{{ pathinfo($file->original_name, PATHINFO_EXTENSION) }}</span>
                            </div>
                        @endif
                    </div>
                    {{-- Info --}}
                    <p class="text-[11px] text-slate-700 truncate font-medium leading-tight" title="{{ $file->original_name }}">{{ $file->original_name }}</p>
                    <p class="text-[10px] text-slate-400 mt-0.5">
                        @php
                            $s = $file->size;
                            echo $s >= 1048576 ? number_format($s/1048576,1).' MB' : ($s >= 1024 ? number_format($s/1024,1).' KB' : $s.' B');
                        @endphp
                    </p>
                </div>
                @endforeach
            </div>
            @if($files->hasPages())
            <div class="mt-5 pt-4 border-t border-slate-100">{{ $files->links() }}</div>
            @endif
        </div>
        @endif

        @if($folders->isEmpty() && $files->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 gap-4">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center">
                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
            </div>
            <div class="text-center">
                <p class="text-sm font-semibold text-slate-600">Folder ini kosong</p>
                <p class="text-xs text-slate-400 mt-1">Upload file atau buat folder baru</p>
            </div>
            <button @click="showUploadModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary rounded-xl hover:bg-primary-dark transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Upload File
            </button>
        </div>
        @endif
    </div>

    {{-- Upload Modal --}}
    @include('admin.files._upload_modal')
    {{-- Folder Modal --}}
    @include('admin.files._folder_modal')

    {{-- Delete Modal (File + Folder) --}}
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showDeleteModal = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 text-center mb-2" x-text="deleteModalTitle">Hapus</h3>
            <div x-show="deleteWarning">
                <p class="text-sm text-red-600 text-center mb-3" x-text="deleteWarningMessage"></p>
                <ul class="text-sm text-slate-600 mb-4 space-y-1 bg-slate-50 rounded-xl p-3">
                    <template x-for="usage in deleteUsages" :key="usage.type + usage.id">
                        <li class="flex items-center gap-2"><span class="w-1.5 h-1.5 bg-red-400 rounded-full shrink-0"></span><span x-text="usage.type + ': ' + usage.title"></span></li>
                    </template>
                </ul>
            </div>
            <p class="text-sm text-slate-600 text-center mb-6">Hapus "<span class="font-semibold" x-text="deleteFileName"></span>"? Aksi ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3">
                <button @click="showDeleteModal = false"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Batal
                </button>
                <button @click="confirmDelete()"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function fileManager() {
    return {
        showUploadModal: false, showFolderModal: false, showDeleteModal: false,
        searchQuery: '', searchResults: null,
        deleteFileId: null, deleteFolderId: null, deleteFileName: '',
        deleteModalTitle: 'Hapus File',
        deleteWarning: false, deleteWarningMessage: '', deleteUsages: [],
        uploading: false, uploadProgress: 0,
        _deleteType: 'file', // 'file' or 'folder'

        formatSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024, sizes = ['B','KB','MB','GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
        },

        async performSearch() {
            if (this.searchQuery.length < 2) { this.searchResults = null; return; }
            try {
                const r = await fetch(`{{ route('admin.files.search') }}?q=${encodeURIComponent(this.searchQuery)}`);
                const d = await r.json();
                this.searchResults = d.files;
            } catch(e) { console.error(e); }
        },

        async deleteFile(id, name) {
            this._deleteType = 'file';
            this.deleteFileId = id; this.deleteFolderId = null;
            this.deleteFileName = name; this.deleteModalTitle = 'Hapus File';
            this.deleteWarning = false; this.deleteWarningMessage = ''; this.deleteUsages = [];
            try {
                const r = await fetch(`/admin/files/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                });
                if (r.status === 409) {
                    const d = await r.json();
                    this.deleteWarning = true; this.deleteWarningMessage = d.message; this.deleteUsages = d.usages;
                    this.showDeleteModal = true;
                } else if (r.ok) { window.location.reload(); }
                else { this.showDeleteModal = true; }
            } catch(e) { this.showDeleteModal = true; }
        },

        deleteFolder(id, name) {
            this._deleteType = 'folder';
            this.deleteFolderId = id; this.deleteFileId = null;
            this.deleteFileName = name; this.deleteModalTitle = 'Hapus Folder';
            this.deleteWarning = false; this.deleteWarningMessage = ''; this.deleteUsages = [];
            this.showDeleteModal = true;
        },

        async confirmDelete() {
            try {
                let url, r;
                if (this._deleteType === 'folder') {
                    url = `/admin/files/folders/${this.deleteFolderId}`;
                    r = await fetch(url, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                    });
                } else {
                    url = `/admin/files/${this.deleteFileId}?confirmed=1`;
                    r = await fetch(url, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, 'Accept': 'application/json' }
                    });
                }
                if (r.ok) { window.location.reload(); }
                else {
                    const d = await r.json().catch(() => ({}));
                    alert(d.message || 'Gagal menghapus.');
                    this.showDeleteModal = false;
                }
            } catch(e) { console.error(e); this.showDeleteModal = false; }
        },

        async uploadFile(file) {
            this.uploading = true; this.uploadProgress = 0;
            const fd = new FormData();
            fd.append('file', file);
            fd.append('folder_id', '{{ $currentFolder?->id ?? '' }}');
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '{{ route("admin.files.upload") }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.upload.onprogress = e => { if (e.lengthComputable) this.uploadProgress = Math.round(e.loaded/e.total*100); };
            xhr.onload = () => { this.uploading = false; if (xhr.status === 200) window.location.reload(); else alert('Upload gagal.'); };
            xhr.onerror = () => { this.uploading = false; alert('Upload gagal.'); };
            xhr.send(fd);
        }
    };
}
</script>
@endsection
