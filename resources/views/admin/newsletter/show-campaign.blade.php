@extends('admin.layouts.app')
@section('title', 'Kampanye: ' . $campaign->subject)
@section('page-title', 'Newsletter')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '{{ route('admin.newsletter.campaigns.destroy', $campaign) }}', deleteItemName: '{{ addslashes($campaign->subject) }}' }" class="max-w-3xl space-y-5">

    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.newsletter.campaigns.index') }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-800">{{ $campaign->subject }}</h1>
                <p class="text-xs text-slate-500">Dibuat {{ $campaign->created_at->translatedFormat('d F Y') }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if(!$campaign->isSent())
            <form method="POST" action="{{ route('admin.newsletter.campaigns.send', $campaign) }}"
                  onsubmit="return confirm('Kirim newsletter ke {{ $activeCount }} subscriber aktif? Tidak bisa dibatalkan setelah dikirim.')">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-secondary text-white text-sm font-bold rounded-xl hover:bg-secondary-dark transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim ke {{ $activeCount }} Subscriber
                </button>
            </form>
            <button type="button" @click="showDeleteModal = true"
                    class="px-4 py-2.5 bg-red-50 border border-red-200 text-red-700 text-sm font-semibold rounded-xl hover:bg-red-100 transition-colors">
                Hapus
            </button>
            @else
            <span class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-50 border border-green-200 text-green-700 text-sm font-semibold rounded-xl">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Terkirim {{ $campaign->sent_at?->translatedFormat('d M Y, H:i') }}
            </span>
            @endif
        </div>
    </div>

    {{-- Stats --}}
    @if($campaign->isSent())
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white border border-slate-200 rounded-xl p-5 text-center shadow-sm">
            <p class="text-2xl font-extrabold text-slate-800">{{ $campaign->recipients_count }}</p>
            <p class="text-xs text-slate-500 mt-1 font-semibold uppercase tracking-wide">Target</p>
        </div>
        <div class="bg-white border border-green-200 rounded-xl p-5 text-center shadow-sm">
            <p class="text-2xl font-extrabold text-green-700">{{ $campaign->sent_count }}</p>
            <p class="text-xs text-slate-500 mt-1 font-semibold uppercase tracking-wide">Berhasil</p>
        </div>
        <div class="bg-white border border-{{ $campaign->recipients_count - $campaign->sent_count > 0 ? 'red' : 'slate' }}-200 rounded-xl p-5 text-center shadow-sm">
            <p class="text-2xl font-extrabold text-{{ $campaign->recipients_count - $campaign->sent_count > 0 ? 'red-600' : 'slate-400' }}">{{ $campaign->recipients_count - $campaign->sent_count }}</p>
            <p class="text-xs text-slate-500 mt-1 font-semibold uppercase tracking-wide">Gagal</p>
        </div>
    </div>
    @endif

    {{-- Preview --}}
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
            <span class="w-1 h-4 bg-primary rounded-full"></span>
            <h2 class="text-sm font-bold text-slate-800">Preview Konten</h2>
        </div>
        <div class="p-6">
            <div class="prose-enhanced max-w-none">{!! $campaign->content !!}</div>
        </div>
    </div>

    @include('components.admin.delete-modal')
</div>
@endsection
