@extends('admin.layouts.app')

@section('title', 'Tambah Slot Iklan')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.ads.index') }}" class="text-slate-500 hover:text-slate-700">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold text-slate-800">Tambah Slot Iklan</h1>
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('admin.ads.store') }}" enctype="multipart/form-data"
          x-data="{
              type: '{{ old('type', 'adsense') }}',
              position: '{{ old('position', 'header') }}'
          }"
          class="space-y-6">
        @csrf

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-6">
            {{-- Name --}}
            <div>
                <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Nama Slot Iklan <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                       placeholder="Contoh: Banner Header Utama"
                       class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                       maxlength="100" required>
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Type --}}
            <div>
                <label for="type" class="block text-sm font-medium text-slate-700 mb-1">Tipe Iklan <span class="text-red-500">*</span></label>
                <select name="type" id="type" x-model="type"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('type') border-red-500 @enderror"
                        required>
                    <option value="adsense">Google AdSense</option>
                    <option value="adstera">Adstera</option>
                    <option value="manual">Manual (HTML/Gambar)</option>
                </select>
                @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Code (for adsense/adstera) --}}
            <div x-show="type === 'adsense' || type === 'adstera'">
                <label for="code" class="block text-sm font-medium text-slate-700 mb-1">Kode Iklan <span class="text-red-500">*</span></label>
                <textarea name="code" id="code" rows="6"
                          placeholder="Paste kode script iklan di sini..."
                          class="w-full rounded-lg border-slate-300 text-sm font-mono focus:border-blue-500 focus:ring-blue-500 @error('code') border-red-500 @enderror"
                          maxlength="5000">{{ old('code') }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Maksimal 5.000 karakter</p>
                @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Code for manual (optional HTML) --}}
            <div x-show="type === 'manual'">
                <label for="code_manual" class="block text-sm font-medium text-slate-700 mb-1">Kode HTML (opsional)</label>
                <textarea name="code" id="code_manual" rows="4"
                          placeholder="Custom HTML untuk iklan manual..."
                          class="w-full rounded-lg border-slate-300 text-sm font-mono focus:border-blue-500 focus:ring-blue-500"
                          maxlength="5000">{{ old('code') }}</textarea>
                <p class="mt-1 text-xs text-slate-500">Isi kode HTML atau upload gambar di bawah</p>
            </div>

            {{-- Image (for manual type) --}}
            <div x-show="type === 'manual'">
                <label for="image" class="block text-sm font-medium text-slate-700 mb-1">Gambar Iklan</label>
                <input type="file" name="image" id="image" accept="image/jpeg,image/png,image/gif"
                       class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 @error('image') border-red-500 @enderror">
                <p class="mt-1 text-xs text-slate-500">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Position --}}
            <div>
                <label for="position" class="block text-sm font-medium text-slate-700 mb-1">Posisi Penempatan <span class="text-red-500">*</span></label>
                <select name="position" id="position" x-model="position"
                        class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('position') border-red-500 @enderror"
                        required>
                    <option value="header">Header</option>
                    <option value="sidebar">Sidebar</option>
                    <option value="in_content">Dalam Konten (setelah paragraf ke-N)</option>
                    <option value="auto_insert">Auto Insert (setiap N paragraf)</option>
                    <option value="footer">Footer</option>
                </select>
                @error('position')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Paragraph Number (for in_content) --}}
            <div x-show="position === 'in_content'">
                <label for="paragraph_number" class="block text-sm font-medium text-slate-700 mb-1">Setelah Paragraf ke- <span class="text-red-500">*</span></label>
                <input type="number" name="paragraph_number" id="paragraph_number" value="{{ old('paragraph_number') }}"
                       min="1" max="20" placeholder="1-20"
                       class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('paragraph_number') border-red-500 @enderror">
                <p class="mt-1 text-xs text-slate-500">Iklan akan ditampilkan setelah paragraf ke-N (1 sampai 20)</p>
                @error('paragraph_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Interval (for auto_insert) --}}
            <div x-show="position === 'auto_insert'">
                <label for="interval" class="block text-sm font-medium text-slate-700 mb-1">Interval Paragraf <span class="text-red-500">*</span></label>
                <input type="number" name="interval" id="interval" value="{{ old('interval') }}"
                       min="2" max="10" placeholder="2-10"
                       class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('interval') border-red-500 @enderror">
                <p class="mt-1 text-xs text-slate-500">Iklan akan disisipkan setiap N paragraf (2 sampai 10)</p>
                @error('interval')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Scheduling --}}
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 space-y-6">
            <h2 class="text-lg font-semibold text-slate-800">Penjadwalan</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Start Date --}}
                <div>
                    <label for="start_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Mulai</label>
                    <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                           class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-slate-500">Kosongkan jika langsung aktif</p>
                    @error('start_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- End Date --}}
                <div>
                    <label for="end_date" class="block text-sm font-medium text-slate-700 mb-1">Tanggal Berakhir</label>
                    <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                           class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-slate-500">Kosongkan jika tanpa batas waktu</p>
                    @error('end_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}
                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                    <span class="text-sm font-medium text-slate-700">Aktifkan slot iklan</span>
                </label>
                <p class="mt-1 text-xs text-slate-500 ml-8">Iklan akan langsung ditampilkan di frontend jika diaktifkan</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-3">
            <button type="submit"
                    class="px-6 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                Simpan Slot Iklan
            </button>
            <a href="{{ route('admin.ads.index') }}"
               class="px-6 py-2 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
