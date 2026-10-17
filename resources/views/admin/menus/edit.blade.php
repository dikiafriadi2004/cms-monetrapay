@extends('admin.layouts.app')

@section('title', 'Edit Menu: ' . $menu->name)
@section('page-title', 'Menu Builder')

@push('styles')
<style>
    .menu-item.is-dragging {
        opacity: 0.45;
        border-color: rgba(61, 158, 122, 0.7);
    }
</style>
@endpush

@section('content')
<div x-data="menuBuilder()" class="space-y-5">

    {{-- ═══ HEADER ═══ --}}
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.menus.index') }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="min-w-0">
                <h1 class="text-lg font-bold text-slate-800 truncate">{{ $menu->name }}</h1>
                <p class="text-xs text-slate-500">Edit struktur menu navigasi</p>
            </div>
        </div>
        <button @click="saveMenu()" :disabled="saving"
                class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-60 disabled:cursor-not-allowed shadow-sm">
            <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <svg x-show="!saving" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span x-text="saving ? 'Menyimpan...' : 'Simpan Menu'"></span>
        </button>
    </div>

    {{-- Message --}}
    <div x-show="message" x-transition x-cloak
         :class="messageType === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'"
         class="flex items-center gap-3 p-3.5 rounded-xl border">
        <svg x-show="messageType === 'success'" class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <svg x-show="messageType !== 'success'" class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <p class="text-sm font-medium" x-text="message"></p>
    </div>

    {{-- ═══ MENU INFO CARD ═══ --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <h2 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-1 h-4 bg-primary rounded-full"></span>
            Informasi Menu
        </h2>
        <form method="POST" action="{{ route('admin.menus.update', $menu) }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            @csrf
            @method('PUT')
            <div class="md:col-span-6">
                <label for="menu_name" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Menu</label>
                <input type="text" name="name" id="menu_name" value="{{ $menu->name }}" required maxlength="100">
            </div>
            <div class="md:col-span-4">
                <label for="menu_location" class="block text-xs font-semibold text-slate-700 mb-1.5">Lokasi</label>
                <select name="location" id="menu_location" required>
                    <option value="header" {{ $menu->location === 'header' ? 'selected' : '' }}>Header</option>
                    <option value="footer" {{ $menu->location === 'footer' ? 'selected' : '' }}>Footer</option>
                    <option value="sidebar" {{ $menu->location === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <button type="submit"
                        class="w-full px-4 py-2.5 bg-slate-800 text-white text-sm font-semibold rounded-lg hover:bg-slate-700 transition-colors">
                    Update Info
                </button>
            </div>
        </form>
    </div>

    {{-- ═══ MAIN GRID ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ═══ LEFT: MENU ITEMS LIST ═══ --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-1 h-4 bg-primary rounded-full"></span>
                        Item Menu
                    </h2>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-full">
                        <span x-text="countItems(items)"></span> / 50
                    </span>
                </div>

                <div class="p-5">
                    <div id="menu-items-container" class="min-h-[260px] space-y-2" x-ref="sortableContainer">
                        <template x-if="items.length === 0">
                            <div class="flex flex-col items-center justify-center py-16 border-2 border-dashed border-slate-200 rounded-xl text-center">
                                <div class="w-14 h-14 rounded-full bg-slate-100 flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                </div>
                                <p class="text-sm font-medium text-slate-600">Belum ada item menu</p>
                                <p class="text-xs text-slate-400 mt-0.5">Tambahkan dari panel kanan</p>
                            </div>
                        </template>

                        <template x-for="(item, index) in items" :key="item._id">
                            <div class="menu-item-wrapper">
                                {{-- LEVEL 1 --}}
                                <div class="menu-item group flex items-center gap-2 p-3 bg-white border-2 border-slate-200 rounded-lg cursor-move hover:border-primary/40 hover:shadow-sm transition-all"
                                     :class="dragging.item && dragging.item._id === item._id ? 'is-dragging' : ''"
                                     :data-id="item._id"
                                     draggable="true"
                                     @dragstart.stop="startDrag(items, index, $event)"
                                     @dragover.prevent
                                     @drop.stop="dropItem(items, index, $event)"
                                     @dragend="endDrag()">
                                    <svg class="w-4 h-4 text-slate-300 shrink-0 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-semibold text-slate-800 truncate" x-text="item.label"></span>
                                            <span class="inline-flex px-1.5 py-0.5 text-[10px] font-bold rounded uppercase tracking-wide"
                                                  :class="{
                                                      'bg-primary/10 text-primary': item.type === 'page',
                                                      'bg-purple-50 text-purple-700': item.type === 'category',
                                                      'bg-amber-50 text-amber-700': item.type === 'custom',
                                                      'bg-secondary/10 text-secondary-dark': item.type === 'external'
                                                  }"
                                                  x-text="item.type"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 truncate font-mono" x-text="item.url || '-'"></p>
                                    </div>
                                    <button @click="removeItem(items, index)"
                                            class="p-1.5 text-slate-300 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors shrink-0"
                                            title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>

                                {{-- LEVEL 2 --}}
                                <div class="ml-7 mt-2 space-y-2 nested-sortable" :data-parent-id="item._id" @dragover.prevent @drop.stop="dropInto(item.children, $event)">
                                    <template x-for="(child, childIndex) in item.children" :key="child._id">
                                        <div class="menu-item-wrapper">
                                            <div class="menu-item group flex items-center gap-2 p-2.5 bg-slate-50 border border-slate-200 rounded-lg cursor-move hover:border-primary/40 transition-all"
                                                 :class="dragging.item && dragging.item._id === child._id ? 'is-dragging' : ''"
                                                 :data-id="child._id"
                                                 draggable="true"
                                                 @dragstart.stop="startDrag(item.children, childIndex, $event)"
                                                 @dragover.prevent
                                                 @drop.stop="dropItem(item.children, childIndex, $event)"
                                                 @dragend="endDrag()">
                                                <svg class="w-3.5 h-3.5 text-slate-300 shrink-0 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-sm font-medium text-slate-700 truncate" x-text="child.label"></span>
                                                        <span class="inline-flex px-1.5 py-0.5 text-[10px] font-bold rounded uppercase tracking-wide"
                                                              :class="{
                                                                  'bg-primary/10 text-primary': child.type === 'page',
                                                                  'bg-purple-50 text-purple-700': child.type === 'category',
                                                                  'bg-amber-50 text-amber-700': child.type === 'custom',
                                                                  'bg-secondary/10 text-secondary-dark': child.type === 'external'
                                                              }"
                                                              x-text="child.type"></span>
                                                    </div>
                                                    <p class="text-[11px] text-slate-500 truncate font-mono" x-text="child.url || '-'"></p>
                                                </div>
                                                <button @click="removeItem(item.children, childIndex)"
                                                        class="p-1.5 text-slate-300 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>

                                            {{-- LEVEL 3 --}}
                                            <div class="ml-6 mt-2 space-y-2 nested-sortable" :data-parent-id="child._id" @dragover.prevent @drop.stop="dropInto(child.children, $event)">
                                                <template x-for="(grandchild, gcIndex) in child.children" :key="grandchild._id">
                                                    <div class="menu-item group flex items-center gap-2 p-2.5 bg-white border border-slate-200 border-dashed rounded-lg cursor-move hover:border-primary/40 transition-all"
                                                         :class="dragging.item && dragging.item._id === grandchild._id ? 'is-dragging' : ''"
                                                         :data-id="grandchild._id"
                                                         draggable="true"
                                                         @dragstart.stop="startDrag(child.children, gcIndex, $event)"
                                                         @dragover.prevent
                                                         @drop.stop="dropItem(child.children, gcIndex, $event)"
                                                         @dragend="endDrag()">
                                                        <svg class="w-3 h-3 text-slate-300 shrink-0 group-hover:text-primary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-xs font-medium text-slate-600 truncate" x-text="grandchild.label"></span>
                                                                <span class="inline-flex px-1.5 py-0.5 text-[10px] font-bold rounded uppercase"
                                                                      :class="{
                                                                          'bg-primary/10 text-primary': grandchild.type === 'page',
                                                                          'bg-purple-50 text-purple-700': grandchild.type === 'category',
                                                                          'bg-amber-50 text-amber-700': grandchild.type === 'custom',
                                                                          'bg-secondary/10 text-secondary-dark': grandchild.type === 'external'
                                                                      }"
                                                                      x-text="grandchild.type"></span>
                                                            </div>
                                                            <p class="text-[10px] text-slate-400 truncate font-mono" x-text="grandchild.url || '-'"></p>
                                                        </div>
                                                        <button @click="removeItem(child.children, gcIndex)"
                                                                class="p-1 text-slate-300 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors shrink-0">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="mt-4 flex items-start gap-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-blue-700">Drag item untuk mengubah urutan. Maksimal 3 level kedalaman dan 50 item per menu.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ RIGHT: ADD ITEM PANEL ═══ --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm sticky top-4">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-1 h-4 bg-secondary rounded-full"></span>
                        Tambah Item
                    </h2>
                </div>

                <div class="p-5">
                    {{-- Tabs --}}
                    <div class="grid grid-cols-4 gap-1 p-1 bg-slate-100 rounded-lg mb-4">
                        <button @click="activeTab = 'custom'" type="button"
                                :class="activeTab === 'custom' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-2 py-1.5 text-[11px] font-bold rounded-md transition-colors uppercase">
                            Custom
                        </button>
                        <button @click="activeTab = 'page'" type="button"
                                :class="activeTab === 'page' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-2 py-1.5 text-[11px] font-bold rounded-md transition-colors uppercase">
                            Halaman
                        </button>
                        <button @click="activeTab = 'category'" type="button"
                                :class="activeTab === 'category' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-2 py-1.5 text-[11px] font-bold rounded-md transition-colors uppercase">
                            Kategori
                        </button>
                        <button @click="activeTab = 'external'" type="button"
                                :class="activeTab === 'external' ? 'bg-white text-slate-800 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                class="px-2 py-1.5 text-[11px] font-bold rounded-md transition-colors uppercase">
                            External
                        </button>
                    </div>

                    {{-- CUSTOM --}}
                    <div x-show="activeTab === 'custom'" class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Label</label>
                            <input type="text" x-model="newItem.label" placeholder="Teks menu" maxlength="100">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">URL</label>
                            <input type="url" x-model="newItem.url" placeholder="https://example.com">
                            <p x-show="urlError" x-cloak class="mt-1 text-xs text-red-600" x-text="urlError"></p>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Target</label>
                            <select x-model="newItem.target">
                                <option value="_self">Same Window</option>
                                <option value="_blank">New Tab</option>
                            </select>
                        </div>
                        <button @click="addCustomItem()" type="button"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah ke Menu
                        </button>
                    </div>

                    {{-- PAGE --}}
                    <div x-show="activeTab === 'page'" x-cloak class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Halaman</label>
                            <select x-model="selectedPage">
                                <option value="">-- Pilih Halaman --</option>
                                @foreach($pages as $page)
                                    <option value="{{ $page->id }}">{{ $page->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button @click="addPageItem()" type="button"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah ke Menu
                        </button>
                    </div>

                    {{-- CATEGORY --}}
                    <div x-show="activeTab === 'category'" x-cloak class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pilih Kategori</label>
                            <select x-model="selectedCategory">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button @click="addCategoryItem()" type="button"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah ke Menu
                        </button>
                    </div>

                    {{-- EXTERNAL --}}
                    <div x-show="activeTab === 'external'" x-cloak class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Label</label>
                            <input type="text" x-model="externalItem.label" placeholder="Teks menu" maxlength="100">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">URL External</label>
                            <input type="url" x-model="externalItem.url" placeholder="https://external-site.com">
                            <p x-show="externalUrlError" x-cloak class="mt-1 text-xs text-red-600" x-text="externalUrlError"></p>
                        </div>
                        <button @click="addExternalItem()" type="button"
                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah ke Menu
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Remove Item Confirm Modal --}}
    <div x-show="showRemoveConfirm" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showRemoveConfirm = false"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-800 text-center mb-2">Hapus Item Menu</h3>
            <p class="text-sm text-slate-600 text-center mb-6">
                Hapus "<span class="font-semibold" x-text="removeConfirmName"></span>"? Aksi ini akan menghapus item beserta sub-itemnya.
            </p>
            <div class="flex gap-3">
                <button type="button" @click="showRemoveConfirm = false"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Batal
                </button>
                <button type="button" @click="confirmRemove()"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-red-600 rounded-xl hover:bg-red-700 transition-colors">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    const pagesData = @json($pages);
    const categoriesData = @json($categories);

    function menuBuilder() {
        return {
            items: @json($menuTree),
            activeTab: 'custom',
            saving: false,
            message: '',
            messageType: 'success',
            urlError: '',
            externalUrlError: '',
            selectedPage: '',
            selectedCategory: '',
            newItem: { label: '', url: '', target: '_self' },
            externalItem: { label: '', url: '' },
            _idCounter: Date.now(),
            showRemoveConfirm: false,
            removeConfirmName: '',
            _pendingRemove: null,
            dragging: { array: null, index: null, item: null },

            init() {
                this.assignIds(this.items);
            },

            assignIds(items) {
                items.forEach(item => {
                    if (!item._id) item._id = this.generateId();
                    if (!item.children) item.children = [];
                    this.assignIds(item.children);
                });
            },

            generateId() { return 'item_' + (this._idCounter++); },

            startDrag(array, index, event) {
                this.dragging = { array, index, item: array[index] };
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', array[index]._id);
            },

            dropItem(targetArray, targetIndex, event) {
                event.preventDefault();
                this.moveDraggedItem(targetArray, targetIndex);
            },

            dropInto(targetArray, event) {
                event.preventDefault();
                this.moveDraggedItem(targetArray, targetArray.length);
            },

            moveDraggedItem(targetArray, targetIndex) {
                const { array: sourceArray, index: sourceIndex, item } = this.dragging;
                if (!sourceArray || !item) return;

                if (this.isDescendantArray(item, targetArray)) {
                    this.showMessage('Item tidak bisa dipindah ke turunannya sendiri.', 'error');
                    this.endDrag();
                    return;
                }

                if (sourceArray === targetArray && sourceIndex === targetIndex) {
                    this.endDrag();
                    return;
                }

                sourceArray.splice(sourceIndex, 1);

                if (sourceArray === targetArray && targetIndex > sourceIndex) {
                    targetIndex--;
                }

                targetArray.splice(targetIndex, 0, item);
                this.endDrag();
            },

            isDescendantArray(item, targetArray) {
                if (item.children === targetArray) return true;

                return (item.children || []).some(child => this.isDescendantArray(child, targetArray));
            },

            endDrag() {
                this.dragging = { array: null, index: null, item: null };
            },

            countItems(items) {
                let count = 0;
                items.forEach(item => {
                    count++;
                    if (item.children && item.children.length > 0) count += this.countItems(item.children);
                });
                return count;
            },

            validateUrl(url) {
                if (!url) return false;
                try {
                    const parsed = new URL(url);
                    return parsed.protocol === 'http:' || parsed.protocol === 'https:';
                } catch { return false; }
            },

            addCustomItem() {
                this.urlError = '';
                if (!this.newItem.label.trim()) { this.urlError = 'Label wajib diisi.'; return; }
                if (!this.newItem.url.trim()) { this.urlError = 'URL wajib diisi.'; return; }
                if (!this.validateUrl(this.newItem.url)) { this.urlError = 'URL harus berformat http:// atau https://.'; return; }
                if (this.countItems(this.items) >= 50) { this.showMessage('Maksimal 50 item per menu.', 'error'); return; }

                this.items.push({
                    _id: this.generateId(), label: this.newItem.label.trim(), type: 'custom',
                    url: this.newItem.url.trim(), target: this.newItem.target,
                    page_id: null, category_id: null, children: []
                });
                this.newItem = { label: '', url: '', target: '_self' };
                this.showMessage('Item ditambahkan.', 'success');
            },

            addPageItem() {
                if (!this.selectedPage) return;
                if (this.countItems(this.items) >= 50) { this.showMessage('Maksimal 50 item per menu.', 'error'); return; }
                const page = pagesData.find(p => p.id == this.selectedPage);
                if (!page) return;
                this.items.push({
                    _id: this.generateId(), label: page.title, type: 'page',
                    url: '/' + page.slug, target: '_self',
                    page_id: page.id, category_id: null, children: []
                });
                this.selectedPage = '';
                this.showMessage('Halaman ditambahkan.', 'success');
            },

            addCategoryItem() {
                if (!this.selectedCategory) return;
                if (this.countItems(this.items) >= 50) { this.showMessage('Maksimal 50 item per menu.', 'error'); return; }
                const category = categoriesData.find(c => c.id == this.selectedCategory);
                if (!category) return;
                this.items.push({
                    _id: this.generateId(), label: category.name, type: 'category',
                    url: '/kategori/' + category.slug, target: '_self',
                    page_id: null, category_id: category.id, children: []
                });
                this.selectedCategory = '';
                this.showMessage('Kategori ditambahkan.', 'success');
            },

            addExternalItem() {
                this.externalUrlError = '';
                if (!this.externalItem.label.trim()) { this.externalUrlError = 'Label wajib diisi.'; return; }
                if (!this.externalItem.url.trim()) { this.externalUrlError = 'URL wajib diisi.'; return; }
                if (!this.validateUrl(this.externalItem.url)) { this.externalUrlError = 'URL harus berformat http:// atau https://.'; return; }
                if (this.countItems(this.items) >= 50) { this.showMessage('Maksimal 50 item per menu.', 'error'); return; }

                this.items.push({
                    _id: this.generateId(), label: this.externalItem.label.trim(), type: 'external',
                    url: this.externalItem.url.trim(), target: '_blank',
                    page_id: null, category_id: null, children: []
                });
                this.externalItem = { label: '', url: '' };
                this.showMessage('Link external ditambahkan.', 'success');
            },

            removeItem(array, index) {
                this._pendingRemove = { array, index };
                this.removeConfirmName = array[index].label;
                this.showRemoveConfirm = true;
            },

            confirmRemove() {
                if (this._pendingRemove) {
                    this._pendingRemove.array.splice(this._pendingRemove.index, 1);
                    this._pendingRemove = null;
                }
                this.showRemoveConfirm = false;
            },

            prepareItemsForSave(items) {
                return items.map(item => ({
                    label: item.label, type: item.type, url: item.url,
                    target: item.target || '_self',
                    page_id: item.page_id || null, category_id: item.category_id || null,
                    children: item.children ? this.prepareItemsForSave(item.children) : []
                }));
            },

            async saveMenu() {
                this.saving = true;
                this.message = '';
                const payload = { items: this.prepareItemsForSave(this.items) };
                try {
                    const response = await fetch('{{ route("admin.menus.save-items", $menu) }}', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    const data = await response.json();
                    if (response.ok && data.success) this.showMessage(data.message || 'Menu berhasil disimpan.', 'success');
                    else this.showMessage(data.message || 'Gagal menyimpan menu.', 'error');
                } catch (error) {
                    this.showMessage('Terjadi kesalahan saat menyimpan menu.', 'error');
                }
                this.saving = false;
            },

            showMessage(text, type) {
                this.message = text;
                this.messageType = type;
                setTimeout(() => { this.message = ''; }, 4000);
            }
        };
    }
</script>
@endsection
