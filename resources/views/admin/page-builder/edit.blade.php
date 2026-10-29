@extends('admin.layouts.app')

@section('title', 'Page Builder — ' . $page->title)

@php
$componentTypes = [
    'layout'  => ['hero','cta'],
    'content' => ['text','features','team','faq','testimonials'],
    'media'   => ['gallery','video'],
    'forms'   => ['contact-form','newsletter'],
    'info'    => ['pricing','social','map','html'],
];
$componentMeta = [
    'hero'         => ['label'=>'Hero Banner',     'icon'=>'🚀', 'desc'=>'Section besar di bagian atas'],
    'cta'          => ['label'=>'Call to Action',  'icon'=>'🎯', 'desc'=>'Ajakan bertindak'],
    'text'         => ['label'=>'Text Block',      'icon'=>'📝', 'desc'=>'Konten teks dengan heading'],
    'features'     => ['label'=>'Features Grid',  'icon'=>'✨', 'desc'=>'Grid fitur/keunggulan'],
    'team'         => ['label'=>'Team Members',   'icon'=>'👥', 'desc'=>'Profil anggota tim'],
    'faq'          => ['label'=>'FAQ Accordion',  'icon'=>'❓', 'desc'=>'Pertanyaan & jawaban'],
    'testimonials' => ['label'=>'Testimonials',   'icon'=>'💬', 'desc'=>'Ulasan pelanggan'],
    'gallery'      => ['label'=>'Image Gallery',  'icon'=>'🖼️', 'desc'=>'Galeri foto'],
    'video'        => ['label'=>'Video Embed',    'icon'=>'🎬', 'desc'=>'YouTube / Vimeo'],
    'contact-form' => ['label'=>'Contact Form',   'icon'=>'📧', 'desc'=>'Formulir kontak'],
    'newsletter'   => ['label'=>'Newsletter',     'icon'=>'📬', 'desc'=>'Form langganan email'],
    'pricing'      => ['label'=>'Pricing Tables', 'icon'=>'💰', 'desc'=>'Tabel harga paket'],
    'social'       => ['label'=>'Social Media',   'icon'=>'🔗', 'desc'=>'Link media sosial'],
    'map'          => ['label'=>'Map Location',   'icon'=>'📍', 'desc'=>'Embed Google Maps'],
    'html'         => ['label'=>'Custom HTML',    'icon'=>'💻', 'desc'=>'HTML & CSS bebas'],
];
$categoryLabels = [
    'layout'  => '📐 Layout',
    'content' => '📝 Konten',
    'media'   => '🖼️ Media',
    'forms'   => '📬 Form',
    'info'    => 'ℹ️ Info',
];

// Signed preview URL so iframe shows this page regardless of active theme
$previewUrl = url('/' . $page->slug);
@endphp

@section('content')
<div
    x-data="pageBuilder()"
    x-init="init()"
    class="flex flex-col"
    style="height: calc(100vh - 4rem); margin: -1.5rem -1rem -1rem; overflow: hidden;"
>

{{-- ══════════════════════════════════════════════════════
     TOP BAR
══════════════════════════════════════════════════════ --}}
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
        {{-- Saving indicator --}}
        <span x-show="saving" class="text-xs text-slate-400 flex items-center gap-1">
            <svg class="w-3 h-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            Menyimpan…
        </span>
        <span x-show="saved && !saving" x-cloak class="text-xs text-green-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
            Tersimpan
        </span>

        {{-- Preview toggle --}}
        <button @click="showPreview = !showPreview"
                :class="showPreview ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <span x-text="showPreview ? 'Sembunyikan Preview' : 'Live Preview'"></span>
        </button>

        {{-- Open in new tab --}}
        <a href="{{ url('/' . $page->slug) }}" target="_blank"
           class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Buka
        </a>

        {{-- Save button --}}
        <button @click="saveAll()" :disabled="saving"
                class="px-4 py-1.5 text-xs font-bold text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center gap-1.5">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            Simpan
        </button>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MAIN AREA: LEFT sidebar + CENTER canvas + RIGHT preview
