@extends('admin.layouts.app')

@section('title', 'Export & Import')
@section('page-title', 'Export & Import')

@section('content')
<div class="space-y-6">

    {{-- Statistics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium">Total Blog</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['blogs']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-secondary/10 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium">Total Halaman</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['pages']) }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-500/10 rounded-xl flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div>
                    <p class="text-sm text-slate-500 font-medium">Total Kategori</p>
                    <p class="text-2xl font-bold text-slate-800">{{ number_format($stats['categories']) }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Export Section --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Export Data
        </h2>
        <p class="text-sm text-slate-600 mb-4">Ekspor konten Anda ke file JSON untuk backup atau migrasi.</p>
        
        <div class="flex flex-wrap gap-3">
            <form method="POST" action="{{ route('admin.tools.export.blogs') }}" class="inline-block">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white rounded-lg font-semibold text-sm hover:bg-primary-dark transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Blog
                </button>
            </form>

            <form method="POST" action="{{ route('admin.tools.export.pages') }}" class="inline-block">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-secondary text-white rounded-lg font-semibold text-sm hover:bg-secondary-dark transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Export Halaman
                </button>
            </form>
        </div>
    </div>

    {{-- Import Section --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Import Blogs --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import Blog
            </h2>
            <form method="POST" action="{{ route('admin.tools.import.blogs') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">File JSON</label>
                    <input type="file" name="file" accept=".json" required class="block w-full text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Mode</label>
                    <select name="mode" required>
                        <option value="skip">Lewati jika slug sudah ada</option>
                        <option value="overwrite">Timpa jika slug sudah ada</option>
                    </select>
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-lg font-semibold text-sm hover:bg-primary-dark transition-colors">
                    Import Blog
                </button>
            </form>
        </div>

        {{-- Import Pages --}}
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h2 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import Halaman
            </h2>
            <form method="POST" action="{{ route('admin.tools.import.pages') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">File JSON</label>
                    <input type="file" name="file" accept=".json" required class="block w-full text-sm">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Mode</label>
                    <select name="mode" required>
                        <option value="skip">Lewati jika slug sudah ada</option>
                        <option value="overwrite">Timpa jika slug sudah ada</option>
                    </select>
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-secondary text-white rounded-lg font-semibold text-sm hover:bg-secondary-dark transition-colors">
                    Import Halaman
                </button>
            </form>
        </div>
    </div>

    {{-- Export History --}}
    @if($exports->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <h2 class="text-lg font-bold text-slate-800 mb-4">Riwayat Export</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-slate-200">
                    <tr>
                        <th class="text-left py-3 px-2 font-semibold text-slate-700">File</th>
                        <th class="text-left py-3 px-2 font-semibold text-slate-700">Ukuran</th>
                        <th class="text-left py-3 px-2 font-semibold text-slate-700">Tanggal</th>
                        <th class="text-right py-3 px-2 font-semibold text-slate-700">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($exports as $export)
                    <tr class="border-b border-slate-100 hover:bg-slate-50">
                        <td class="py-3 px-2 font-mono text-xs">{{ $export['name'] }}</td>
                        <td class="py-3 px-2 text-slate-600">{{ number_format($export['size'] / 1024, 2) }} KB</td>
                        <td class="py-3 px-2 text-slate-600">{{ \Carbon\Carbon::createFromTimestamp($export['date'])->format('d M Y H:i') }}</td>
                        <td class="py-3 px-2 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.tools.export.download', ['file' => $export['name']]) }}" class="text-primary hover:text-primary-dark font-semibold text-xs">
                                    Download
                                </a>
                                <form method="POST" action="{{ route('admin.tools.export.delete') }}" class="inline-block" onsubmit="return confirm('Hapus file ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="file" value="{{ $export['name'] }}">
                                    <button type="submit" class="text-red-500 hover:text-red-700 font-semibold text-xs">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection
