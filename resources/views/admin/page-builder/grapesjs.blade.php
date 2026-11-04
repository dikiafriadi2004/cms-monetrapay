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
        <div style="display:flex;gap:4px;margin-right:8px;">
            <button onclick="editor.runCommand('core:undo')" title="Undo" style="padding:6px 10px;background:rgba(255,255,255,.1);color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;">↩</button>
            <button onclick="editor.runCommand('core:redo')" title="Redo" style="padding:6px 10px;background:rgba(255,255,255,.1);color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;">↪</button>
        </div>
        <div style="display:flex;gap:4px;margin-right:8px;">
            <button onclick="editor.setDevice('Desktop')" title="Desktop" style="padding:6px 10px;background:rgba(255,255,255,.1);color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;" id="dev-desktop">🖥</button>
            <button onclick="editor.setDevice('Tablet')" title="Tablet" style="padding:6px 10px;background:rgba(255,255,255,.1);color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;" id="dev-tablet">📱</button>
            <button onclick="editor.setDevice('Mobile')" title="Mobile" style="padding:6px 10px;background:rgba(255,255,255,.1);color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:13px;" id="dev-mobile">📲</button>
        </div>
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
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>',
        content: '<section style="background:linear-gradient(135deg,#0D9488,#0a7a70);padding:80px 20px;text-align:center;color:#fff;"><h1 style="font-size:3rem;font-weight:800;margin-bottom:16px;line-height:1.1;">Selamat Datang di {{ $siteName }}</h1><p style="font-size:1.25rem;opacity:.9;margin-bottom:32px;max-width:600px;margin-left:auto;margin-right:auto;">Solusi pembayaran digital yang aman, cepat, dan terpercaya untuk bisnis Anda.</p><div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap;"><a href="#" style="background:#F59E0B;color:#000;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;display:inline-block;">Mulai Sekarang</a><a href="#" style="background:rgba(255,255,255,.15);color:#fff;padding:14px 32px;border-radius:8px;font-weight:700;text-decoration:none;display:inline-block;border:2px solid rgba(255,255,255,.4);">Pelajari Lebih</a></div></section>',
    },
    {
        id: 'mp-feature-cards',
        label: 'Feature Cards',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="6" height="10" rx="1"/><rect x="9" y="7" width="6" height="10" rx="1"/><rect x="16" y="7" width="6" height="10" rx="1"/></svg>',
        content: '<section style="padding:60px 20px;background:#f8fafc;"><h2 style="text-align:center;font-size:2rem;font-weight:700;margin-bottom:48px;color:#1e293b;">Fitur Unggulan</h2><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:24px;max-width:1100px;margin:0 auto;"><div style="background:#fff;border-radius:12px;padding:32px 24px;box-shadow:0 2px 12px rgba(0,0,0,.07);text-align:center;"><div style="width:56px;height:56px;background:#e6f7f5;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;">⚡</div><h3 style="font-weight:700;font-size:1.1rem;margin-bottom:8px;color:#1e293b;">Transaksi Cepat</h3><p style="color:#64748b;font-size:.9rem;line-height:1.6;">Proses pembayaran dalam hitungan detik dengan teknologi terkini.</p></div><div style="background:#fff;border-radius:12px;padding:32px 24px;box-shadow:0 2px 12px rgba(0,0,0,.07);text-align:center;"><div style="width:56px;height:56px;background:#e6f7f5;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;">🔒</div><h3 style="font-weight:700;font-size:1.1rem;margin-bottom:8px;color:#1e293b;">Keamanan Tinggi</h3><p style="color:#64748b;font-size:.9rem;line-height:1.6;">Dilindungi enkripsi SSL dan sistem anti-fraud berlapis.</p></div><div style="background:#fff;border-radius:12px;padding:32px 24px;box-shadow:0 2px 12px rgba(0,0,0,.07);text-align:center;"><div style="width:56px;height:56px;background:#e6f7f5;border-radius:12px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:28px;">📊</div><h3 style="font-weight:700;font-size:1.1rem;margin-bottom:8px;color:#1e293b;">Laporan Real-time</h3><p style="color:#64748b;font-size:.9rem;line-height:1.6;">Pantau transaksi dan pendapatan kapan saja dari dashboard.</p></div></div></section>',
    },
    {
        id: 'mp-stats',
        label: 'Stats Bar',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 20V10M12 20V4M6 20v-6"/></svg>',
        content: '<section style="background:#0D9488;padding:48px 20px;"><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:32px;max-width:1100px;margin:0 auto;text-align:center;color:#fff;"><div><div style="font-size:2.5rem;font-weight:800;line-height:1;">500K+</div><div style="opacity:.85;margin-top:8px;font-size:.95rem;">Pengguna Aktif</div></div><div><div style="font-size:2.5rem;font-weight:800;line-height:1;">Rp 2T+</div><div style="opacity:.85;margin-top:8px;font-size:.95rem;">Total Transaksi</div></div><div><div style="font-size:2.5rem;font-weight:800;line-height:1;">99.9%</div><div style="opacity:.85;margin-top:8px;font-size:.95rem;">Uptime</div></div><div><div style="font-size:2.5rem;font-weight:800;line-height:1;">24/7</div><div style="opacity:.85;margin-top:8px;font-size:.95rem;">Dukungan</div></div></div></section>',
    },
    {
        id: 'mp-testimonial',
        label: 'Testimonial',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
        content: '<section style="padding:60px 20px;background:#fff;"><h2 style="text-align:center;font-size:2rem;font-weight:700;margin-bottom:48px;color:#1e293b;">Kata Mereka</h2><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;max-width:900px;margin:0 auto;"><div style="background:#f8fafc;border-radius:12px;padding:32px;border-left:4px solid #0D9488;"><p style="color:#475569;line-height:1.7;margin-bottom:20px;font-style:italic;">{{ $siteName }} benar-benar mengubah cara kami mengelola pembayaran. Prosesnya cepat dan mudah!</p><div style="display:flex;align-items:center;gap:12px;"><div style="width:44px;height:44px;background:#0D9488;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;flex-shrink:0;">A</div><div><div style="font-weight:700;color:#1e293b;font-size:.9rem;">Ahmad Fauzi</div><div style="color:#64748b;font-size:.8rem;">CEO, TechStart Indonesia</div></div></div></div><div style="background:#f8fafc;border-radius:12px;padding:32px;border-left:4px solid #F59E0B;"><p style="color:#475569;line-height:1.7;margin-bottom:20px;font-style:italic;">Laporan keuangan real-time dari {{ $siteName }} sangat membantu pengambilan keputusan bisnis kami.</p><div style="display:flex;align-items:center;gap:12px;"><div style="width:44px;height:44px;background:#F59E0B;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#000;font-weight:700;flex-shrink:0;">S</div><div><div style="font-weight:700;color:#1e293b;font-size:.9rem;">Sari Dewi</div><div style="color:#64748b;font-size:.8rem;">CFO, Retail Modern</div></div></div></div></div></section>',
    },
    {
        id: 'mp-cta',
        label: 'CTA Section',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 8 16 12 12 16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>',
        content: '<section style="background:linear-gradient(135deg,#0D9488,#0a7a70);padding:64px 20px;text-align:center;color:#fff;"><h2 style="font-size:2.2rem;font-weight:800;margin-bottom:16px;">Siap Mulai Dengan {{ $siteName }}?</h2><p style="font-size:1.1rem;opacity:.9;margin-bottom:32px;max-width:500px;margin-left:auto;margin-right:auto;">Bergabung bersama ribuan bisnis yang sudah mempercayai {{ $siteName }}.</p><a href="#" style="background:#F59E0B;color:#000;padding:14px 40px;border-radius:8px;font-weight:700;text-decoration:none;font-size:1rem;display:inline-block;">Daftar Gratis Sekarang</a></section>',
    },
    {
        id: 'mp-2col',
        label: '2 Column',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="9" height="16" rx="1"/><rect x="13" y="4" width="9" height="16" rx="1"/></svg>',
        content: '<div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;padding:40px 20px;max-width:1100px;margin:0 auto;"><div style="background:#f8fafc;border-radius:8px;padding:32px;min-height:160px;"><p style="color:#64748b;">Kolom kiri — klik untuk edit konten</p></div><div style="background:#f8fafc;border-radius:8px;padding:32px;min-height:160px;"><p style="color:#64748b;">Kolom kanan — klik untuk edit konten</p></div></div>',
    },
    {
        id: 'mp-3col',
        label: '3 Column',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="6" height="16" rx="1"/><rect x="9" y="4" width="6" height="16" rx="1"/><rect x="17" y="4" width="6" height="16" rx="1"/></svg>',
        content: '<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:24px;padding:40px 20px;max-width:1100px;margin:0 auto;"><div style="background:#f8fafc;border-radius:8px;padding:24px;min-height:120px;"><p style="color:#64748b;">Kolom 1</p></div><div style="background:#f8fafc;border-radius:8px;padding:24px;min-height:120px;"><p style="color:#64748b;">Kolom 2</p></div><div style="background:#f8fafc;border-radius:8px;padding:24px;min-height:120px;"><p style="color:#64748b;">Kolom 3</p></div></div>',
    },
    {
        id: 'mp-text-block',
        label: 'Text Block',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 6h16M4 12h16M4 18h10"/></svg>',
        content: '<div style="padding:48px 20px;max-width:800px;margin:0 auto;"><h2 style="font-size:1.875rem;font-weight:700;color:#1e293b;margin-bottom:16px;">Judul Konten</h2><p style="color:#475569;line-height:1.8;font-size:1rem;">Tulis deskripsi atau konten Anda di sini. Klik teks ini untuk mulai mengedit.</p></div>',
    },
    {
        id: 'mp-img-text',
        label: 'Image + Text',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="18" rx="1"/><path d="M14 7h6M14 12h6M14 17h4"/></svg>',
        content: '<section style="padding:60px 20px;"><div style="display:flex;gap:40px;align-items:center;max-width:1100px;margin:0 auto;flex-wrap:wrap;"><div style="flex:1;min-width:260px;"><img src="https://placehold.co/500x340/0D9488/white?text=Gambar" alt="Image" style="width:100%;border-radius:12px;"></div><div style="flex:1;min-width:260px;"><h2 style="font-size:2rem;font-weight:700;color:#1e293b;margin-bottom:16px;">Judul Bagian</h2><p style="color:#475569;line-height:1.8;margin-bottom:20px;">Tulis deskripsi konten di sini. Anda dapat mengganti gambar, judul, dan teks sesuai kebutuhan.</p><a href="#" style="color:#0D9488;font-weight:600;text-decoration:none;">Baca Selengkapnya →</a></div></div></section>',
    },
    {
        id: 'mp-contact-form',
        label: 'Form Kontak',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
        content: '<section style="padding:60px 20px;background:#f8fafc;"><div style="max-width:600px;margin:0 auto;"><h2 style="font-size:2rem;font-weight:700;color:#1e293b;margin-bottom:8px;text-align:center;">Hubungi Kami</h2><p style="color:#64748b;text-align:center;margin-bottom:40px;">Ada pertanyaan? Kami siap membantu Anda.</p><form style="background:#fff;border-radius:12px;padding:40px;box-shadow:0 2px 12px rgba(0,0,0,.07);"><div style="margin-bottom:20px;"><label style="display:block;font-weight:600;color:#374151;margin-bottom:6px;font-size:.875rem;">Nama Lengkap</label><input type="text" placeholder="John Doe" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.95rem;outline:none;"></div><div style="margin-bottom:20px;"><label style="display:block;font-weight:600;color:#374151;margin-bottom:6px;font-size:.875rem;">Email</label><input type="email" placeholder="john@example.com" style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.95rem;outline:none;"></div><div style="margin-bottom:24px;"><label style="display:block;font-weight:600;color:#374151;margin-bottom:6px;font-size:.875rem;">Pesan</label><textarea rows="4" placeholder="Tulis pesan Anda..." style="width:100%;padding:10px 14px;border:1px solid #d1d5db;border-radius:8px;font-size:.95rem;outline:none;resize:vertical;"></textarea></div><button type="submit" style="width:100%;background:#0D9488;color:#fff;padding:12px;border:none;border-radius:8px;font-size:1rem;font-weight:700;cursor:pointer;">Kirim Pesan</button></form></div></section>',
    },
    {
        id: 'block-pricing',
        label: 'Pricing Table',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="18" rx="2"/><path d="M2 9h20M9 21V9"/></svg>',
        content: '<section style="padding:60px 20px;background:#f8fafc;"><h2 style="text-align:center;font-size:2rem;font-weight:700;margin-bottom:48px;color:#1e293b;">Harga & Paket</h2><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:24px;max-width:900px;margin:0 auto;"><div style="background:#fff;border-radius:16px;padding:40px 32px;box-shadow:0 2px 12px rgba(0,0,0,.07);text-align:center;"><div style="font-size:.875rem;font-weight:600;color:#64748b;text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">Starter</div><div style="font-size:3rem;font-weight:800;color:#1e293b;line-height:1;">Rp 0</div><div style="color:#64748b;margin-bottom:24px;font-size:.875rem;">/bulan</div><ul style="list-style:none;margin-bottom:32px;text-align:left;"><li style="padding:8px 0;border-bottom:1px solid #f1f5f9;color:#475569;font-size:.9rem;">✅ Fitur A</li><li style="padding:8px 0;border-bottom:1px solid #f1f5f9;color:#475569;font-size:.9rem;">✅ Fitur B</li><li style="padding:8px 0;color:#94a3b8;font-size:.9rem;">❌ Fitur C</li></ul><a href="#" style="display:block;background:#e2e8f0;color:#1e293b;padding:12px;border-radius:8px;font-weight:700;text-decoration:none;">Mulai Gratis</a></div><div style="background:#0D9488;border-radius:16px;padding:40px 32px;box-shadow:0 8px 32px rgba(13,148,136,.3);text-align:center;position:relative;"><div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#F59E0B;color:#000;font-size:.75rem;font-weight:700;padding:4px 16px;border-radius:999px;">POPULER</div><div style="font-size:.875rem;font-weight:600;color:rgba(255,255,255,.8);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">Pro</div><div style="font-size:3rem;font-weight:800;color:#fff;line-height:1;">Rp 99K</div><div style="color:rgba(255,255,255,.7);margin-bottom:24px;font-size:.875rem;">/bulan</div><ul style="list-style:none;margin-bottom:32px;text-align:left;"><li style="padding:8px 0;border-bottom:1px solid rgba(255,255,255,.2);color:#fff;font-size:.9rem;">✅ Fitur A</li><li style="padding:8px 0;border-bottom:1px solid rgba(255,255,255,.2);color:#fff;font-size:.9rem;">✅ Fitur B</li><li style="padding:8px 0;color:#fff;font-size:.9rem;">✅ Fitur C</li></ul><a href="#" style="display:block;background:#fff;color:#0D9488;padding:12px;border-radius:8px;font-weight:700;text-decoration:none;">Pilih Pro</a></div></div></section>',
    },
    {
        id: 'block-faq',
        label: 'FAQ Section',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3M12 17h.01"/></svg>',
        content: '<section style="padding:60px 20px;background:#fff;"><div style="max-width:700px;margin:0 auto;"><h2 style="text-align:center;font-size:2rem;font-weight:700;margin-bottom:48px;color:#1e293b;">Pertanyaan Umum</h2><div style="border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;"><div style="padding:20px 24px;border-bottom:1px solid #e2e8f0;background:#f8fafc;"><div style="font-weight:600;color:#1e293b;margin-bottom:8px;">Bagaimana cara mendaftar?</div><div style="color:#64748b;font-size:.9rem;line-height:1.6;">Cukup klik tombol Daftar di halaman utama dan isi data yang diperlukan. Proses pendaftaran hanya membutuhkan beberapa menit.</div></div><div style="padding:20px 24px;border-bottom:1px solid #e2e8f0;"><div style="font-weight:600;color:#1e293b;margin-bottom:8px;">Apakah data saya aman?</div><div style="color:#64748b;font-size:.9rem;line-height:1.6;">Ya, kami menggunakan enkripsi SSL 256-bit dan mengikuti standar keamanan internasional untuk melindungi data Anda.</div></div><div style="padding:20px 24px;border-bottom:1px solid #e2e8f0;"><div style="font-weight:600;color:#1e293b;margin-bottom:8px;">Apa metode pembayaran yang diterima?</div><div style="color:#64748b;font-size:.9rem;line-height:1.6;">Kami menerima transfer bank, kartu kredit/debit, dompet digital (GoPay, OVO, DANA), dan QRIS.</div></div><div style="padding:20px 24px;"><div style="font-weight:600;color:#1e293b;margin-bottom:8px;">Bagaimana cara menghubungi dukungan?</div><div style="color:#64748b;font-size:.9rem;line-height:1.6;">Tim dukungan kami tersedia 24/7 melalui live chat, email, dan telepon.</div></div></div></div></section>',
    },
    {
        id: 'block-team',
        label: 'Team Members',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
        content: '<section style="padding:60px 20px;background:#f8fafc;"><h2 style="text-align:center;font-size:2rem;font-weight:700;margin-bottom:48px;color:#1e293b;">Tim Kami</h2><div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px;max-width:900px;margin:0 auto;"><div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,.06);"><div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#0D9488,#0a7a70);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;font-weight:700;">A</div><div style="font-weight:700;color:#1e293b;margin-bottom:4px;">Ahmad Rifai</div><div style="color:#0D9488;font-size:.875rem;font-weight:600;margin-bottom:12px;">CEO & Founder</div><p style="color:#64748b;font-size:.85rem;line-height:1.6;">Visionary leader dengan 10+ tahun di industri fintech.</p></div><div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,.06);"><div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#F59E0B,#d97706);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;font-weight:700;">S</div><div style="font-weight:700;color:#1e293b;margin-bottom:4px;">Sari Putri</div><div style="color:#0D9488;font-size:.875rem;font-weight:600;margin-bottom:12px;">CTO</div><p style="color:#64748b;font-size:.85rem;line-height:1.6;">Ahli teknologi dengan keahlian di cloud dan keamanan.</p></div><div style="background:#fff;border-radius:16px;padding:32px 24px;text-align:center;box-shadow:0 2px 12px rgba(0,0,0,.06);"><div style="width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#4f46e5);margin:0 auto 16px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:2rem;font-weight:700;">B</div><div style="font-weight:700;color:#1e293b;margin-bottom:4px;">Budi Santoso</div><div style="color:#0D9488;font-size:.875rem;font-weight:600;margin-bottom:12px;">Head of Design</div><p style="color:#64748b;font-size:.85rem;line-height:1.6;">Designer kreatif yang fokus pada pengalaman pengguna.</p></div></div></section>',
    },
    {
        id: 'block-gallery',
        label: 'Image Gallery',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
        content: '<section style="padding:60px 20px;background:#f8fafc;"><h2 style="text-align:center;font-size:2rem;font-weight:700;margin-bottom:48px;color:#1e293b;">Galeri</h2><div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;max-width:900px;margin:0 auto;"><img src="https://placehold.co/400x300/0D9488/white?text=Foto+1" alt="Foto 1" style="width:100%;height:200px;object-fit:cover;border-radius:8px;"><img src="https://placehold.co/400x300/0a7a70/white?text=Foto+2" alt="Foto 2" style="width:100%;height:200px;object-fit:cover;border-radius:8px;"><img src="https://placehold.co/400x300/F59E0B/white?text=Foto+3" alt="Foto 3" style="width:100%;height:200px;object-fit:cover;border-radius:8px;"><img src="https://placehold.co/400x300/1e293b/white?text=Foto+4" alt="Foto 4" style="width:100%;height:200px;object-fit:cover;border-radius:8px;"><img src="https://placehold.co/400x300/6366f1/white?text=Foto+5" alt="Foto 5" style="width:100%;height:200px;object-fit:cover;border-radius:8px;"><img src="https://placehold.co/400x300/d97706/white?text=Foto+6" alt="Foto 6" style="width:100%;height:200px;object-fit:cover;border-radius:8px;"></div></section>',
    },
    {
        id: 'block-divider',
        label: 'Divider',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/></svg>',
        content: '<hr style="border:none;border-top:1px solid #e2e8f0;margin:32px 0;">',
    },
    {
        id: 'block-spacer',
        label: 'Spacer',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 3H5a2 2 0 0 0-2 2v3M21 8V5a2 2 0 0 0-2-2h-3M3 16v3a2 2 0 0 0 2 2h3M16 21h3a2 2 0 0 0 2-2v-3"/></svg>',
        content: '<div style="height:60px;"></div>',
    },
    {
        id: 'block-button',
        label: 'Button',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="10" rx="5"/><path d="M12 12h.01"/></svg>',
        content: '<div style="text-align:center;padding:20px;"><a href="#" style="background:#0D9488;color:#fff;padding:12px 32px;border-radius:8px;font-weight:700;text-decoration:none;display:inline-block;">Tombol Aksi</a></div>',
    },
    {
        id: 'block-video',
        label: 'Video Embed',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>',
        content: '<div style="padding:40px 20px;text-align:center;"><div style="position:relative;padding-bottom:56.25%;height:0;overflow:hidden;max-width:100%;border-radius:12px;"><iframe style="position:absolute;top:0;left:0;width:100%;height:100%;" src="https://www.youtube.com/embed/dQw4w9WgXcQ" frameborder="0" allowfullscreen></iframe></div></div>',
    },
    {
        id: 'block-image',
        label: 'Image',
        category: 'Blocks',
        media: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
        content: { type: 'image' },
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
    assetManager: {
        upload: '{{route("admin.files.upload")}}',
        uploadName: 'file',
        headers: {
            'X-CSRF-TOKEN': CSRF,
        },
        multiUpload: false,
        autoAdd: false,
        uploadText: 'Drop images here or click to upload',
        addBtnText: 'Add image',
        handleAdd: function(textFromInput) {},
        // Custom upload that correctly parses the FileManagerController response
        // FileManagerController::upload returns { success: true, message: '...' }
        // It does NOT return the file URL, so we reload assets after upload
    },
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

// Handle upload response from FileManagerController
// The controller returns { success: true, message: '...' } without a file URL
// So after a successful upload, we reload the asset list from the search API
editor.on('asset:upload:response', function(response) {
    // Reload assets from file manager after upload
    loadAssetsFromFileManager();
});

editor.on('asset:upload:error', function(err) {
    console.warn('Asset upload error:', err);
});

// Load existing images from file manager into GrapesJS Asset Manager
function loadAssetsFromFileManager() {
    fetch('{{route("admin.files.search")}}?q=a&type=image', {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.files && data.files.length) {
            var am = editor.AssetManager;
            am.getAll().reset(); // clear existing
            var assets = data.files
                .filter(function(f) { return f.mime_type && f.mime_type.indexOf('image/') === 0; })
                .map(function(f) {
                    return {
                        type: 'image',
                        src: f.url,
                        thumbnail: f.thumbnail_url || f.url,
                        name: f.original_name,
                    };
                });
            am.add(assets);
        }
    })
    .catch(function() {});
}

// Also try a broader search to get recent images
function loadRecentAssetsFromFileManager() {
    // Search with empty-ish query — use 'e' as a catch-all (most filenames contain a vowel)
    // The search API requires min 2 chars; we use broad terms
    var searches = ['a', 'e', 'i', 'png', 'jpg', 'webp', 'jpeg'];
    var allAssets = {};
    var done = 0;
    searches.forEach(function(q) {
        fetch('{{route("admin.files.search")}}?q=' + q, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
            if (data.files) {
                data.files.forEach(function(f) {
                    if (f.mime_type && f.mime_type.indexOf('image/') === 0) {
                        allAssets[f.id] = f;
                    }
                });
            }
            done++;
            if (done === searches.length) {
                var am = editor.AssetManager;
                am.getAll().reset();
                Object.values(allAssets).forEach(function(f) {
                    am.add({
                        type: 'image',
                        src: f.url,
                        thumbnail: f.thumbnail_url || f.url,
                        name: f.original_name,
                    });
                });
            }
        })
        .catch(function() { done++; });
    });
}

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

// Auto-open blocks panel on load and load file manager assets
editor.on('load', function() {
    editor.runCommand('open-blocks');
    // Load file manager images into asset manager
    loadRecentAssetsFromFileManager();
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
