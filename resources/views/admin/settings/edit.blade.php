@extends('admin.layouts.app')

@section('title', 'Pengaturan - ' . ucfirst($group))
@section('page-title', 'Pengaturan')

@section('content')
@php
    $tabs = [
        'general'     => ['label' => 'Umum',        'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
        'homepage'    => ['label' => 'Homepage',    'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
        'social'      => ['label' => 'Social Media','icon' => 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z'],
        'seo'         => ['label' => 'SEO',          'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
        'email'       => ['label' => 'Email / SMTP', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
        'footer'      => ['label' => 'Footer',       'icon' => 'M4 6h16M4 10h16M4 14h16M4 18h16'],
        'maintenance' => ['label' => 'Maintenance',  'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
    ];
@endphp
<div class="space-y-5" x-data="{ dirty: false }">

    {{-- HEADER --}}
    <div>
        <h1 class="text-lg font-bold text-slate-800">Pengaturan Website</h1>
        <p class="text-xs text-slate-500 mt-0.5">Kelola konfigurasi global website Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

        {{-- SIDEBAR TABS --}}
        <aside class="lg:col-span-3">
            <nav class="bg-white border border-slate-200 rounded-xl p-2 shadow-sm sticky top-4">
                @foreach($tabs as $key => $tab)
                    <a href="{{ route('admin.settings.edit', $key) }}"
                       class="flex items-center gap-2.5 px-3 py-2.5 rounded-lg text-sm transition-colors mb-0.5
                              {{ $group === $key
                                  ? 'bg-primary/10 text-primary font-semibold'
                                  : 'text-slate-600 hover:bg-slate-50 hover:text-slate-800 font-medium' }}">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/></svg>
                        <span>{{ $tab['label'] }}</span>
                        @if($group === $key)
                            <svg class="w-3.5 h-3.5 ml-auto text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                        @endif
                    </a>
                @endforeach
            </nav>
        </aside>

        {{-- FORM CONTENT --}}
        <div class="lg:col-span-9">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm">
                <form method="POST" action="{{ route('admin.settings.update', $group) }}" enctype="multipart/form-data" @input="dirty = true" @change="dirty = true">
                    @csrf
                    @method('PUT')

                    <div class="px-6 py-5 border-b border-slate-100">
                        <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                            <span class="w-1 h-5 bg-primary rounded-full"></span>
                            {{ $tabs[$group]['label'] ?? ucfirst($group) }}
                        </h2>
                    </div>

                    <div class="p-6">
                        @if ($errors->any())
                            <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div>
                                        <p class="text-sm font-semibold text-red-800">Terdapat kesalahan pada input:</p>
                                        <ul class="mt-1 text-xs text-red-700 list-disc list-inside space-y-0.5">
                                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($group === 'general') @include('admin.settings.partials.general')
                        @elseif($group === 'homepage') @include('admin.settings.partials.homepage')
                        @elseif($group === 'social') @include('admin.settings.partials.social')
                        @elseif($group === 'seo') @include('admin.settings.partials.seo')
                        @elseif($group === 'email') @include('admin.settings.partials.email')
                        @elseif($group === 'footer') @include('admin.settings.partials.footer')
                        @elseif($group === 'maintenance') @include('admin.settings.partials.maintenance')
                        @endif
                    </div>

                    <div class="sticky bottom-0 z-20 px-6 py-4 bg-white/95 backdrop-blur border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-b-xl">
                        <p class="text-xs text-slate-500">
                            <span x-show="dirty" x-cloak class="font-semibold text-amber-600">Ada perubahan belum disimpan.</span>
                            <span x-show="!dirty">Pastikan perubahan sudah sesuai sebelum disimpan.</span>
                        </p>
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ url()->current() }}"
                               class="px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-100 transition-colors">
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-5 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors shadow-sm shadow-primary/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Simpan Pengaturan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
