@extends('admin.layouts.app')
@section('title', 'Kampanye Newsletter')
@section('page-title', 'Newsletter')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '', deleteItemName: '' }" class="space-y-5">

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div>
            <h1 class="text-xl font-bold text-slate-800">Kampanye Newsletter</h1>
            <p class="text-sm text-slate-500 mt-0.5">Buat dan kirim newsletter ke {{ $activeCount }} subscriber aktif</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.newsletter.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                Subscribers
            </a>
            <a href="{{ route('admin.newsletter.campaigns.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-dark transition-colors shadow-sm shadow-primary/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Kampanye
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Subjek</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Terkirim</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal</th>
                        <th class="px-5 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($campaigns as $c)
                    @php
                        $badge = match($c->status) {
                            'sent' => 'bg-green-100 text-green-700',
                            'sending' => 'bg-blue-100 text-blue-700',
                            'failed' => 'bg-red-100 text-red-700',
                            default => 'bg-slate-100 text-slate-600',
                        };
                        $label = match($c->status) {
                            'sent' => 'Terkirim',
                            'sending' => 'Mengirim...',
                            'failed' => 'Gagal',
                            default => 'Draft',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-5 py-3.5">
                            <a href="{{ route('admin.newsletter.campaigns.show', $c) }}" class="font-semibold text-slate-800 hover:text-primary transition-colors">{{ $c->subject }}</a>
                            <p class="text-xs text-slate-400 mt-0.5">Oleh {{ $c->user?->name ?? '—' }}</p>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">{{ $label }}</span>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-slate-600">
                            @if($c->isSent())
                                {{ $c->sent_count }} / {{ $c->recipients_count }}
                            @else
                                {{ $c->recipients_count }} target
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-xs text-slate-500 whitespace-nowrap">
                            {{ $c->sent_at?->translatedFormat('d M Y, H:i') ?? $c->created_at->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.newsletter.campaigns.show', $c) }}"
                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-primary bg-primary-light rounded-lg hover:bg-primary hover:text-white transition-all">
                                    Lihat
                                </a>
                                @if(!$c->isSent())
                                <button type="button"
                                        @click="deleteUrl = '{{ route('admin.newsletter.campaigns.destroy', $c) }}'; deleteItemName = '{{ addslashes($c->subject) }}'; showDeleteModal = true"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-red-600 bg-red-50 rounded-lg hover:bg-red-600 hover:text-white transition-all border border-red-100">
                                    Hapus
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 bg-slate-100 rounded-2xl flex items-center justify-center">
                                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-slate-700">Belum ada kampanye</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Klik "Buat Kampanye" untuk membuat newsletter pertama</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($campaigns->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $campaigns->links() }}</div>
        @endif
    </div>

    @include('components.admin.delete-modal')
</div>
@endsection
