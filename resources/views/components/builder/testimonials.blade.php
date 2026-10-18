@php
    $sectionTitle    = $content['section_title']    ?? 'Apa Kata Mereka';
    $sectionSubtitle = $content['section_subtitle'] ?? 'Ribuan mitra sudah merasakan manfaatnya.';
    $columns         = (int)($content['columns'] ?? 3);
    $items           = is_array($content['items'] ?? null) ? $content['items'] : [];

    if (empty($items)) {
        $items = [
            ['name'=>'Budi Santoso',  'role'=>'Agen Pulsa', 'company'=>'Toko Rizki',  'quote'=>'Platform ini benar-benar mengubah bisnis saya. Transaksi jauh lebih cepat dan margin lebih baik dari sebelumnya.', 'rating'=>5],
            ['name'=>'Siti Rahayu',   'role'=>'Reseller',   'company'=>'Warung Berkah','quote'=>'Sudah 2 tahun bergabung dan tidak pernah mengecewakan. Support tim sangat responsif dan membantu.', 'rating'=>5],
            ['name'=>'Ahmad Fauzi',   'role'=>'Distributor','company'=>'CV Maju Jaya', 'quote'=>'Harga bersaing, transaksi real-time, dan laporan lengkap. Semua ada di satu aplikasi!', 'rating'=>4],
        ];
    }

    $colClass = match($columns) {
        2 => 'grid-cols-1 md:grid-cols-2',
        default => 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<section class="py-16 md:py-24" style="background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 4%,white)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-block text-xs font-bold tracking-widest uppercase mb-3 px-3 py-1 rounded-full"
                  style="color:var(--theme-primary,#3D9E7A);background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 12%,white)">
                Testimoni
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">{{ $sectionTitle }}</h2>
            @if($sectionSubtitle)
            <p class="text-lg text-slate-500">{{ $sectionSubtitle }}</p>
            @endif
        </div>

        <div class="grid {{ $colClass }} gap-6">
            @foreach($items as $item)
            @php
                $name    = $item['name']    ?? 'Pelanggan';
                $role    = $item['role']    ?? '';
                $company = $item['company'] ?? '';
                $quote   = $item['quote']   ?? '';
                $rating  = min(5, max(1, (int)($item['rating'] ?? 5)));
                $avatar  = $item['avatar_url'] ?? '';
                $initials = collect(explode(' ', $name))->take(2)->map(fn($w) => strtoupper($w[0] ?? ''))->join('');
            @endphp
            <div class="bg-white rounded-2xl p-7 shadow-sm border border-slate-100 flex flex-col hover:shadow-md transition-shadow duration-300">
                {{-- Quote icon --}}
                <svg class="w-8 h-8 mb-4 opacity-20" fill="currentColor" viewBox="0 0 32 32"
                     style="color:var(--theme-primary,#3D9E7A)">
                    <path d="M10 8C5.582 8 2 11.582 2 16s3.582 8 8 8h1v4l6-4h3c4.418 0 8-3.582 8-8V8H10z"/>
                </svg>

                {{-- Stars --}}
                <div class="flex gap-0.5 mb-4">
                    @for($s = 1; $s <= 5; $s++)
                    <svg class="w-4 h-4 {{ $s <= $rating ? '' : 'opacity-25' }}" fill="currentColor" viewBox="0 0 20 20"
                         style="{{ $s <= $rating ? 'color:var(--theme-secondary,#FF6B35)' : 'color:#94a3b8' }}">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                    @endfor
                </div>

                <p class="text-slate-600 leading-relaxed italic flex-1 mb-6">"{{ $quote }}"</p>

                <div class="flex items-center gap-3 mt-auto pt-5 border-t border-slate-100">
                    @if($avatar)
                        <img src="{{ $avatar }}" alt="{{ $name }}" class="w-11 h-11 rounded-full object-cover">
                    @else
                        <div class="w-11 h-11 rounded-full flex items-center justify-center text-white text-sm font-bold"
                             style="background:linear-gradient(135deg,var(--theme-primary,#3D9E7A),var(--theme-secondary,#FF6B35))">
                            {{ $initials }}
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-bold text-slate-800">{{ $name }}</p>
                        <p class="text-xs text-slate-400">{{ $role }}{{ $company ? ' · '.$company : '' }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
