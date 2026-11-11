@extends('admin.layouts.app')

@section('title', 'Page Builder - Halaman dengan Builder')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Page Builder</h1>
            <p class="text-sm text-slate-600 mt-1">Kelola halaman yang menggunakan Page Builder</p>
        </div>
        <a href="{{ route('admin.pages.create') }}"
           class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Buat Halaman Baru
        </a>
    </div>

    {{-- Info Alert --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div class="flex-1">
            <h3 class="text-sm font-semibold text-blue-900">Cara Menggunakan Page Builder</h3>
            <ol class="mt-2 text-sm text-blue-800 space-y-1 list-decimal list-inside">
                <li>Buat halaman baru atau edit halaman yang ada</li>
                <li>Toggle <strong>"Gunakan Page Builder"</strong> menjadi ON</li>
                <li>Simpan halaman</li>
                <li>Klik tombol <strong>"Edit Komponen"</strong> untuk menambah/edit komponen</li>
                <li>Pilih komponen yang ingin ditambahkan (Hero, Text, Contact Form, dll)</li>
                <li>Preview hasilnya di frontend</li>
            </ol>
        </div>
    </div>

    {{-- Pages with Page Builder --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800">Halaman dengan Page Builder</h2>
        </div>

        @php
            $builderPages = \App\Models\Page::where('use_page_builder', true)
                ->with('components')
                ->latest()
                ->get();
        @endphp

        @if($builderPages->isEmpty())
            <div class="px-6 py-12 text-center">
                <svg class="w-16 h-16 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Belum ada halaman dengan Page Builder</h3>
                <p class="mt-2 text-sm text-slate-500 max-w-md mx-auto">Buat halaman baru dan aktifkan Page Builder untuk mulai membuat halaman dengan drag & drop components</p>
                <a href="{{ route('admin.pages.create') }}"
                   class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Buat Halaman Pertama
                </a>
            </div>
        @else
            <div class="divide-y divide-slate-200">
                @foreach($builderPages as $page)
                    <div class="px-6 py-4 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3">
                                    <h3 class="text-base font-semibold text-slate-800 truncate">
                                        {{ $page->title }}
                                    </h3>
                                    @if($page->status === 'published')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                            Draft
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-1 flex items-center gap-4 text-sm text-slate-500">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                                        {{ $page->components->count() }} komponen
                                    </span>
                                    <span>•</span>
                                    <span>/{{ $page->slug }}</span>
                                    <span>•</span>
                                    <span>{{ $page->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <a href="{{ url($page->slug) }}" target="_blank"
                                   class="px-3 py-1.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors inline-flex items-center gap-1.5"
                                   title="Lihat di frontend">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Preview
                                </a>
                                <a href="{{ route('admin.page-builder.edit', $page) }}"
                                   class="px-3 py-1.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit Komponen
                                </a>
                                <a href="{{ route('admin.pages.edit', $page) }}"
                                   class="px-3 py-1.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors inline-flex items-center gap-1.5"
                                   title="Edit halaman">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Settings
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Available Components --}}
    <div class="bg-white rounded-lg shadow-sm border border-slate-200">
        <div class="px-6 py-4 border-b border-slate-200">
            <h2 class="text-lg font-semibold text-slate-800">Komponen yang Tersedia</h2>
            <p class="text-sm text-slate-600 mt-1">15 komponen siap pakai untuk membuat halaman Anda</p>
        </div>

        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
            @php
                $components = [
                    ['name' => 'Hero', 'icon' => '🚀', 'desc' => 'Banner besar dengan CTA'],
                    ['name' => 'Text', 'icon' => '📝', 'desc' => 'Rich text content'],
                    ['name' => 'Contact Form', 'icon' => '📧', 'desc' => 'Form kontak'],
                    ['name' => 'Features', 'icon' => '✨', 'desc' => 'Grid fitur dengan icon'],
                    ['name' => 'CTA', 'icon' => '🎯', 'desc' => 'Call-to-action'],
                    ['name' => 'Testimonials', 'icon' => '💬', 'desc' => 'Review customer'],
                    ['name' => 'Team', 'icon' => '👥', 'desc' => 'Team members'],
                    ['name' => 'Gallery', 'icon' => '🖼️', 'desc' => 'Image gallery'],
                    ['name' => 'Video', 'icon' => '🎬', 'desc' => 'Embed video'],
                    ['name' => 'Pricing', 'icon' => '💰', 'desc' => 'Tabel harga'],
                    ['name' => 'Social', 'icon' => '🔗', 'desc' => 'Social media links'],
                    ['name' => 'Newsletter', 'icon' => '📬', 'desc' => 'Email signup'],
                    ['name' => 'FAQ', 'icon' => '❓', 'desc' => 'Accordion Q&A'],
                    ['name' => 'Map', 'icon' => '📍', 'desc' => 'Google Maps'],
                    ['name' => 'Custom HTML', 'icon' => '💻', 'desc' => 'Raw HTML'],
                ];
            @endphp

            @foreach($components as $component)
                <div class="bg-slate-50 rounded-lg p-4 text-center hover:bg-slate-100 transition-colors">
                    <div class="text-3xl mb-2">{{ $component['icon'] }}</div>
                    <h3 class="font-semibold text-slate-800 text-sm">{{ $component['name'] }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ $component['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
