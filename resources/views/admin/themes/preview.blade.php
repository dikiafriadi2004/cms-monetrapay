<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview: {{ $theme->name }}</title>
    @vite(['resources/css/app.css'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #0f172a; display: flex; flex-direction: column; height: 100vh; overflow: hidden; font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── Toolbar ── */
        .toolbar {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px;
            background: #1e293b;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }
        .toolbar-left  { display:flex; align-items:center; gap:10px; flex:1; min-width:0; }
        .toolbar-right { display:flex; align-items:center; gap:8px; flex-shrink:0; }

        .theme-badge {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 4px 12px; border-radius: 999px;
            font-size: 11px; font-weight: 700; color: white;
            background: linear-gradient(135deg, {{ $theme->config['primary_color'] ?? '#3D9E7A' }}, {{ $theme->config['secondary_color'] ?? '#FF6B35' }});
        }
        .theme-name { font-size: 13px; font-weight: 600; color: #e2e8f0; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }

        /* Device switcher */
        .device-btn {
            width: 34px; height: 34px; border-radius: 8px; border: none; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            background: transparent; color: #64748b; transition: all 0.15s;
        }
        .device-btn:hover { background: rgba(255,255,255,0.08); color: #e2e8f0; }
        .device-btn.active { background: rgba(255,255,255,0.12); color: white; }

        /* Action buttons */
        .btn {
            padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700;
            border: none; cursor: pointer; text-decoration: none; transition: all 0.15s;
            display: inline-flex; align-items: center; gap: 6px; white-space:nowrap;
        }
        .btn-activate { background: #22c55e; color: white; }
        .btn-activate:hover { background: #16a34a; }
        .btn-customize { background: rgba(255,255,255,0.1); color: #e2e8f0; border:1px solid rgba(255,255,255,0.15); }
        .btn-customize:hover { background: rgba(255,255,255,0.15); }
        .btn-close { background: rgba(255,255,255,0.06); color: #94a3b8; border:1px solid rgba(255,255,255,0.08); }
        .btn-close:hover { background: rgba(255,255,255,0.1); color: #e2e8f0; }

        /* URL bar */
        .url-bar {
            flex: 1; max-width: 360px;
            padding: 5px 12px; border-radius: 8px; font-size: 11px;
            background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.08);
            color: #94a3b8; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
            cursor: pointer;
        }

        /* ── Preview area ── */
        .preview-area {
            flex: 1; display: flex; align-items: center; justify-content: center;
            overflow: hidden; padding: 16px;
            background: #0f172a;
        }
        .preview-shell {
            width: 100%; height: 100%;
            background: white; border-radius: 8px;
            overflow: hidden; position: relative;
            transition: all 0.3s ease;
            box-shadow: 0 0 0 1px rgba(255,255,255,0.06), 0 24px 48px rgba(0,0,0,0.5);
        }
        /* Device sizes */
        .preview-shell.mobile  { max-width: 390px; border-radius: 24px; box-shadow: 0 0 0 8px #1e293b, 0 0 0 9px rgba(255,255,255,0.08), 0 24px 64px rgba(0,0,0,0.6); }
        .preview-shell.tablet  { max-width: 768px; border-radius: 16px; }
        .preview-shell.desktop { max-width: 100%; }

        .preview-shell iframe {
            width: 100%; height: 100%; border: none; display: block;
        }
        /* Scale mobile content */
        .preview-shell.mobile iframe { width: 100%; }

        /* Loading overlay */
        .loading-overlay {
            position: absolute; inset: 0;
            background: white;
            display: flex; align-items: center; justify-content: center;
            flex-direction: column; gap: 12px;
            transition: opacity 0.3s;
            z-index: 10;
        }
        .spinner {
            width: 32px; height: 32px; border-radius: 50%;
            border: 3px solid rgba(0,0,0,0.1);
            border-top-color: {{ $theme->config['primary_color'] ?? '#3D9E7A' }};
            animation: spin 0.7s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* Active theme indicator */
        @if($theme->is_active)
        .toolbar { border-top: 3px solid #22c55e; }
        @endif
    </style>
</head>
<body>

{{-- ── TOOLBAR ── --}}
<div class="toolbar">
    <div class="toolbar-left">
        {{-- Back --}}
        <a href="{{ route('admin.themes.index') }}" class="btn btn-close" title="Kembali">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>

        {{-- Theme info --}}
        <span class="theme-badge">
            @if($theme->is_active) ✓ Aktif @else 👁 Preview @endif
        </span>
        <span class="theme-name">{{ $theme->name }}</span>

        {{-- URL bar --}}
        <span class="url-bar" id="currentUrl">{{ $previewUrl }}</span>
    </div>

    <div class="toolbar-right">
        {{-- Device switcher --}}
        <button class="device-btn active" id="btn-desktop" onclick="setDevice('desktop')" title="Desktop">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </button>
        <button class="device-btn" id="btn-tablet" onclick="setDevice('tablet')" title="Tablet">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
        </button>
        <button class="device-btn" id="btn-mobile" onclick="setDevice('mobile')" title="Mobile">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a1 1 0 001-1V4a1 1 0 00-1-1H8a1 1 0 00-1 1v16a1 1 0 001 1z"/></svg>
        </button>

        <div style="width:1px;height:24px;background:rgba(255,255,255,0.1);margin:0 4px"></div>

        {{-- Reload --}}
        <button class="device-btn" onclick="reloadFrame()" title="Refresh">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        </button>

        {{-- Open in new tab --}}
        <a href="{{ $previewUrl }}" target="_blank" class="device-btn" title="Buka di tab baru">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
        </a>

        <div style="width:1px;height:24px;background:rgba(255,255,255,0.1);margin:0 4px"></div>

        {{-- Customize --}}
        <a href="{{ route('admin.themes.customize', $theme) }}" class="btn btn-customize">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Customize
        </a>

        {{-- Activate --}}
        @if(!$theme->is_active)
        <form method="POST" action="{{ route('admin.themes.activate', $theme) }}">
            @csrf
            <button type="submit" class="btn btn-activate">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                Aktifkan
            </button>
        </form>
        @else
        <span class="btn" style="background:rgba(34,197,94,0.15);color:#22c55e;border:1px solid rgba(34,197,94,0.3);cursor:default">
            ✓ Sedang Aktif
        </span>
        @endif
    </div>
</div>

{{-- ── PREVIEW AREA ── --}}
<div class="preview-area">
    <div class="preview-shell desktop" id="previewShell">
        <div class="loading-overlay" id="loadingOverlay">
            <div class="spinner"></div>
            <p style="font-size:12px;color:#64748b;font-family:sans-serif">Memuat preview {{ $theme->name }}…</p>
        </div>
        <iframe
            id="previewFrame"
            src="{{ $previewUrl }}"
            title="Preview: {{ $theme->name }}"
            onload="hideLoading()"
            onerror="showError()"
            allow="same-origin">
        </iframe>
    </div>
</div>

<script>
    const basePreviewUrl = @json($previewUrl);
    const shell = document.getElementById('previewShell');
    const frame = document.getElementById('previewFrame');
    const urlBar = document.getElementById('currentUrl');

    function setDevice(device) {
        // Remove all device classes
        shell.classList.remove('desktop', 'tablet', 'mobile');
        shell.classList.add(device);

        // Update active button
        document.querySelectorAll('.device-btn[id^="btn-"]').forEach(b => b.classList.remove('active'));
        document.getElementById('btn-' + device).classList.add('active');
    }

    function reloadFrame() {
        showLoading();
        frame.src = frame.src;
    }

    function hideLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.opacity = '0';
            setTimeout(() => overlay.style.display = 'none', 300);
        }
    }

    function showLoading() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
            overlay.style.opacity = '1';
        }
    }

    function showError() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.innerHTML = `
                <svg width="40" height="40" fill="none" stroke="#ef4444" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                <p style="font-size:13px;color:#ef4444;font-family:sans-serif">Gagal memuat preview</p>
                <button onclick="reloadFrame()" style="padding:6px 16px;background:#3b82f6;color:white;border:none;border-radius:6px;font-size:12px;cursor:pointer;margin-top:4px">Coba Lagi</button>
            `;
        }
    }

    // Update URL bar when iframe navigates
    try {
        frame.addEventListener('load', function() {
            try {
                const path = frame.contentWindow.location.href;
                urlBar.textContent = path;
            } catch(e) {
                // Cross-origin — just show base url
                urlBar.textContent = basePreviewUrl;
            }
        });
    } catch(e) {}
</script>
</body>
</html>