══════════════════════════════════════════════════════ --}}
<div class="flex flex-1 overflow-hidden">

    {{-- ── LEFT: Component Palette (fixed width) ── --}}
    <aside class="w-56 shrink-0 bg-white border-r border-slate-200 flex flex-col overflow-hidden">
        <div class="px-3 py-2.5 border-b border-slate-100 shrink-0">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Komponen</p>
        </div>
        <div class="flex-1 overflow-y-auto py-2">
            @foreach($componentTypes as $cat => $types)
            <div class="mb-2">
                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider px-3 py-1">{{ $categoryLabels[$cat] }}</p>
                @foreach($types as $type)
                @php $meta = $componentMeta[$type] ?? ['label'=>$type,'icon'=>'📦','desc'=>'']; @endphp
                <button type="button"
                        @click="addComponent('{{ $type }}')"
                        class="w-full flex items-center gap-2 px-3 py-2 hover:bg-primary/5 hover:text-primary transition-all text-left group">
                    <span class="text-base w-6 text-center group-hover:scale-110 transition-transform shrink-0">{{ $meta['icon'] }}</span>
                    <div class="min-w-0">
                        <p class="text-[11px] font-semibold text-slate-700 group-hover:text-primary leading-tight truncate">{{ $meta['label'] }}</p>
                    </div>
                </button>
                @endforeach
            </div>
            @endforeach
        </div>
    </aside>

    {{-- ── CENTER: Canvas ── --}}
    <div class="flex-1 flex flex-col overflow-hidden bg-slate-50 min-w-0"
         :class="showPreview ? 'max-w-[40%]' : ''">

        <div class="flex-1 overflow-y-auto p-4">
            {{-- Empty state --}}
            <div x-show="components.length === 0" x-cloak
                 class="flex flex-col items-center justify-center h-full text-center py-16">
                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-3xl mb-3">🧩</div>
                <h3 class="text-sm font-bold text-slate-600 mb-1">Canvas Kosong</h3>
                <p class="text-xs text-slate-400">Klik komponen di panel kiri untuk mulai.</p>
            </div>

            {{-- Components list --}}
            <div id="components-canvas" class="space-y-3">
                <template x-for="(comp, index) in components" :key="comp._uid">
                    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden"
                         :class="{ 'opacity-50': !comp.is_visible, 'border-primary ring-1 ring-primary': activeEdit === index }">

                        {{-- Card header --}}
                        <div class="flex items-center gap-2 px-3 py-2 bg-slate-50/80 border-b border-slate-100">
                            <div class="drag-handle cursor-grab text-slate-300 hover:text-slate-500 transition-colors">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M8 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 12a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/></svg>
                            </div>
                            <span class="text-sm" x-text="getIcon(comp.component_type)"></span>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-slate-700 truncate" x-text="getLabel(comp.component_type)"></p>
                                <p class="text-[10px] text-slate-400 truncate" x-text="comp.content.title || comp.content.section_title || comp.content.heading || '—'"></p>
                            </div>
                            <div class="flex items-center gap-0.5 shrink-0">
                                <button @click="moveUp(index)" :disabled="index===0"
                                        class="w-6 h-6 flex items-center justify-center rounded text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition-colors disabled:opacity-25">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                </button>
                                <button @click="moveDown(index)" :disabled="index===components.length-1"
                                        class="w-6 h-6 flex items-center justify-center rounded text-slate-400 hover:text-slate-600 hover:bg-slate-200 transition-colors disabled:opacity-25">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <button @click="toggleVisible(index)"
                                        :class="comp.is_visible ? 'text-primary' : 'text-slate-300'"
                                        class="w-6 h-6 flex items-center justify-center rounded hover:bg-slate-200 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                                <button @click="editComponent(index)"
                                        :class="activeEdit===index ? 'text-blue-600 bg-blue-100' : 'text-slate-400 hover:bg-slate-200'"
                                        class="w-6 h-6 flex items-center justify-center rounded transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button @click="removeComponent(index)"
                                        class="w-6 h-6 flex items-center justify-center rounded text-slate-300 hover:text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Inline Edit Form --}}
                        <div x-show="activeEdit === index" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="p-4 border-t border-slate-100 bg-white">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <template x-for="field in getFields(comp.component_type)" :key="field.key">
                                    <div :class="field.full ? 'sm:col-span-2' : ''">
                                        <label class="block text-[11px] font-semibold text-slate-600 mb-1" x-text="field.label"></label>
                                        <template x-if="field.type === 'textarea'">
                                            <textarea :placeholder="field.placeholder || ''"
                                                      x-model="comp.content[field.key]"
                                                      @input="debouncedPreviewRefresh()"
                                                      rows="3"
                                                      class="w-full text-xs rounded-lg border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary bg-slate-50 px-2.5 py-2 resize-y"></textarea>
                                        </template>
                                        <template x-if="field.type === 'select'">
                                            <select x-model="comp.content[field.key]"
                                                    @change="debouncedPreviewRefresh()"
                                                    class="w-full text-xs rounded-lg border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary bg-slate-50 px-2.5 py-2">
                                                <template x-for="opt in field.options" :key="opt.value">
                                                    <option :value="opt.value" x-text="opt.label"></option>
                                                </template>
                                            </select>
                                        </template>
                                        <template x-if="field.type === 'checkbox'">
                                            <label class="flex items-center gap-2 cursor-pointer mt-1">
                                                <input type="checkbox" x-model="comp.content[field.key]"
                                                       @change="debouncedPreviewRefresh()"
                                                       class="rounded border-slate-300 text-primary focus:ring-primary w-4 h-4">
                                                <span class="text-xs text-slate-600" x-text="field.checkLabel || 'Aktif'"></span>
                                            </label>
                                        </template>
                                        <template x-if="!field.type || field.type === 'text'">
                                            <input type="text" :placeholder="field.placeholder || ''"
                                                   x-model="comp.content[field.key]"
                                                   @input="debouncedPreviewRefresh()"
                                                   class="w-full text-xs rounded-lg border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary bg-slate-50 px-2.5 py-2">
                                        </template>
                                    </div>
                                </template>
                            </div>
                            <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100">
                                <button @click="saveAndRefresh()"
                                        class="px-3 py-1.5 text-xs font-bold text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors inline-flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Simpan & Refresh Preview
                                </button>
                                <button @click="activeEdit = null"
                                        class="px-3 py-1.5 text-xs font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- ── RIGHT: Live Preview ── --}}
    <div x-show="showPreview" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-x-4"
         x-transition:enter-end="opacity-100 translate-x-0"
         class="flex-1 flex flex-col border-l border-slate-200 bg-slate-900 overflow-hidden min-w-0">

        {{-- Preview header --}}
        <div class="flex items-center justify-between px-3 py-2 bg-slate-800 shrink-0">
            <div class="flex items-center gap-2">
                {{-- Device switcher --}}
                <div class="flex items-center gap-0.5 bg-slate-700 rounded-lg p-0.5">
                    <button @click="previewDevice='desktop'"
                            :class="previewDevice==='desktop' ? 'bg-slate-500 text-white' : 'text-slate-400'"
                            class="p-1.5 rounded transition-colors" title="Desktop">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </button>
                    <button @click="previewDevice='tablet'"
                            :class="previewDevice==='tablet' ? 'bg-slate-500 text-white' : 'text-slate-400'"
                            class="p-1.5 rounded transition-colors" title="Tablet">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </button>
                    <button @click="previewDevice='mobile'"
                            :class="previewDevice==='mobile' ? 'bg-slate-500 text-white' : 'text-slate-400'"
                            class="p-1.5 rounded transition-colors" title="Mobile">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a1 1 0 001-1V4a1 1 0 00-1-1H8a1 1 0 00-1 1v16a1 1 0 001 1z"/></svg>
                    </button>
                </div>
                <span class="text-[10px] text-slate-400 ml-1">Preview langsung setelah simpan</span>
            </div>
            <button @click="reloadPreview()" class="p-1.5 rounded text-slate-400 hover:text-white hover:bg-slate-700 transition-colors" title="Refresh preview">
                <svg class="w-3.5 h-3.5" :class="previewLoading ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </button>
        </div>

        {{-- iframe container --}}
        <div class="flex-1 flex items-center justify-center bg-slate-900 overflow-hidden p-3">
            <div class="bg-white overflow-hidden shadow-2xl transition-all duration-300 h-full"
                 :class="{
                     'w-full': previewDevice === 'desktop',
                     'w-[768px] max-w-full': previewDevice === 'tablet',
                     'w-[390px] max-w-full': previewDevice === 'mobile',
                 }"
                 style="border-radius:4px">
                {{-- Loading overlay --}}
                <div x-show="previewLoading"
                     class="absolute inset-0 bg-white/80 flex items-center justify-center z-10 rounded">
                    <svg class="w-6 h-6 animate-spin text-primary" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                </div>
                <iframe id="preview-iframe"
                        src="{{ $previewUrl }}"
                        class="w-full h-full border-0"
                        @load="previewLoading = false"
                        title="Live Preview: {{ $page->title }}">
                </iframe>
            </div>
        </div>

        {{-- Unsaved changes notice --}}
        <div x-show="unsavedChanges" x-cloak
             class="px-3 py-2 bg-amber-900/50 border-t border-amber-700/30 text-[10px] text-amber-300 flex items-center gap-1.5 shrink-0">
            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Ada perubahan yang belum disimpan — klik "Simpan & Refresh Preview" untuk melihat hasil terbaru
        </div>
    </div>

