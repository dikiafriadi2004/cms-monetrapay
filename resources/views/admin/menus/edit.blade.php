@extends('admin.layouts.app')

@section('title', 'Edit Menu: ' . $menu->name)
@section('page-title', 'Menu Builder')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js">
<style>
    .menu-item.sortable-ghost { opacity: 0.35; background: #f0fdf4; border-color: #86efac; }
    .menu-item.sortable-chosen { box-shadow: 0 4px 16px rgba(61,158,122,0.2); }
    .menu-item.sortable-drag { opacity: 0.9; }
    .nested-container { min-height: 8px; }
</style>
@endpush

@section('content')
<div x-data="menuBuilder()" class="space-y-5">

    {{-- â•â•â• HEADER â•â•â• --}}
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

    {{-- â•â•â• MENU INFO CARD â•â•â• --}}
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

    {{-- â•â•â• MAIN GRID â•â•â• --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- â•â•â• LEFT: MENU ITEMS LIST â•â•â• --}}
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
                    <div id="sortable-root" class="min-h-[260px] space-y-2">
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
                            <div :data-id="item._id">
                                {{-- LEVEL 1 --}}
                                <div class="menu-item group flex items-center gap-2 p-3 bg-white border-2 border-slate-200 rounded-lg hover:border-primary/40 hover:shadow-sm transition-all">
                                    {{-- Drag handle --}}
                                    <div class="drag-handle cursor-grab active:cursor-grabbing shrink-0 text-slate-300 hover:text-primary transition-colors p-1 rounded">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 12a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                                    </div>
                                    {{-- Inline edit form --}}
                                    <template x-if="editingId === item._id">
                                        <div class="flex-1 flex items-center gap-2">
                                            <input type="text" x-model="editLabel" @keydown.enter="saveEdit(item)" @keydown.escape="cancelEdit()"
                                                   class="flex-1 text-sm px-2 py-1 rounded border border-primary focus:outline-none">
                                            <input x-show="item.type === 'custom' || item.type === 'external'"
                                                   type="text" x-model="editUrl" placeholder="URL"
                                                   @keydown.enter="saveEdit(item)" @keydown.escape="cancelEdit()"
                                                   class="flex-1 text-xs px-2 py-1 rounded border border-slate-300 focus:outline-none font-mono">
                                            <button @click="saveEdit(item)" class="p-1 text-green-600 hover:bg-green-50 rounded" title="Simpan"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                            <button @click="cancelEdit()" class="p-1 text-slate-400 hover:bg-slate-50 rounded" title="Batal"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                        </div>
                                    </template>
                                    <template x-if="editingId !== item._id">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-semibold text-slate-800 truncate" x-text="item.label"></span>
                                                <span class="inline-flex px-1.5 py-0.5 text-[10px] font-bold rounded uppercase tracking-wide"
                                                      :class="{'bg-primary/10 text-primary':item.type==='page','bg-purple-50 text-purple-700':item.type==='category','bg-amber-50 text-amber-700':item.type==='custom','bg-secondary/10 text-secondary-dark':item.type==='external'}"
                                                      x-text="item.type"></span>
                                            </div>
                                            <p class="text-[11px] text-slate-500 truncate font-mono" x-text="item.url||'-'"></p>
                                        </div>
                                    </template>
                                    {{-- Edit + Delete --}}
                                    <template x-if="editingId !== item._id">
                                        <div class="flex items-center gap-1 shrink-0">
                                            <button @click="startEdit(item)" class="p-1.5 text-slate-300 hover:text-primary hover:bg-primary/5 rounded-md transition-colors" title="Edit">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button @click="removeItem(items, index)" class="p-1.5 text-slate-300 hover:text-red-600 hover:bg-red-50 rounded-md transition-colors" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                {{-- CHILDREN (level 2+) --}}
                                <div class="nested-container ml-7 mt-1.5 space-y-1.5 min-h-[8px]">
                                    <template x-for="(child, ci) in item.children" :key="child._id">
                                        <div :data-id="child._id">
                                            <div class="menu-item group flex items-center gap-2 p-2.5 bg-slate-50 border border-slate-200 rounded-lg hover:border-primary/40 transition-all">
                                                <div class="drag-handle cursor-grab active:cursor-grabbing shrink-0 text-slate-300 hover:text-primary transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 12a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                                                </div>
                                                <template x-if="editingId === child._id">
                                                    <div class="flex-1 flex items-center gap-2">
                                                        <input type="text" x-model="editLabel" @keydown.enter="saveEdit(child)" @keydown.escape="cancelEdit()" class="flex-1 text-sm px-2 py-1 rounded border border-primary focus:outline-none">
                                                        <input x-show="child.type==='custom'||child.type==='external'" type="text" x-model="editUrl" class="flex-1 text-xs px-2 py-1 rounded border border-slate-300 font-mono focus:outline-none">
                                                        <button @click="saveEdit(child)" class="p-1 text-green-600 hover:bg-green-50 rounded"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                                        <button @click="cancelEdit()" class="p-1 text-slate-400 hover:bg-slate-50 rounded"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                                    </div>
                                                </template>
                                                <template x-if="editingId !== child._id">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="text-sm font-medium text-slate-700 truncate" x-text="child.label"></span>
                                                            <span class="inline-flex px-1 py-0.5 text-[9px] font-bold rounded uppercase"
                                                                  :class="{'bg-primary/10 text-primary':child.type==='page','bg-purple-50 text-purple-700':child.type==='category','bg-amber-50 text-amber-700':child.type==='custom','bg-secondary/10 text-secondary-dark':child.type==='external'}"
                                                                  x-text="child.type"></span>
                                                        </div>
                                                        <p class="text-[10px] text-slate-400 truncate font-mono" x-text="child.url||'-'"></p>
                                                    </div>
                                                </template>
                                                <template x-if="editingId !== child._id">
                                                    <div class="flex items-center gap-1 shrink-0">
                                                        <button @click="startEdit(child)" class="p-1 text-slate-300 hover:text-primary hover:bg-primary/5 rounded transition-colors"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                        <button @click="removeItem(item.children, ci)" class="p-1 text-slate-300 hover:text-red-600 hover:bg-red-50 rounded transition-colors"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                                    </div>
                                                </template>
                                            </div>
                                            {{-- Level 3 --}}
                                            <div class="nested-container ml-6 mt-1 space-y-1 min-h-[6px]">
                                                <template x-for="(gc, gi) in child.children" :key="gc._id">
                                                    <div :data-id="gc._id" class="menu-item group flex items-center gap-2 p-2.5 bg-white border border-dashed border-slate-200 rounded-lg hover:border-primary/40 transition-all">
                                                        <div class="drag-handle cursor-grab active:cursor-grabbing shrink-0 text-slate-300 hover:text-primary transition-colors">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 12a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <span class="text-xs font-medium text-slate-600 truncate" x-text="gc.label"></span>
                                                            <p class="text-[10px] text-slate-400 truncate font-mono" x-text="gc.url||'-'"></p>
                                                        </div>
                                                        <button @click="startEdit(gc)" class="p-1 text-slate-300 hover:text-primary rounded transition-colors opacity-0 group-hover:opacity-100"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                        <button @click="removeItem(child.children, gi)" class="p-1 text-slate-300 hover:text-red-600 rounded transition-colors opacity-0 group-hover:opacity-100"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
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
                        <p class="text-xs text-blue-700">Drag â ¿ untuk mengubah urutan. Seret ke dalam item lain untuk membuat sub-menu. Klik âœï¸ untuk edit label/URL. Maksimal 3 level, 50 item.</p>
                    </div>
                </div>
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

                    <div class="mt-4 flex items-start gap-2 p-3 bg-blue-50 border border-blue-100 rounded-lg">
                        <svg class="w-4 h-4 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-xs text-blue-700">Drag item untuk mengubah urutan. Maksimal 3 level kedalaman dan 50 item per menu.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- â•â•â• RIGHT: ADD ITEM PANEL â•â•â• --}}
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
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
    const pagesData     = @json($pages);
    const categoriesData = @json($categories);
    const SAVE_URL      = "{{ route('admin.menus.save-items', $menu) }}";
    const CSRF          = "{{ csrf_token() }}";

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

            // â”€â”€ Inline edit state â”€â”€
            editingId: null,
            editLabel: '',
            editUrl: '',

            init() {
                this.assignIds(this.items);
                this.$nextTick(() => this._initSortable());
            },

            assignIds(items) {
                items.forEach(item => {
                    if (!item._id) item._id = this.generateId();
                    if (!item.children) item.children = [];
                    this.assignIds(item.children);
                });
            },

            generateId() { return 'item_' + (this._idCounter++); },

            _initSortable() {
                const root = document.getElementById('sortable-root');
                if (!root || typeof Sortable === 'undefined') return;
                this._makeSortable(root, this.items);
            },

            _makeSortable(el, array) {
                if (!el) return;
                Sortable.create(el, {
                    group: 'menu-items',
                    animation: 150,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    dragClass: 'sortable-drag',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onEnd: () => {
                        // Sync DOM order back to Alpine data after drag
                        this.$nextTick(() => this._syncFromDom());
                    }
                });
                // Also init nested containers
                el.querySelectorAll('.nested-container').forEach(nested => {
                    if (!nested._sortable) {
                        nested._sortable = true;
                        Sortable.create(nested, {
                            group: 'menu-items',
                            animation: 150,
                            handle: '.drag-handle',
                            ghostClass: 'sortable-ghost',
                            chosenClass: 'sortable-chosen',
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            onEnd: () => {
                                this.$nextTick(() => this._syncFromDom());
                            }
                        });
                    }
                });
            },

            _syncFromDom() {
                // After sortable changes DOM, rebuild items array from DOM order
                const root = document.getElementById('sortable-root');
                if (!root) return;
                const flat = {}; // id â†’ item
                const flatAll = (arr) => arr.forEach(i => { flat[i._id] = i; flatAll(i.children || []); });
                flatAll(this.items);

                const buildFromDom = (container) => {
                    const result = [];
                    container.querySelectorAll(':scope > [data-id]').forEach(el => {
                        const id = el.dataset.id;
                        const item = flat[id];
                        if (!item) return;
                        const nested = el.querySelector('.nested-container');
                        item.children = nested ? buildFromDom(nested) : [];
                        result.push(item);
                    });
                    return result;
                };

                this.items = buildFromDom(root);
                // Re-init sortable on new nested containers
                this.$nextTick(() => {
                    document.querySelectorAll('.nested-container:not([data-sortable-init])').forEach(el => {
                        el.setAttribute('data-sortable-init', '1');
                        Sortable.create(el, {
                            group: 'menu-items',
                            animation: 150,
                            handle: '.drag-handle',
                            ghostClass: 'sortable-ghost',
                            fallbackOnBody: true,
                            swapThreshold: 0.65,
                            onEnd: () => this.$nextTick(() => this._syncFromDom()),
                        });
                    });
                });
            },

            // â”€â”€ Inline edit â”€â”€
            startEdit(item) {
                this.editingId = item._id;
                this.editLabel = item.label;
                this.editUrl   = item.url;
            },

            saveEdit(item) {
                if (!this.editLabel.trim()) return;
                item.label = this.editLabel.trim();
                if (item.type === 'custom' || item.type === 'external') {
                    item.url = this.editUrl.trim();
                }
                this.editingId = null;
            },

            cancelEdit() { this.editingId = null; },

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
                this.items.push({ _id: this.generateId(), label: this.newItem.label.trim(), type: 'custom', url: this.newItem.url.trim(), target: this.newItem.target, page_id: null, category_id: null, children: [] });
                this.newItem = { label: '', url: '', target: '_self' };
                this.showMessage('Item ditambahkan.', 'success');
                this.$nextTick(() => this._syncFromDom());
            },

            addPageItem() {
                if (!this.selectedPage) return;
                if (this.countItems(this.items) >= 50) { this.showMessage('Maksimal 50 item per menu.', 'error'); return; }
                const page = pagesData.find(p => p.id == this.selectedPage);
                if (!page) return;
                this.items.push({ _id: this.generateId(), label: page.title, type: 'page', url: '/' + page.slug, target: '_self', page_id: page.id, category_id: null, children: [] });
                this.selectedPage = '';
                this.showMessage('Halaman ditambahkan.', 'success');
                this.$nextTick(() => this._syncFromDom());
            },

            addCategoryItem() {
                if (!this.selectedCategory) return;
                if (this.countItems(this.items) >= 50) { this.showMessage('Maksimal 50 item per menu.', 'error'); return; }
                const cat = categoriesData.find(c => c.id == this.selectedCategory);
                if (!cat) return;
                this.items.push({ _id: this.generateId(), label: cat.name, type: 'category', url: '/kategori/' + cat.slug, target: '_self', page_id: null, category_id: cat.id, children: [] });
                this.selectedCategory = '';
                this.showMessage('Kategori ditambahkan.', 'success');
                this.$nextTick(() => this._syncFromDom());
            },

            addExternalItem() {
                this.externalUrlError = '';
                if (!this.externalItem.label.trim()) { this.externalUrlError = 'Label wajib diisi.'; return; }
                if (!this.externalItem.url.trim()) { this.externalUrlError = 'URL wajib diisi.'; return; }
                if (!this.validateUrl(this.externalItem.url)) { this.externalUrlError = 'URL harus berformat http:// atau https://.'; return; }
                if (this.countItems(this.items) >= 50) { this.showMessage('Maksimal 50 item per menu.', 'error'); return; }
                this.items.push({ _id: this.generateId(), label: this.externalItem.label.trim(), type: 'external', url: this.externalItem.url.trim(), target: '_blank', page_id: null, category_id: null, children: [] });
                this.externalItem = { label: '', url: '' };
                this.showMessage('Link external ditambahkan.', 'success');
                this.$nextTick(() => this._syncFromDom());
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
                    const response = await fetch(SAVE_URL, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
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
                this.message = text; this.messageType = type;
                setTimeout(() => { this.message = ''; }, 4000);
            }
        };
    }
</script>
@endsection
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
