@extends('admin.layouts.app')

@section('title', 'Page Builder — ' . $page->title)

@section('content')
@php
$componentTypes = [
    'layout'  => ['hero','cta'],
    'content' => ['text','features','team','faq','testimonials'],
    'media'   => ['gallery','video'],
    'forms'   => ['contact-form','newsletter'],
    'info'    => ['pricing','social','map','html'],
];
$componentMeta = [
    'hero'         => ['label'=>'Hero Banner',      'icon'=>'🚀', 'desc'=>'Section besar di bagian atas'],
    'cta'          => ['label'=>'Call to Action',   'icon'=>'🎯', 'desc'=>'Ajakan bertindak'],
    'text'         => ['label'=>'Text Block',       'icon'=>'📝', 'desc'=>'Konten teks dengan heading'],
    'features'     => ['label'=>'Features Grid',   'icon'=>'✨', 'desc'=>'Grid fitur/keunggulan'],
    'team'         => ['label'=>'Team Members',    'icon'=>'👥', 'desc'=>'Profil anggota tim'],
    'faq'          => ['label'=>'FAQ Accordion',   'icon'=>'❓', 'desc'=>'Pertanyaan & jawaban'],
    'testimonials' => ['label'=>'Testimonials',    'icon'=>'💬', 'desc'=>'Ulasan pelanggan'],
    'gallery'      => ['label'=>'Image Gallery',   'icon'=>'🖼️', 'desc'=>'Galeri foto'],
    'video'        => ['label'=>'Video Embed',     'icon'=>'🎬', 'desc'=>'YouTube / Vimeo'],
    'contact-form' => ['label'=>'Contact Form',    'icon'=>'📧', 'desc'=>'Formulir kontak'],
    'newsletter'   => ['label'=>'Newsletter',      'icon'=>'📬', 'desc'=>'Form langganan email'],
    'pricing'      => ['label'=>'Pricing Tables',  'icon'=>'💰', 'desc'=>'Tabel harga paket'],
    'social'       => ['label'=>'Social Media',    'icon'=>'🔗', 'desc'=>'Link media sosial'],
    'map'          => ['label'=>'Map Location',    'icon'=>'📍', 'desc'=>'Embed Google Maps'],
    'html'         => ['label'=>'Custom HTML',     'icon'=>'💻', 'desc'=>'HTML & CSS bebas'],
];
$categoryLabels = [
    'layout'  => '📐 Layout',
    'content' => '📝 Konten',
    'media'   => '🖼️ Media',
    'forms'   => '📬 Form',
    'info'    => 'ℹ️ Info',
];
@endphp

<div
    x-data="pageBuilder()"
    x-init="init()"
    class="flex h-[calc(100vh-4rem)] overflow-hidden -mx-4 sm:-mx-6 lg:-mx-8 -mt-6"
>

{{-- ═══════════════════════════════════════════
     LEFT SIDEBAR — Component Palette
═══════════════════════════════════════════ --}}
<aside class="w-72 shrink-0 bg-white border-r border-slate-200 flex flex-col overflow-hidden">
    <div class="p-4 border-b border-slate-100">
        <h2 class="text-sm font-bold text-slate-700 uppercase tracking-wider">Komponen</h2>
        <p class="text-xs text-slate-400 mt-0.5">Klik untuk menambahkan ke halaman</p>
    </div>
    <div class="flex-1 overflow-y-auto p-3 space-y-4">
        @foreach($componentTypes as $cat => $types)
        <div>
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-2 mb-2">
                {{ $categoryLabels[$cat] }}
            </p>
            @foreach($types as $type)
            @php $meta = $componentMeta[$type] ?? ['label'=>$type,'icon'=>'📦','desc'=>'']; @endphp
            <button type="button"
                    @click="addComponent('{{ $type }}')"
                    class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-primary/5 hover:text-primary transition-all text-left group mb-1">
                <span class="text-xl w-7 text-center group-hover:scale-110 transition-transform">{{ $meta['icon'] }}</span>
                <div class="min-w-0">
                    <p class="text-xs font-semibold text-slate-700 group-hover:text-primary leading-tight">{{ $meta['label'] }}</p>
                    <p class="text-[10px] text-slate-400 leading-tight truncate">{{ $meta['desc'] }}</p>
                </div>
            </button>
            @endforeach
        </div>
        @endforeach
    </div>