</div>{{-- end flex --}}
</div>{{-- end x-data --}}

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
const SAVE_URL = "{{ route('admin.page-builder.save', $page) }}";
const CSRF     = "{{ csrf_token() }}";
const COMPONENT_META = @json($componentMeta);

const FIELD_MAP = {
    hero:         [{key:'title',label:'Judul Utama',placeholder:'Solusi Terbaik…'},{key:'subtitle',label:'Subtitle',type:'textarea',placeholder:'Deskripsi singkat…'},{key:'badge_text',label:'Badge',placeholder:'Baru!'},{key:'button_text',label:'Tombol 1',placeholder:'Mulai Sekarang'},{key:'button_link',label:'Link Tombol 1',placeholder:'https://'},{key:'button2_text',label:'Tombol 2',placeholder:'Pelajari'},{key:'button2_link',label:'Link Tombol 2',placeholder:'#'},{key:'height',label:'Tinggi',type:'select',options:[{value:'medium',label:'Sedang'},{value:'large',label:'Besar'},{value:'fullscreen',label:'Full Screen'}]},{key:'text_align',label:'Rata',type:'select',options:[{value:'center',label:'Tengah'},{value:'left',label:'Kiri'},{value:'right',label:'Kanan'}]}],
    text:         [{key:'heading',label:'Judul'},{key:'heading_level',label:'Level',type:'select',options:[{value:'h2',label:'H2'},{value:'h3',label:'H3'},{value:'h4',label:'H4'}]},{key:'text',label:'Konten HTML',type:'textarea',full:true,placeholder:'<p>Teks…</p>'},{key:'text_align',label:'Rata',type:'select',options:[{value:'left',label:'Kiri'},{value:'center',label:'Tengah'},{value:'right',label:'Kanan'}]}],
    features:     [{key:'section_title',label:'Judul Seksi'},{key:'section_subtitle',label:'Subtitle'},{key:'columns',label:'Kolom',type:'select',options:[{value:'2',label:'2'},{value:'3',label:'3'},{value:'4',label:'4'}]},{key:'style',label:'Gaya',type:'select',options:[{value:'cards',label:'Kartu'},{value:'icons',label:'Ikon'},{value:'numbered',label:'Nomor'}]},{key:'features',label:'Item (JSON)',type:'textarea',full:true,placeholder:'[{"icon_emoji":"⚡","title":"Judul","description":"Deskripsi"}]'}],
    cta:          [{key:'heading',label:'Judul'},{key:'subheading',label:'Subtitle',type:'textarea'},{key:'button_text',label:'Tombol 1'},{key:'button_link',label:'Link 1',placeholder:'https://'},{key:'button2_text',label:'Tombol 2'},{key:'button2_link',label:'Link 2',placeholder:'https://'}],
    testimonials: [{key:'section_title',label:'Judul'},{key:'section_subtitle',label:'Subtitle'},{key:'columns',label:'Kolom',type:'select',options:[{value:'2',label:'2'},{value:'3',label:'3'}]},{key:'items',label:'Testimoni (JSON)',type:'textarea',full:true,placeholder:'[{"name":"Budi","role":"Agen","quote":"Ulasan…","rating":5}]'}],
    team:         [{key:'section_title',label:'Judul'},{key:'section_subtitle',label:'Subtitle'},{key:'columns',label:'Kolom',type:'select',options:[{value:'3',label:'3'},{value:'4',label:'4'},{value:'2',label:'2'}]},{key:'members',label:'Anggota (JSON)',type:'textarea',full:true,placeholder:'[{"name":"Nama","role":"Jabatan","bio":"Bio…"}]'}],
    gallery:      [{key:'section_title',label:'Judul'},{key:'columns',label:'Kolom',type:'select',options:[{value:'2',label:'2'},{value:'3',label:'3'},{value:'4',label:'4'}]},{key:'images',label:'Gambar (JSON)',type:'textarea',full:true,placeholder:'[{"url":"https://…","caption":"Ket","alt":"Alt"}]'}],
    pricing:      [{key:'section_title',label:'Judul'},{key:'section_subtitle',label:'Subtitle'},{key:'currency',label:'Mata Uang',placeholder:'Rp'},{key:'plans',label:'Paket (JSON)',type:'textarea',full:true,placeholder:'[{"name":"Starter","price":"0","period":"bulan","is_popular":false,"button_text":"Mulai","button_link":"/kontak","features":["Fitur 1"]}]'}],
    faq:          [{key:'section_title',label:'Judul'},{key:'section_subtitle',label:'Subtitle'},{key:'columns',label:'Kolom',type:'select',options:[{value:'1',label:'1'},{value:'2',label:'2'}]},{key:'items',label:'FAQ (JSON)',type:'textarea',full:true,placeholder:'[{"question":"Pertanyaan?","answer":"Jawaban."}]'}],
    video:        [{key:'title',label:'Judul'},{key:'subtitle',label:'Subtitle'},{key:'video_url',label:'URL Video',full:true,placeholder:'https://www.youtube.com/watch?v=…'},{key:'caption',label:'Keterangan'}],
    'contact-form':[{key:'title',label:'Judul Form'},{key:'subtitle',label:'Subtitle',type:'textarea'},{key:'button_text',label:'Teks Tombol',placeholder:'Kirim'},{key:'success_message',label:'Pesan Sukses'}],
    newsletter:   [{key:'title',label:'Judul'},{key:'subtitle',label:'Subtitle'},{key:'button_text',label:'Teks Tombol',placeholder:'Daftar'},{key:'bg_style',label:'Background',type:'select',options:[{value:'gradient',label:'Gradient'},{value:'light',label:'Terang'},{value:'dark',label:'Gelap'}]}],
    social:       [{key:'title',label:'Judul'},{key:'facebook',label:'Facebook URL'},{key:'instagram',label:'Instagram URL'},{key:'twitter',label:'Twitter URL'},{key:'linkedin',label:'LinkedIn URL'},{key:'youtube',label:'YouTube URL'},{key:'tiktok',label:'TikTok URL'},{key:'style',label:'Gaya',type:'select',options:[{value:'icon-only',label:'Ikon saja'},{value:'buttons',label:'Tombol'}]}],
    map:          [{key:'title',label:'Judul'},{key:'address',label:'Alamat',type:'textarea'},{key:'embed_url',label:'Google Maps Embed URL',full:true,placeholder:'https://www.google.com/maps/embed?pb=…'},{key:'height',label:'Tinggi',type:'select',options:[{value:'300',label:'300px'},{value:'400',label:'400px'},{value:'500',label:'500px'}]},{key:'show_info_card',label:'Kartu Info',type:'checkbox',checkLabel:'Tampilkan info alamat'}],
    html:         [{key:'title',label:'Judul'},{key:'html_content',label:'Konten HTML',type:'textarea',full:true,placeholder:'<p>HTML Anda…</p>'},{key:'css_content',label:'CSS Tambahan',type:'textarea',full:true,placeholder:'/* CSS… */'}],
};

