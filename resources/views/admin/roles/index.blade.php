@extends('admin.layouts.app')

@section('title', 'Kelola Role')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '', deleteItemName: '' }">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Kelola Role</h1>
        <a href="{{ route('admin.roles.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Role
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama Role</th>
                    <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Permissions</th>
                    <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Users</th>
                    <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($roles as $role)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="font-medium text-slate-800">{{ $role->name }}</span>
                            @if ($role->name === 'Super Admin')
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                    Protected
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $role->permissions_count }} permission{{ $role->permissions_count !== 1 ? 's' : '' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $role->users_count }} user{{ $role->users_count !== 1 ? 's' : '' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            @if ($role->name !== 'Super Admin')
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.roles.edit', $role) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-primary hover:text-primary-dark hover:bg-primary/10 rounded-md transition-colors">
                                        Edit
                                    </a>
                                    <button type="button"
                                            @click="deleteUrl = '{{ route('admin.roles.destroy', $role) }}'; deleteItemName = '{{ addslashes($role->name) }}'; showDeleteModal = true"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                        Hapus
                                    </button>
                                </div>
                            @else
                                <span class="text-sm text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                            Belum ada role. Klik "Tambah Role" untuk membuat role baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Delete Confirmation Modal --}}
    @include('components.admin.delete-modal')
</div>
@endsection
