@php
    $title     = $content['title']     ?? 'Ikuti Kami';
    $subtitle  = $content['subtitle']  ?? 'Tetap terhubung dengan kami di media sosial.';
    $style     = $content['style']     ?? 'icon-only';
    $links = [
        'facebook'  => ['url'=>$content['facebook']  ?? '', 'label'=>'Facebook',  'color'=>'#1877F2'],
        'instagram' => ['url'=>$content['instagram'] ?? '', 'label'=>'Instagram', 'color'=>'#E4405F'],
        'twitter'   => ['url'=>$content['twitter']   ?? '', 'label'=>'Twitter/X', 'color'=>'#000000'],
        'linkedin'  => ['url'=>$content['linkedin']  ?? '', 'label'=>'LinkedIn',  'color'=>'#0A66C2'],
        'youtube'   => ['url'=>$content['youtube']   ?? '', 'label'=>'YouTube',   'color'=>'#FF0000'],
        'tiktok'    => ['url'=>$content['tiktok']    ?? '', 'label'=>'TikTok',    'color'=>'#000000'],
    ];
    $icons = [
        'facebook'  => '<path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>',
        'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/>',
        'twitter'   => '<path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>',
        'linkedin'  => '<path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>',
        'youtube'   => '<path d="M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/>',
        'tiktok'    => '<path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.88 2.89 2.89 0 01-2.88-2.88 2.89 2.89 0 012.88-2.88c.28 0 .56.04.82.11v-3.5a6.37 6.37 0 00-.82-.05A6.34 6.34 0 003.15 15.7a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V9.41a8.16 8.16 0 004.76 1.52v-3.4a4.85 4.85 0 01-1-.84z"/>',
    ];
    $activeLinks = array_filter($links, fn($l) => !empty($l['url']));
@endphp

<section class="py-14 md:py-20" style="background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 5%,white)">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        @if($title)
        <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800 mb-2">{{ $title }}</h2>
        @endif
        @if($subtitle)
        <p class="text-slate-500 mb-8">{{ $subtitle }}</p>
        @endif

        @if(empty($activeLinks))
        <p class="text-slate-400 text-sm">Belum ada link media sosial yang dikonfigurasi.</p>
        @else
        <div class="flex flex-wrap justify-center gap-4">
            @foreach($activeLinks as $platform => $link)
            @if($style === 'buttons')
            <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold text-white transition-all hover:-translate-y-0.5 hover:shadow-lg"
               style="background:{{ $link['color'] }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    {!! $icons[$platform] ?? '' !!}
                </svg>
                {{ $link['label'] }}
            </a>
            @else
            <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
               class="w-12 h-12 rounded-full flex items-center justify-center text-white transition-all hover:-translate-y-1 hover:shadow-lg"
               style="background:{{ $link['color'] }}"
               aria-label="{{ $link['label'] }}">
                <svg class="w-5 h-5" fill="{{ in_array($platform,['instagram','twitter']) ? 'none' : 'currentColor' }}"
                     stroke="{{ in_array($platform,['instagram','twitter']) ? 'currentColor' : 'none' }}"
                     stroke-width="2" viewBox="0 0 24 24">
                    {!! $icons[$platform] ?? '' !!}
                </svg>
            </a>
            @endif
            @endforeach
        </div>
        @endif
    </div>
</section>
