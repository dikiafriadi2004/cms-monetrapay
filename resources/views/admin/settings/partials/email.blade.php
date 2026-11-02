<div class="space-y-5">

    {{-- Quick guide --}}
    <div class="p-4 bg-blue-50 border border-blue-100 rounded-xl">
        <div class="flex items-start gap-3">
            <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div class="text-sm text-blue-900 space-y-2">
                <p class="font-semibold">Setup Gmail SMTP (rekomendasi):</p>
                <ol class="list-decimal list-inside space-y-1 text-blue-800 text-xs">
                    <li>Aktifkan 2-Step Verification di akun Google Anda — <a href="https://myaccount.google.com/security" target="_blank" class="font-semibold underline">myaccount.google.com/security</a></li>
                    <li>Generate <strong>App Password</strong> di <a href="https://myaccount.google.com/apppasswords" target="_blank" class="font-semibold underline">myaccount.google.com/apppasswords</a> (pilih "Mail")</li>
                    <li>Copy 16-karakter App Password ke field <strong>Password</strong> di bawah (bukan password Gmail biasa)</li>
                    <li>Isi field lain dengan: Host <code class="bg-white px-1 rounded">smtp.gmail.com</code>, Port <code class="bg-white px-1 rounded">587</code>, Encryption <code class="bg-white px-1 rounded">tls</code></li>
                </ol>
            </div>
        </div>
    </div>

    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mail Driver</h3>
    <div x-data="{ mailer: '{{ old('mail_mailer', $settings['mail_mailer'] ?? 'smtp') }}' }">
        <label for="mail_mailer" class="block text-xs font-semibold text-slate-700 mb-1.5">Driver</label>
        <select name="mail_mailer" id="mail_mailer" x-model="mailer">
            <option value="smtp">SMTP (Gmail, Mailgun, dll.) — untuk production</option>
            <option value="log">Log (development — email TIDAK terkirim, hanya ke log file)</option>
            <option value="sendmail">Sendmail</option>
            <option value="array">Array (testing only)</option>
        </select>
        <div x-show="mailer === 'log'" x-cloak class="mt-2 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-start gap-2">
            <svg class="w-4 h-4 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <p class="text-xs text-amber-800"><strong>Perhatian:</strong> Driver "log" tidak mengirim email sungguhan. Ganti ke <strong>SMTP</strong> untuk production.</p>
        </div>
        <div x-show="mailer === 'smtp'" x-cloak class="mt-2 p-2 bg-green-50 border border-green-100 rounded-lg">
            <p class="text-xs text-green-700">✓ SMTP aktif — email akan dikirim melalui server yang dikonfigurasi di bawah.</p>
        </div>
        @error('mail_mailer') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- SMTP fields (always visible; ignored if driver != smtp) --}}
    <div class="pt-5 border-t border-slate-100 space-y-4">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">SMTP Connection</h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="md:col-span-2">
                <label for="mail_host" class="block text-xs font-semibold text-slate-700 mb-1.5">Host</label>
                <input type="text" name="mail_host" id="mail_host"
                       value="{{ old('mail_host', $settings['mail_host'] ?? '') }}"
                       placeholder="smtp.gmail.com">
                @error('mail_host') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="mail_port" class="block text-xs font-semibold text-slate-700 mb-1.5">Port</label>
                <input type="number" name="mail_port" id="mail_port"
                       value="{{ old('mail_port', $settings['mail_port'] ?? '587') }}"
                       placeholder="587">
                @error('mail_port') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="mail_encryption" class="block text-xs font-semibold text-slate-700 mb-1.5">Encryption</label>
            <select name="mail_encryption" id="mail_encryption" class="md:max-w-xs">
                <option value="tls" {{ ($settings['mail_encryption'] ?? 'tls') === 'tls' ? 'selected' : '' }}>TLS / STARTTLS (port 587 — Gmail default)</option>
                <option value="ssl" {{ ($settings['mail_encryption'] ?? '') === 'ssl' ? 'selected' : '' }}>SSL (port 465)</option>
                <option value="none" {{ ($settings['mail_encryption'] ?? '') === 'none' ? 'selected' : '' }}>None</option>
            </select>
            <p class="mt-1 text-[11px] text-slate-500">Untuk Gmail dengan port 587, pilih <strong>TLS / STARTTLS</strong>.</p>
            @error('mail_encryption') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="mail_username" class="block text-xs font-semibold text-slate-700 mb-1.5">Username</label>
                <input type="text" name="mail_username" id="mail_username"
                       value="{{ old('mail_username', $settings['mail_username'] ?? '') }}"
                       placeholder="youraddress@gmail.com" autocomplete="off">
                @error('mail_username') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div x-data="{ show: false }">
                <label for="mail_password" class="block text-xs font-semibold text-slate-700 mb-1.5">Password / App Password</label>
                <div class="relative">
                    <input :type="show ? 'text' : 'password'" name="mail_password" id="mail_password"
                           value="{{ old('mail_password', $settings['mail_password'] ?? '') }}"
                           placeholder="xxxx xxxx xxxx xxxx" autocomplete="new-password" style="padding-right: 2.5rem;">
                    <button type="button" @click="show = !show"
                            class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-slate-400 hover:text-slate-600">
                        <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                    </button>
                </div>
                <p class="mt-1 text-[11px] text-slate-500">Untuk Gmail, gunakan <strong>App Password</strong> 16-karakter, bukan password biasa.</p>
                @error('mail_password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    <div class="pt-5 border-t border-slate-100 space-y-4">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pengirim (From)</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="mail_from_address" class="block text-xs font-semibold text-slate-700 mb-1.5">From Address</label>
                <input type="email" name="mail_from_address" id="mail_from_address"
                       value="{{ old('mail_from_address', $settings['mail_from_address'] ?? '') }}"
                       placeholder="youraddress@gmail.com">
                <p class="mt-1 text-[11px] text-amber-700 font-semibold">⚠ Gmail SMTP: From Address <strong>harus sama</strong> dengan Username di atas. Jika berbeda email gagal terkirim.</p>
                @error('mail_from_address') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label for="mail_from_name" class="block text-xs font-semibold text-slate-700 mb-1.5">From Name</label>
                <input type="text" name="mail_from_name" id="mail_from_name"
                       value="{{ old('mail_from_name', $settings['mail_from_name'] ?? '') }}"
                       maxlength="100" placeholder="Nama Brand">
                @error('mail_from_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
    </div>

    {{-- Test email --}}
    <div class="pt-5 border-t border-slate-100">
        <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-3">Test Pengiriman</h3>
        <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
            <p class="text-sm text-slate-700 mb-3">
                Setelah simpan pengaturan di atas, kirim test email untuk verifikasi konfigurasi bekerja.
            </p>
            <form method="POST" action="{{ route('admin.settings.email-test') }}" class="flex flex-col sm:flex-row gap-2">
                @csrf
                <input type="email" name="email" required placeholder="email-tujuan@example.com"
                       value="{{ auth()->user()->email }}"
                       class="flex-1">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-secondary text-white text-sm font-semibold rounded-lg hover:bg-secondary-dark transition-colors whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Kirim Test Email
                </button>
            </form>
        </div>
    </div>
</div>
