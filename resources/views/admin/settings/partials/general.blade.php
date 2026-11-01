<div class="space-y-5">

    {{-- Section: Identity --}}
    <div>
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Identitas Website</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="site_name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Website</label>
                <input type="text" name="site_name" id="site_name"
                       value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                       maxlength="100" placeholder="MonetraPay">
                <p class="mt-1 text-[11px] text-slate-500">Maksimal 100 karakter</p>
                @error('site_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="tagline" class="block text-xs font-semibold text-slate-700 mb-1.5">Tagline</label>
                <textarea name="tagline" id="tagline" rows="3" maxlength="255" placeholder="Solusi pembayaran digital untuk agen modern">{{ old('tagline', $settings['tagline'] ?? '') }}</textarea>
                <p class="mt-1 text-[11px] text-slate-500">Deskripsi singkat website. Idealnya 80–140 karakter agar rapi di footer dan SEO.</p>
                @error('tagline') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Section: Branding --}}
    <div class="pt-5 border-t border-slate-100">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Branding</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            {{-- Logo --}}
            <div>
                <label for="logo" class="block text-xs font-semibold text-slate-700 mb-1.5">Logo Website</label>
                @if(!empty($settings['logo']))
                    <div class="mb-3 p-3 bg-slate-50 border border-slate-200 rounded-lg flex items-center gap-3">
                        <img src="{{ Storage::url($settings['logo']) }}" alt="Logo" class="h-12 w-auto max-w-[120px] object-contain">
                        <span class="text-xs text-slate-500 ml-auto">Logo aktif — upload baru untuk mengganti</span>
                    </div>
                @endif
                <input type="file" name="logo" id="logo" accept=".png,.jpg,.jpeg,.svg,.webp">
                <p class="mt-1 text-[11px] text-slate-500">PNG, JPG, SVG, WebP. Maks 5MB. Rekomendasi: logo horizontal tinggi 40–56px; gunakan SVG/PNG transparan jika tersedia.</p>
                @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Favicon --}}
            <div>
                <label for="favicon" class="block text-xs font-semibold text-slate-700 mb-1.5">Favicon</label>
                @if(!empty($settings['favicon']))
                    <div class="mb-3 p-3 bg-slate-50 border border-slate-200 rounded-lg flex items-center gap-3">
                        <img src="{{ Storage::url($settings['favicon']) }}" alt="Favicon" class="h-10 w-10 object-contain rounded border border-slate-200 bg-white p-1">
                        <span class="text-xs text-slate-500 ml-auto">Favicon aktif — upload baru untuk mengganti</span>
                    </div>
                @endif
                <input type="file" name="favicon" id="favicon" accept=".ico,.png,.jpg,.jpeg,.svg">
                <p class="mt-1 text-[11px] text-slate-500">ICO, PNG, JPG, SVG. Maks 2MB. Rekomendasi: 512×512 PNG atau ICO square agar tajam di browser.</p>
                @error('favicon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Section: Contact --}}
    <div class="pt-5 border-t border-slate-100">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Kontak</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="contact_email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email Kontak</label>
                <input type="email" name="contact_email" id="contact_email"
                       value="{{ old('contact_email', $settings['contact_email'] ?? '') }}"
                       placeholder="admin@monetrapay.com">
                @error('contact_email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="contact_phone" class="block text-xs font-semibold text-slate-700 mb-1.5">Nomor Telepon</label>
                <input type="text" name="contact_phone" id="contact_phone"
                       value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}"
                       maxlength="20" placeholder="+62 812 3456 7890">
                @error('contact_phone') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Google Maps Koordinat --}}
        <div class="mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl">
            <h4 class="text-xs font-bold text-slate-600 uppercase tracking-wider mb-3">📍 Lokasi Kantor (Google Maps)</h4>
            <p class="text-[11px] text-slate-400 mb-3">Masukkan koordinat kantor untuk ditampilkan di halaman Kontak. Cari koordinat di <a href="https://maps.google.com" target="_blank" class="text-primary hover:underline">Google Maps</a> → klik kanan lokasi → salin koordinat.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                <div>
                    <label for="maps_lat" class="block text-xs font-semibold text-slate-700 mb-1.5">Latitude</label>
                    <input type="text" name="maps_lat" id="maps_lat"
                           value="{{ old('maps_lat', $settings['maps_lat'] ?? '-6.2088') }}"
                           placeholder="-6.2088">
                    <p class="mt-1 text-[11px] text-slate-400">Contoh: -6.2088</p>
                </div>
                <div>
                    <label for="maps_lng" class="block text-xs font-semibold text-slate-700 mb-1.5">Longitude</label>
                    <input type="text" name="maps_lng" id="maps_lng"
                           value="{{ old('maps_lng', $settings['maps_lng'] ?? '106.8456') }}"
                           placeholder="106.8456">
                    <p class="mt-1 text-[11px] text-slate-400">Contoh: 106.8456</p>
                </div>
                <div>
                    <label for="maps_zoom" class="block text-xs font-semibold text-slate-700 mb-1.5">Zoom Level</label>
                    <select name="maps_zoom" id="maps_zoom">
                        @foreach([12=>'12 - Kota',13=>'13',14=>'14',15=>'15 - Default',16=>'16',17=>'17 - Detail',18=>'18 - Sangat Detail'] as $v=>$l)
                            <option value="{{ $v }}" {{ old('maps_zoom', $settings['maps_zoom'] ?? '15') == $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <label for="office_address" class="block text-xs font-semibold text-slate-700 mb-1.5">Alamat Kantor (teks)</label>
                <input type="text" name="office_address" id="office_address"
                       value="{{ old('office_address', $settings['office_address'] ?? '') }}"
                       placeholder="Jl. Sudirman No. 1, Jakarta Selatan 12190">
            </div>
        </div>
    </div>

    {{-- Section: Navbar CTA --}}
    <div class="pt-5 border-t border-slate-100">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tombol CTA Navbar</h3>
        <p class="text-[11px] text-slate-400 mb-3">Tombol di pojok kanan navbar yang tampil di semua halaman. Kosongkan untuk menyembunyikan tombol.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="navbar_cta_text" class="block text-xs font-semibold text-slate-700 mb-1.5">Teks Tombol</label>
                <input type="text" name="navbar_cta_text" id="navbar_cta_text"
                       value="{{ old('navbar_cta_text', $settings['navbar_cta_text'] ?? 'Daftar Jadi Agen') }}"
                       maxlength="50" placeholder="Daftar Jadi Agen">
                @error('navbar_cta_text') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="navbar_cta_url" class="block text-xs font-semibold text-slate-700 mb-1.5">Link Tombol</label>
                <input type="text" name="navbar_cta_url" id="navbar_cta_url"
                       value="{{ old('navbar_cta_url', $settings['navbar_cta_url'] ?? '/kontak') }}"
                       maxlength="255" placeholder="/kontak atau https://...">
                @error('navbar_cta_url') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Section: WhatsApp Floating Button --}}
    <div class="pt-5 border-t border-slate-100">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Tombol WhatsApp Floating</h3>
        <p class="text-[11px] text-slate-400 mb-3">Tombol WhatsApp yang mengambang di pojok kanan bawah semua halaman frontend. Sangat efektif untuk bisnis di Indonesia.</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="md:col-span-2 flex items-center gap-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="whatsapp_float_enabled" value="1" id="whatsapp_float_enabled"
                           {{ old('whatsapp_float_enabled', $settings['whatsapp_float_enabled'] ?? '1') == '1' ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-10 h-6 bg-slate-200 peer-checked:bg-green-500 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all"></div>
                    <span class="ms-3 text-xs font-semibold text-slate-700">Tampilkan tombol WhatsApp</span>
                </label>
                <span class="flex items-center gap-1.5 text-xs text-slate-400">
                    <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span>
                    Aktif di semua halaman frontend
                </span>
            </div>
            <div>
                <label for="whatsapp_number" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Nomor WhatsApp
                    <span class="text-slate-400 font-normal ml-1">(format internasional tanpa + atau spasi)</span>
                </label>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-400 font-mono bg-slate-50 px-3 py-2.5 border border-slate-200 rounded-lg shrink-0">+</span>
                    <input type="text" name="whatsapp_number" id="whatsapp_number"
                           value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}"
                           maxlength="20" placeholder="6281234567890"
                           class="flex-1">
                </div>
                <p class="mt-1 text-[11px] text-slate-400">Contoh: 6281234567890 (62 = kode negara Indonesia, tanpa 0 di depan)</p>
                @error('whatsapp_number') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="whatsapp_text" class="block text-xs font-semibold text-slate-700 mb-1.5">Pesan Default</label>
                <textarea name="whatsapp_text" id="whatsapp_text" rows="2" maxlength="200"
                          placeholder="Halo, saya ingin tahu lebih lanjut...">{{ old('whatsapp_text', $settings['whatsapp_text'] ?? 'Halo, saya ingin tahu lebih lanjut tentang layanan MonetraPay') }}</textarea>
                <p class="mt-1 text-[11px] text-slate-400">Pesan yang sudah terisi saat pelanggan klik tombol. Maks 200 karakter.</p>
                @error('whatsapp_text') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>
