@extends('admin.layouts.app')

@section('title', 'Preview — ' . $page->title)

@section('content')
<div class="flex flex-col" style="height: calc(100vh - 4rem); margin: -1.5rem -1rem -1rem; overflow: hidden;">

    {{-- Top bar --}}
    <div class="bg-white border-b border-slate-200 px-4 py-2.5 flex items-center justify-between gap-3 shrink-0 shadow-sm">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.page-builder.edit', $page) }}"
               class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div class="min-w-0">
                <h1 class="text-sm font-bold text-slate-800 truncate">{{ $page->title }}</h1>
                <p class="text-[10px] text-slate-400 hidden sm:block">Preview</p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ url('/' . $page->slug) }}" target="_blank"
               class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                Buka di Tab Baru
            </a>
            <a href="{{ route('admin.page-builder.edit', $page) }}"
               class="px-4 py-1.5 text-xs font-bold text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>

    {{-- Iframe wrapper --}}
    <div class="flex-1 overflow-hidden bg-slate-200 p-4">
        <div class="w-full h-full bg-white rounded-xl shadow-lg overflow-hidden">
            <iframe
                src="{{ url('/' . $page->slug) }}"
                class="w-full h-full border-0"
                title="Preview: {{ $page->title }}"
                loading="lazy"
            ></iframe>
        </div>
    </div>

</div>
@endsection
