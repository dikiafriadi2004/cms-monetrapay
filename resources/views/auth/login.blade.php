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
    @endphp
    <title>Login — {{ $siteName }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --p: {{ $primaryColor }};
            --s: {{ $secondaryColor }};
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        /* Subtle dot pattern background */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, #e2e8f0 1px, transparent 1px);
            background-size: 24px 24px;
            pointer-events: none;
            z-index: 0;
        }

        .wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 400px;
        }

        /* Brand */
        .brand {
            text-align: center;
            margin-bottom: 32px;
        }
        .brand-logo {
            height: 44px;
            width: auto;
            margin: 0 auto 12px;
            display: block;
        }
        .brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            background: var(--p);
        }
        .brand-name {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.3px;
        }
        .brand-sub {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 3px;
        }

        /* Card */
        .card {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 36px 32px;
            box-shadow:
                0 1px 3px rgba(0,0,0,0.04),
                0 8px 32px rgba(0,0,0,0.06),
                0 0 0 1px rgba(0,0,0,0.02);
        }

        .card-title {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.4px;
            margin-bottom: 4px;
        }
        .card-sub {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 28px;
        }

        /* Alert */
        .alert {
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .alert-error  { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; }
        .alert svg { width: 15px; height: 15px; flex-shrink: 0; margin-top: 1px; }

        /* Form */
        .field { margin-bottom: 18px; }
        .field-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 6px;
        }
        label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }
        .forgot {
            font-size: 12px;
            font-weight: 600;
            color: var(--p);
            text-decoration: none;
        }
        .forgot:hover { opacity: 0.8; }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 13px;
            top: 50%;
            transform: translateY(-50%);
            color: #cbd5e1;
            pointer-events: none;
            display: flex;
        }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            height: 44px;
            padding: 0 14px 0 40px;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #fff;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }
        input[type="email"]::placeholder,
        input[type="password"]::placeholder { color: #c0ccda; }
        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-color: var(--p);
            box-shadow: 0 0 0 3px color-mix(in srgb, var(--p) 15%, transparent);
        }
        .pass-wrap input { padding-right: 42px; }
        .toggle-pass {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #cbd5e1;
            display: flex;
            padding: 4px;
            transition: color 0.15s;
        }
        .toggle-pass:hover { color: #64748b; }

        /* Remember */
        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }
        .remember input[type="checkbox"] {
            width: 16px;
            height: 16px;
            padding: 0;
            border-radius: 4px;
            border: 1.5px solid #e2e8f0;
            accent-color: var(--p);
            cursor: pointer;
        }
        .remember label {
            font-size: 13px;
            font-weight: 400;
            color: #64748b;
            cursor: pointer;
        }

        /* Button */
        .btn {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.15s;
            background: var(--p);
            color: #fff;
            letter-spacing: 0.1px;
        }
        .btn:hover   { filter: brightness(1.06); transform: translateY(-1px); box-shadow: 0 4px 16px color-mix(in srgb, var(--p) 35%, transparent); }
        .btn:active  { transform: translateY(0); filter: brightness(0.97); }

        /* Divider + back */
        .footer {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            display: flex;
            justify-content: center;
        }
        .back-link {
            font-size: 13px;
            font-weight: 500;
            color: #94a3b8;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: color 0.15s;
        }
        .back-link:hover { color: var(--p); }
        .back-link svg { width: 14px; height: 14px; }

        /* Bottom copyright */
        .copyright {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #c0ccda;
        }

        @media (max-width: 480px) {
            .card { padding: 28px 20px; border-radius: 16px; }
        }
    </style>
</head>
<body>
    <div class="wrap">

        {{-- Brand --}}
        <div class="brand">
            @if($logo)
                <img src="{{ Storage::url($logo) }}" alt="{{ $siteName }}" class="brand-logo">
            @else
                <div class="brand-icon">
                    <svg width="26" height="26" fill="none" stroke="#fff" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                              d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
            <div class="brand-name">{{ $siteName }}</div>
            <div class="brand-sub">Panel Administrasi</div>
        </div>

        {{-- Card --}}
        <div class="card">
            <div class="card-title">Masuk ke akun Anda</div>
            <div class="card-sub">Gunakan email dan password admin untuk melanjutkan.</div>

            {{-- Error --}}
            @if($errors->any())
            <div class="alert alert-error">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            @if(session('status'))
            <div class="alert alert-success">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="field">
                    <label for="email">Email</label>
                    <div class="input-wrap" style="margin-top:6px;">
                        <span class="input-icon">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </span>
                        <input type="email" id="email" name="email"
                               value="{{ old('email') }}"
                               required autofocus autocomplete="email"
                               placeholder="admin@domain.com">
                    </div>
                </div>

                {{-- Password --}}
                <div class="field">
                    <div class="field-row">
                        <label for="password">Password</label>
                        <a href="{{ route('password.request') }}" class="forgot">Lupa password?</a>
                    </div>
                    <div class="input-wrap pass-wrap">
                        <span class="input-icon">
                            <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input type="password" id="password" name="password"
                               required autocomplete="current-password"
                               placeholder="••••••••">
                        <button type="button" id="togglePass" class="toggle-pass" aria-label="Toggle password">
                            <svg id="eyeOn" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg id="eyeOff" width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Ingat saya selama 30 hari</label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn">
                    Masuk
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </button>

                {{-- Back --}}
                <div class="footer">
                    <a href="{{ url('/') }}" class="back-link">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        Kembali ke Website
                    </a>
                </div>
            </form>
        </div>

        <div class="copyright">&copy; {{ date('Y') }} {{ $siteName }}</div>
    </div>

    <script>
    document.getElementById('togglePass')?.addEventListener('click', function() {
        const inp    = document.getElementById('password');
        const eyeOn  = document.getElementById('eyeOn');
        const eyeOff = document.getElementById('eyeOff');
        const isPass = inp.type === 'password';
        inp.type           = isPass ? 'text' : 'password';
        eyeOn.style.display  = isPass ? 'none' : '';
        eyeOff.style.display = isPass ? ''     : 'none';
    });
    </script>
</body>
</html>
