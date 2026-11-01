@extends('admin.layouts.app')

@section('title', 'Kelola Iklan')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '', deleteItemName: '' }" class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Kelola Iklan</h1>
        <a href="{{ route('admin.ads.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-primary-dark transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Slot Iklan
        </a>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3 font-medium text-slate-700">Nama</th>
                        <th class="px-6 py-3 font-medium text-slate-700">Tipe</th>
                        <th class="px-6 py-3 font-medium text-slate-700">Posisi</th>
                        <th class="px-6 py-3 font-medium text-slate-700">Status</th>
                        <th class="px-6 py-3 font-medium text-slate-700">Jadwal</th>
                        <th class="px-6 py-3 font-medium text-slate-700 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($ads as $ad)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-900">{{ $ad->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($ad->type === 'adsense')
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-purple-50 text-purple-700 rounded">
                                        AdSense
                                    </span>
                                @elseif($ad->type === 'adstera')
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-orange-50 text-orange-700 rounded">
                                        Adstera
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-cyan-50 text-cyan-700 rounded">
                                        Manual
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $positionLabels = [
                                        'header' => 'Header',
                                        'sidebar' => 'Sidebar',
                                        'in_content' => 'Dalam Konten',
                                        'auto_insert' => 'Auto Insert',
                                        'footer' => 'Footer',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-0.5 text-xs font-medium bg-slate-100 text-slate-700 rounded">
                                    {{ $positionLabels[$ad->position] ?? $ad->position }}
                                </span>
                                @if($ad->position === 'in_content' && $ad->paragraph_number)
                                    <span class="text-xs text-slate-500 ml-1">(paragraf {{ $ad->paragraph_number }})</span>
                                @elseif($ad->position === 'auto_insert' && $ad->interval)
                                    <span class="text-xs text-slate-500 ml-1">(setiap {{ $ad->interval }} paragraf)</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.ads.toggle', $ad) }}">
                                    @csrf
                                    <button type="submit"
                                            title="{{ $ad->is_active ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan' }}"
                                            class="flex items-center gap-2.5 group focus:outline-none">
                                        {{-- Toggle switch --}}
                                        <div style="
                                            position:relative;
                                            width:44px;height:24px;
                                            border-radius:12px;
                                            background:{{ $ad->is_active ? '#0D9488' : '#cbd5e1' }};
                                            transition:background .2s;
                                            flex-shrink:0;
                                        ">
                                            <div style="
                                                position:absolute;
                                                top:3px;
                                                left:{{ $ad->is_active ? '23px' : '3px' }};
                                                width:18px;height:18px;
                                                background:#fff;
                                                border-radius:50%;
                                                box-shadow:0 1px 3px rgba(0,0,0,.2);
                                                transition:left .2s;
                                            "></div>
                                        </div>
                                        <span class="text-xs font-semibold {{ $ad->is_active ? 'text-primary' : 'text-slate-400' }}">
                                            {{ $ad->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-slate-600 text-xs">
                                @if($ad->start_date || $ad->end_date)
                                    <div>
                                        @if($ad->start_date)
                                            <span>Mulai: {{ $ad->start_date->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        @if($ad->end_date)
                                            <span>Berakhir: {{ $ad->end_date->format('d M Y') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-slate-400">Tanpa jadwal</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.ads.edit', $ad) }}"
                                       class="text-sm text-primary hover:text-primary-dark font-semibold">
                                        Edit
                                    </a>
                                    <button type="button"
                                            @click="deleteUrl = '{{ route('admin.ads.destroy', $ad) }}'; deleteItemName = '{{ addslashes($ad->name) }}'; showDeleteModal = true"
                                            class="text-sm text-red-600 hover:text-red-800 font-medium">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                Belum ada slot iklan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($ads->hasPages())
            <div class="px-6 py-4 border-t border-slate-200">
                {{ $ads->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    @include('components.admin.delete-modal')
</div>
@endsection
