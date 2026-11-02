<div class="space-y-5">
    <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-start gap-2">
        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-xs text-blue-700">Konfigurasi konten yang ditampilkan di bagian footer website.</p>
    </div>

    <div>
        <label for="copyright_text" class="block text-xs font-semibold text-slate-700 mb-1.5">Teks Copyright</label>
        <input type="text" name="copyright_text" id="copyright_text"
               value="{{ old('copyright_text', $settings['copyright_text'] ?? '') }}"
               maxlength="255" placeholder="© 2024 Nama Brand. All rights reserved.">
        <p class="mt-1 text-[11px] text-slate-500">Maksimal 255 karakter</p>
        @error('copyright_text') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="additional_content" class="block text-xs font-semibold text-slate-700 mb-1.5">Konten Tambahan</label>
        <textarea name="additional_content" id="additional_content" rows="5" maxlength="1000"
                  placeholder="Konten tambahan yang ditampilkan di footer (HTML diperbolehkan)">{{ old('additional_content', $settings['additional_content'] ?? '') }}</textarea>
        <p class="mt-1 text-[11px] text-slate-500">Maksimal 1000 karakter. HTML diperbolehkan.</p>
        @error('additional_content') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>
</div>
