@extends('admin.layouts.app')

@section('title', 'Kelola Menu')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '', deleteItemName: '' }" class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Kelola Menu</h1>
        <a href="{{ route('admin.menus.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-dark transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Menu
        </a>
    </div>

    {{-- Menu List --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 font-medium text-slate-700">Nama Menu</th>
                        <th class="px-6 py-3 font-medium text-slate-700">Lokasi</th>
                        <th class="px-6 py-3 font-medium text-slate-700">Jumlah Item</th>
                        <th class="px-6 py-3 font-medium text-slate-700">Status</th>
                        <th class="px-6 py-3 font-medium text-slate-700 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($menus as $menu)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $menu->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-slate-100 text-slate-700 rounded capitalize">
                                    {{ $menu->location }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-600">
                                {{ $menu->items_count }} item
                            </td>
                            <td class="px-6 py-4">
                                @if($menu->is_active)
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-green-50 text-green-700 rounded">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-gray-100 text-gray-700 rounded">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.menus.edit', $menu) }}"
                                       class="text-sm text-primary hover:text-primary-dark font-semibold">
                                        Edit
                                    </a>
                                    <button type="button"
                                            @click="deleteUrl = '{{ route('admin.menus.destroy', $menu) }}'; deleteItemName = '{{ addslashes($menu->name) }}'; showDeleteModal = true"
                                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                                Belum ada menu. Klik "Tambah Menu" untuk membuat menu baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @include('components.admin.delete-modal')
</div>
@endsection
