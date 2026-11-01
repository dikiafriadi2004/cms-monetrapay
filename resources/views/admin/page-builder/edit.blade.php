@extends('admin.layouts.app')

@section('title', 'Page Builder — ' . $page->title)

@php
$componentMeta = [
    'hero'         => ['label'=>'Hero Banner',     'icon'=>'🚀'],
    'about-hero'   => ['label'=>'About Hero',      'icon'=>'🏢'],
    'cta'          => ['label'=>'Call to Action',  'icon'=>'🎯'],
    'text'         => ['label'=>'Text Block',      'icon'=>'📝'],
    'features'     => ['label'=>'Features Grid',  'icon'=>'✨'],
    'team'         => ['label'=>'Team Members',   'icon'=>'👥'],
    'faq'          => ['label'=>'FAQ Accordion',  'icon'=>'❓'],
    'testimonials' => ['label'=>'Testimonials',   'icon'=>'💬'],
    'visi-misi'    => ['label'=>'Visi & Misi',    'icon'=>'🎯'],
    'values'       => ['label'=>'Nilai-Nilai',    'icon'=>'💎'],
    'timeline'     => ['label'=>'Timeline',       'icon'=>'📅'],
    'gallery'      => ['label'=>'Image Gallery',  'icon'=>'🖼️'],
    'video'        => ['label'=>'Video Embed',    'icon'=>'🎬'],
    'contact-form' => ['label'=>'Contact Form',   'icon'=>'📧'],
    'newsletter'   => ['label'=>'Newsletter',     'icon'=>'📬'],
    'pricing'      => ['label'=>'Pricing Tables', 'icon'=>'💰'],
    'social'       => ['label'=>'Social Media',   'icon'=>'🔗'],
    'map'          => ['label'=>'Map Location',   'icon'=>'📍'],
    'html'         => ['label'=>'Custom HTML',    'icon'=>'💻'],
];

$widgetCategories = [
    'Layout'  => ['hero', 'about-hero', 'cta'],
    'Konten'  => ['text', 'features', 'team', 'faq', 'testimonials', 'visi-misi', 'values', 'timeline'],
    'Media'   => ['gallery', 'video'],
    'Form'    => ['contact-form', 'newsletter'],
    'Info'    => ['pricing', 'social', 'map', 'html'],
];

$previewUrl = url('/' . $page->slug);
@endphp

@section('content')
<div
    x-data="pageBuilder()"
    x-init="init()"
    class="flex flex-col"
    style="height: calc(100vh - 4rem); margin: -1.5rem -1rem -1rem; overflow: hidden;"
>

{{-- ═══════════════════════════════════════════════
     TOP BAR
═══════════════════════════════════════════════ --}}
<div class="bg-white border-b border-slate-200 px-4 py-2.5 flex items-center justify-between gap-3 shrink-0 z-10 shadow-sm">
    <div class="flex items-center gap-3 min-w-0">
        <a href="{{ route('admin.pages.edit', $page) }}"
           class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div class="min-w-0">
            <h1 class="text-sm font-bold text-slate-800 truncate">{{ $page->title }}</h1>
            <p class="text-[10px] text-slate-400 hidden sm:block">Page Builder</p>
        </div>
    </div>

    <div class="flex items-center gap-2 shrink-0">
        <span x-show="saving" class="text-xs text-slate-400 flex items-center gap-1">
            <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Menyimpan…
        </span>
        <span x-show="saved && !saving" x-cloak class="text-xs text-green-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Tersimpan
        </span>

        <a href="{{ url('/' . $page->slug) }}" target="_blank"
           class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Preview
        </a>

        <button @click="savePage()" :disabled="saving"
                class="px-4 py-1.5 text-xs font-bold text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Simpan
        </button>
    </div>
</div>