</aside>

{{-- ═══════════════════════════════════════════
     MAIN CANVAS
═══════════════════════════════════════════ --}}
<main class="flex-1 flex flex-col overflow-hidden bg-slate-50">

    {{-- Canvas Header --}}
    <div class="bg-white border-b border-slate-200 px-6 py-3 flex items-center justify-between gap-4 shrink-0">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.pages.edit', $page) }}"
               class="p-1.5 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="min-w-0">
                <h1 class="text-sm font-bold text-slate-800 truncate">{{ $page->title }}</h1>
                <p class="text-xs text-slate-400">Page Builder</p>
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
               class="px-3 py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Preview
            </a>
            <button @click="saveAll()"
                    :disabled="saving"
                    class="px-4 py-1.5 text-xs font-bold text-white rounded-lg transition-all disabled:opacity-60 disabled:cursor-not-allowed bg-primary hover:bg-primary-dark">
                Simpan Semua
            </button>
        </div>
    </div>

    {{-- Canvas Body --}}
    <div class="flex-1 overflow-y-auto p-6">

        {{-- Empty state --}}
        <div x-show="components.length === 0" x-cloak
             class="flex flex-col items-center justify-center h-full text-center py-20">
            <div class="w-20 h-20 rounded-2xl bg-slate-100 flex items-center justify-center text-4xl mb-4">🧩</div>
            <h3 class="text-lg font-bold text-slate-600 mb-2">Canvas Kosong</h3>
            <p class="text-sm text-slate-400">Klik komponen di panel kiri untuk mulai membangun halaman.</p>
        </div>

        {{-- Components list --}}
        <div id="components-canvas" class="space-y-4">
            <template x-for="(comp, index) in components" :key="comp._uid">
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden transition-all hover:shadow-md"
                     :class="{ 'opacity-50': !comp.is_visible }">

                    {{-- Component card header --}}
                    <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-100 bg-slate-50/50">
                        {{-- Drag handle --}}
                        <div class="drag-handle cursor-grab active:cursor-grabbing text-slate-300 hover:text-slate-500 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 6a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 12a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3zM8 18a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm8 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/>
                            </svg>
                        </div>

                        {{-- Type badge --}}
                        <span class="text-base leading-none" x-text="getIcon(comp.component_type)"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-700" x-text="getLabel(comp.component_type)"></p>
                            <p class="text-xs text-slate-400 truncate" x-text="comp.content.title || comp.content.section_title || comp.content.heading || '—'"></p>
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center gap-1">
                            <button @click="moveUp(index)" :disabled="index===0"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors disabled:opacity-30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                            </button>
                            <button @click="moveDown(index)" :disabled="index===components.length-1"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors disabled:opacity-30">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <button @click="toggleVisible(index)"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors"
                                    :class="comp.is_visible ? 'text-primary bg-primary/10 hover:bg-primary/20' : 'text-slate-300 hover:text-slate-500 hover:bg-slate-100'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            </button>
                            <button @click="editComponent(index)"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg transition-colors"
                                    :class="activeEdit===index ? 'text-blue-600 bg-blue-100' : 'text-slate-400 hover:text-slate-600 hover:bg-slate-100'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button @click="removeComponent(index)"
                                    class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-300 hover:text-red-600 hover:bg-red-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Inline Edit Form --}}
                    <div x-show="activeEdit === index"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 -translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-cloak
                         class="p-5 border-t border-slate-100 bg-white">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <template x-for="field in getFields(comp.component_type)" :key="field.key">
                                <div :class="field.full ? 'sm:col-span-2' : ''">
                                    <label class="block text-xs font-semibold text-slate-600 mb-1" x-text="field.label"></label>
                                    <template x-if="field.type === 'textarea'">
                                        <textarea :placeholder="field.placeholder || ''"
                                                  x-model="comp.content[field.key]"
                                                  rows="4"
                                                  class="w-full text-sm rounded-lg border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary bg-slate-50 px-3 py-2 resize-y"></textarea>
                                    </template>
                                    <template x-if="field.type === 'select'">
                                        <select x-model="comp.content[field.key]"
                                                class="w-full text-sm rounded-lg border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary bg-slate-50 px-3 py-2">
                                            <template x-for="opt in field.options" :key="opt.value">
                                                <option :value="opt.value" x-text="opt.label"></option>
                                            </template>
                                        </select>
                                    </template>
                                    <template x-if="field.type === 'checkbox'">
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" x-model="comp.content[field.key]"
                                                   class="rounded border-slate-300 text-primary focus:ring-primary w-4 h-4">
                                            <span class="text-sm text-slate-600" x-text="field.checkLabel || 'Aktif'"></span>
                                        </label>
                                    </template>
                                    <template x-if="!field.type || field.type === 'text'">
                                        <input type="text" :placeholder="field.placeholder || ''"
                                               x-model="comp.content[field.key]"
                                               class="w-full text-sm rounded-lg border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary bg-slate-50 px-3 py-2">
                                    </template>
                                </div>
                            </template>
                        </div>
                        <div class="flex items-center gap-2 mt-4 pt-4 border-t border-slate-100">
                            <button @click="saveAll()" class="px-4 py-2 text-xs font-bold text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors">
                                Simpan
                            </button>
                            <button @click="activeEdit = null" class="px-4 py-2 text-xs font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</main>

