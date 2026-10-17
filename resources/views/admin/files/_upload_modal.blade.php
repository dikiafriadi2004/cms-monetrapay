{{-- Upload Modal --}}
<div x-show="showUploadModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showUploadModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
        {{-- Header --}}
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-primary/10 flex items-center justify-center">
                    <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-800">Upload File</h3>
                    <p class="text-[11px] text-slate-500">Pilih atau drag file ke area di bawah</p>
                </div>
            </div>
            <button @click="showUploadModal = false" type="button" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="p-6">
            <div
                x-on:dragover.prevent="$el.classList.add('border-primary', 'bg-primary-light')"
                x-on:dragleave.prevent="$el.classList.remove('border-primary', 'bg-primary-light')"
                x-on:drop.prevent="
                    $el.classList.remove('border-primary', 'bg-primary-light');
                    const files = $event.dataTransfer.files;
                    if (files.length > 0) uploadFile(files[0]);
                "
                class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center transition-all hover:border-primary/40 hover:bg-primary-light/30"
            >
                <div class="mx-auto w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                </div>
                <p class="text-sm font-semibold text-slate-700 mb-1">Drag & drop file ke sini</p>
                <p class="text-xs text-slate-500 mb-5">atau klik tombol di bawah</p>
                <label class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary rounded-lg cursor-pointer hover:bg-primary-dark transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    Pilih File
                    <input type="file" class="hidden" accept=".jpg,.jpeg,.png,.gif,.webp,.svg,.pdf,.doc,.docx"
                           @change="if ($event.target.files.length > 0) uploadFile($event.target.files[0])">
                </label>
            </div>

            {{-- Upload Progress --}}
            <div x-show="uploading" x-cloak class="mt-5 p-3 bg-primary-light/40 border border-primary/20 rounded-lg">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs font-semibold text-primary-dark">Mengupload...</span>
                    <span class="text-xs font-bold text-primary-dark" x-text="uploadProgress + '%'"></span>
                </div>
                <div class="w-full bg-white/60 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-primary h-1.5 rounded-full transition-all" :style="'width: ' + uploadProgress + '%'"></div>
                </div>
            </div>

            {{-- Info --}}
            <div class="mt-5 p-3 bg-slate-50 rounded-lg border border-slate-100">
                <p class="text-[11px] text-slate-600 font-semibold mb-0.5">Format yang diizinkan</p>
                <p class="text-[11px] text-slate-500">JPG, PNG, GIF, WebP, SVG, PDF, DOC, DOCX — Maksimal 10MB</p>
            </div>
        </div>
    </div>
</div>
