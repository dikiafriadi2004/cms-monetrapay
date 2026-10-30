@php
    $title      = $content['title']          ?? 'Dapatkan Update Terbaru';
    $subtitle   = $content['subtitle']       ?? 'Daftarkan email Anda untuk mendapatkan artikel dan promo terbaru.';
    $placeholder= $content['placeholder']   ?? 'Masukkan email Anda...';
    $btnText    = $content['button_text']    ?? 'Daftar Sekarang';
    $successMsg = $content['success_message'] ?? 'Terima kasih! Anda akan segera menerima konfirmasi.';
    $bgStyle    = $content['bg_style']       ?? 'gradient';

    $sectionBg = match($bgStyle) {
        'light'  => 'background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 6%,white)',
        'dark'   => 'background:#1e293b',
        default  => 'background:var(--theme-primary,#3D9E7A)',
    };
    $textClass = $bgStyle === 'dark' || $bgStyle === 'gradient' ? 'text-white' : 'text-slate-800';
    $subClass  = $bgStyle === 'dark' || $bgStyle === 'gradient' ? 'text-white/75' : 'text-slate-500';
@endphp

<section class="py-16 md:py-20" style="{{ $sectionBg }}">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl md:text-3xl font-extrabold {{ $textClass }} mb-3">{{ $title }}</h2>
        @if($subtitle)
        <p class="{{ $subClass }} text-base mb-8">{{ $subtitle }}</p>
        @endif

        @if(session('newsletter_success'))
        <div class="inline-flex items-center gap-2 px-6 py-3 bg-white/20 rounded-xl text-white font-semibold">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('newsletter_success', $successMsg) }}
        </div>
        @else
        <form action="{{ route('newsletter.subscribe') }}" method="POST">
            @csrf
            {{-- Honeypot --}}
            <div class="absolute opacity-0 pointer-events-none h-0" aria-hidden="true">
                <input type="text" name="honeypot" tabindex="-1" autocomplete="off" value="">
            </div>

            @if($errors->has('email'))
            <p class="text-red-300 text-sm mb-3">{{ $errors->first('email') }}</p>
            @endif

            <div class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                <input type="email" name="email" required
                       value="{{ old('email') }}"
                       placeholder="{{ $placeholder }}"
                       class="flex-1 px-5 py-3.5 rounded-xl text-slate-800 text-sm bg-white border-2 border-white/30 focus:border-white focus:outline-none focus:ring-0 placeholder-slate-400">
                <button type="submit"
                        class="px-6 py-3.5 font-bold text-sm rounded-xl transition-all duration-200 hover:-translate-y-0.5 whitespace-nowrap
                               {{ $bgStyle === 'light' ? 'text-white' : 'text-slate-800 bg-white hover:bg-slate-50' }}"
                        @if($bgStyle === 'light') style="background:var(--theme-primary,#3D9E7A)" @endif>
                    {{ $btnText }}
                </button>
            </div>
            <p class="{{ $subClass }} text-xs mt-4 opacity-75">Kami tidak akan mengirim spam. Anda bisa berhenti berlangganan kapan saja.</p>
        </form>
        @endif
    </div>
</section>
