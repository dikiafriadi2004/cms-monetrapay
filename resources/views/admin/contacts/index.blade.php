@extends('admin.layouts.app')
@section('title', 'Kontak')
@section('page-title', 'Kontak')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '', deleteItemName: '' }" class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Kontak</h1>
            <p class="text-sm text-slate-500 mt-0.5">Pesan masuk dari form kontak website</p>
        </div>
        @if($unreadCount > 0)
            <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-secondary/10 text-secondary-dark text-xs font-bold rounded-full">
                <span class="w-2 h-2 bg-secondary rounded-full animate-pulse"></span>
                {{ $unreadCount }} belum dibaca
            </span>
        @endif
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <form method="GET" action="{{ route('admin.contacts.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama, telepon, email...">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Status</label>
                <select name="filter">
                    <option value="">Semua</option>
                    <option value="unread" {{ request('filter') === 'unread' ? 'selected' : '' }}>Belum Dibaca</option>
                    <option value="read" {{ request('filter') === 'read' ? 'selected' : '' }}>Sudah Dibaca</option>
                </select>
            </div>
            <div class="sm:col-span-3 flex items-center gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-dark transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    Filter
                </button>
                <a href="{{ route('admin.contacts.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide w-8"></th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Kontak</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Kota</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($submissions as $sub)
                    <tr class="hover:bg-slate-50/70 transition-colors {{ !$sub->is_read ? 'bg-primary/5' : '' }}">
                        <td class="px-5 py-3.5">
                            @if(!$sub->is_read)
                                <span class="block w-2 h-2 bg-primary rounded-full" title="Belum dibaca"></span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.contacts.show', $sub) }}" class="font-semibold text-slate-800 hover:text-primary transition-colors">{{ $sub->name }}</a>
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">
                            <div class="font-mono font-semibold">{{ $sub->phone }}</div>
                            @if($sub->email)<div class="text-slate-400 mt-0.5">{{ $sub->email }}</div>@endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-600">{{ $sub->city ?? '—' }}</td>
                        <td class="px-5 py-3.5 text-xs text-slate-500 whitespace-nowrap">{{ $sub->created_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-2">
                                @if($sub->hasBeenReplied())
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold bg-green-50 text-green-700">Dibalas</span>
                                @endif
                                <a href="{{ route('admin.contacts.show', $sub) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-primary bg-primary-light rounded-lg hover:bg-primary hover:text-white transition-all">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Lihat
                                </a>
                                <button type="button"
                                        @click="deleteUrl = '{{ route('admin.contacts.destroy', $sub) }}'; deleteItemName = '{{ addslashes($sub->name) }}'; showDeleteModal = true"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-all border border-red-100">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">Belum ada pesan kontak</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Pesan dari form kontak website akan muncul di sini</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 border-t border-slate-100">
            <x-admin.pagination :paginator="$submissions" />
        </div>
    </div>

    @include('components.admin.delete-modal')
</div>
@endsection
