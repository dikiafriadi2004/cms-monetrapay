<div class="space-y-5">

    {{-- Section: Identity --}}
    <div>
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Identitas Website</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="site_name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Website</label>
                <input type="text" name="site_name" id="site_name"
                       value="{{ old('site_name', $settings['site_name'] ?? '') }}"
                       maxlength="100" placeholder="Nama Situs">
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
                {{-- Preview logo yang sudah tersimpan --}}
                <div id="logo-preview-wrap" class="mb-3 p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-4 {{ empty($settings['logo']) ? 'hidden' : '' }}">
                    <div class="w-32 h-14 flex items-center justify-center bg-white rounded-lg border border-slate-200 overflow-hidden shrink-0">
                        <img id="logo-preview-img"
                             src="{{ !empty($settings['logo']) ? Storage::url($settings['logo']) : '' }}"
                             alt="Logo" class="max-h-12 max-w-[120px] w-auto object-contain">
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-700" id="logo-preview-name">
                            {{ !empty($settings['logo']) ? basename($settings['logo']) : '' }}
                        </p>
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ !empty($settings['logo']) ? 'Logo aktif — upload baru untuk mengganti' : 'Preview file baru' }}</p>
                    </div>
                </div>
                <input type="file" name="logo" id="logo" accept=".png,.jpg,.jpeg,.svg,.webp"
                       onchange="previewImage(this, 'logo-preview-img', 'logo-preview-wrap', 'logo-preview-name')">
                <p class="mt-1 text-[11px] text-slate-500">PNG, JPG, SVG, WebP. Maks 5MB. Rekomendasi: logo horizontal tinggi 40–56px.</p>
                @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Favicon --}}
            <div>
                <label for="favicon" class="block text-xs font-semibold text-slate-700 mb-1.5">Favicon</label>
                {{-- Preview favicon yang sudah tersimpan --}}
                <div id="favicon-preview-wrap" class="mb-3 p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-4 {{ empty($settings['favicon']) ? 'hidden' : '' }}">
                    <div class="w-14 h-14 flex items-center justify-center bg-white rounded-lg border border-slate-200 overflow-hidden shrink-0">
                        <img id="favicon-preview-img"
                             src="{{ !empty($settings['favicon']) ? Storage::url($settings['favicon']) : '' }}"
                             alt="Favicon" class="w-10 h-10 object-contain">
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-semibold text-slate-700" id="favicon-preview-name">
                            {{ !empty($settings['favicon']) ? basename($settings['favicon']) : '' }}
                        </p>
                        <p class="text-[11px] text-slate-400 mt-0.5">{{ !empty($settings['favicon']) ? 'Favicon aktif — upload baru untuk mengganti' : 'Preview file baru' }}</p>
                    </div>
                </div>
                <input type="file" name="favicon" id="favicon" accept=".ico,.png,.jpg,.jpeg,.svg"
                       onchange="previewImage(this, 'favicon-preview-img', 'favicon-preview-wrap', 'favicon-preview-name')">
                <p class="mt-1 text-[11px] text-slate-500">ICO, PNG, JPG, SVG. Maks 2MB. Rekomendasi: 512×512 PNG atau ICO square.</p>
                @error('favicon') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- JS untuk preview gambar sebelum upload --}}
        <script>
        function previewImage(input, imgId, wrapId, nameId) {
            const file = input.files[0];
            if (!file) return;
            const wrap = document.getElementById(wrapId);
            const img  = document.getElementById(imgId);
            const name = document.getElementById(nameId);
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                if (name) name.textContent = file.name;
                if (wrap) wrap.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
        </script>

    {{-- ═══ BRAND COLORS ═══ --}}
    <div class="pt-5 border-t border-slate-100">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Warna Brand</h3>
        <div class="border border-slate-200 rounded-xl overflow-hidden">
            <div class="px-5 py-3 bg-slate-50 border-b border-slate-100">
                <p class="text-sm font-bold text-slate-800">Warna Brand</p>
                <p class="text-xs text-slate-500 mt-0.5">Warna utama dan aksen yang digunakan di seluruh website</p>
            </div>
            <div class="p-5 space-y-4">
                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2">
                    ⚠ Setelah simpan, refresh browser (Ctrl+Shift+R) untuk melihat perubahan warna.
                </p>

                {{-- Primary Color --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Warna Utama (Primary)</label>
                    <div class="flex flex-wrap gap-2 mb-3">
                        @php
                        $primaryPresets = [
                            ['name' => 'Emerald',       'hex' => '#3D9E7A'],
                            ['name' => 'Ocean Blue',    'hex' => '#2563EB'],
                            ['name' => 'Royal Purple',  'hex' => '#7C3AED'],
                            ['name' => 'Sunset Orange', 'hex' => '#EA580C'],
                            ['name' => 'Rose Red',      'hex' => '#E11D48'],
                            ['name' => 'Slate Blue',    'hex' => '#0F4C81'],
                            ['name' => 'Forest Green',  'hex' => '#166534'],
                            ['name' => 'Golden',        'hex' => '#D97706'],
                        ];
                        $currentPrimary = old('primary_color', $settings['primary_color'] ?? '#3D9E7A');
                        @endphp
                        @foreach($primaryPresets as $preset)
                        <button type="button"
                            onclick="document.getElementById('primary_color').value='{{$preset['hex']}}'; document.getElementById('primary_preview').style.backgroundColor='{{$preset['hex']}}';"
                            title="{{$preset['name']}}"
                            class="w-8 h-8 rounded-lg border-2 transition-all hover:scale-110 {{$currentPrimary === $preset['hex'] ? 'border-slate-800 scale-110' : 'border-transparent'}}"
                            style="background-color: {{$preset['hex']}};">
                        </button>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-3">
                        <div id="primary_preview" class="w-10 h-10 rounded-lg border border-slate-200 shrink-0" style="background-color: {{$currentPrimary}};"></div>
                        <input type="text" name="primary_color" id="primary_color"
                               value="{{$currentPrimary}}"
                               placeholder="#3D9E7A"
                               maxlength="7"
                               oninput="document.getElementById('primary_preview').style.backgroundColor=this.value"
                               class="w-32 font-mono text-sm">
                        <span class="text-xs text-slate-500">Format: #RRGGBB</span>
                    </div>
                </div>

                {{-- Secondary Color --}}
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-2">Warna Aksen (Secondary)</label>
                    <div class="flex flex-wrap gap-2 mb-3">
                        @php
                        $secondaryPresets = [
                            ['name' => 'Amber',       'hex' => '#F59E0B'],
                            ['name' => 'Sky Blue',    'hex' => '#0EA5E9'],
                            ['name' => 'Lime Green',  'hex' => '#65A30D'],
                            ['name' => 'Pink',        'hex' => '#EC4899'],
                            ['name' => 'Cyan',        'hex' => '#06B6D4'],
                            ['name' => 'Indigo',      'hex' => '#4F46E5'],
                        ];
                        $currentSecondary = old('secondary_color', $settings['secondary_color'] ?? '#F59E0B');
                        @endphp
                        @foreach($secondaryPresets as $preset)
                        <button type="button"
                            onclick="document.getElementById('secondary_color').value='{{$preset['hex']}}'; document.getElementById('secondary_preview').style.backgroundColor='{{$preset['hex']}}';"
                            title="{{$preset['name']}}"
                            class="w-8 h-8 rounded-lg border-2 transition-all hover:scale-110 {{$currentSecondary === $preset['hex'] ? 'border-slate-800 scale-110' : 'border-transparent'}}"
                            style="background-color: {{$preset['hex']}};">
                        </button>
                        @endforeach
                    </div>
                    <div class="flex items-center gap-3">
                        <div id="secondary_preview" class="w-10 h-10 rounded-lg border border-slate-200 shrink-0" style="background-color: {{$currentSecondary}};"></div>
                        <input type="text" name="secondary_color" id="secondary_color"
                               value="{{$currentSecondary}}"
                               placeholder="#F59E0B"
                               maxlength="7"
                               oninput="document.getElementById('secondary_preview').style.backgroundColor=this.value"
                               class="w-32 font-mono text-sm">
                        <span class="text-xs text-slate-500">Format: #RRGGBB</span>
                    </div>
                </div>
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
                          placeholder="Halo, saya ingin tahu lebih lanjut...">{{ old('whatsapp_text', $settings['whatsapp_text'] ?? 'Halo, saya ingin tahu lebih lanjut tentang layanan kami') }}</textarea>
                <p class="mt-1 text-[11px] text-slate-400">Pesan yang sudah terisi saat pelanggan klik tombol. Maks 200 karakter.</p>
                @error('whatsapp_text') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>
</div>
