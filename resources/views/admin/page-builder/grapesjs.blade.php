<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Visual Editor — {{ $page->title }}</title>

    {{-- GrapesJS Core --}}
    <link href="https://unpkg.com/grapesjs@0.21.7/dist/css/grapes.min.css" rel="stylesheet">
    <script src="https://unpkg.com/grapesjs@0.21.7/dist/grapes.min.js"></script>
    <script src="https://unpkg.com/grapesjs-preset-webpage@1.0.2/dist/index.js"></script>
    <script src="https://unpkg.com/grapesjs-blocks-basic@0.9.4/dist/index.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #1a1a2e; }

        /* Top bar */
        #topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #0f3460;
            color: #fff;
            padding: 0 16px;
            height: 50px;
            z-index: 1000;
            position: relative;
        }
        #topbar .page-title { font-size: 14px; font-weight: 600; opacity: 0.9; }
        #topbar .actions { display: flex; gap: 8px; align-items: center; }
        #topbar a.back-btn {
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            opacity: 0.8;
            margin-right: 12px;
        }
        #topbar a.back-btn:hover { opacity: 1; }
        #topbar button {
            padding: 6px 16px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }
        #topbar button:hover { opacity: 0.85; }
        #btn-save { background: #0D9488; color: #fff; }
        #btn-preview { background: #F59E0B; color: #000; }
        #save-status { font-size: 12px; opacity: 0.7; margin-left: 8px; }

        /* GrapesJS wrapper — fill remaining height */
        #gjs-wrapper {
            display: flex;
            height: calc(100vh - 50px);
        }

        /* GrapesJS takes full space */
        #gjs {
            flex: 1;
            height: 100%;
            overflow: hidden;
        }

        /* Force blocks panel visible on left */
        .gjs-pn-panels { display: flex; height: 100%; }
        .gjs-pn-panel.gjs-pn-views-container { width: 250px !important; min-width: 250px; }
        .gjs-blocks-c { display: flex; flex-wrap: wrap; gap: 8px; padding: 12px; }
        .gjs-block { width: 100px !important; min-height: 90px; padding: 12px 8px; }
        .gjs-block__media svg { width: 32px; height: 32px; }

        /* Override GrapesJS panel colors to match brand */
        .gjs-one-bg { background-color: #1e2533 !important; }
        .gjs-two-bg { background-color: #232b3a !important; }
        .gjs-cv-canvas { background: #f8fafc; }

        /* Notification toast */
        #toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            padding: 12px 20px;
            border-radius: 8px;
            color: #fff;
            font-size: 14px;
            font-weight: 500;
            z-index: 9999;
            display: none;
            animation: slideIn 0.3s ease;
        }
        #toast.success { background: #0D9488; }
        #toast.error { background: #ef4444; }
        @keyframes slideIn {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>

{{-- Top Bar --}}
<div id="topbar">
    <div style="display:flex;align-items:center">
        <a href="{{ route('admin.pages.index') }}" class="back-btn">← Kembali</a>
        <span class="page-title">Visual Editor: {{ $page->title }}</span>
    </div>
    <div class="actions">
        <span id="save-status">Belum ada perubahan</span>
        <button id="btn-preview" onclick="previewPage()">👁 Preview</button>
        <button id="btn-save" onclick="savePage()">💾 Simpan</button>
    </div>
</div>

{{-- Editor Canvas --}}
<div id="gjs-wrapper">
    <div id="gjs"></div>
</div>

{{-- Toast notification --}}
<div id="toast"></div>

<script>
const PAGE_ID = {{ $page->id }};
const SAVE_URL = '{{ route('admin.pages.grapesjs.save', $page) }}';
const LOAD_URL = '{{ route('admin.pages.grapesjs.load', $page) }}';
const CSRF    = document.querySelector('meta[name="csrf-token"]').content;

// -----------------------------------------------
// Show toast helper
// -----------------------------------------------
function showToast(msg, type) {
    type = type || 'success';
    var t = document.getElementById('toast');
    t.textContent = msg;
    t.className = type;
    t.style.display = 'block';
    setTimeout(function() { t.style.display = 'none'; }, 3000);
}

// -----------------------------------------------
// Custom page builder blocks
// -----------------------------------------------
@php $siteName = \App\Models\Setting::get('site_name', 'general') ?? config('app.name'); @endphp
var monetraBlocks = [
    {
        id: 'mp-hero',
        label: 'Hero Section',
        category: '{{ $siteName }}',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
        content: '<section style="background:linear-gradient(135deg,#0D9488,#0a7a70);padding:80px 20px;text-align:center;color:#fff;"><h1 style="font-size:3rem;font-weight:800;margin-bottom:16px;line-height:1.1;">Selamat Datang di {{ $siteName }}</h1><p style="font-size:1.25rem;opacity:.9;margin-bottom:32px;max-width:600px;margin-left:auto;margin-right:auto;">Solusi pembayaran digital yang aman, cepat, dan terpercaya untuk bisnis Anda.</p><div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;"><a href="#" style="background:#F59E0B;color:#000;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;display:inline-block;">Mulai Sekarang</a><a href="#" style="background:rgba(255,255,255,.15);color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;display:inline-block;border:2px solid rgba(255,255,255,.4);">Pelajari Lebih</a></div></section>',
    },
    {
        id: 'mp-feature-cards',
        label: 'Feature Cards',
        category: '{{ $siteName }}',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="6" height="10" rx="1"/><rect x="9" y="7" width="6" height="10" rx="1"/><rect x="16" y="7" width="6" height="10" rx="1"/></svg>',
        content: '<section style="padding:60px 20px;background:#f8fafc;"><h2 style="text-align:center;font-size:2rem;font-weight:700;margin-bottom:48px;color:#1e293b;">Fitur Unggulan</h2><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px;max-width:1100px;margin:0 auto;"><div style="background:#fff;border-radius:12px;padding:32px 24px;box-shadow:0 2px 12px rgba(0,0,0,.07);text-align:center;"><div style="width:56px;height:56px;background:#e6f7f5;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;">⚡</div><h3 style="font-weight:700;font-size:1.1rem;margin-bottom:8px;color:#1e293b;">Transaksi Cepat</h3><p style="color:#64748b;font-size:.9rem;line-height:1.6;">Proses pembayaran dalam hitungan detik dengan teknologi terkini.</p></div><div style="background:#fff;border-radius:12px;padding:32px 24px;box-shadow:0 2px 12px rgba(0,0,0,.07);text-align:center;"><div style="width:56px;height:56px;background:#e6f7f5;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;">🔒</div><h3 style="font-weight:700;font-size:1.1rem;margin-bottom:8px;color:#1e293b;">Keamanan Tinggi</h3><p style="color:#64748b;font-size:.9rem;line-height:1.6;">Dilindungi enkripsi SSL dan sistem anti-fraud berlapis.</p></div><div style="background:#fff;border-radius:12px;padding:32px 24px;box-shadow:0 2px 12px rgba(0,0,0,.07);text-align:center;"><div style="width:56px;height:56px;background:#e6f7f5;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;">📊</div><h3 style="font-weight:700;font-size:1.1rem;margin-bottom:8px;color:#1e293b;">Laporan Real-time</h3><p style="color:#64748b;font-size:.9rem;line-height:1.6;">Pantau transaksi dan pendapatan kapan saja dari dashboard.</p></div></div></section>',
    },
    {
        id: 'mp-stats',
        label: 'Stats Bar',
        category: '{{ $siteName }}',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>',
        content: '<section style="background:#0D9488;padding:48px 20px;"><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:32px;max-width:1100px;margin:0 auto;text-align:center;color:#fff;"><div><div style="font-size:2.5rem;font-weight:800;line-height:1;">500K+</div><div style="opacity:.85;margin-top:8px;font-size:.95rem;">Pengguna Aktif</div></div><div><div style="font-size:2.5rem;font-weight:800;line-height:1;">Rp 2T+</div><div style="opacity:.85;margin-top:8px;font-size:.95rem;">Total Transaksi</div></div><div><div style="font-size:2.5rem;font-weight:800;line-height:1;">99.9%</div><div style="opacity:.85;margin-top:8px;font-size:.95rem;">Uptime</div></div><div><div style="font-size:2.5rem;font-weight:800;line-height:1;">24/7</div><div style="opacity:.85;margin-top:8px;font-size:.95rem;">Dukungan</div></div></div></section>',
    },
    {
        id: 'mp-testimonial',
        label: 'Testimonial',
        category: '{{ $siteName }}',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
        content: '<section style="padding:60px 20px;background:#fff;"><h2 style="text-align:center;font-size:2rem;font-weight:700;margin-bottom:48px;color:#1e293b;">Kata Mereka</h2><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;max-width:900px;margin:0 auto;"><div style="background:#f8fafc;border-radius:12px;padding:32px;border-left:4px solid #0D9488;"><p style="color:#475569;line-height:1.7;margin-bottom:20px;font-style:italic;">{{ $siteName }} benar-benar mengubah cara kami mengelola pembayaran. Prosesnya cepat dan mudah!</p><div style="display:flex;align-items:center;gap:12px;"><div style="width:44px;height:44px;background:#0D9488;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0;">A</div><div><div style="font-weight:700;color:#1e293b;font-size:.9rem;">Ahmad Fauzi</div><div style="color:#64748b;font-size:.8rem;">CEO, TechStart Indonesia</div></div></div></div><div style="background:#f8fafc;border-radius:12px;padding:32px;border-left:4px solid #F59E0B;"><p style="color:#475569;line-height:1.7;margin-bottom:20px;font-style:italic;">Laporan keuangan real-time dari {{ $siteName }} sangat membantu pengambilan keputusan bisnis kami.</p><div style="display:flex;align-items:center;gap:12px;"><div style="width:44px;height:44px;background:#F59E0B;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#000;font-weight:700;flex-shrink:0;">S</div><div><div style="font-weight:700;color:#1e293b;font-size:.9rem;">Sari Dewi</div><div style="color:#64748b;font-size:.8rem;">CFO, Retail Modern</div></div></div></div></div></section>',
    },
    {
        id: 'mp-cta',
        label: 'CTA Section',
        category: '{{ $siteName }}',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 8 16 12 12 16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>',
        content: '<section style="background:linear-gradient(135deg,#0D9488,#0a7a70);padding:64px 20px;text-align:center;color:#fff;"><h2 style="font-size:2.2rem;font-weight:800;margin-bottom:16px;">Siap Mulai Dengan {{ $siteName }}?</h2><p style="font-size:1.1rem;opacity:.9;margin-bottom:32px;max-width:500px;margin-left:auto;margin-right:auto;">Bergabung bersama ribuan bisnis yang sudah mempercayai {{ $siteName }}.</p><a href="#" style="background:#F59E0B;color:#000;padding:14px 40px;border-radius:8px;font-weight:700;text-decoration:none;font-size:1rem;display:inline-block;">Daftar Gratis Sekarang</a></section>',
    },
    {
        id: 'mp-2col',
        label: '2 Column',
        category: '{{ $siteName }}',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="9" height="16" rx="1"/><rect x="13" y="4" width="9" height="16" rx="1"/></svg>',
        content: '<div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;padding:40px 20px;max-width:1100px;margin:0 auto;"><div style="background:#f8fafc;border-radius:8px;padding:32px;min-height:160px;"><p style="color:#64748b;">Kolom kiri — klik untuk edit konten</p></div><div style="background:#f8fafc;border-radius:8px;padding:32px;min-height:160px;"><p style="color:#64748b;">Kolom kanan — klik untuk edit konten</p></div></div>',
    },
    {
        id: 'mp-3col',
        label: '3 Column',
        category: '{{ $siteName }}',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="6" height="16" rx="1"/><rect x="9" y="4" width="6" height="16" rx="1"/><rect x="17" y="4" width="6" height="16" rx="1"/></svg>',
        content: '<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:24px;padding:40px 20px;max-width:1100px;margin:0 auto;"><div style="background:#f8fafc;border-radius:8px;padding:24px;min-height:120px;"><p style="color:#64748b;">Kolom 1</p></div><div style="background:#f8fafc;border-radius:8px;padding:24px;min-height:120px;"><p style="color:#64748b;">Kolom 2</p></div><div style="background:#f8fafc;border-radius:8px;padding:24px;min-height:120px;"><p style="color:#64748b;">Kolom 3</p></div></div>',
    },
    {
        id: 'mp-text-block',
        label: 'Text Block',
        category: '{{ $siteName }}',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h10"/></svg>',
        content: '<div style="padding:48px 20px;max-width:800px;margin:0 auto;"><h2 style="font-size:1.875rem;font-weight:700;color:#1e293b;margin-bottom:16px;">Judul Konten</h2><p style="color:#475569;line-height:1.8;font-size:1rem;">Tulis deskripsi atau konten Anda di sini. Klik teks ini untuk mulai mengedit.</p></div>',
    },
    {
        id: 'mp-img-text',
        label: 'Image + Text',
        category: '{{ $siteName }}',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><path d="M14 7h6M14 12h6M14 17h4"/></svg>',
        content: '<section style="padding:60px 20px;"><div style="display:flex;gap:40px;align-items:center;max-width:1100px;margin:0 auto;flex-wrap:wrap;"><div style="flex:1;min-width:260px;"><img src="https://placehold.co/500x340/0D9488/white?text=Gambar" alt="Image" style="width:100%;border-radius:12px;"></div><div style="flex:1;min-width:260px;"><h2 style="font-size:2rem;font-weight:700;color:#1e293b;margin-bottom:16px;">Judul Bagian</h2><p style="color:#475569;line-height:1.8;margin-bottom:20px;">Tulis deskripsi konten di sini. Anda dapat mengganti gambar, judul, dan teks sesuai kebutuhan.</p><a href="#" style="color:#0D9488;font-weight:600;text-decoration:none;">Baca Selengkapnya →</a></div></div></section>',
    },
    {
        id: 'mp-contact-form',
        label: 'Form Kontak',
        category: '{{ $siteName }}',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
        content: '<section style="padding:60px 20px;background:#f8fafc;"><div style="max-width:600px;margin:0 auto;"><h2 style="font-size:2rem;font-weight:700;color:#1e293b;margin-bottom:8px;text-align:center;">Hubungi Kami</h2><p style="color:#64748b;text-align:center;margin-bottom:40px;">Ada pertanyaan? Kami siap membantu Anda.</p><form style="background:#fff;border-radius:12px;padding:40px;box-shadow:0 2px 12px rgba(0,0,0,.07);"><div style="margin-bottom:20px;"><label style="display:block;font-weight:600;color:#374151;margin-bottom:6px;font-size:.875rem;">Nama Lengkap</label><input type="text" placeholder="John Doe" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.95rem;outline:none;"></div><div style="margin-bottom:20px;"><label style="display:block;font-weight:600;color:#374151;margin-bottom:6px;font-size:.875rem;">Email</label><input type="email" placeholder="john@example.com" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.95rem;outline:none;"></div><div style="margin-bottom:24px;"><label style="display:block;font-weight:600;color:#374151;margin-bottom:6px;font-size:.875rem;">Pesan</label><textarea rows="4" placeholder="Tulis pesan Anda..." style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.95rem;outline:none;resize:vertical;"></textarea></div><button type="submit" style="width:100%;background:#0D9488;color:#fff;padding:12px;border:none;border-radius:8px;font-size:1rem;font-weight:700;cursor:pointer;">Kirim Pesan</button></form></div></section>',
    },
];

// -----------------------------------------------
// Initialize GrapesJS
// -----------------------------------------------
var editorPlugins = [];
var editorPluginsOpts = {};

if (typeof gjsPresetWebpage !== 'undefined') {
    editorPlugins.push(gjsPresetWebpage);
    editorPluginsOpts[gjsPresetWebpage] = {};
}
if (typeof gjsBlocksBasic !== 'undefined') {
    editorPlugins.push(gjsBlocksBasic);
    editorPluginsOpts[gjsBlocksBasic] = {
        blocks: ['column1','column2','column3','text','image','link','video','map']
    };
}

var editor = grapesjs.init({
    container: '#gjs',
    height: '100%',
    width: 'auto',
    fromElement: false,
    storageManager: false,
    undoManager: { trackChanges: true },
    plugins: editorPlugins,
    pluginsOpts: editorPluginsOpts,
    canvas: {
        styles: [
            'https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap',
        ],
        scripts: [],
        customBadgeLabel: function(component) { return component.getName(); },
    },
    protectedCss: `
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: #334155;
            line-height: 1.6;
            background: #fff;
        }
        img { max-width: 100%; height: auto; display: block; }
        a { color: inherit; }
        section, div { box-sizing: border-box; }
        [data-gjs-type="wrapper"] { min-height: 100vh; }
    `,
    deviceManager: {
        devices: [
            { name: 'Desktop', width: '' },
            { name: 'Tablet', width: '768px', widthMedia: '992px' },
            { name: 'Mobile', width: '375px', widthMedia: '480px' },
        ],
    },
    styleManager: {
        sectors: [
            { name: 'Dimension', open: false, buildProps: ['width','height','max-width','min-height','padding','margin'] },
            { name: 'Typography', open: false, buildProps: ['font-family','font-size','font-weight','color','text-align','line-height'] },
            { name: 'Background', open: false, buildProps: ['background-color','background'] },
            { name: 'Border', open: false, buildProps: ['border-radius','border','box-shadow'] },
        ],
    },
});

// -----------------------------------------------
// Add custom page builder blocks
// -----------------------------------------------
monetraBlocks.forEach(function(block) {
    editor.BlockManager.add(block.id, {
        label: block.label,
        category: block.category,
        media: block.media,
        content: block.content,
    });
});

// Mark changes
editor.on('change:changesCount', function() {
    document.getElementById('save-status').textContent = 'Ada perubahan yang belum disimpan';
});

// Auto-open blocks panel on load
editor.on('load', function() {
    editor.runCommand('open-blocks');
});

// Device commands
editor.Commands.add('set-device-desktop', { run: function(e) { e.setDevice('Desktop'); } });
editor.Commands.add('set-device-tablet',  { run: function(e) { e.setDevice('Tablet'); } });
editor.Commands.add('set-device-mobile',  { run: function(e) { e.setDevice('Mobile'); } });

// -----------------------------------------------
// Load existing content
// -----------------------------------------------
function loadContent() {
    fetch(LOAD_URL, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            if (data.gjs_components && data.gjs_components.length > 2) {
                try {
                    var projectData = JSON.parse(data.gjs_components);
                    editor.loadProjectData(projectData);
                } catch (e) {
                    if (data.gjs_html) {
                        editor.setComponents(data.gjs_html);
                    }
                }
            } else if (data.gjs_html) {
                // Load HTML directly — GrapesJS will parse it into components
                editor.setComponents(data.gjs_html);
            }
            // Don't set gjs_css as editor style — it's just the @import font,
            // which is already handled by protectedCss + canvas.styles
        }
    })
    .catch(function(e) {
        console.warn('Could not load content:', e);
    });
}
loadContent();

// -----------------------------------------------
// Save page
// -----------------------------------------------
function savePage() {
    var btn = document.getElementById('btn-save');
    btn.textContent = '⏳ Menyimpan...';
    btn.disabled = true;

    var projectData = JSON.stringify(editor.getProjectData());
    var params = new URLSearchParams();
    params.append('gjs_html', editor.getHtml());
    params.append('gjs_css', editor.getCss());
    params.append('gjs_components', projectData);
    params.append('gjs_styles', JSON.stringify(editor.getStyle()));
    params.append('_token', CSRF);

    fetch(SAVE_URL, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: params.toString(),
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            showToast('✅ Halaman berhasil disimpan!', 'success');
            document.getElementById('save-status').textContent = 'Tersimpan';
        } else {
            showToast('❌ Gagal menyimpan: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(function(e) {
        showToast('❌ Error: ' + e.message, 'error');
    })
    .finally(function() {
        btn.textContent = '💾 Simpan';
        btn.disabled = false;
    });
}

// Preview
function previewPage() {
    editor.runCommand('preview');
}

// Keyboard shortcut: Ctrl+S
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        savePage();
    }
});
</script>
</body>
</html>
