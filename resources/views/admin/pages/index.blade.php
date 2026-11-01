@extends('admin.layouts.app')

@section('title', 'Kelola Halaman')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '', deleteItemName: '' }" class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Kelola Halaman</h1>
        <a href="{{ route('admin.pages.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-dark transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Halaman
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
        <form method="GET" action="{{ route('admin.pages.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Search --}}
            <div>
                <label for="search" class="block text-sm font-medium text-slate-700 mb-1">Cari Judul</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}"
                       placeholder="Ketik judul halaman..."
                       class="w-full rounded-lg border-slate-300 text-sm focus:border-primary focus:ring-primary">
            </div>

            {{-- Status Filter --}}
            <div>
                <label for="status" class="block text-sm font-medium text-slate-700 mb-1">Status</label>
                <select name="status" id="status"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-primary focus:ring-primary">
                    <option value="">Semua Status</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="trashed" {{ request('status') === 'trashed' ? 'selected' : '' }}>Trashed</option>
                </select>
            </div>

            {{-- Filter Actions --}}
            <div class="flex items-end gap-2">
                <button type="submit"
                        class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.pages.index') }}"
                   class="px-4 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 font-medium text-slate-700">Judul</th>
                        <th class="px-6 py-3 font-medium text-slate-700">Template</th>
                        <th class="px-6 py-3 font-medium text-slate-700">Status</th>
                        <th class="px-6 py-3 font-medium text-slate-700">Tanggal</th>
                        <th class="px-6 py-3 font-medium text-slate-700 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pages as $page)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    {{-- Indentation for hierarchy --}}
                                    @if($page->parent_id)
                                        @php
                                            $depth = 0;
                                            $current = $page;
                                            while ($current->parent_id) {
                                                $depth++;
                                                $current = $current->parent;
                                                if ($depth > 5) break;
                                            }
                                        @endphp
                                        <span class="text-slate-300" style="padding-left: {{ $depth * 1.5 }}rem;">
                                            └─
                                        </span>
                                    @endif
                                    <div>
                                        <div class="font-medium text-slate-900">{{ $page->title }}</div>
                                        <div class="text-xs text-slate-500">/{{ $page->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-slate-100 text-slate-700 rounded">
                                    {{ ucfirst(str_replace('-', ' ', $page->template ?? 'default')) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($page->trashed())
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-red-50 text-red-700 rounded">
                                        Trashed
                                    </span>
                                @elseif($page->status === 'published')
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-green-50 text-green-700 rounded">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded">
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $page->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($page->trashed())
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.pages.restore', $page->id) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                                Restore
                                            </button>
                                        </form>
                                        <button type="button"
                                                @click="deleteUrl = '{{ route('admin.pages.force-delete', $page->id) }}'; deleteItemName = '{{ addslashes($page->title) }}'; showDeleteModal = true"
                                                class="text-sm text-red-600 hover:text-red-800 font-medium">
                                            Hapus Permanen
                                        </button>
                                    </div>
                                @else
                                    <div class="flex items-center justify-end gap-2">
                                        @if($page->use_page_builder)
                                        <a href="{{ route('admin.pages.grapesjs', $page) }}"
                                           class="text-sm text-teal-600 hover:text-teal-800 font-medium"
                                           title="Visual Drag & Drop Editor">
                                            🎨 Visual
                                        </a>
                                        @endif
                                        <a href="{{ route('admin.pages.edit', $page) }}"
                                           class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                            Edit
                                        </a>
                                        <button type="button"
                                                @click="deleteUrl = '{{ route('admin.pages.destroy', $page) }}'; deleteItemName = '{{ addslashes($page->title) }}'; showDeleteModal = true"
                                                class="text-sm text-red-600 hover:text-red-800 font-medium">
                                            Hapus
                                        </button>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                Belum ada halaman.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($pages->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $pages->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    @include('components.admin.delete-modal')
</div>
@endsection
