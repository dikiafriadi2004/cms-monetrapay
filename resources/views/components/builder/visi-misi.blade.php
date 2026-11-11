@php
    $visiTitle = $content['visi_title'] ?? 'Visi';
    $visiText  = $content['visi_text']  ?? 'Menjadi platform transaksi digital terdepan yang menghubungkan jutaan masyarakat Indonesia dengan layanan keuangan digital yang mudah, cepat, dan terjangkau.';
    $misiTitle = $content['misi_title'] ?? 'Misi';
    $misiItems = $content['misi_items'] ?? [
        'Menyediakan akses layanan digital yang mudah dan terjangkau bagi semua kalangan.',
        'Membangun ekosistem agen yang kuat dan merata di seluruh Indonesia.',
        'Berinovasi terus-menerus untuk menghadirkan produk digital terbaik.',
        'Menjaga kepercayaan pengguna dengan sistem yang aman dan transparan.',
    ];
    if (is_string($misiItems)) {
        $misiItems = array_filter(array_map('trim', explode("\n", $misiItems)));
    }
@endphp

<section class="py-16 md:py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Visi --}}
            <div class="animate-on-scroll bg-primary rounded-3xl p-8 md:p-10 text-white relative overflow-hidden">
                <div class="absolute top-0 right-0 w-40 h-40 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
                <div class="relative">
                    <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-6">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-extrabold mb-4">{{ $visiTitle }}</h3>
                    <p class="text-white/85 leading-relaxed text-base">{{ $visiText }}</p>
                </div>
            </div>

            {{-- Misi --}}
            <div class="animate-on-scroll delay-200 bg-slate-50 rounded-3xl p-8 md:p-10 border border-slate-100">
                <div class="w-12 h-12 bg-secondary/15 rounded-xl flex items-center justify-center mb-6">
                    <svg class="w-6 h-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <h3 class="text-2xl font-extrabold text-slate-800 mb-5">{{ $misiTitle }}</h3>
                <ul class="space-y-4">
                    @foreach($misiItems as $item)
                    <li class="flex items-start gap-3">
                        <div class="w-5 h-5 rounded-full bg-primary/15 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3 h-3 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $item }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>
