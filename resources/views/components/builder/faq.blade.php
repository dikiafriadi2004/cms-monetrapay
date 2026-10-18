@php
    $sectionTitle    = $content['section_title']    ?? 'Pertanyaan Umum';
    $sectionSubtitle = $content['section_subtitle'] ?? 'Temukan jawaban atas pertanyaan yang sering diajukan.';
    $items           = is_array($content['items'] ?? null) ? $content['items'] : [];
    $columns         = (int)($content['columns'] ?? 1);

    if (empty($items)) {
        $items = [
            ['question'=>'Bagaimana cara mendaftar menjadi agen?',                   'answer'=>'Pendaftaran sangat mudah. Isi formulir di halaman Kontak kami, tim kami akan menghubungi Anda dalam 1x24 jam untuk proses selanjutnya.'],
            ['question'=>'Berapa minimal deposit untuk memulai?',                    'answer'=>'Tidak ada minimal deposit untuk paket Starter. Untuk paket Growth dan Enterprise, kami menyediakan saldo awal yang fleksibel sesuai kebutuhan Anda.'],
            ['question'=>'Apakah transaksi dijamin 24 jam?',                        'answer'=>'Ya, platform kami beroperasi 24/7 dengan uptime 99.9%. Transaksi dapat dilakukan kapan saja, termasuk hari libur nasional.'],
            ['question'=>'Bagaimana jika transaksi gagal?',                         'answer'=>'Setiap transaksi yang gagal akan otomatis di-refund ke saldo Anda dalam waktu maksimal 5 menit. Tidak ada uang yang hilang.'],
            ['question'=>'Apakah ada pelatihan untuk agen baru?',                   'answer'=>'Tentu! Kami menyediakan onboarding session, video tutorial, dan panduan lengkap untuk membantu Anda memulai dengan mudah.'],
        ];
    }
@endphp

<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-block text-xs font-bold tracking-widest uppercase mb-3 px-3 py-1 rounded-full"
                  style="color:var(--theme-primary,#3D9E7A);background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 12%,white)">
                FAQ
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">{{ $sectionTitle }}</h2>
            @if($sectionSubtitle)
            <p class="text-lg text-slate-500">{{ $sectionSubtitle }}</p>
            @endif
        </div>

        <div class="{{ $columns === 2 ? 'grid grid-cols-1 md:grid-cols-2 gap-4' : 'max-w-3xl mx-auto space-y-3' }}"
             x-data="{ open: null }">
            @foreach($items as $i => $item)
            <div class="border border-slate-200 rounded-2xl overflow-hidden hover:border-slate-300 transition-colors">
                <button @click="open === {{ $i }} ? open = null : open = {{ $i }}"
                        class="w-full flex items-center justify-between gap-4 px-6 py-5 text-left hover:bg-slate-50 transition-colors">
                    <span class="text-sm md:text-base font-semibold text-slate-800 leading-snug">
                        {{ $item['question'] ?? '' }}
                    </span>
                    <svg class="w-5 h-5 shrink-0 transition-transform duration-300"
                         :class="open === {{ $i }} ? 'rotate-180' : ''"
                         style="color:var(--theme-primary,#3D9E7A)"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open === {{ $i }}"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="px-6 pb-5 border-t border-slate-100">
                    <p class="text-sm text-slate-600 leading-relaxed pt-4">{{ $item['answer'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