</div>{{-- end x-data --}}

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>

<script>
const SAVE_URL  = "{{ route('admin.page-builder.save', $page) }}";
const CSRF      = "{{ csrf_token() }}";

const COMPONENT_META = @json($componentMeta ?? []);

const FIELD_MAP = {
    hero:         [{key:'title',label:'Judul Utama',placeholder:'Solusi Terbaik…'},{key:'subtitle',label:'Subtitle',type:'textarea',placeholder:'Deskripsi singkat…'},{key:'badge_text',label:'Badge (opsional)',placeholder:'Baru!'},{key:'button_text',label:'Tombol 1 Teks',placeholder:'Mulai Sekarang'},{key:'button_link',label:'Tombol 1 Link',placeholder:'https://'},{key:'button2_text',label:'Tombol 2 Teks',placeholder:'Pelajari'},{key:'button2_link',label:'Tombol 2 Link',placeholder:'#'},{key:'height',label:'Tinggi',type:'select',options:[{value:'medium',label:'Sedang (60vh)'},{value:'large',label:'Besar (80vh)'},{value:'fullscreen',label:'Full Screen'}]},{key:'text_align',label:'Rata Teks',type:'select',options:[{value:'center',label:'Tengah'},{value:'left',label:'Kiri'},{value:'right',label:'Kanan'}]}],
    text:         [{key:'heading',label:'Judul'},{key:'heading_level',label:'Level Heading',type:'select',options:[{value:'h2',label:'H2'},{value:'h3',label:'H3'},{value:'h4',label:'H4'}]},{key:'text',label:'Konten HTML',type:'textarea',full:true,placeholder:'<p>Teks Anda…</p>'},{key:'text_align',label:'Rata',type:'select',options:[{value:'left',label:'Kiri'},{value:'center',label:'Tengah'},{value:'right',label:'Kanan'}]},{key:'columns',label:'Kolom',type:'select',options:[{value:'1',label:'1 Kolom'},{value:'2',label:'2 Kolom'}]}],
    features:     [{key:'section_title',label:'Judul Seksi'},{key:'section_subtitle',label:'Subtitle',placeholder:'Opsional'},{key:'columns',label:'Kolom',type:'select',options:[{value:'2',label:'2 Kolom'},{value:'3',label:'3 Kolom'},{value:'4',label:'4 Kolom'}]},{key:'style',label:'Gaya',type:'select',options:[{value:'cards',label:'Kartu'},{value:'icons',label:'Ikon'},{value:'numbered',label:'Bernomor'}]},{key:'features',label:'Item Fitur (JSON Array)',type:'textarea',full:true,placeholder:'[{"icon_emoji":"⚡","title":"Judul","description":"Deskripsi"}]'}],
    cta:          [{key:'heading',label:'Judul'},{key:'subheading',label:'Subtitle',type:'textarea'},{key:'button_text',label:'Tombol 1'},{key:'button_link',label:'Link Tombol 1',placeholder:'https://'},{key:'button2_text',label:'Tombol 2 (opsional)'},{key:'button2_link',label:'Link Tombol 2',placeholder:'https://'}],
    testimonials: [{key:'section_title',label:'Judul Seksi'},{key:'section_subtitle',label:'Subtitle'},{key:'columns',label:'Kolom',type:'select',options:[{value:'2',label:'2 Kolom'},{value:'3',label:'3 Kolom'}]},{key:'items',label:'Item Testimoni (JSON Array)',type:'textarea',full:true,placeholder:'[{"name":"Budi","role":"Agen","company":"Toko A","quote":"Ulasan…","rating":5}]'}],
    team:         [{key:'section_title',label:'Judul Seksi'},{key:'section_subtitle',label:'Subtitle'},{key:'columns',label:'Kolom',type:'select',options:[{value:'3',label:'3 Kolom'},{value:'4',label:'4 Kolom'},{value:'2',label:'2 Kolom'}]},{key:'members',label:'Anggota Tim (JSON Array)',type:'textarea',full:true,placeholder:'[{"name":"Nama","role":"Jabatan","bio":"Bio…","photo_url":"https://…"}]'}],
    gallery:      [{key:'section_title',label:'Judul (opsional)'},{key:'columns',label:'Kolom',type:'select',options:[{value:'2',label:'2 Kolom'},{value:'3',label:'3 Kolom'},{value:'4',label:'4 Kolom'}]},{key:'images',label:'Gambar (JSON Array)',type:'textarea',full:true,placeholder:'[{"url":"https://…","caption":"Keterangan","alt":"Alt text"}]'}],
    pricing:      [{key:'section_title',label:'Judul Seksi'},{key:'section_subtitle',label:'Subtitle'},{key:'currency',label:'Mata Uang',placeholder:'Rp'},{key:'plans',label:'Paket (JSON Array)',type:'textarea',full:true,placeholder:'[{"name":"Starter","price":"0","period":"bulan","description":"Cocok untuk pemula","is_popular":false,"button_text":"Mulai","button_link":"/kontak","features":["Fitur 1","Fitur 2"]}]'}],
    faq:          [{key:'section_title',label:'Judul Seksi'},{key:'section_subtitle',label:'Subtitle'},{key:'columns',label:'Kolom',type:'select',options:[{value:'1',label:'1 Kolom'},{value:'2',label:'2 Kolom'}]},{key:'items',label:'Item FAQ (JSON Array)',type:'textarea',full:true,placeholder:'[{"question":"Pertanyaan?","answer":"Jawaban."}]'}],
    video:        [{key:'title',label:'Judul (opsional)'},{key:'subtitle',label:'Subtitle'},{key:'video_url',label:'URL Video YouTube/Vimeo',full:true,placeholder:'https://www.youtube.com/watch?v=…'},{key:'caption',label:'Keterangan (opsional)'}],
    'contact-form':[{key:'title',label:'Judul Form'},{key:'subtitle',label:'Subtitle',type:'textarea'},{key:'button_text',label:'Teks Tombol',placeholder:'Kirim Pesan'},{key:'success_message',label:'Pesan Sukses',placeholder:'Terima kasih!'}],
    newsletter:   [{key:'title',label:'Judul'},{key:'subtitle',label:'Subtitle'},{key:'placeholder',label:'Placeholder Input',placeholder:'Masukkan email…'},{key:'button_text',label:'Teks Tombol',placeholder:'Daftar'},{key:'bg_style',label:'Background',type:'select',options:[{value:'gradient',label:'Gradient'},{value:'light',label:'Terang'},{value:'dark',label:'Gelap'}]}],
    social:       [{key:'title',label:'Judul'},{key:'subtitle',label:'Subtitle'},{key:'facebook',label:'URL Facebook',placeholder:'https://facebook.com/…'},{key:'instagram',label:'URL Instagram',placeholder:'https://instagram.com/…'},{key:'twitter',label:'URL Twitter',placeholder:'https://twitter.com/…'},{key:'linkedin',label:'URL LinkedIn',placeholder:'https://linkedin.com/…'},{key:'youtube',label:'URL YouTube',placeholder:'https://youtube.com/…'},{key:'tiktok',label:'URL TikTok',placeholder:'https://tiktok.com/…'},{key:'style',label:'Gaya',type:'select',options:[{value:'icon-only',label:'Ikon saja'},{value:'buttons',label:'Tombol dengan label'}]}],
    map:          [{key:'title',label:'Judul (opsional)'},{key:'address',label:'Alamat Teks',type:'textarea',placeholder:'Jl. Contoh No.1, Jakarta'},{key:'embed_url',label:'Google Maps Embed URL',full:true,placeholder:'https://www.google.com/maps/embed?pb=…'},{key:'height',label:'Tinggi (px)',type:'select',options:[{value:'300',label:'300px'},{value:'400',label:'400px'},{value:'500',label:'500px'},{value:'600',label:'600px'}]},{key:'show_info_card',label:'Tampilkan kartu info',type:'checkbox',checkLabel:'Tampilkan info alamat di samping peta'}],
    html:         [{key:'title',label:'Judul (opsional)'},{key:'html_content',label:'Konten HTML',type:'textarea',full:true,placeholder:'<p>HTML Anda…</p>'},{key:'css_content',label:'CSS Tambahan (opsional)',type:'textarea',full:true,placeholder:'/* CSS Anda… */'}],
};

