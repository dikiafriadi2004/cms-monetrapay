<div class="space-y-5" x-data="{ enabled: {{ old('maintenance_mode', $settings['maintenance_mode'] ?? '0') == '1' ? 'true' : 'false' }} }">

    <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 flex items-start gap-3">
        <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center shrink-0">
            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
        </div>
        <div>
            <p class="text-sm font-bold text-amber-900">Perhatian</p>
            <p class="mt-1 text-xs text-amber-800 leading-relaxed">
                Saat mode maintenance aktif, semua pengunjung yang tidak login akan melihat halaman "Coming Soon".
                Administrator yang sudah login tetap dapat mengakses seluruh website secara normal.
            </p>
        </div>
    </div>

    {{-- Toggle Card --}}
    <div class="p-5 bg-white border-2 border-slate-200 rounded-xl"
         :class="enabled ? 'border-red-200 bg-red-50/40' : ''">
        <div class="flex items-center gap-4">
            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                <input type="hidden" name="maintenance_mode" value="0">
                <input type="checkbox" name="maintenance_mode" value="1"
                       x-model="enabled" class="sr-only peer"
                       {{ old('maintenance_mode', $settings['maintenance_mode'] ?? '0') == '1' ? 'checked' : '' }}>
                <div class="w-12 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300/40 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-500"></div>
            </label>
            <div class="flex-1">
                <p class="text-sm font-bold" :class="enabled ? 'text-red-700' : 'text-slate-700'">
                    <span x-text="enabled ? 'Maintenance Mode AKTIF' : 'Maintenance Mode Nonaktif'"></span>
                </p>
                <p class="text-xs text-slate-500 mt-0.5" x-show="enabled">Pengunjung publik akan melihat halaman Coming Soon</p>
                <p class="text-xs text-slate-500 mt-0.5" x-show="!enabled">Website dapat diakses publik secara normal</p>
            </div>
        </div>
    </div>

    {{-- Status Indicator — reactive with Alpine toggle --}}
    <div class="p-4 rounded-xl border-2 transition-colors"
         :class="enabled ? 'bg-red-50 border-red-200' : 'bg-green-50 border-green-200'">
        <template x-if="enabled">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-3 h-3 rounded-full bg-red-500"></div>
                    <div class="absolute inset-0 w-3 h-3 rounded-full bg-red-500 animate-ping opacity-60"></div>
                </div>
                <div>
                    <p class="text-sm font-bold text-red-800">Status Saat Ini: Maintenance</p>
                    <p class="text-xs text-red-600 mt-0.5">Pengunjung publik akan melihat halaman Coming Soon</p>
                </div>
            </div>
        </template>
        <template x-if="!enabled">
            <div class="flex items-center gap-3">
                <div class="w-3 h-3 rounded-full bg-green-500"></div>
                <div>
                    <p class="text-sm font-bold text-green-800">Status Saat Ini: Aktif</p>
                    <p class="text-xs text-green-600 mt-0.5">Website dapat diakses publik secara normal</p>
                </div>
            </div>
        </template>
    </div>
</div>