{{-- ═══════════════════════════════════════════════
     MAIN: LEFT sidebar + CENTER canvas + RIGHT properties
═══════════════════════════════════════════════ --}}
<div class="flex flex-1 overflow-hidden">

    {{-- ── LEFT: Widget Sidebar (280px) ── --}}
    <aside class="w-[280px] shrink-0 bg-white border-r border-slate-200 flex flex-col overflow-hidden">
        <div class="px-3 py-2.5 border-b border-slate-100 shrink-0">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2">Widget</p>
            <div class="relative">
                <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" x-model="widgetSearch" placeholder="Cari widget…"
                       class="w-full pl-8 pr-3 py-1.5 text-xs rounded-lg border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary bg-slate-50">
            </div>
        </div>
        <div id="sidebar-widgets" class="flex-1 overflow-y-auto py-2">
            @foreach($widgetCategories as $cat => $types)
            <div class="mb-3">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider px-3 py-1">{{ $cat }}</p>
                @foreach($types as $type)
                @php $meta = $componentMeta[$type] ?? ['label'=>$type,'icon'=>'📦']; @endphp
                <div data-widget-type="{{ $type }}"
                     draggable="true"
                     x-show="!widgetSearch || '{{ strtolower($meta['label']) }}'.includes(widgetSearch.toLowerCase())"
                     class="sidebar-widget-item flex items-center gap-2.5 px-3 py-2 hover:bg-primary/5 hover:text-primary transition-all cursor-grab active:cursor-grabbing select-none group">
                    <span class="text-base w-6 text-center shrink-0 group-hover:scale-110 transition-transform">{{ $meta['icon'] }}</span>
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold text-slate-700 group-hover:text-primary leading-tight truncate">{{ $meta['label'] }}</p>
                        <p class="text-[10px] text-slate-400 leading-tight">Drag ke kolom</p>
                    </div>
                    <svg class="w-3 h-3 text-slate-300 ml-auto shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 12a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                </div>
                @endforeach
            </div>
            @endforeach
        </div>
    </aside>

    {{-- ── CENTER: Canvas ── --}}
    <div class="flex-1 flex flex-col overflow-hidden bg-slate-100 min-w-0">
        {{-- Canvas body --}}
        <div class="flex-1 overflow-y-auto p-5" id="canvas-scroll">

            {{-- Empty state --}}
            <div x-show="rows.length === 0" x-cloak
                 class="flex flex-col items-center justify-center min-h-[400px] text-center">
                <div class="w-20 h-20 rounded-2xl bg-white border-2 border-dashed border-slate-300 flex items-center justify-center text-3xl mb-4">🏗️</div>
                <h3 class="text-sm font-bold text-slate-600 mb-1">Canvas Kosong</h3>
                <p class="text-xs text-slate-400 mb-4">Tambah row lalu drag widget dari sidebar kiri ke kolom.</p>
                <button @click="addRow('100')"
                        class="px-4 py-2 text-xs font-semibold text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors inline-flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Row Pertama
                </button>
            </div>

            {{-- Rows --}}
            <div class="space-y-4">
                <template x-for="(row, rowIdx) in rows" :key="row.id">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">

                        {{-- Row Header --}}
                        <div class="flex items-center gap-2 px-3 py-2 bg-slate-50 border-b border-slate-200">
                            {{-- Row label --}}
                            <div class="w-5 h-5 rounded bg-slate-200 flex items-center justify-center shrink-0">
                                <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Row</span>

                            {{-- Layout selector --}}
                            <div class="flex items-center gap-1 ml-2 flex-1 overflow-x-auto">
                                <template x-for="layout in layouts" :key="layout.id">
                                    <button
                                        @click="setRowLayout(rowIdx, layout.id)"
                                        :class="row.layout === layout.id
                                            ? 'bg-primary text-white border-primary'
                                            : 'bg-white text-slate-500 border-slate-200 hover:border-primary hover:text-primary'"
                                        class="px-2 py-0.5 text-[10px] font-semibold rounded border transition-colors whitespace-nowrap shrink-0"
                                        :title="layout.label"
                                        x-text="layout.label">
                                    </button>
                                </template>
                            </div>

                            {{-- Row actions --}}
                            <button @click="removeRow(rowIdx)"
                                    class="w-6 h-6 flex items-center justify-center rounded text-slate-300 hover:text-red-600 hover:bg-red-50 transition-colors shrink-0">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>

                        {{-- Row Columns --}}
                        <div class="p-3 grid gap-3"
                             :style="getRowGridStyle(row.layout)">
                            <template x-for="(col, colIdx) in row.columns" :key="col.id">
                                <div
                                    :style="getColSpanStyle(row.layout, colIdx)"
                                    class="flex flex-col min-h-[80px]">

                                    {{-- Column drop zone --}}
                                    <div
                                        :id="'col-' + row.id + '-' + colIdx"
                                        :data-row-idx="rowIdx"
                                        :data-col-idx="colIdx"
                                        class="widget-dropzone flex-1 rounded-lg border-2 border-dashed border-slate-200 bg-slate-50/50 p-2 transition-colors min-h-[60px] space-y-2">

                                        {{-- Widget cards --}}
                                        <template x-for="(widget, widgetIdx) in col.widgets" :key="widget.id">
                                            <div
                                                :data-widget-id="widget.id"
                                                class="widget-card bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                                                <div class="flex items-center gap-2 px-2.5 py-2">
                                                    {{-- Drag handle --}}
                                                    <div class="widget-drag-handle cursor-grab text-slate-300 hover:text-slate-500 transition-colors shrink-0">
                                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 12a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                                                    </div>
                                                    {{-- Icon + label --}}
                                                    <span class="text-sm shrink-0" x-text="getWidgetIcon(widget.type)"></span>
                                                    <p class="text-xs font-semibold text-slate-700 truncate flex-1" x-text="getWidgetLabel(widget.type)"></p>
                                                    {{-- Visibility --}}
                                                    <button @click="widget.is_visible = !widget.is_visible; unsavedChanges = true"
                                                            :class="widget.is_visible ? 'text-primary' : 'text-slate-300'"
                                                            class="w-5 h-5 flex items-center justify-center rounded hover:bg-slate-100 transition-colors shrink-0">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                    </button>
                                                    {{-- Edit --}}
                                                    <button @click="selectWidget(rowIdx, colIdx, widgetIdx)"
                                                            :class="selectedWidget && selectedWidget.rowIdx===rowIdx && selectedWidget.colIdx===colIdx && selectedWidget.widgetIdx===widgetIdx ? 'text-blue-600 bg-blue-100' : 'text-slate-400 hover:bg-slate-100'"
                                                            class="w-5 h-5 flex items-center justify-center rounded transition-colors shrink-0">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    </button>
                                                    {{-- Delete --}}
                                                    <button @click="removeWidget(rowIdx, colIdx, widgetIdx)"
                                                            class="w-5 h-5 flex items-center justify-center rounded text-slate-300 hover:text-red-600 hover:bg-red-50 transition-colors shrink-0">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>

                                        {{-- Empty drop hint --}}
                                        <div x-show="col.widgets.length === 0"
                                             class="flex flex-col items-center justify-center py-4 text-slate-300 pointer-events-none">
                                            <svg class="w-5 h-5 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"/></svg>
                                            <p class="text-[10px]">Drop widget di sini</p>
                                        </div>
                                    </div>

                                    {{-- Add widget button --}}
                                    <div class="flex justify-center mt-1">
                                        <div class="relative" x-data="{ open: false }">
                                            <button @click="open = !open"
                                                    class="w-6 h-6 flex items-center justify-center rounded-full bg-white border border-slate-300 text-slate-400 hover:text-primary hover:border-primary transition-colors shadow-sm text-sm leading-none"
                                                    title="Tambah widget">+</button>
                                            <div x-show="open" @click.outside="open = false" x-cloak
                                                 class="absolute bottom-8 left-1/2 -translate-x-1/2 bg-white border border-slate-200 rounded-xl shadow-xl z-30 w-48 py-1 max-h-64 overflow-y-auto">
                                                @foreach($componentMeta as $wtype => $wmeta)
                                                <button
                                                    @click="addWidget(rowIdx, colIdx, '{{ $wtype }}'); open = false"
                                                    class="w-full flex items-center gap-2 px-3 py-1.5 text-xs hover:bg-primary/5 hover:text-primary transition-colors text-left">
                                                    <span>{{ $wmeta['icon'] }}</span>
                                                    <span class="font-medium">{{ $wmeta['label'] }}</span>
                                                </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>

            {{-- Add Row button --}}
            <div class="mt-5 flex justify-center" x-show="rows.length > 0">
                <button @click="addRow('100')"
                        class="px-5 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded-xl hover:border-primary hover:text-primary hover:shadow-sm transition-all inline-flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Tambah Row
                </button>
            </div>

        </div>{{-- /canvas-scroll --}}
    </div>{{-- /center --}}

    {{-- ── RIGHT: Properties Panel (320px, conditional) ── --}}
    <div x-show="selectedWidget !== null" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-x-4"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-x-0"
         x-transition:leave-end="opacity-0 translate-x-4"
         class="w-[320px] shrink-0 bg-white border-l border-slate-200 flex flex-col overflow-hidden">

        {{-- Properties header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 shrink-0">
            <div class="flex items-center gap-2">
                <template x-if="selectedWidget !== null">
                    <span class="text-base" x-text="getWidgetIcon(getSelectedWidget()?.type)"></span>
                </template>
                <div>
                    <p class="text-xs font-bold text-slate-800" x-text="getWidgetLabel(getSelectedWidget()?.type)"></p>
                    <p class="text-[10px] text-slate-400">Properties</p>
                </div>
            </div>
            <button @click="selectedWidget = null"
                    class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Properties form --}}
        <div class="flex-1 overflow-y-auto p-4">
            <template x-if="selectedWidget !== null">
                <div class="space-y-3">
                    <template x-for="field in getFields(getSelectedWidget()?.type)" :key="field.key">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1" x-text="field.label"></label>

                            <template x-if="field.type === 'textarea'">
                                <textarea :placeholder="field.placeholder || ''"
                                          x-model="getSelectedWidget().content[field.key]"
                                          @input="unsavedChanges = true"
                                          rows="3"
                                          class="w-full text-xs rounded-lg border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary bg-slate-50 px-2.5 py-2 resize-y"></textarea>
                            </template>

                            <template x-if="field.type === 'select'">
                                <select x-model="getSelectedWidget().content[field.key]"
                                        @change="unsavedChanges = true"
                                        class="w-full text-xs rounded-lg border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary bg-slate-50 px-2.5 py-2">
                                    <template x-for="opt in field.options" :key="opt.value">
                                        <option :value="opt.value" x-text="opt.label"></option>
                                    </template>
                                </select>
                            </template>

                            <template x-if="field.type === 'checkbox'">
                                <label class="flex items-center gap-2 cursor-pointer mt-1">
                                    <input type="checkbox"
                                           x-model="getSelectedWidget().content[field.key]"
                                           @change="unsavedChanges = true"
                                           class="rounded border-slate-300 text-primary focus:ring-primary w-4 h-4">
                                    <span class="text-xs text-slate-600" x-text="field.checkLabel || 'Aktif'"></span>
                                </label>
                            </template>

                            <template x-if="!field.type || field.type === 'text'">
                                <input type="text"
                                       :placeholder="field.placeholder || ''"
                                       x-model="getSelectedWidget().content[field.key]"
                                       @input="unsavedChanges = true"
                                       class="w-full text-xs rounded-lg border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary bg-slate-50 px-2.5 py-2">
                            </template>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        {{-- Properties footer --}}
        <div class="px-4 py-3 border-t border-slate-100 shrink-0 flex gap-2">
            <button @click="savePage()"
                    :disabled="saving"
                    class="flex-1 px-3 py-2 text-xs font-bold text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-60 inline-flex items-center justify-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan
            </button>
            <button @click="selectedWidget = null"
                    class="px-3 py-2 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                Tutup
            </button>
        </div>

    </div>{{-- /properties panel --}}

