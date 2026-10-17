@extends('admin.layouts.app')
@section('title', 'Blog')
@section('page-title', 'Kelola Artikel')

@section('content')
<div x-data="{
        showDeleteModal: false, deleteUrl: '', deleteItemName: '',
        selectedIds: [],
        get allChecked() { return this.selectedIds.length > 0 && this.selectedIds.length === document.querySelectorAll('[data-bulk-row]').length; },
        toggleAll(e) {
            this.selectedIds = e.target.checked ? Array.from(document.querySelectorAll('[data-bulk-row]')).map(el => parseInt(el.dataset.id)) : [];
        }
     }" class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Kelola Artikel</h1>
            <p class="text-sm text-slate-500 mt-0.5">Buat, filter, dan kelola status artikel blog. Pilih checkbox untuk bulk action.</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.blogs.export') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-700 text-white text-sm font-semibold rounded-xl hover:bg-slate-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Export CSV
            </a>
            <a href="{{ route('admin.blogs.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-dark transition-colors shadow-sm shadow-primary/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tulis Artikel
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <form method="GET" action="{{ route('admin.blogs.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Cari Judul</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik judul..."
                       class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-primary focus:bg-white transition-colors">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Status</label>
                <select name="status" class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:border-primary focus:bg-white transition-colors">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="trashed" {{ request('status') === 'trashed' ? 'selected' : '' }}>Trashed</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Kategori</label>
                <select name="category_id" class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:border-primary focus:bg-white transition-colors">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}"
                       class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:border-primary focus:bg-white transition-colors">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}"
                       class="w-full px-3.5 py-2.5 text-sm rounded-xl border-2 border-slate-200 bg-slate-50 text-slate-800 focus:outline-none focus:border-primary focus:bg-white transition-colors">
            </div>
            <div class="lg:col-span-5 flex items-center gap-2 pt-1">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    Filter
                </button>
                <a href="{{ route('admin.blogs.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Bulk Actions Bar (only when selection exists) --}}
    <div x-show="selectedIds.length > 0" x-cloak x-transition
         class="bg-primary text-white rounded-2xl shadow-lg p-4 flex items-center justify-between flex-wrap gap-3 sticky top-2 z-20">
        <div class="flex items-center gap-3">
            <span class="text-sm font-bold">
                <span x-text="selectedIds.length"></span> artikel dipilih
            </span>
            <button @click="selectedIds = []; document.querySelectorAll('[data-bulk-row]').forEach(el => el.checked = false); document.getElementById('select-all-blogs').checked = false;" type="button"
                    class="text-xs text-white/80 hover:text-white underline">Batalkan</button>
        </div>
        <form method="POST" action="{{ route('admin.blogs.bulk') }}" class="flex items-center gap-2 flex-wrap">
            @csrf
            <template x-for="id in selectedIds" :key="id">
                <input type="hidden" name="ids[]" :value="id">
            </template>
            @if(request('status') === 'trashed')
                <button type="submit" name="action" value="restore"
                        class="px-3 py-1.5 bg-white/15 hover:bg-white/25 rounded-lg text-xs font-bold text-white transition-colors">Restore</button>
                <button type="submit" name="action" value="force_delete" onclick="return confirm('Hapus permanen ' + document.querySelectorAll('[data-bulk-row]:checked').length + ' artikel? Tidak bisa dibatalkan.')"
                        class="px-3 py-1.5 bg-red-500 hover:bg-red-600 rounded-lg text-xs font-bold text-white transition-colors">Hapus Permanen</button>
            @else
                <button type="submit" name="action" value="publish"
                        class="px-3 py-1.5 bg-white/15 hover:bg-white/25 rounded-lg text-xs font-bold text-white transition-colors">Publikasi</button>
                <button type="submit" name="action" value="draft"
                        class="px-3 py-1.5 bg-white/15 hover:bg-white/25 rounded-lg text-xs font-bold text-white transition-colors">Jadikan Draft</button>
                <button type="submit" name="action" value="trash" onclick="return confirm('Pindahkan ke trash?')"
                        class="px-3 py-1.5 bg-red-500 hover:bg-red-600 rounded-lg text-xs font-bold text-white transition-colors">Pindah ke Trash</button>
            @endif
        </form>
    </div>

    @if(request()->hasAny(['search','status','category_id','date_from','date_to']))
    <div class="rounded-2xl border border-primary/20 bg-primary-light/60 px-4 py-3 text-sm text-primary flex items-center justify-between gap-3">
        <span class="font-semibold">Filter aktif. Hasil tabel sudah disesuaikan.</span>
        <a href="{{ route('admin.blogs.index') }}" class="text-xs font-bold underline">Bersihkan filter</a>
    </div>
    @endif

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3 w-10">
                            <input id="select-all-blogs" type="checkbox" @change="toggleAll($event)" :checked="allChecked">
                        </th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Judul</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Kategori</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($blogs as $blog)
                    <tr class="hover:bg-slate-50/70 transition-colors" :class="selectedIds.includes({{ $blog->id }}) ? 'bg-primary/5' : ''">
                        <td class="px-5 py-3.5">
                            <input type="checkbox" data-bulk-row data-id="{{ $blog->id }}"
                                   :checked="selectedIds.includes({{ $blog->id }})"
                                   @change="if ($event.target.checked) selectedIds.push({{ $blog->id }}); else selectedIds = selectedIds.filter(i => i !== {{ $blog->id }})">
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="font-semibold text-slate-800 max-w-xs truncate" title="{{ $blog->title }}">{{ $blog->title }}</div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $blog->author->name ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex flex-wrap gap-1">
                                @foreach($blog->categories->take(2) as $cat)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">{{ $cat->name }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @if($blog->trashed())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-red-50 text-red-700">Trashed</span>
                            @elseif($blog->status === 'published')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-green-50 text-green-700">Published</span>
                            @elseif($blog->scheduled_at)
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Terjadwal
                                </span>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $blog->scheduled_at->format('d M Y H:i') }}</div>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600">Draft</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-500">{{ $blog->created_at->format('d M Y') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-2">
                                @if($blog->trashed())
                                    <form method="POST" action="{{ route('admin.blogs.restore', $blog->id) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-600 hover:text-white transition-all duration-150">
                                            Restore
                                        </button>
                                    </form>
                                    <button type="button"
                                            @click="deleteUrl = '{{ route('admin.blogs.force-delete', $blog->id) }}'; deleteItemName = '{{ addslashes($blog->title) }}'; showDeleteModal = true"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-150 border border-red-100">
                                        Hapus Permanen
                                    </button>
                                @else
                                    @if($blog->status === 'published')
                                    <a href="{{ route('blog.show', $blog->slug) }}" target="_blank"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-700 hover:text-white transition-all duration-150">
                                        Lihat
                                    </a>
                                    @endif
                                    <a href="{{ route('admin.blogs.edit', $blog) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-primary bg-primary-light rounded-lg hover:bg-primary hover:text-white transition-all duration-150">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        Edit
                                    </a>
                                    <button type="button"
                                            @click="deleteUrl = '{{ route('admin.blogs.destroy', $blog) }}'; deleteItemName = '{{ addslashes($blog->title) }}'; showDeleteModal = true"
                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-all duration-150 border border-red-100">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-14 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                                </div>
                                <p class="text-sm text-slate-500 font-medium">Belum ada artikel</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($blogs->hasPages())
        <div class="px-5 py-4 border-t border-slate-100">{{ $blogs->links() }}</div>
        @endif
    </div>

    @include('components.admin.delete-modal')
</div>
@endsection
