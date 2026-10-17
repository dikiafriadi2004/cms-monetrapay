{{-- Create Folder Modal --}}
<div x-show="showFolderModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showFolderModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-amber-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Buat Folder Baru</h3>
            </div>
            <button @click="showFolderModal = false" type="button" class="p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.files.create-folder') }}" class="p-6 space-y-5">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder?->id }}">

            <div>
                <label for="folder_name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Folder</label>
                <input type="text" id="folder_name" name="name" required maxlength="100"
                       pattern="[a-zA-Z0-9\s\-_]+" placeholder="Misal: Banner Bulan Ini">
                <p class="mt-1.5 text-[11px] text-slate-500">Hanya huruf, angka, spasi, dash dan underscore. Maks 100 karakter.</p>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-slate-100">
                <button type="button" @click="showFolderModal = false"
                        class="px-4 py-2 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                    Batal
                </button>
                <button type="submit"
                        class="inline-flex items-center gap-2 px-5 py-2 text-sm font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Buat Folder
                </button>
            </div>
        </form>
    </div>
</div>