function pageBuilder() {
    return {
        components: [],
        saving: false,
        saved:  false,
        activeEdit: null,
        _uid: 0,

        // Preview state
        showPreview:    true,
        previewDevice:  'desktop',
        previewLoading: false,
        unsavedChanges: false,
        _previewDebounce: null,

        init() {
            const raw = @json($page->components->sortBy('sort_order')->values());
            this.components = raw.map(c => ({
                ...c,
                _uid: ++this._uid,
                content:  (typeof c.content  === 'object' && c.content  !== null) ? c.content  : {},
                settings: (typeof c.settings === 'object' && c.settings !== null) ? c.settings : {},
            }));

            this.$nextTick(() => {
                const el = document.getElementById('components-canvas');
                if (el && window.Sortable) {
                    Sortable.create(el, {
                        handle: '.drag-handle',
                        animation: 200,
                        ghostClass: 'opacity-30',
                        chosenClass: 'shadow-xl',
                        onEnd: (evt) => {
                            const moved = this.components.splice(evt.oldIndex, 1)[0];
                            this.components.splice(evt.newIndex, 0, moved);
                            this.reorder();
                            this.unsavedChanges = true;
                        }
                    });
                }
            });
        },

        // ── Preview ──────────────────────────────────────────────
        reloadPreview() {
            const frame = document.getElementById('preview-iframe');
            if (!frame) return;
            this.previewLoading = true;
            frame.src = frame.src.split('?')[0] + '?_t=' + Date.now();
        },

        debouncedPreviewRefresh() {
            this.unsavedChanges = true;
            // No auto-reload on every keystroke — user should save first
        },

        async saveAndRefresh() {
            await this.saveAll();
            this.$nextTick(() => this.reloadPreview());
        },

        // ── Components ───────────────────────────────────────────
        addComponent(type) {
            this.components.push({
                _uid: ++this._uid,
                component_type: type,
                content:  {},
                settings: {},
                sort_order: this.components.length + 1,
                is_visible: true,
            });
            this.activeEdit = this.components.length - 1;
            this.unsavedChanges = true;
            this.$nextTick(() => {
                document.getElementById('components-canvas')
                    ?.lastElementChild
                    ?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        },

        removeComponent(index) {
            if (!confirm('Hapus komponen ini?')) return;
            this.components.splice(index, 1);
            this.reorder();
            if (this.activeEdit === index) this.activeEdit = null;
            this.unsavedChanges = true;
            this.saveAll();
        },

        moveUp(index) {
            if (index === 0) return;
            [this.components[index-1], this.components[index]] = [this.components[index], this.components[index-1]];
            this.reorder();
            this.activeEdit = index - 1;
            this.unsavedChanges = true;
        },

        moveDown(index) {
            if (index >= this.components.length - 1) return;
            [this.components[index], this.components[index+1]] = [this.components[index+1], this.components[index]];
            this.reorder();
            this.activeEdit = index + 1;
            this.unsavedChanges = true;
        },

        toggleVisible(index) {
            this.components[index].is_visible = !this.components[index].is_visible;
            this.unsavedChanges = true;
        },

        editComponent(index) {
            this.activeEdit = this.activeEdit === index ? null : index;
        },

        reorder() {
            this.components.forEach((c, i) => c.sort_order = i + 1);
        },

        getIcon(type)  { return COMPONENT_META[type]?.icon  ?? '📦'; },
        getLabel(type) { return COMPONENT_META[type]?.label ?? type; },
        getFields(type){ return FIELD_MAP[type] ?? [{key:'content',label:'Konten (JSON)',type:'textarea',full:true}]; },

        async saveAll() {
            this.saving = true;
            this.saved  = false;
            this.reorder();

            const payload = this.components.map(c => ({
                component_type: c.component_type,
                content:        c.content  || {},
                settings:       c.settings || {},
                sort_order:     c.sort_order,
                is_visible:     c.is_visible ? true : false,
            }));

            try {
                const res = await fetch(SAVE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ components: payload }),
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
