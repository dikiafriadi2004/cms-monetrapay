<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    @php
        $siteName = \App\Models\Setting::get('site_name', 'general', 'MonetraPay');
        $contactEmail = \App\Models\Setting::get('contact_email', 'general', 'info@monetrapay.com');
        $logo = \App\Models\Setting::get('logo', 'general');
    @endphp
    <title>Maintenance — {{ $siteName }}</title>
    @vite(['resources/css/app.css'])
    <style>
        @keyframes pulse-ring {
            0% { transform: scale(0.8); opacity: 1; }
            100% { transform: scale(2); opacity: 0; }
        }
        .pulse-ring::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: #3D9E7A;
            animation: pulse-ring 1.8s ease-out infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-50 flex flex-col items-center justify-center px-4 py-10">

    <main role="main" class="w-full max-w-md text-center">

        {{-- Logo --}}
        <div class="mb-8">
            @if($logo)
                <img src="{{ Storage::url($logo) }}" alt="{{ $siteName }}" class="h-12 w-auto mx-auto object-contain">
            @elseif(file_exists(public_path('assets/images/logo.png')))
                <img src="/assets/images/logo.png" alt="{{ $siteName }}" class="h-12 w-auto mx-auto object-contain">
            @else
                <span class="text-2xl font-extrabold">
                    <span style="color:#3D9E7A">Monetra</span><span style="color:#FE8134">Pay</span>
                </span>
            @endif
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 p-10">

            {{-- Icon --}}
            <div class="flex justify-center mb-7">
                <div class="relative w-20 h-20">
                    <div class="pulse-ring absolute inset-0 rounded-full opacity-20" style="background:#3D9E7A"></div>
                    <div class="relative w-20 h-20 rounded-full flex items-center justify-center" style="background: #E8F5F0">
                        <svg class="w-10 h-10" style="color:#3D9E7A" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <h1 class="text-2xl font-extrabold text-slate-800 mb-3">
                Sedang Maintenance
            </h1>
            <p class="text-slate-500 text-sm leading-relaxed mb-8">
                Kami sedang melakukan pemeliharaan sistem untuk meningkatkan performa dan pengalaman Anda.
                Website akan kembali normal dalam waktu dekat.
            </p>

            {{-- Divider --}}
            <div class="border-t border-slate-100 pt-6">
                <p class="text-xs text-slate-400 mb-2">Butuh bantuan segera?</p>
                @if($contactEmail)
                <a href="mailto:{{ $contactEmail }}"
                   class="inline-flex items-center gap-2 text-sm font-semibold transition-colors duration-200"
                   style="color:#3D9E7A">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ $contactEmail }}
                </a>
                @endif
            </div>
        </div>

        {{-- Admin login link --}}
        <div class="mt-6">
            <a href="{{ route('login') }}"
               class="text-xs text-slate-400 hover:text-slate-600 transition-colors duration-200">
                Admin Login →
            </a>
        </div>

        <p class="mt-8 text-xs text-slate-300">
            &copy; {{ date('Y') }} {{ $siteName }}
        </p>
    </main>

</body>
</html>
