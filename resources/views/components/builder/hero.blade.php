@php
    $title      = $content['title']       ?? 'Solusi Terbaik untuk Bisnis Anda';
    $subtitle   = $content['subtitle']    ?? 'Platform lengkap yang membantu bisnis Anda berkembang lebih cepat.';
    $badge      = $content['badge_text']  ?? '';
    $btn1Text   = $content['button_text'] ?? 'Mulai Sekarang';
    $btn1Link   = $content['button_link'] ?? url('/kontak');
    $btn2Text   = $content['button2_text'] ?? 'Pelajari Lebih';
    $btn2Link   = $content['button2_link'] ?? '#';
    $bgType     = $content['background_type'] ?? 'gradient';
    $bgValue    = $content['bg_value']    ?? '';
    $textAlign  = $content['text_align']  ?? 'center';
    $height     = $content['height']      ?? 'large';

    $heightClass = match($height) {
        'medium'     => 'min-h-[60vh]',
        'fullscreen' => 'min-h-screen',
        default      => 'min-h-[80vh]',
    };
    $alignClass = match($textAlign) {
        'left'  => 'items-start text-left',
        'right' => 'items-end text-right',
        default => 'items-center text-center',
    };
@endphp

<section class="relative overflow-hidden {{ $heightClass }} flex items-center"
    @if($bgType === 'image' && $bgValue)
        style="background-image:url('{{ $bgValue }}');background-size:cover;background-position:center;"
    @elseif($bgType === 'color' && $bgValue)
        style="background-color:{{ $bgValue }};"
    @else
        style="background:linear-gradient(135deg,var(--theme-primary,#3D9E7A) 0%,var(--theme-secondary,#FF6B35) 100%);"
    @endif
>
    {{-- Background overlay --}}
    <div class="absolute inset-0 bg-black/35"></div>

    {{-- Decorative blobs --}}
    <div class="absolute -top-32 -right-32 w-96 h-96 rounded-full opacity-20 blur-3xl"
         style="background:var(--theme-secondary,#FF6B35)"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 rounded-full opacity-20 blur-3xl"
         style="background:var(--theme-primary,#3D9E7A)"></div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="flex flex-col {{ $alignClass }} max-w-4xl {{ $textAlign === 'center' ? 'mx-auto' : '' }}">

            @if($badge)
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 bg-white/20 backdrop-blur-sm text-white text-sm font-semibold rounded-full border border-white/30 mb-6 self-start
                {{ $textAlign === 'center' ? 'self-center' : '' }}">
                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                {{ $badge }}
            </span>
            @endif

            <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white leading-[1.1] tracking-tight mb-6"
                style="text-shadow:0 2px 20px rgba(0,0,0,0.3)">
                {!! nl2br(e($title)) !!}
            </h1>

            @if($subtitle)
            <p class="text-lg sm:text-xl text-white/85 leading-relaxed mb-10 max-w-2xl
                {{ $textAlign === 'center' ? 'mx-auto' : '' }}">
                {{ $subtitle }}
            </p>
            @endif

            @if($btn1Text || $btn2Text)
            <div class="flex flex-wrap gap-4 {{ $textAlign === 'center' ? 'justify-center' : '' }}">
                @if($btn1Text)
                <a href="{{ $btn1Link }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-white text-slate-900 font-bold rounded-2xl text-base hover:bg-slate-100 transition-all duration-200 shadow-xl hover:-translate-y-0.5 hover:shadow-2xl">
                    {{ $btn1Text }}
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
                @endif
                @if($btn2Text)
                <a href="{{ $btn2Link }}"
                   class="inline-flex items-center gap-2 px-8 py-4 bg-transparent text-white font-bold rounded-2xl text-base border-2 border-white/60 hover:border-white hover:bg-white/10 transition-all duration-200">
                    {{ $btn2Text }}
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</section>