function pageBuilder() {
    return {
        components: [],
        saving: false,
        saved: false,
        activeEdit: null,
        _uid: 0,

        init() {
            // Load existing components from server
            const raw = @json($page->components->sortBy('sort_order')->values());
            this.components = raw.map(c => ({
                ...c,
                _uid: ++this._uid,
                content: (typeof c.content === 'object' && c.content !== null) ? c.content : {},
                settings: (typeof c.settings === 'object' && c.settings !== null) ? c.settings : {},
            }));

            // Init SortableJS
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
                        }
                    });
                }
            });
        },

        addComponent(type) {
            this.components.push({
                _uid: ++this._uid,
                component_type: type,
                content: {},
                settings: {},
                sort_order: this.components.length + 1,
                is_visible: true,
            });
            this.activeEdit = this.components.length - 1;
            this.$nextTick(() => {
                document.getElementById('components-canvas')?.lastElementChild?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        },

        removeComponent(index) {
            if (!confirm('Hapus komponen ini?')) return;
            this.components.splice(index, 1);
            this.reorder();
            if (this.activeEdit === index) this.activeEdit = null;
            this.saveAll();
        },

        moveUp(index) {
            if (index === 0) return;
            [this.components[index-1], this.components[index]] = [this.components[index], this.components[index-1]];
            this.reorder();
            this.activeEdit = index - 1;
        },

        moveDown(index) {
            if (index >= this.components.length - 1) return;
            [this.components[index], this.components[index+1]] = [this.components[index+1], this.components[index]];
            this.reorder();
            this.activeEdit = index + 1;
        },

        toggleVisible(index) {
            this.components[index].is_visible = !this.components[index].is_visible;
        },

        editComponent(index) {
            this.activeEdit = this.activeEdit === index ? null : index;
        },

        reorder() {
            this.components.forEach((c, i) => c.sort_order = i + 1);
        },

        getIcon(type) {
            return COMPONENT_META[type]?.icon ?? '📦';
        },

        getLabel(type) {
            return COMPONENT_META[type]?.label ?? type;
        },

        getFields(type) {
            return FIELD_MAP[type] ?? [{key:'content',label:'Konten (JSON)',type:'textarea',full:true}];
        },

        async saveAll() {
            this.saving = true;
            this.saved  = false;
            this.reorder();
            try {
                const payload = this.components.map(c => ({
                    component_type: c.component_type,
                    content:  c.content  || {},
                    settings: c.settings || {},
                    sort_order: c.sort_order,
                    is_visible: c.is_visible ? true : false,
                }));

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
                    this.saved = true;
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
