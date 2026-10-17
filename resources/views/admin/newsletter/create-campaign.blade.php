@extends('admin.layouts.app')
@section('title', 'Buat Kampanye Newsletter')
@section('page-title', 'Newsletter')
@section('uses-editor', true)

@section('content')
<div class="max-w-3xl space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('admin.newsletter.campaigns.index') }}"
           class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-bold text-slate-800">Buat Kampanye Newsletter</h1>
            <p class="text-xs text-slate-500 mt-0.5">Akan dikirim ke <strong>{{ $activeCount }}</strong> subscriber aktif</p>
        </div>
    </div>

    @if($activeCount === 0)
    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        <p class="text-sm text-amber-800">Belum ada subscriber aktif. Kampanye tidak akan terkirim ke siapa pun.</p>
    </div>
    @endif

    <form method="POST" action="{{ route('admin.newsletter.campaigns.store') }}" class="space-y-5">
        @csrf

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <span class="w-1 h-4 bg-primary rounded-full"></span>
                    Konten Newsletter
                </h2>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label for="subject" class="block text-xs font-semibold text-slate-700 mb-1.5">Subjek Email <span class="text-red-500">*</span></label>
                    <input type="text" name="subject" id="subject" value="{{ old('subject') }}" required maxlength="255"
                           placeholder="Contoh: Update Terbaru dari MonetraPay - Juni 2025">
                    @error('subject') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="content" class="block text-xs font-semibold text-slate-700 mb-1.5">Isi Newsletter <span class="text-red-500">*</span></label>
                    <textarea name="content" id="content" rows="15">{{ old('content') }}</textarea>
                    @error('content') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-xl hover:bg-primary-dark transition-colors shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan sebagai Draft
            </button>
            <a href="{{ route('admin.newsletter.campaigns.index') }}"
               class="px-5 py-2.5 bg-slate-100 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
