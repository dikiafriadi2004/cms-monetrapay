@php
    $sectionLabel    = $content['section_label']    ?? 'Nilai Kami';
    $sectionTitle    = $content['section_title']    ?? 'Prinsip yang kami pegang';
    $sectionSubtitle = $content['section_subtitle'] ?? '';
    $items = $content['items'] ?? [
        ['icon'=>'🤝','title'=>'Transparansi','desc'=>'Kami percaya pada keterbukaan informasi dan komunikasi yang jujur kepada seluruh pemangku kepentingan.'],
        ['icon'=>'🛡️','title'=>'Dapat Dipercaya','desc'=>'Keamanan dan kepercayaan pengguna adalah prioritas utama dalam setiap keputusan yang kami buat.'],
        ['icon'=>'💡','title'=>'Fokus Pengguna','desc'=>'Setiap fitur dan layanan kami dirancang dengan mengutamakan kemudahan dan kebutuhan pengguna.'],
        ['icon'=>'🌱','title'=>'Terus Berkembang','desc'=>'Kami terus belajar, berinovasi, dan berkembang untuk memberikan layanan terbaik bagi Indonesia.'],
    ];
    $cols = count($items);
    $colClass = match(true) {
        $cols <= 2 => 'grid-cols-1 sm:grid-cols-2',
        $cols === 3 => 'grid-cols-1 sm:grid-cols-3',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
    };
@endphp

<section class="py-16 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14 animate-on-scroll">
            @if($sectionLabel)
            <span class="inline-block text-xs font-bold text-primary bg-primary/10 px-3 py-1.5 rounded-full uppercase tracking-widest mb-4">{{ $sectionLabel }}</span>
            @endif
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800">{{ $sectionTitle }}</h2>
            @if($sectionSubtitle)
            <p class="text-slate-500 mt-3 max-w-2xl mx-auto">{{ $sectionSubtitle }}</p>
            @endif
        </div>
        <div class="grid {{ $colClass }} gap-6">
            @foreach($items as $item)
            <div class="animate-on-scroll bg-white rounded-2xl p-7 border border-slate-100 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="text-3xl mb-4">{{ $item['icon'] ?? '✨' }}</div>
                <h3 class="text-base font-bold text-slate-800 mb-2">{{ $item['title'] ?? '' }}</h3>
                <p class="text-sm text-slate-500 leading-relaxed">{{ $item['desc'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
