{{-- Alpine.js powered delete confirmation modal --}}
{{-- Usage: Include this at the bottom of your page inside an x-data wrapper that has:
     showDeleteModal: false, deleteUrl: '', deleteItemName: ''
     Then trigger with: @click="deleteUrl = '...'; deleteItemName = '...'; showDeleteModal = true"
--}}

{{-- Modal Overlay --}}
<div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-transition>
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" @click="showDeleteModal = false"></div>
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 transform"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95">

        {{-- Warning Icon --}}
        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>

        <h3 class="text-lg font-semibold text-slate-800 text-center mb-2">Konfirmasi Hapus</h3>
        <p class="text-sm text-slate-600 text-center mb-6">
            Apakah Anda yakin ingin menghapus <span class="font-medium text-slate-800" x-text="deleteItemName"></span>? Aksi ini tidak dapat dibatalkan.
        </p>

        <div class="flex items-center gap-3">
            <button @click="showDeleteModal = false" type="button"
                    class="flex-1 px-4 py-2.5 bg-slate-100 text-slate-700 font-medium rounded-lg hover:bg-slate-200 transition-colors text-sm">
                Batal
            </button>
            <form :action="deleteUrl" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="w-full px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition-colors text-sm">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>
