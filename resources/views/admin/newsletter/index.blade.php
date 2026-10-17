@extends('admin.layouts.app')
@section('title', 'Newsletter Subscribers')
@section('page-title', 'Newsletter')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '', deleteItemName: '' }" class="space-y-5">

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Newsletter Subscribers</h1>
            <p class="text-sm text-slate-500 mt-0.5">Daftar email pelanggan newsletter</p>
        </div>
        <a href="{{ route('admin.newsletter.export') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-700 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export CSV
        </a>
    </div>

    {{-- Stats grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @foreach([
            ['label'=>'Aktif', 'value'=>$counts['active'], 'color'=>'green'],
            ['label'=>'Belum Konfirmasi', 'value'=>$counts['pending'], 'color'=>'amber'],
            ['label'=>'Unsubscribed', 'value'=>$counts['unsubscribed'], 'color'=>'red'],
            ['label'=>'Total', 'value'=>$counts['total'], 'color'=>'slate'],
        ] as $s)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 border-l-4 border-{{ $s['color'] }}-500">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $s['label'] }}</p>
            <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $s['value'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4">
        <form method="GET" action="{{ route('admin.newsletter.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
            <div class="md:col-span-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari email...">
            </div>
            <div>
                <select name="status">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status')==='active' ? 'selected' : '' }}>Aktif</option>
                    <option value="pending" {{ request('status')==='pending' ? 'selected' : '' }}>Pending</option>
                    <option value="unsubscribed" {{ request('status')==='unsubscribed' ? 'selected' : '' }}>Unsubscribed</option>
                </select>
            </div>
            <div class="md:col-span-3 flex items-center gap-2">
                <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-sm font-semibold rounded-lg hover:bg-slate-700">Filter</button>
                <a href="{{ route('admin.newsletter.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 text-sm font-semibold rounded-lg hover:bg-slate-200">Reset</a>
            </div>
        </form>
    </div>

    {{-- Subscribers table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Email</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Subscribed</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($subscribers as $sub)
                    @php
                        $status = $sub->isUnsubscribed() ? 'unsubscribed' : ($sub->isConfirmed() ? 'active' : 'pending');
                        $badge = match($status) {
                            'active' => 'bg-green-100 text-green-700',
                            'pending' => 'bg-amber-100 text-amber-700',
                            'unsubscribed' => 'bg-red-100 text-red-700',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-5 py-3 font-mono text-xs">{{ $sub->email }}</td>
                        <td class="px-5 py-3 text-sm text-slate-700">{{ $sub->name ?? '—' }}</td>
                        <td class="px-5 py-3">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $badge }}">{{ $status }}</span>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-500">{{ $sub->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-5 py-3 text-right">
                            <button type="button"
                                    @click="deleteUrl = '{{ route('admin.newsletter.destroy', $sub) }}'; deleteItemName = '{{ addslashes($sub->email) }}'; showDeleteModal = true"
                                    class="text-xs text-red-600 hover:text-red-800 font-semibold">Hapus</button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada subscriber.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($subscribers->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $subscribers->links() }}</div>
        @endif
    </div>

    @include('components.admin.delete-modal')
</div>
@endsection
