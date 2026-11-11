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

        {{-- GA4 Property ID --}}
        <div class="mt-5">
            <label for="ga_property_id" class="block text-xs font-semibold text-slate-700 mb-1.5">GA4 Property ID</label>
            <input type="text" name="ga_property_id" id="ga_property_id"
                   value="{{old('ga_property_id', $settings['ga_property_id'] ?? '')}}"
                   placeholder="properties/123456789">
            <p class="mt-1 text-[11px] text-slate-500">Format: properties/ diikuti angka. Temukan di Google Analytics &rarr; Admin &rarr; Property Settings</p>
            @error('ga_property_id') <p class="mt-1 text-xs text-red-600">{{$message}}</p> @enderror
        </div>

        {{-- GA4 Service Account Credentials JSON --}}
        <div class="mt-5">
            <label for="ga_credentials_json" class="block text-xs font-semibold text-slate-700 mb-1.5">Service Account Credentials JSON</label>
            <textarea name="ga_credentials_json" id="ga_credentials_json" rows="8"
                      placeholder='{"type": "service_account", "project_id": "...", ...}'
                      class="font-mono text-xs">{{old('ga_credentials_json', isset($settings['ga_credentials_json']) && $settings['ga_credentials_json'] ? '(tersimpan — paste ulang untuk mengubah)' : '')}}</textarea>
            <p class="mt-1 text-[11px] text-slate-500">Paste isi file JSON Service Account dari Google Cloud Console. Credentials disimpan terenkripsi.</p>
            @if(!empty($settings['ga_credentials_json']))
                <p class="text-green-600 text-xs mt-1">&check; Credentials sudah tersimpan</p>
            @endif
            @error('ga_credentials_json') <p class="mt-1 text-xs text-red-600">{{$message}}</p> @enderror
        </div>

        {{-- Tombol Test Connection --}}
        <div class="mt-4 flex items-center gap-3">
            <button type="button" id="btn-test-ga4"
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-lg border border-slate-300 bg-white text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-primary"
                    onclick="testGA4Connection()">
                Test Koneksi GA4
            </button>
            <span id="ga4-test-result" class="text-xs"></span>
        </div>

        {{-- Panduan Setup GA4 --}}
        <div class="mt-4">
            <details class="border border-slate-200 rounded-lg">
                <summary class="px-4 py-3 text-xs font-semibold text-slate-600 cursor-pointer hover:bg-slate-50 select-none">
                    &darr; Cara Setup Google Analytics Data API
                </summary>
                <div class="px-4 pb-4 pt-2 text-xs text-slate-600 space-y-2">
                    <ol class="list-decimal list-inside space-y-2">
                        <li>Buka <a href="https://console.cloud.google.com" target="_blank" class="text-primary underline">Google Cloud Console</a> dan buat atau pilih project.</li>
                        <li>Di menu samping, pergi ke <strong>APIs &amp; Services &rarr; Library</strong>, cari <strong>Google Analytics Data API</strong>, lalu klik Enable.</li>
                        <li>Pergi ke <strong>APIs &amp; Services &rarr; Credentials</strong>, klik <strong>Create Credentials &rarr; Service Account</strong>.</li>
                        <li>Isi nama service account, klik Create, lalu Done.</li>
                        <li>Klik service account yang baru dibuat, pergi ke tab <strong>Keys</strong>, klik <strong>Add Key &rarr; Create new key</strong>, pilih <strong>JSON</strong>, lalu Download.</li>
                        <li>Buka <a href="https://analytics.google.com" target="_blank" class="text-primary underline">Google Analytics</a> &rarr; <strong>Admin &rarr; Property Access Management</strong>. Tambahkan email service account (ada di file JSON, field <code>client_email</code>) dengan role <strong>Viewer</strong>.</li>
                        <li>Di GA4 Admin &rarr; Property Settings, salin <strong>Property ID</strong> (format angka), lalu tulis sebagai <code>properties/&lt;ANGKA&gt;</code> di field GA4 Property ID di atas.</li>
                        <li>Buka file JSON yang didownload, salin seluruh isinya, paste di field Service Account Credentials JSON di atas.</li>
                        <li>Klik <strong>Test Koneksi GA4</strong> untuk verifikasi, lalu simpan settings.</li>
                    </ol>
                </div>
            </details>
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

<script>
function testGA4Connection() {
    const btn = document.getElementById('btn-test-ga4');
    const result = document.getElementById('ga4-test-result');
    btn.disabled = true;
    btn.textContent = 'Testing...';
    result.textContent = '';

    fetch('/admin/analytics/test-connection', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        result.textContent = data.success ? '\u2713 ' + data.message : '\u2717 ' + data.message;
        result.style.color = data.success ? '#16a34a' : '#dc2626';
    })
    .catch(e => {
        result.textContent = '\u2717 Error: ' + e.message;
        result.style.color = '#dc2626';
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = 'Test Koneksi GA4';
    });
}
</script>