</div>{{-- /flex main --}}

{{-- ═══════════════════════════════════════════════
     DELETE CONFIRMATION MODAL
═══════════════════════════════════════════════ --}}
<div x-show="deleteModal.open"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-50 flex items-center justify-center p-4"
     style="background: rgba(0,0,0,0.5)">
    <div x-show="deleteModal.open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         @click.stop
         class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-slate-800" x-text="deleteModal.title || 'Hapus?'"></h3>
                <p class="text-xs text-slate-500 mt-0.5">Tindakan ini tidak bisa dibatalkan.</p>
            </div>
        </div>
        <p class="text-xs text-slate-600 mb-5">
            Anda akan menghapus <strong x-text="deleteModal.label" class="text-slate-800"></strong>. Lanjutkan?
        </p>
        <div class="flex items-center gap-2 justify-end">
            <button @click="deleteModal.open = false; deleteModal.target = null"
                    class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                Batalkan
            </button>
            <button @click="confirmDelete()"
                    class="px-4 py-2 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-xl transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

</div>{{-- /x-data --}}

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
const SAVE_URL  = "{{ route('admin.page-builder.save', $page) }}";
const CSRF      = "{{ csrf_token() }}";
const COMP_META = @json($componentMeta);

// ── Field definitions for properties panel ────────────────────────────
const FIELD_MAP = {
    'about-hero':  [
        {key:'title',    label:'Judul (HTML diizinkan)', type:'textarea', full:true, placeholder:'Membuat pembayaran digital terasa <span class="text-primary">mudah</span>...'},
        {key:'subtitle', label:'Subjudul',               type:'textarea', placeholder:'Deskripsi singkat perusahaan...'},
        {key:'stats',    label:'Statistik (JSON)',        type:'textarea', full:true,  placeholder:'[{"value":"100K+","label":"Pengguna Aktif"},{"value":"500+","label":"Produk"}]'},
    ],
    'visi-misi': [
        {key:'visi_title', label:'Judul Visi',                placeholder:'Visi'},
        {key:'visi_text',  label:'Teks Visi',  type:'textarea', full:true, placeholder:'Menjadi platform...'},
        {key:'misi_title', label:'Judul Misi',                placeholder:'Misi'},
        {key:'misi_items', label:'Item Misi (1 per baris)', type:'textarea', full:true, placeholder:'Menyediakan akses layanan...\nMembangun ekosistem agen...'},
    ],
    'values': [
        {key:'section_label',    label:'Label Seksi',  placeholder:'Nilai Kami'},
        {key:'section_title',    label:'Judul Seksi',  placeholder:'Prinsip yang kami pegang'},
        {key:'section_subtitle', label:'Subtitle',     placeholder:'...'},
        {key:'items',            label:'Item (JSON)',   type:'textarea', full:true, placeholder:'[{"icon":"🤝","title":"Transparansi","desc":"Deskripsi..."}]'},
    ],
    'timeline': [
        {key:'section_label',    label:'Label Seksi',     placeholder:'Perjalanan Kami'},
        {key:'section_title',    label:'Judul',           placeholder:'Tumbuh selangkah demi selangkah'},
        {key:'section_subtitle', label:'Subtitle',        type:'textarea', placeholder:'...'},
        {key:'items',            label:'Item Timeline (JSON)', type:'textarea', full:true, placeholder:'[{"year":"2019","color":"#0D9488","title":"Didirikan","desc":"Deskripsi..."}]'},
    ],
    'hero': [
        {key:'title',        label:'Judul Utama',  placeholder:'Solusi Terbaik…'},
        {key:'subtitle',     label:'Subtitle',     type:'textarea', placeholder:'Deskripsi singkat…'},
        {key:'badge_text',   label:'Badge',        placeholder:'Baru!'},
        {key:'button_text',  label:'Tombol 1',     placeholder:'Mulai Sekarang'},
        {key:'button_link',  label:'Link Tombol 1',placeholder:'https://'},
        {key:'button2_text', label:'Tombol 2',     placeholder:'Pelajari'},
        {key:'button2_link', label:'Link Tombol 2',placeholder:'#'},
        {key:'height',       label:'Tinggi',       type:'select', options:[{value:'medium',label:'Sedang'},{value:'large',label:'Besar'},{value:'fullscreen',label:'Full Screen'}]},
        {key:'text_align',   label:'Rata',         type:'select', options:[{value:'center',label:'Tengah'},{value:'left',label:'Kiri'},{value:'right',label:'Kanan'}]},
    ],
    'text': [
        {key:'heading',       label:'Judul'},
        {key:'heading_level', label:'Level', type:'select', options:[{value:'h2',label:'H2'},{value:'h3',label:'H3'},{value:'h4',label:'H4'}]},
        {key:'text',          label:'Konten HTML', type:'textarea', full:true, placeholder:'<p>Teks…</p>'},
        {key:'text_align',    label:'Rata', type:'select', options:[{value:'left',label:'Kiri'},{value:'center',label:'Tengah'},{value:'right',label:'Kanan'}]},
    ],
    'features': [
        {key:'section_title',    label:'Judul Seksi'},
        {key:'section_subtitle', label:'Subtitle'},
        {key:'columns', label:'Kolom', type:'select', options:[{value:'2',label:'2'},{value:'3',label:'3'},{value:'4',label:'4'}]},
        {key:'style',   label:'Gaya',  type:'select', options:[{value:'cards',label:'Kartu'},{value:'icons',label:'Ikon'},{value:'numbered',label:'Nomor'}]},
        {key:'features',label:'Item (JSON)', type:'textarea', full:true, placeholder:'[{"icon_emoji":"⚡","title":"Judul","description":"Deskripsi"}]'},
    ],
    'cta': [
        {key:'heading',      label:'Judul'},
        {key:'subheading',   label:'Subtitle',  type:'textarea'},
        {key:'button_text',  label:'Tombol 1'},
        {key:'button_link',  label:'Link 1',    placeholder:'https://'},
        {key:'button2_text', label:'Tombol 2'},
        {key:'button2_link', label:'Link 2',    placeholder:'https://'},
    ],
    'testimonials': [
        {key:'section_title',    label:'Judul'},
        {key:'section_subtitle', label:'Subtitle'},
        {key:'columns', label:'Kolom', type:'select', options:[{value:'2',label:'2'},{value:'3',label:'3'}]},
        {key:'items',   label:'Testimoni (JSON)', type:'textarea', full:true, placeholder:'[{"name":"Budi","role":"Agen","quote":"Ulasan…","rating":5}]'},
    ],
    'team': [
        {key:'section_title',    label:'Judul'},
        {key:'section_subtitle', label:'Subtitle'},
        {key:'columns',  label:'Kolom',   type:'select', options:[{value:'3',label:'3'},{value:'4',label:'4'},{value:'2',label:'2'}]},
        {key:'members',  label:'Anggota (JSON)', type:'textarea', full:true, placeholder:'[{"name":"Nama","role":"Jabatan","bio":"Bio…"}]'},
    ],
    'gallery': [
        {key:'section_title', label:'Judul'},
        {key:'columns', label:'Kolom', type:'select', options:[{value:'2',label:'2'},{value:'3',label:'3'},{value:'4',label:'4'}]},
        {key:'images',  label:'Gambar (JSON)', type:'textarea', full:true, placeholder:'[{"url":"https://…","caption":"Ket","alt":"Alt"}]'},
    ],
    'pricing': [
        {key:'section_title',    label:'Judul'},
        {key:'section_subtitle', label:'Subtitle'},
        {key:'currency', label:'Mata Uang', placeholder:'Rp'},
        {key:'plans',    label:'Paket (JSON)', type:'textarea', full:true, placeholder:'[{"name":"Starter","price":"0","period":"bulan","is_popular":false,"button_text":"Mulai","button_link":"/kontak","features":["Fitur 1"]}]'},
    ],
    'faq': [
        {key:'section_title',    label:'Judul'},
        {key:'section_subtitle', label:'Subtitle'},
        {key:'columns', label:'Kolom', type:'select', options:[{value:'1',label:'1'},{value:'2',label:'2'}]},
        {key:'items',   label:'FAQ (JSON)', type:'textarea', full:true, placeholder:'[{"question":"Pertanyaan?","answer":"Jawaban."}]'},
    ],
    'video': [
        {key:'title',     label:'Judul'},
        {key:'subtitle',  label:'Subtitle'},
        {key:'video_url', label:'URL Video', full:true, placeholder:'https://www.youtube.com/watch?v=…'},
        {key:'caption',   label:'Keterangan'},
    ],
    'contact-form': [
        {key:'title',           label:'Judul Form'},
        {key:'subtitle',        label:'Subtitle',       type:'textarea'},
        {key:'button_text',     label:'Teks Tombol',    placeholder:'Kirim'},
        {key:'success_message', label:'Pesan Sukses'},
    ],
    'newsletter': [
        {key:'title',       label:'Judul'},
        {key:'subtitle',    label:'Subtitle'},
        {key:'button_text', label:'Teks Tombol', placeholder:'Daftar'},
        {key:'bg_style',    label:'Background', type:'select', options:[{value:'gradient',label:'Gradient'},{value:'light',label:'Terang'},{value:'dark',label:'Gelap'}]},
    ],
    'social': [
        {key:'title',     label:'Judul'},
        {key:'facebook',  label:'Facebook URL'},
        {key:'instagram', label:'Instagram URL'},
        {key:'twitter',   label:'Twitter URL'},
        {key:'linkedin',  label:'LinkedIn URL'},
        {key:'youtube',   label:'YouTube URL'},
        {key:'tiktok',    label:'TikTok URL'},
        {key:'style',     label:'Gaya', type:'select', options:[{value:'icon-only',label:'Ikon saja'},{value:'buttons',label:'Tombol'}]},
    ],
    'map': [
        {key:'title',          label:'Judul'},
        {key:'address',        label:'Alamat',       type:'textarea'},
        {key:'embed_url',      label:'Google Maps Embed URL', full:true, placeholder:'https://www.google.com/maps/embed?pb=…'},
        {key:'height',         label:'Tinggi', type:'select', options:[{value:'300',label:'300px'},{value:'400',label:'400px'},{value:'500',label:'500px'}]},
        {key:'show_info_card', label:'Kartu Info', type:'checkbox', checkLabel:'Tampilkan info alamat'},
    ],
    'html': [
        {key:'title',        label:'Judul'},
        {key:'html_content', label:'Konten HTML', type:'textarea', full:true, placeholder:'<p>HTML Anda…</p>'},
        {key:'css_content',  label:'CSS Tambahan', type:'textarea', full:true, placeholder:'/* CSS… */'},
    ],
};

