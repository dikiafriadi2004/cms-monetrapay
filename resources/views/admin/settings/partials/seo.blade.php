<div class="space-y-5">
    <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg flex items-start gap-2">
        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="text-xs text-blue-700">Konfigurasi SEO global yang digunakan sebagai default jika halaman tidak memiliki meta tag sendiri.</p>
    </div>

    <div>
        <label for="meta_title_default" class="block text-xs font-semibold text-slate-700 mb-1.5">Meta Title Default</label>
        <input type="text" name="meta_title_default" id="meta_title_default"
               value="{{ old('meta_title_default', $settings['meta_title_default'] ?? '') }}"
               maxlength="70" placeholder="Judul default untuk mesin pencari">
        <p class="mt-1 text-[11px] text-slate-500">Maksimal 70 karakter. Digunakan jika halaman tidak memiliki meta title sendiri.</p>
        @error('meta_title_default') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="meta_description_default" class="block text-xs font-semibold text-slate-700 mb-1.5">Meta Description Default</label>
        <textarea name="meta_description_default" id="meta_description_default" rows="3" maxlength="160"
                  placeholder="Deskripsi default untuk mesin pencari">{{ old('meta_description_default', $settings['meta_description_default'] ?? '') }}</textarea>
        <p class="mt-1 text-[11px] text-slate-500">Maksimal 160 karakter</p>
        @error('meta_description_default') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="pt-5 border-t border-slate-100">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Google Analytics</h3>
        <div>
            <label for="ga_measurement_id" class="block text-xs font-semibold text-slate-700 mb-1.5">Measurement ID</label>
            <div class="relative">
                <input type="text" name="ga_measurement_id" id="ga_measurement_id"
                       value="{{ old('ga_measurement_id', $settings['ga_measurement_id'] ?? '') }}"
                       placeholder="G-XXXXXXXXXX">
            </div>
            <p class="mt-1 text-[11px] text-slate-500">Format: G- diikuti karakter alfanumerik (contoh: G-ABC123DEF4)</p>
            @error('ga_measurement_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <div class="pt-5 border-t border-slate-100">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Robots.txt</h3>
        <div>
            <label for="robots_txt" class="block text-xs font-semibold text-slate-700 mb-1.5">Konten Robots.txt</label>
            <textarea name="robots_txt" id="robots_txt" rows="8" maxlength="5000"
                      style="font-family: ui-monospace, 'Cascadia Code', monospace; font-size: 0.8125rem;"
                      placeholder="User-agent: *&#10;Allow: /&#10;Disallow: /admin/&#10;Sitemap: {{ url('/sitemap.xml') }}">{{ old('robots_txt', $settings['robots_txt'] ?? '') }}</textarea>
            <p class="mt-1.5 text-[11px] text-slate-500">
                Kosongkan untuk pakai default. Akan diserve di
                <a href="{{ url('/robots.txt') }}" target="_blank" class="text-primary font-semibold underline">{{ url('/robots.txt') }}</a>
            </p>
            @error('robots_txt') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>
</div>
