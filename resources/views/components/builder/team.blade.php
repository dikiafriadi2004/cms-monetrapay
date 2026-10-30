@php
    $sectionTitle    = $content['section_title']    ?? 'Tim Kami';
    $sectionSubtitle = $content['section_subtitle'] ?? 'Orang-orang berdedikasi di balik layanan kami.';
    $columns         = (int)($content['columns'] ?? 3);
    $members         = is_array($content['members'] ?? null) ? $content['members'] : [];

    if (empty($members)) {
        $members = [
            ['name'=>'Ahmad Rizki',    'role'=>'CEO & Co-Founder',  'bio'=>'10+ tahun pengalaman di industri fintech dan payment gateway.', 'linkedin'=>'#','twitter'=>'#'],
            ['name'=>'Dewi Kusuma',    'role'=>'CTO',               'bio'=>'Ahli backend & infrastruktur dengan fokus pada skalabilitas.', 'linkedin'=>'#','twitter'=>'#'],
            ['name'=>'Budi Hartono',   'role'=>'Head of Operations','bio'=>'Memastikan setiap transaksi berjalan lancar dan tepat waktu.', 'linkedin'=>'#','twitter'=>'#'],
        ];
    }

    $colClass = match($columns) {
        4 => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4',
        2 => 'grid-cols-1 sm:grid-cols-2',
        default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
    };
@endphp

<section class="py-16 md:py-24" style="background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 4%,white)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-block text-xs font-bold tracking-widest uppercase mb-3 px-3 py-1 rounded-full"
                  style="color:var(--theme-primary,#3D9E7A);background:color-mix(in srgb,var(--theme-primary,#3D9E7A) 12%,white)">
                Tim
            </span>
            <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4">{{ $sectionTitle }}</h2>
            @if($sectionSubtitle)
            <p class="text-lg text-slate-500">{{ $sectionSubtitle }}</p>
            @endif
        </div>

        <div class="grid {{ $colClass }} gap-6">
            @foreach($members as $member)
            @php
                $mName    = $member['name']     ?? 'Team Member';
                $mRole    = $member['role']     ?? '';
                $mBio     = $member['bio']      ?? '';
                $mPhoto   = $member['photo_url'] ?? '';
                $mLinkedin= $member['linkedin'] ?? '';
                $mTwitter = $member['twitter']  ?? '';
                $initials = collect(explode(' ',$mName))->take(2)->map(fn($w)=>strtoupper($w[0]??''))->join('');
            @endphp
            <div class="group bg-white rounded-2xl overflow-hidden shadow-sm border border-slate-100 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                {{-- Photo / Avatar --}}
                <div class="relative h-56 overflow-hidden"
                     style="background:var(--theme-primary,#3D9E7A)">
                    @if($mPhoto)
                        <img src="{{ $mPhoto }}" alt="{{ $mName }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-5xl font-extrabold text-white/60">
                            {{ $initials }}
                        </div>
                    @endif
                    {{-- Social hover overlay --}}
                    @if($mLinkedin || $mTwitter)
                    <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                        @if($mLinkedin)
                        <a href="{{ $mLinkedin }}" target="_blank" rel="noopener"
                           class="w-10 h-10 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h14m-.5 15.5v-5.3a3.26 3.26 0 00-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 011.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 001.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 00-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"/></svg>
                        </a>
                        @endif
                        @if($mTwitter)
                        <a href="{{ $mTwitter }}" target="_blank" rel="noopener"
                           class="w-10 h-10 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 01-1.93.07 4.28 4.28 0 004 2.98 8.521 8.521 0 01-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/></svg>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="p-6">
                    <h3 class="font-bold text-lg text-slate-800">{{ $mName }}</h3>
                    <p class="text-sm font-semibold mb-3" style="color:var(--theme-primary,#3D9E7A)">{{ $mRole }}</p>
                    @if($mBio)
                    <p class="text-sm text-slate-500 leading-relaxed line-clamp-3">{{ $mBio }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
