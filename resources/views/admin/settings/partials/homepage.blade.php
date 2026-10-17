<div class="space-y-6">

    {{-- ═══ HERO SECTION ═══ --}}
    <div class="border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-3 bg-gradient-to-r from-primary-light to-white border-b border-slate-100 flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Hero Section</h3>
                <p class="text-[11px] text-slate-500">Tampilan utama landing page</p>
            </div>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <label for="hero_title" class="block text-xs font-semibold text-slate-700 mb-1.5">Judul Hero</label>
                <input type="text" name="hero_title" id="hero_title"
                       value="{{ old('hero_title', $settings['hero_title'] ?? '') }}" maxlength="255"
                       placeholder="Solusi Lengkap untuk Pulsa, PPOB, Top-Up Game & E-Money">
                @error('hero_title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="hero_subtitle" class="block text-xs font-semibold text-slate-700 mb-1.5">Subtitle Hero</label>
                <textarea name="hero_subtitle" id="hero_subtitle" rows="3" maxlength="500"
                          placeholder="Deskripsi singkat untuk hero section">{{ old('hero_subtitle', $settings['hero_subtitle'] ?? '') }}</textarea>
                @error('hero_subtitle') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="hero_cta_text" class="block text-xs font-semibold text-slate-700 mb-1.5">Teks Tombol CTA</label>
                    <input type="text" name="hero_cta_text" id="hero_cta_text"
                           value="{{ old('hero_cta_text', $settings['hero_cta_text'] ?? '') }}" maxlength="50"
                           placeholder="Mulai Sekarang">
                    @error('hero_cta_text') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="hero_cta_url" class="block text-xs font-semibold text-slate-700 mb-1.5">URL Tombol CTA</label>
                    <input type="text" name="hero_cta_url" id="hero_cta_url"
                           value="{{ old('hero_cta_url', $settings['hero_cta_url'] ?? '') }}" maxlength="255"
                           placeholder="/register">
                    @error('hero_cta_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            <div>
                <label for="hero_image" class="block text-xs font-semibold text-slate-700 mb-1.5">Gambar Hero</label>
                @if(!empty($settings['hero_image']))
                    <div class="mb-3 p-3 bg-slate-50 border border-slate-200 rounded-lg flex items-center gap-3">
                        <img src="{{ Storage::url($settings['hero_image']) }}" alt="Hero" class="h-20 w-auto object-contain">
                        <span class="text-xs text-slate-500 ml-auto">Gambar aktif — upload baru untuk mengganti</span>
                    </div>
                @endif
                <input type="file" name="hero_image" id="hero_image" accept=".png,.jpg,.jpeg,.webp">
                <p class="mt-1 text-[11px] text-slate-500">PNG, JPG, WebP. Maks 5MB. Upload baru otomatis mengganti gambar lama.</p>
                @error('hero_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- ═══ SERVICES (4 cards) ═══ --}}
    <div class="border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-3 bg-gradient-to-r from-secondary-light to-white border-b border-slate-100 flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-secondary/10 flex items-center justify-center">
                <svg class="w-4 h-4 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">Layanan</h3>
                <p class="text-[11px] text-slate-500">4 kartu layanan unggulan</p>
            </div>
        </div>
        <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
            @for($i = 1; $i <= 4; $i++)
            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-primary text-white text-xs font-bold">{{ $i }}</span>
                    <p class="text-sm font-bold text-slate-700">Layanan {{ $i }}</p>
                </div>
                <div>
                    <label for="service_{{ $i }}_title" class="block text-xs font-semibold text-slate-700 mb-1.5">Judul</label>
                    <input type="text" name="service_{{ $i }}_title" id="service_{{ $i }}_title"
                           value="{{ old("service_{$i}_title", $settings["service_{$i}_title"] ?? '') }}"
                           maxlength="100" placeholder="Nama layanan">
                </div>
                <div>
                    <label for="service_{{ $i }}_description" class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi</label>
                    <input type="text" name="service_{{ $i }}_description" id="service_{{ $i }}_description"
                           value="{{ old("service_{$i}_description", $settings["service_{$i}_description"] ?? '') }}"
                           maxlength="255" placeholder="Deskripsi singkat">
                </div>
            </div>
            @endfor
        </div>
    </div>

    {{-- ═══ ABOUT SECTION ═══ --}}
    <div class="border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-3 bg-gradient-to-r from-purple-50 to-white border-b border-slate-100 flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">About Section</h3>
                <p class="text-[11px] text-slate-500">Tentang perusahaan/platform</p>
            </div>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <label for="about_title" class="block text-xs font-semibold text-slate-700 mb-1.5">Judul About</label>
                <input type="text" name="about_title" id="about_title"
                       value="{{ old('about_title', $settings['about_title'] ?? '') }}" maxlength="255"
                       placeholder="Tentang MonetraPay">
                @error('about_title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="about_description" class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi About</label>
                <textarea name="about_description" id="about_description" rows="4" maxlength="1000"
                          placeholder="Deskripsi tentang perusahaan/platform Anda">{{ old('about_description', $settings['about_description'] ?? '') }}</textarea>
                @error('about_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="about_image" class="block text-xs font-semibold text-slate-700 mb-1.5">Gambar About</label>
                @if(!empty($settings['about_image']))
                    <div class="mb-3 p-3 bg-slate-50 border border-slate-200 rounded-lg flex items-center gap-3">
                        <img src="{{ Storage::url($settings['about_image']) }}" alt="About" class="h-20 w-auto object-contain">
                        <span class="text-xs text-slate-500 ml-auto">Gambar aktif — upload baru untuk mengganti</span>
                    </div>
                @endif
                <input type="file" name="about_image" id="about_image" accept=".png,.jpg,.jpeg,.webp">
                <p class="mt-1 text-[11px] text-slate-500">PNG, JPG, WebP. Maks 5MB. Upload baru otomatis mengganti gambar lama.</p>
                @error('about_image') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- ═══ CTA SECTION ═══ --}}
    <div class="border border-slate-200 rounded-xl overflow-hidden">
        <div class="px-5 py-3 bg-gradient-to-r from-amber-50 to-white border-b border-slate-100 flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800">CTA Section</h3>
                <p class="text-[11px] text-slate-500">Call-to-action di akhir landing page</p>
            </div>
        </div>
        <div class="p-5 space-y-4">
            <div>
                <label for="cta_title" class="block text-xs font-semibold text-slate-700 mb-1.5">Judul CTA</label>
                <input type="text" name="cta_title" id="cta_title"
                       value="{{ old('cta_title', $settings['cta_title'] ?? '') }}" maxlength="255"
                       placeholder="Siap Memulai Transaksi Digital?">
                @error('cta_title') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="cta_description" class="block text-xs font-semibold text-slate-700 mb-1.5">Deskripsi CTA</label>
                <textarea name="cta_description" id="cta_description" rows="3" maxlength="500"
                          placeholder="Deskripsi ajakan untuk pengguna">{{ old('cta_description', $settings['cta_description'] ?? '') }}</textarea>
                @error('cta_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="cta_button_text" class="block text-xs font-semibold text-slate-700 mb-1.5">Teks Tombol</label>
                    <input type="text" name="cta_button_text" id="cta_button_text"
                           value="{{ old('cta_button_text', $settings['cta_button_text'] ?? '') }}" maxlength="50"
                           placeholder="Daftar Sekarang">
                    @error('cta_button_text') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="cta_button_url" class="block text-xs font-semibold text-slate-700 mb-1.5">URL Tombol</label>
                    <input type="text" name="cta_button_url" id="cta_button_url"
                           value="{{ old('cta_button_url', $settings['cta_button_url'] ?? '') }}" maxlength="255"
                           placeholder="/register">
                    @error('cta_button_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>
</div>
