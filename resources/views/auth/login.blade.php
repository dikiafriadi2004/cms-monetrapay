<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    @php
        try {
            $siteName       = \App\Models\Setting::get('site_name', 'general') ?? config('app.name');
            $logo           = \App\Models\Setting::get('logo', 'general');
            $primaryColor   = \App\Models\Setting::get('primary_color', 'general') ?? '#3D9E7A';
            $secondaryColor = \App\Models\Setting::get('secondary_color', 'general') ?? '#F59E0B';
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $primaryColor))   $primaryColor   = '#3D9E7A';
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $secondaryColor)) $secondaryColor = '#F59E0B';
        } catch (\Throwable $e) {
            $siteName       = config('app.name');
            $logo           = null;
            $primaryColor   = '#3D9E7A';
            $secondaryColor = '#F59E0B';
        }
        // Darken primary for gradient
        $hex = ltrim($primaryColor, '#');
        $r   = hexdec(substr($hex, 0, 2));
        $g   = hexdec(substr($hex, 2, 2));
        $b   = hexdec(substr($hex, 4, 2));
        $darkColor = sprintf('#%02x%02x%02x',
            max(0, (int)($r * 0.65)),
            max(0, (int)($g * 0.65)),
            max(0, (int)($b * 0.65))
        );
    @endphp
    <title>Login — {{ $siteName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --color-primary:   {{ $primaryColor }};
            --color-secondary: {{ $secondaryColor }};
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }

        .login-bg {
            min-height: 100vh;
            background-color: {{ $primaryColor }};
            background-image:
                radial-gradient(ellipse at 20% 50%, {{ $darkColor }}88 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, {{ $secondaryColor }}33 0%, transparent 50%),
                radial-gradient(circle at 50% 100%, {{ $darkColor }}66 0%, transparent 60%);
        }
        .noise {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.04'/%3E%3C/svg%3E");
        }
        .grid-lines {
            position: fixed; inset: 0; pointer-events: none; z-index: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }
        .login-card {
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.6);
            border-radius: 24px;
            box-shadow:
                0 32px 64px rgba(0,0,0,0.18),
                0 0 0 1px rgba(255,255,255,0.1) inset;
        }
        .input-field {
            width: 100%;
            padding: 11px 16px 11px 42px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            color: #0f172a;
            background: #f8fafc;
            outline: none;
            transition: all 0.2s;
        }
        .input-field:focus {
            border-color: {{ $primaryColor }};
            background: #fff;
            box-shadow: 0 0 0 4px {{ $primaryColor }}18;
        }
        .input-field::placeholder { color: #94a3b8; }
        .btn-primary {
            width: 100%;
            padding: 13px;
            background: {{ $primaryColor }};
            color: #fff;
            font-weight: 700;
            font-size: 15px;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 16px {{ $primaryColor }}50;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-primary:hover {
            filter: brightness(1.07);
            box-shadow: 0 8px 24px {{ $primaryColor }}60;
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: translateY(0); }
        .top-bar {
            height: 4px;
            background: linear-gradient(90deg, {{ $primaryColor }}, {{ $secondaryColor }});
            border-radius: 24px 24px 0 0;
        }

        /* Floating orbs */
        .orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            z-index: 0;
        }
    </style>
</head>
<body>
    <div class="login-bg">
        <div class="noise"></div>
        <div class="grid-lines"></div>

        {{-- Floating orbs --}}
        <div class="orb" style="width:400px;height:400px;top:-100px;right:-100px;background:{{ $secondaryColor }}30;"></div>
        <div class="orb" style="width:300px;height:300px;bottom:-50px;left:-80px;background:{{ $darkColor }}50;"></div>

        {{-- Center layout --}}
        <div style="position:relative;z-index:10;min-height:100vh;display:flex;flex-direction:column;align-items:center;justify-content:center;padding:24px;">

            {{-- Logo / Brand --}}
            <div style="text-align:center;margin-bottom:28px;">
                @if($logo)
                    <img src="{{ Storage::url($logo) }}" alt="{{ $siteName }}"
                         style="height:48px;width:auto;margin:0 auto 12px;display:block;filter:drop-shadow(0 4px 12px rgba(0,0,0,0.25));">
                @else
                    <div style="width:60px;height:60px;background:rgba(255,255,255,0.15);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;backdrop-filter:blur(10px);border:1px solid rgba(255,255,255,0.2);">
                        <svg width="28" height="28" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                @endif
                <p style="color:rgba(255,255,255,0.9);font-size:20px;font-weight:800;letter-spacing:-0.3px;">{{ $siteName }}</p>
                <p style="color:rgba(255,255,255,0.55);font-size:13px;margin-top:4px;">Panel Administrasi</p>
            </div>

            {{-- Card --}}
            <div class="login-card" style="width:100%;max-width:400px;">
                <div class="top-bar"></div>
                <div style="padding:32px;">

                    <h2 style="font-size:20px;font-weight:800;color:#0f172a;margin:0 0 6px;">Selamat Datang 👋</h2>
                    <p style="font-size:13px;color:#64748b;margin:0 0 28px;">Masukkan email dan password Anda untuk melanjutkan.</p>

                    {{-- Error --}}
                    @if($errors->any())
                    <div style="margin-bottom:20px;padding:12px 16px;background:#fef2f2;border:1px solid #fecaca;border-radius:12px;display:flex;align-items:flex-start;gap:10px;">
                        <svg style="width:16px;height:16px;color:#ef4444;flex-shrink:0;margin-top:1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p style="font-size:13px;color:#dc2626;margin:0;">{{ $errors->first() }}</p>
                    </div>
                    @endif

                    @if(session('status'))
                    <div style="margin-bottom:20px;padding:12px 16px;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;display:flex;align-items:flex-start;gap:10px;">
                        <svg style="width:16px;height:16px;color:#16a34a;flex-shrink:0;margin-top:1px;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p style="font-size:13px;color:#15803d;margin:0;">{{ session('status') }}</p>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- Email --}}
                        <div style="margin-bottom:16px;">
                            <label for="email" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px;">Email</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:13px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <input type="email" id="email" name="email"
                                       value="{{ old('email') }}"
                                       required autofocus autocomplete="email"
                                       placeholder="admin@domain.com"
                                       class="input-field">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div style="margin-bottom:16px;">
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                                <label for="password" style="font-size:13px;font-weight:600;color:#374151;">Password</label>
                                <a href="{{ route('password.request') }}"
                                   style="font-size:12px;font-weight:600;color:{{ $primaryColor }};text-decoration:none;">
                                    Lupa password?
                                </a>
                            </div>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:13px;top:50%;transform:translateY(-50%);width:16px;height:16px;color:#94a3b8;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <input type="password" id="password" name="password"
                                       required autocomplete="current-password"
                                       placeholder="••••••••"
                                       class="input-field" style="padding-right:44px;">
                                <button type="button" id="togglePassword"
                                        style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:4px;color:#94a3b8;display:flex;"
                                        aria-label="Toggle password">
                                    <svg id="eyeShow" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    <svg id="eyeHide" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Remember --}}
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:24px;">
                            <input type="checkbox" id="remember" name="remember"
                                   style="width:16px;height:16px;border-radius:4px;accent-color:{{ $primaryColor }};cursor:pointer;">
                            <label for="remember" style="font-size:13px;color:#64748b;cursor:pointer;user-select:none;">
                                Ingat saya selama 30 hari
                            </label>
                        </div>

                        {{-- Submit --}}
                        <button type="submit" class="btn-primary">
                            Masuk ke Dashboard
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </form>

                    {{-- Back --}}
                    <div style="text-align:center;margin-top:20px;padding-top:20px;border-top:1px solid #f1f5f9;">
                        <a href="{{ url('/') }}"
                           style="font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;display:inline-flex;align-items:center;gap:6px;transition:color 0.2s;"
                           onmouseover="this.style.color='{{ $primaryColor }}'"
                           onmouseout="this.style.color='#94a3b8'">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            Kembali ke Website
                        </a>
                    </div>
                </div>
            </div>

            {{-- Footer --}}
            <p style="color:rgba(255,255,255,0.4);font-size:12px;margin-top:24px;text-align:center;">
                &copy; {{ date('Y') }} {{ $siteName }} &middot; v{{ config('app.version', '1.1') }}
            </p>

        </div>
    </div>

    <script>
    document.getElementById('togglePassword')?.addEventListener('click', function() {
        const input   = document.getElementById('password');
        const eyeShow = document.getElementById('eyeShow');
        const eyeHide = document.getElementById('eyeHide');
        const isPass  = input.type === 'password';
        input.type         = isPass ? 'text' : 'password';
        eyeShow.style.display = isPass ? 'none'  : '';
        eyeHide.style.display = isPass ? ''      : 'none';
    });
    </script>
</body>
</html>
