@php
    $heading    = $content['heading']     ?? 'Siap Memulai Perjalanan Anda?';
    $subheading = $content['subheading']  ?? 'Bergabung dengan ribuan mitra yang sudah sukses bersama kami.';
    $btn1Text   = $content['button_text'] ?? 'Daftar Sekarang';
    $btn1Link   = $content['button_link'] ?? url('/kontak');
    $btn2Text   = $content['button2_text'] ?? '';
    $btn2Link   = $content['button2_link'] ?? '#';
    $background = $content['background']  ?? 'gradient';
    $bgValue    = $content['bg_value']    ?? '';

    $bgStyle = match($background) {
        'color'  => "background:{$bgValue};",
        'image'  => "background-image:url('{$bgValue}');background-size:cover;background-position:center;",
        default  => 'background:linear-gradient(135deg,var(--theme-primary,#3D9E7A) 0%,var(--theme-secondary,#FF6B35) 100%);',
    };
@endphp

<section class="py-20 md:py-28 relative overflow-hidden" style="{{ $bgStyle }}">
    {{-- Overlay --}}
    <div class="absolute inset-0 bg-black/25"></div>

    {{-- Decorative circles --}}
    <div class="absolute top-0 right-0 w-72 h-72 rounded-full opacity-10 -translate-y-1/2 translate-x-1/2"
         style="background:white"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full opacity-10 translate-y-1/2 -translate-x-1/2"
         style="background:white"></div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-5 leading-tight">
            {!! nl2br(e($heading)) !!}
        </h2>
        @if($subheading)
        <p class="text-lg md:text-xl text-white/80 mb-10 leading-relaxed max-w-2xl mx-auto">
            {{ $subheading }}
        </p>
        @endif
        <div class="flex flex-wrap justify-center gap-4">
            @if($btn1Text)
            <a href="{{ $btn1Link }}"
               class="inline-flex items-center gap-2 px-8 py-4 bg-white font-bold rounded-2xl text-base hover:bg-slate-50 transition-all duration-200 shadow-xl hover:-translate-y-0.5"
               style="color:var(--theme-primary,#3D9E7A)">
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
    </div>
</section>
