@extends('admin.layouts.app')

@section('title', 'Edit Menu: ' . $menu->name)
@section('page-title', 'Menu Builder')

@section('content')
<div x-data="menuBuilder()" x-init="init()" class="space-y-5">

    {{-- HEADER --}}
    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.menus.index') }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-800">{{ $menu->name }}</h1>
                <p class="text-xs text-slate-500">Edit struktur menu navigasi</p>
            </div>
        </div>
        <button @click="saveMenu()" :disabled="saving"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-60 shadow-sm">
            <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
            </svg>
            <svg x-show="!saving" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span x-text="saving ? 'Menyimpan...' : 'Simpan Menu'"></span>
        </button>
    </div>

    {{-- ALERT --}}
    <div x-show="message" x-cloak x-transition
         :class="messageType === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'"
         class="flex items-center gap-3 p-3.5 rounded-xl border text-sm font-medium">
        <svg x-show="messageType === 'success'" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <svg x-show="messageType !== 'success'" class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span x-text="message"></span>
    </div>

    {{-- INFO MENU --}}
    <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
        <h2 class="text-sm font-bold text-slate-700 mb-4">Informasi Menu</h2>
        <form method="POST" action="{{ route('admin.menus.update', $menu) }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-end">
            @csrf @method('PUT')
            <div class="md:col-span-6">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Menu</label>
                <input type="text" name="name" value="{{ $menu->name }}" required maxlength="100"
                       class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15">
            </div>
            <div class="md:col-span-4">
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lokasi</label>
                <select name="location" required
                        class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15">
                    <option value="header"  {{ $menu->location === 'header'  ? 'selected' : '' }}>Header (Navigasi Utama)</option>
                    <option value="footer"  {{ $menu->location === 'footer'  ? 'selected' : '' }}>Footer</option>
                    <option value="sidebar" {{ $menu->location === 'sidebar' ? 'selected' : '' }}>Sidebar</option>
                    <option value="social"  {{ $menu->location === 'social'  ? 'selected' : '' }}>Social Media</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <button type="submit"
                        class="w-full px-4 py-2.5 bg-slate-700 text-white text-sm font-semibold rounded-lg hover:bg-slate-800 transition-colors">
                    Update
                </button>
            </div>
        </form>
    </div>

    {{-- MAIN GRID: seperti WordPress --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 items-start">

        {{-- KIRI: Panel tambah item (seperti WP) --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- HALAMAN --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <button @click="openPanel = openPanel === 'pages' ? '' : 'pages'"
                        class="w-full flex items-center justify-between px-5 py-3.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Halaman
                        <span class="px-1.5 py-0.5 text-[10px] font-bold bg-primary/10 text-primary rounded-full">{{ count($pages) }}</span>
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="openPanel === 'pages' ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openPanel === 'pages'" x-collapse class="border-t border-slate-100">
                    <div class="p-4 space-y-3">
                        {{-- Search --}}
                        <input type="text" x-model="pageSearch" placeholder="Cari halaman..."
                               class="w-full px-3 py-2 text-xs border border-slate-200 rounded-lg focus:outline-none focus:border-primary">
                        {{-- List --}}
                        <div class="space-y-1.5 max-h-52 overflow-y-auto pr-1">
                            @foreach($pages as $page)
                            <label class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-slate-50 cursor-pointer group"
                                   x-show="!pageSearch || '{{ strtolower($page->title) }}'.includes(pageSearch.toLowerCase())">
                                <input type="checkbox" value="{{ $page->id }}" x-model="selectedPages"
                                       class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/30 cursor-pointer">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-slate-700 group-hover:text-primary transition-colors truncate">{{ $page->title }}</p>
                                    <p class="text-xs text-slate-400 font-mono truncate">/{{ $page->slug }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        {{-- Actions --}}
                        <div class="flex items-center gap-2 pt-1 border-t border-slate-100">
                            <button @click="selectAllPages()" type="button"
                                    class="text-xs text-primary hover:underline font-semibold">Pilih Semua</button>
                            <span class="text-slate-300">|</span>
                            <button @click="selectedPages = []" type="button"
                                    class="text-xs text-slate-500 hover:underline">Batal Pilih</button>
                            <button @click="addPages()" type="button"
                                    :disabled="selectedPages.length === 0"
                                    class="ml-auto px-4 py-1.5 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                                Tambah ke Menu
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KATEGORI --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <button @click="openPanel = openPanel === 'categories' ? '' : 'categories'"
                        class="w-full flex items-center justify-between px-5 py-3.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        Kategori Blog
                        <span class="px-1.5 py-0.5 text-[10px] font-bold bg-purple-100 text-purple-700 rounded-full">{{ count($categories) }}</span>
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="openPanel === 'categories' ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openPanel === 'categories'" x-collapse class="border-t border-slate-100">
                    <div class="p-4 space-y-3">
                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1">
                            @foreach($categories as $cat)
                            <label class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-slate-50 cursor-pointer group">
                                <input type="checkbox" value="{{ $cat->id }}" x-model="selectedCategories"
                                       class="w-4 h-4 rounded border-slate-300 text-purple-600 focus:ring-purple-500/30 cursor-pointer">
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-slate-700 group-hover:text-purple-600 transition-colors truncate">{{ $cat->name }}</p>
                                    <p class="text-xs text-slate-400 font-mono truncate">/blog/kategori/{{ $cat->slug }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <div class="flex items-center gap-2 pt-1 border-t border-slate-100">
                            <button @click="selectedCategories = []" type="button"
                                    class="text-xs text-slate-500 hover:underline">Batal Pilih</button>
                            <button @click="addCategories()" type="button"
                                    :disabled="selectedCategories.length === 0"
                                    class="ml-auto px-4 py-1.5 bg-purple-600 text-white text-xs font-bold rounded-lg hover:bg-purple-700 transition-colors disabled:opacity-40 disabled:cursor-not-allowed">
                                Tambah ke Menu
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CUSTOM LINK --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <button @click="openPanel = openPanel === 'custom' ? '' : 'custom'"
                        class="w-full flex items-center justify-between px-5 py-3.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                        Custom Link
                    </span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="openPanel === 'custom' ? 'rotate-180' : ''"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div x-show="openPanel === 'custom'" x-collapse class="border-t border-slate-100">
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">URL</label>
                            <input type="text" x-model="customLink.url" placeholder="/kontak atau https://example.com"
                                   class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/15">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Teks Link</label>
                            <input type="text" x-model="customLink.label" placeholder="Teks yang ditampilkan"
                                   @keydown.enter="addCustomLink()"
                                   class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-400/15">
                        </div>
                        <p x-show="customError" x-cloak class="text-xs text-red-600 font-medium" x-text="customError"></p>
                        <button @click="addCustomLink()" type="button"
                                class="w-full px-4 py-2 bg-amber-500 text-white text-sm font-bold rounded-lg hover:bg-amber-600 transition-colors">
                            Tambah ke Menu
                        </button>
                    </div>
                </div>
            </div>

        </div>

        {{-- KANAN: Struktur menu --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-slate-700">Struktur Menu</h2>
                    <span class="text-xs text-slate-500 font-medium">
                        <span x-text="countItems(items)"></span>/50 item
                    </span>
                </div>
                <div class="p-5">
                    {{-- Empty state --}}
                    <template x-if="items.length === 0">
                        <div class="flex flex-col items-center justify-center py-16 border-2 border-dashed border-slate-200 rounded-xl text-center">
                            <svg class="w-10 h-10 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            <p class="text-sm font-medium text-slate-500">Belum ada item menu</p>
                            <p class="text-xs text-slate-400 mt-1">Pilih halaman atau buat custom link dari panel kiri</p>
                        </div>
                    </template>

                    {{-- Item list --}}
                    <div id="menu-root" class="space-y-2">
                        <template x-for="(item, idx) in items" :key="item._id">
                            <div>
                                {{-- Level 1 --}}
                                <div class="menu-row flex items-center gap-2 p-3 bg-white border-2 border-slate-200 rounded-xl hover:border-primary/40 transition-all group"
                                     :data-id="item._id">
                                    <div class="drag-handle cursor-grab text-slate-300 hover:text-primary p-1 shrink-0">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 12a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                                    </div>

                                    {{-- Edit mode --}}
                                    <template x-if="editingId === item._id">
                                        <div class="flex-1 flex items-center gap-2">
                                            <input type="text" x-model="editLabel" @keydown.enter="saveEdit(item)" @keydown.escape="cancelEdit()"
                                                   class="flex-1 text-sm px-2 py-1 rounded-lg border border-primary focus:outline-none" x-ref="editInput">
                                            <input type="text" x-model="editUrl" @keydown.enter="saveEdit(item)" @keydown.escape="cancelEdit()"
                                                   class="flex-1 text-xs px-2 py-1 rounded-lg border border-slate-300 font-mono focus:outline-none" placeholder="URL">
                                            <button @click="saveEdit(item)" class="p-1.5 text-green-600 hover:bg-green-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                            <button @click="cancelEdit()" class="p-1.5 text-slate-400 hover:bg-slate-100 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                        </div>
                                    </template>

                                    {{-- View mode --}}
                                    <template x-if="editingId !== item._id">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm font-semibold text-slate-800 truncate" x-text="item.label"></span>
                                                <span class="px-1.5 py-0.5 text-[10px] font-bold rounded uppercase"
                                                      :class="{'bg-primary/10 text-primary':item.type==='page','bg-purple-100 text-purple-700':item.type==='category','bg-amber-100 text-amber-700':item.type==='custom','bg-blue-100 text-blue-700':item.type==='external'}"
                                                      x-text="item.type"></span>
                                            </div>
                                            <p class="text-[11px] text-slate-400 font-mono truncate mt-0.5" x-text="item.url || '-'"></p>
                                        </div>
                                    </template>

                                    <template x-if="editingId !== item._id">
                                        <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button @click="startEdit(item)" title="Edit" class="p-1.5 text-slate-400 hover:text-primary hover:bg-primary/5 rounded-lg transition-colors">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button @click="removeItem(items, idx)" title="Hapus" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>

                                {{-- Children level 2 --}}
                                <div class="ml-8 mt-1.5 space-y-1.5 nested-container min-h-[6px]">
                                    <template x-for="(child, ci) in item.children" :key="child._id">
                                        <div>
                                            <div class="menu-row flex items-center gap-2 p-2.5 bg-slate-50 border border-slate-200 rounded-xl hover:border-primary/40 transition-all group"
                                                 :data-id="child._id">
                                                <div class="drag-handle cursor-grab text-slate-300 hover:text-primary shrink-0">
                                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 12a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                                                </div>
                                                <template x-if="editingId === child._id">
                                                    <div class="flex-1 flex items-center gap-2">
                                                        <input type="text" x-model="editLabel" @keydown.enter="saveEdit(child)" @keydown.escape="cancelEdit()"
                                                               class="flex-1 text-sm px-2 py-1 rounded-lg border border-primary focus:outline-none">
                                                        <input type="text" x-model="editUrl" @keydown.enter="saveEdit(child)" @keydown.escape="cancelEdit()"
                                                               class="flex-1 text-xs px-2 py-1 rounded-lg border border-slate-300 font-mono focus:outline-none">
                                                        <button @click="saveEdit(child)" class="p-1 text-green-600 rounded"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></button>
                                                        <button @click="cancelEdit()" class="p-1 text-slate-400 rounded"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                                    </div>
                                                </template>
                                                <template x-if="editingId !== child._id">
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-1.5">
                                                            <span class="text-sm font-medium text-slate-700 truncate" x-text="child.label"></span>
                                                            <span class="px-1 py-0.5 text-[9px] font-bold rounded uppercase"
                                                                  :class="{'bg-primary/10 text-primary':child.type==='page','bg-purple-100 text-purple-700':child.type==='category','bg-amber-100 text-amber-700':child.type==='custom','bg-blue-100 text-blue-700':child.type==='external'}"
                                                                  x-text="child.type"></span>
                                                        </div>
                                                        <p class="text-[10px] text-slate-400 font-mono truncate" x-text="child.url || '-'"></p>
                                                    </div>
                                                </template>
                                                <template x-if="editingId !== child._id">
                                                    <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <button @click="startEdit(child)" class="p-1 text-slate-400 hover:text-primary rounded"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                        <button @click="removeItem(item.children, ci)" class="p-1 text-slate-400 hover:text-red-600 rounded"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                                    </div>
                                                </template>
                                            </div>
                                            {{-- Level 3 --}}
                                            <div class="ml-6 mt-1 space-y-1 nested-container min-h-[4px]">
                                                <template x-for="(gc, gi) in child.children" :key="gc._id">
                                                    <div class="menu-row flex items-center gap-2 p-2 bg-white border border-dashed border-slate-200 rounded-xl hover:border-primary/30 transition-all group"
                                                         :data-id="gc._id">
                                                        <div class="drag-handle cursor-grab text-slate-300 hover:text-primary shrink-0">
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 12a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <span class="text-xs font-medium text-slate-600 truncate" x-text="gc.label"></span>
                                                            <p class="text-[10px] text-slate-400 font-mono truncate" x-text="gc.url || '-'"></p>
                                                        </div>
                                                        <div class="flex items-center gap-1 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <button @click="startEdit(gc)" class="p-1 text-slate-400 hover:text-primary rounded"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg></button>
                                                            <button @click="removeItem(child.children, gi)" class="p-1 text-slate-400 hover:text-red-600 rounded"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <p class="mt-4 text-xs text-slate-400 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Drag untuk mengubah urutan. Seret ke dalam item lain untuk membuat sub-menu. Maks. 3 level.
                    </p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
const PAGES_DATA      = @json($pages->map(fn($p) => ['id' => $p->id, 'title' => $p->title, 'slug' => $p->slug]));
const CATEGORIES_DATA = @json($categories->map(fn($c) => ['id' => $c->id, 'name' => $c->name, 'slug' => $c->slug]));
const SAVE_URL        = "{{ route('admin.menus.save-items', $menu) }}";
const CSRF            = "{{ csrf_token() }}";

function menuBuilder() {
    return {
        items: @json($menuTree),

        // Panel state
        openPanel: 'pages',

        // Selections
        selectedPages: [],
        selectedCategories: [],
        pageSearch: '',

        // Custom link
        customLink: { label: '', url: '' },
        customError: '',

        // Edit state
        editingId: null,
        editLabel: '',
        editUrl: '',

        // Message
        message: '',
        messageType: 'success',
        saving: false,

        _idCounter: Date.now(),

        init() {
            this.assignIds(this.items);
            this.$nextTick(() => this.initSortable());
        },

        generateId() { return 'item_' + (++this._idCounter); },

        assignIds(arr) {
            arr.forEach(item => {
                if (!item._id) item._id = this.generateId();
                if (!item.children) item.children = [];
                this.assignIds(item.children);
            });
        },

        countItems(arr) {
            return arr.reduce((n, item) => n + 1 + this.countItems(item.children || []), 0);
        },

        // ── Add pages (checklist) ──
        selectAllPages() {
            this.selectedPages = PAGES_DATA.map(p => String(p.id));
        },

        addPages() {
            if (this.selectedPages.length === 0) return;
            PAGES_DATA.forEach(page => {
                if (!this.selectedPages.includes(String(page.id))) return;
                if (this.countItems(this.items) >= 50) { this.showMsg('Maksimal 50 item.', 'error'); return; }
                this.items.push({
                    _id: this.generateId(), label: page.title, type: 'page',
                    url: '/' + page.slug, target: '_self',
                    page_id: page.id, category_id: null, children: []
                });
            });
            this.selectedPages = [];
            this.showMsg(PAGES_DATA.filter(p => true).length + ' halaman ditambahkan.', 'success');
            this.$nextTick(() => this.refreshSortable());
        },

        // ── Add categories ──
        addCategories() {
            if (this.selectedCategories.length === 0) return;
            CATEGORIES_DATA.forEach(cat => {
                if (!this.selectedCategories.includes(String(cat.id))) return;
                if (this.countItems(this.items) >= 50) { this.showMsg('Maksimal 50 item.', 'error'); return; }
                this.items.push({
                    _id: this.generateId(), label: cat.name, type: 'category',
                    url: '/blog/kategori/' + cat.slug, target: '_self',
                    page_id: null, category_id: cat.id, children: []
                });
            });
            this.selectedCategories = [];
            this.showMsg('Kategori ditambahkan.', 'success');
            this.$nextTick(() => this.refreshSortable());
        },

        // ── Add custom link ──
        addCustomLink() {
            this.customError = '';
            if (!this.customLink.label.trim()) { this.customError = 'Teks link wajib diisi.'; return; }
            if (!this.customLink.url.trim())   { this.customError = 'URL wajib diisi.'; return; }
            const url = this.customLink.url.trim();
            const valid = url.startsWith('/') || url.startsWith('#') || url.startsWith('http://') || url.startsWith('https://');
            if (!valid) { this.customError = 'URL harus diawali / atau https://'; return; }
            if (this.countItems(this.items) >= 50) { this.customError = 'Maksimal 50 item.'; return; }
            this.items.push({
                _id: this.generateId(), label: this.customLink.label.trim(), type: 'custom',
                url, target: '_self', page_id: null, category_id: null, children: []
            });
            this.customLink = { label: '', url: '' };
            this.showMsg('Custom link ditambahkan.', 'success');
            this.$nextTick(() => this.refreshSortable());
        },

        // ── Edit ──
        startEdit(item) {
            this.editingId = item._id;
            this.editLabel = item.label;
            this.editUrl   = item.url;
        },
        saveEdit(item) {
            if (!this.editLabel.trim()) return;
            item.label = this.editLabel.trim();
            item.url   = this.editUrl.trim();
            this.editingId = null;
        },
        cancelEdit() { this.editingId = null; },

        // ── Remove ──
        removeItem(arr, idx) {
            arr.splice(idx, 1);
        },

        // ── Sortable ──
        initSortable() {
            const root = document.getElementById('menu-root');
            if (!root || typeof Sortable === 'undefined') return;
            this._makeSortable(root);
        },

        refreshSortable() {
            // Re-init newly added nested containers
            document.querySelectorAll('.nested-container:not([data-si])').forEach(el => {
                el.setAttribute('data-si', '1');
                Sortable.create(el, {
                    group: 'menu-items', animation: 150, handle: '.drag-handle',
                    ghostClass: 'sortable-ghost', fallbackOnBody: true,
                    onEnd: () => this.$nextTick(() => this.syncFromDom()),
                });
            });
        },

        _makeSortable(el) {
            Sortable.create(el, {
                group: 'menu-items', animation: 150, handle: '.drag-handle',
                ghostClass: 'sortable-ghost', fallbackOnBody: true,
                onEnd: () => this.$nextTick(() => this.syncFromDom()),
            });
            el.querySelectorAll('.nested-container').forEach(nested => {
                if (!nested.dataset.si) {
                    nested.setAttribute('data-si', '1');
                    Sortable.create(nested, {
                        group: 'menu-items', animation: 150, handle: '.drag-handle',
                        ghostClass: 'sortable-ghost', fallbackOnBody: true,
                        onEnd: () => this.$nextTick(() => this.syncFromDom()),
                    });
                }
            });
        },

        syncFromDom() {
            const flat = {};
            const flatAll = arr => arr.forEach(i => { flat[i._id] = i; flatAll(i.children || []); });
            flatAll(this.items);
            const buildFromDom = container => {
                const result = [];
                container.querySelectorAll(':scope > [data-id]').forEach(el => {
                    const item = flat[el.dataset.id];
                    if (!item) return;
                    const nested = el.querySelector('.nested-container');
                    item.children = nested ? buildFromDom(nested) : [];
                    result.push(item);
                });
                return result;
            };
            this.items = buildFromDom(document.getElementById('menu-root'));
            this.$nextTick(() => this.refreshSortable());
        },

        // ── Save to server ──
        prepareItems(arr) {
            return arr.map(item => ({
                label: item.label, type: item.type, url: item.url,
                target: item.target || '_self',
                page_id: item.page_id || null,
                category_id: item.category_id || null,
                children: this.prepareItems(item.children || []),
            }));
        },

        async saveMenu() {
            this.saving = true;
            this.message = '';
            try {
                const res = await fetch(SAVE_URL, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                    body: JSON.stringify({ items: this.prepareItems(this.items) }),
                });
                const data = await res.json();
                if (res.ok && data.success) this.showMsg(data.message || 'Menu berhasil disimpan.', 'success');
                else this.showMsg(data.message || 'Gagal menyimpan menu.', 'error');
            } catch (e) {
                this.showMsg('Terjadi kesalahan koneksi.', 'error');
            }
            this.saving = false;
        },

        showMsg(text, type = 'success') {
            this.message = text; this.messageType = type;
            setTimeout(() => { this.message = ''; }, 4000);
        },
    };
}
</script>
@endsection