// ── Unique ID counter ──────────────────────────────────────────────────
let _uidCounter = 0;
function uid(prefix) { return prefix + '_' + (++_uidCounter) + '_' + Math.random().toString(36).slice(2, 6); }

// ── Alpine.js component ────────────────────────────────────────────────
function pageBuilder() {
    return {
        rows: [],

        layouts: [
            {id: '100',       label: '1 Kol',  cols: 1},
            {id: '50/50',     label: '50/50',  cols: 2},
            {id: '30/70',     label: '30/70',  cols: 2},
            {id: '70/30',     label: '70/30',  cols: 2},
            {id: '33/33/33',  label: '3 Kol',  cols: 3},
            {id: '25/75',     label: '25/75',  cols: 2},
        ],

        widgetSearch:     '',
        selectedWidget:   null,   // {rowIdx, colIdx, widgetIdx}
        saving:           false,
        saved:            false,
        unsavedChanges:   false,
        deleteModal: {
            open:   false,
            target: null,  // {type:'row'|'widget', rowIdx, colIdx?, widgetIdx?}
            title:  '',
            label:  '',
        },

        // ── Init ──────────────────────────────────────────────────
        init() {
            const raw = @json($page->components->sortBy('sort_order')->values());

            raw.forEach(c => {
                if (c.component_type === 'row') {
                    // New format: row with columns
                    const colsData = (c.content && c.content.columns) ? c.content.columns : [];
                    const columns = colsData.map(col => ({
                        id: col.id || uid('col'),
                        widgets: (col.widgets || []).map(w => ({
                            id:         w.id       || uid('w'),
                            type:       w.type     || '',
                            content:    w.content  || {},
                            settings:   w.settings || {},
                            is_visible: w.is_visible !== undefined ? w.is_visible : true,
                        })),
                    }));
                    this.rows.push({
                        id:      uid('row'),
                        layout:  (c.settings && c.settings.layout) ? c.settings.layout : '100',
                        columns: columns.length ? columns : this._makeColumns('100'),
                    });
                } else {
                    // Legacy format: flat widget → wrap in 1-col row
                    this.rows.push({
                        id:     uid('row'),
                        layout: '100',
                        columns: [{
                            id: uid('col'),
                            widgets: [{
                                id:         uid('w'),
                                type:       c.component_type,
                                content:    c.content  || {},
                                settings:   c.settings || {},
                                is_visible: c.is_visible !== undefined ? c.is_visible : true,
                            }],
                        }],
                    });
                }
            });

            this.$nextTick(() => this.initSortable());
        },

        // ── Row helpers ───────────────────────────────────────────
        _makeColumns(layoutId) {
            const layout = this.layouts.find(l => l.id === layoutId) || {cols: 1};
            const count = typeof layout.cols === 'number' ? layout.cols : 1;
            return Array.from({length: count}, () => ({ id: uid('col'), widgets: [] }));
        },

        addRow(layoutId) {
            this.rows.push({
                id:      uid('row'),
                layout:  layoutId,
                columns: this._makeColumns(layoutId),
            });
            this.unsavedChanges = true;
            this.$nextTick(() => this.initSortable());
        },

        removeRow(rowIdx) {
            this.deleteModal.target = { type: 'row', rowIdx };
            this.deleteModal.title  = 'Hapus Row?';
            this.deleteModal.label  = 'Row ini beserta semua widget di dalamnya';
            this.deleteModal.open   = true;
        },

        setRowLayout(rowIdx, layoutId) {
            const row       = this.rows[rowIdx];
            const newLayout = this.layouts.find(l => l.id === layoutId) || {cols: 1};
            const newCols   = typeof newLayout.cols === 'number' ? newLayout.cols : 1;
            const oldCols   = row.columns;

            const newColumns = [];
            for (let i = 0; i < newCols; i++) {
                if (i < oldCols.length) {
                    newColumns.push({ ...oldCols[i] });
                } else {
                    newColumns.push({ id: uid('col'), widgets: [] });
                }
            }

            row.layout  = layoutId;
            row.columns = newColumns;
            this.unsavedChanges = true;
            this.$nextTick(() => this.initSortable());
        },

        // ── Widget helpers ────────────────────────────────────────
        addWidget(rowIdx, colIdx, type) {
            const widget = {
                id:         uid('w'),
                type:       type,
                content:    {},
                settings:   {},
                is_visible: true,
            };
            this.rows[rowIdx].columns[colIdx].widgets.push(widget);
            const widgetIdx = this.rows[rowIdx].columns[colIdx].widgets.length - 1;
            this.selectWidget(rowIdx, colIdx, widgetIdx);
            this.unsavedChanges = true;
            this.$nextTick(() => this.initSortable());
        },

        removeWidget(rowIdx, colIdx, widgetIdx) {
            const widget = this.rows[rowIdx].columns[colIdx].widgets[widgetIdx];
            this.deleteModal.target = { type: 'widget', rowIdx, colIdx, widgetIdx };
            this.deleteModal.title  = 'Hapus Widget?';
            this.deleteModal.label  = this.getWidgetLabel(widget.type);
            this.deleteModal.open   = true;
        },

        confirmDelete() {
            const t = this.deleteModal.target;
            if (!t) return;
            if (t.type === 'row') {
                this.rows.splice(t.rowIdx, 1);
                if (this.selectedWidget && this.selectedWidget.rowIdx === t.rowIdx) {
                    this.selectedWidget = null;
                }
            } else if (t.type === 'widget') {
                this.rows[t.rowIdx].columns[t.colIdx].widgets.splice(t.widgetIdx, 1);
                if (this.selectedWidget
                    && this.selectedWidget.rowIdx   === t.rowIdx
                    && this.selectedWidget.colIdx   === t.colIdx
                    && this.selectedWidget.widgetIdx === t.widgetIdx) {
                    this.selectedWidget = null;
                }
            }
            this.unsavedChanges = true;
            this.deleteModal.open   = false;
            this.deleteModal.target = null;
        },

        selectWidget(rowIdx, colIdx, widgetIdx) {
            if (this.selectedWidget
                && this.selectedWidget.rowIdx    === rowIdx
                && this.selectedWidget.colIdx    === colIdx
                && this.selectedWidget.widgetIdx === widgetIdx) {
                this.selectedWidget = null;
            } else {
                this.selectedWidget = { rowIdx, colIdx, widgetIdx };
            }
        },

        getSelectedWidget() {
            if (!this.selectedWidget) return null;
            const { rowIdx, colIdx, widgetIdx } = this.selectedWidget;
            return this.rows[rowIdx]?.columns[colIdx]?.widgets[widgetIdx] ?? null;
        },

        // ── Meta helpers ──────────────────────────────────────────
        getWidgetIcon(type)  { return COMP_META[type]?.icon  ?? '📦'; },
        getWidgetLabel(type) { return COMP_META[type]?.label ?? type;  },
        getFields(type)      { return FIELD_MAP[type] ?? [{key:'content', label:'Konten (JSON)', type:'textarea', full:true}]; },

        // ── Layout helpers for CSS ────────────────────────────────
        getRowGridStyle(layoutId) {
            const map = {
                '100':      'grid-template-columns: 1fr;',
                '50/50':    'grid-template-columns: 1fr 1fr;',
                '30/70':    'grid-template-columns: 3fr 7fr;',
                '70/30':    'grid-template-columns: 7fr 3fr;',
                '33/33/33': 'grid-template-columns: 1fr 1fr 1fr;',
                '25/75':    'grid-template-columns: 1fr 3fr;',
            };
            return map[layoutId] || 'grid-template-columns: 1fr;';
        },

        getColSpanStyle(layoutId, colIdx) {
            // All layouts use inline grid-template-columns on parent, no span needed
            return '';
        },

        // ── SortableJS ────────────────────────────────────────────
        _sortableInstances: [],
        _sidebarListeners: [],

        initSortable() {
            // Destroy old instances
            this._sortableInstances.forEach(s => s.destroy());
            this._sortableInstances = [];

            // Remove old native sidebar listeners to avoid duplicates
            this._sidebarListeners.forEach(({el, type, fn}) => el.removeEventListener(type, fn));
            this._sidebarListeners = [];

            // ── Native HTML5 drag & drop: sidebar → canvas ────────
            const sidebarItems = document.querySelectorAll('.sidebar-widget-item');
            sidebarItems.forEach(el => {
                const onDragStart = (event) => {
                    event.dataTransfer.setData('widget-type', el.dataset.widgetType);
                    event.dataTransfer.effectAllowed = 'copy';
                };
                el.addEventListener('dragstart', onDragStart);
                this._sidebarListeners.push({el, type: 'dragstart', fn: onDragStart});
            });

            // ── Column drop zones ─────────────────────────────────
            const zones = document.querySelectorAll('.widget-dropzone');
            zones.forEach(zone => {
                const rowIdx = parseInt(zone.dataset.rowIdx);
                const colIdx = parseInt(zone.dataset.colIdx);

                // Native drop handlers for sidebar items
                const onDragOver = (event) => {
                    event.preventDefault();
                    event.dataTransfer.dropEffect = 'copy';
                    zone.classList.add('border-primary', 'bg-primary/5');
                };
                const onDragLeave = (event) => {
                    // Only remove highlight when truly leaving this zone
                    if (!zone.contains(event.relatedTarget)) {
                        zone.classList.remove('border-primary', 'bg-primary/5');
                    }
                };
                const onDrop = (event) => {
                    event.preventDefault();
                    zone.classList.remove('border-primary', 'bg-primary/5');
                    const widgetType = event.dataTransfer.getData('widget-type');
                    if (widgetType) {
                        this.addWidget(rowIdx, colIdx, widgetType);
                    }
                };

                zone.addEventListener('dragover',   onDragOver);
                zone.addEventListener('dragleave',  onDragLeave);
                zone.addEventListener('drop',       onDrop);
                this._sidebarListeners.push(
                    {el: zone, type: 'dragover',  fn: onDragOver},
                    {el: zone, type: 'dragleave', fn: onDragLeave},
                    {el: zone, type: 'drop',      fn: onDrop},
                );

                // SortableJS for reorder / move between columns
                const instance = Sortable.create(zone, {
                    group:      { name: 'widgets', pull: true, put: true },
                    handle:     '.widget-drag-handle',
                    animation:  200,
                    ghostClass: 'opacity-30',
                    chosenClass:'shadow-xl',
                    dataIdAttr: 'data-widget-id',

                    onEnd: (evt) => {
                        const fromZone = evt.from;
                        const toZone   = evt.to;
                        const fromRowIdx = parseInt(fromZone.dataset.rowIdx);
                        const fromColIdx = parseInt(fromZone.dataset.colIdx);
                        const toRowIdx   = parseInt(toZone.dataset.rowIdx);
                        const toColIdx   = parseInt(toZone.dataset.colIdx);

                        if (fromZone === toZone) {
                            // Reorder within same column
                            const col = this.rows[fromRowIdx].columns[fromColIdx];
                            const [moved] = col.widgets.splice(evt.oldIndex, 1);
                            col.widgets.splice(evt.newIndex, 0, moved);
                        } else {
                            // Move between columns
                            const fromCol = this.rows[fromRowIdx].columns[fromColIdx];
                            const toCol   = this.rows[toRowIdx].columns[toColIdx];
                            const [moved] = fromCol.widgets.splice(evt.oldIndex, 1);
                            toCol.widgets.splice(evt.newIndex, 0, moved);
                        }

                        this.selectedWidget   = null;
                        this.unsavedChanges   = true;
                        this.$nextTick(() => this.initSortable());
                    },
                });

                this._sortableInstances.push(instance);
            });
        },

        // ── Save ──────────────────────────────────────────────────
        async savePage() {
            this.saving = true;
            this.saved  = false;

            const components = this.rows.map((row, idx) => ({
                component_type: 'row',
                content: {
                    columns: row.columns.map(col => ({
                        id:      col.id,
                        widgets: col.widgets.map(w => ({
                            id:         w.id,
                            type:       w.type,
                            content:    w.content  || {},
                            settings:   w.settings || {},
                            is_visible: w.is_visible ? true : false,
                        })),
                    })),
                },
                settings:   { layout: row.layout },
                sort_order: idx + 1,
                is_visible: true,
            }));

            try {
                const res = await fetch(SAVE_URL, {
                    method:  'POST',
                    headers: {
                        'Content-Type':  'application/json',
                        'X-CSRF-TOKEN':  CSRF,
                        'Accept':        'application/json',
                    },
                    body: JSON.stringify({ components }),
                });

                if (res.ok) {
                    this.saved          = true;
                    this.unsavedChanges = false;
                    setTimeout(() => this.saved = false, 3000);
                } else {
                    const err = await res.json().catch(() => ({}));
                    alert('Gagal menyimpan: ' + (err.message || res.statusText));
                }
            } catch (e) {
                alert('Terjadi kesalahan: ' + e.message);
            } finally {
                this.saving = false;
            }
        },
    };
}
</script>
@endsection
