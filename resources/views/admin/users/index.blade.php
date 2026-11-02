@extends('admin.layouts.app')

@section('title', 'Kelola Pengguna')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '', deleteItemName: '' }">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Kelola Pengguna</h1>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-primary hover:bg-primary-dark text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pengguna
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider">Dibuat</th>
                    <th class="px-6 py-3 text-xs font-semibold text-slate-600 uppercase tracking-wider text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($users as $user)
                    <tr class="hover:bg-slate-50">
                        <td class="px-6 py-4">
                            <span class="font-medium text-slate-800">{{ $user->name }}</span>
                            @if ($user->id === auth()->id())
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                    Anda
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $user->email }}
                        </td>
                        <td class="px-6 py-4">
                            @foreach ($user->roles as $role)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if ($role->name === 'Super Admin') bg-amber-100 text-amber-800
                                    @elseif ($role->name === 'Admin') bg-blue-100 text-blue-800
                                    @elseif ($role->name === 'Editor') bg-purple-100 text-purple-800
                                    @else bg-slate-100 text-slate-800
                                    @endif">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-primary hover:text-primary-dark hover:bg-primary/10 rounded-md transition-colors">
                                    Edit
                                </a>
                                @if ($user->id !== auth()->id())
                                    <button type="button"
                                            @click="deleteUrl = '{{ route('admin.users.destroy', $user) }}'; deleteItemName = '{{ addslashes($user->name) }}'; showDeleteModal = true"
                                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-800 hover:bg-red-50 rounded-md transition-colors">
                                        Hapus
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                            Belum ada pengguna. Klik "Tambah Pengguna" untuk membuat pengguna baru.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 border-t border-slate-200">
            <x-admin.pagination :paginator="$users" />
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    @include('components.admin.delete-modal')
</div>
@endsection
