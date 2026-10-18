@extends('admin.layouts.app')

@section('title', 'Theme Management')
@section('page-title', 'Theme Management')

@section('content')
<div class="space-y-6">

    {{-- Active Theme Banner --}}
    @if($activeTheme)
    @php
        $ap = $activeTheme->config['primary_color']   ?? '#3D9E7A';
        $as = $activeTheme->config['secondary_color'] ?? '#FF6B35';
    @endphp
    <div class="rounded-2xl p-6 text-white relative overflow-hidden"
         style="background: linear-gradient(135deg, {{ $ap }} 0%, {{ $as }} 100%)">
        <div class="absolute inset-0 opacity-10"
             style="background-image:radial-gradient(circle,rgba(255,255,255,0.3) 1px,transparent 1px);background-size:24px 24px"></div>
        <div class="relative flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase text-white/70 mb-1">Tema Aktif Sekarang</p>
                <h2 class="text-2xl font-extrabold">{{ $activeTheme->name }}</h2>
                <p class="text-sm text-white/80 mt-1 max-w-xl">{{ $activeTheme->description }}</p>
                <div class="flex items-center gap-2 mt-3">
                    <span class="text-xs text-white/70">Font:</span>
                    <span class="text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full">{{ $activeTheme->config['font_heading'] ?? '—' }}</span>
                    <span class="text-xs font-semibold bg-white/20 px-2 py-0.5 rounded-full">{{ $activeTheme->config['font_body'] ?? '—' }}</span>
                </div>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('admin.themes.preview', $activeTheme) }}" target="_blank"
                   class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl text-sm font-semibold transition-colors border border-white/20">
                    Live Preview
                </a>
                <a href="{{ route('admin.themes.customize', $activeTheme) }}"
                   class="px-4 py-2 bg-white text-slate-800 rounded-xl text-sm font-semibold hover:bg-slate-50 transition-colors">
                    Customize ✏️
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Theme Grid --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-lg font-bold text-slate-800">Semua Tema ({{ $themes->count() }})</h2>
                <p class="text-sm text-slate-500 mt-0.5">Klik "Preview" untuk melihat tampilan, "Aktifkan" untuk menggunakannya</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($themes as $theme)
            @php
                $p = $theme->config['primary_color']   ?? '#3D9E7A';
                $s = $theme->config['secondary_color'] ?? '#FF6B35';
                $fh= $theme->config['font_heading']    ?? 'Sans';
                $fb= $theme->config['font_body']       ?? 'Sans';

                // Visual mock style per theme
                $mockStyle = match($theme->slug) {
                    'dark-mode-elegant'   => 'background:#080810',
                    'portfolio-showcase'  => 'background:#111',
                    'classic-business'    => "background:#fdfdf8",
                    'minimalist-blog'     => 'background:#fffef9',
                    'tech-startup'        => 'background:#f8faff',
                    default               => 'background:white',
                };
                $navStyle = match($theme->slug) {
                    'dark-mode-elegant'  => "background:rgba(8,8,16,0.9);border-bottom:1px solid rgba(255,255,255,0.08)",
                    'portfolio-showcase' => "background:#000;border-bottom:1px solid rgba(255,215,0,0.2)",
                    'magazine-layout'    => "background:white;border-top:4px solid {$p}",
                    'landing-page'       => "background:transparent;position:absolute;width:100%;z-index:2",
                    'classic-business'   => "background:white;border-top:5px solid {$p};border-bottom:2px solid #e8e0d0",
                    'ecommerce-style'    => "background:white;border-bottom:2px solid {$p}",
                    default              => "background:white;border-bottom:1px solid #e2e8f0",
                };
                $headingStyle = match($theme->slug) {
                    'dark-mode-elegant'  => "color:#f0f4ff;font-family:'Poppins',sans-serif;font-weight:700",
                    'portfolio-showcase' => "color:white;font-family:'Oswald',sans-serif;text-transform:uppercase;letter-spacing:0.05em",
                    'magazine-layout'    => "color:#1a1a1a;font-family:'Georgia',serif;font-weight:900",
                    'classic-business'   => "color:{$p};font-family:'Georgia',serif;font-weight:900",
                    'minimalist-blog'    => "color:#1a1a1a;font-family:'Georgia',serif",
                    'tech-startup'       => "background:linear-gradient(135deg,{$p},{$s});-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;font-weight:700",
                    'creative-agency'    => "color:#1a1a1a;font-family:'Montserrat',sans-serif;font-weight:900",
                    default              => "color:#1e293b;font-weight:700",
                };
            @endphp

            <div class="group rounded-2xl overflow-hidden border-2 transition-all duration-300
                        {{ $theme->is_active
                            ? 'border-primary shadow-lg shadow-primary/10'
                            : 'border-slate-200 hover:border-slate-300 hover:shadow-md' }}">

                {{-- Theme Visual Mock --}}
                <div class="relative h-44 overflow-hidden cursor-pointer"
                     style="{{ $mockStyle }}"
                     onclick="window.open('{{ route('admin.themes.preview', $theme) }}','_blank')">

                    {{-- Simulated Navbar --}}
                    <div class="flex items-center justify-between px-4 py-2.5 gap-3" style="{{ $navStyle }}">
                        {{-- Logo placeholder --}}
                        <div class="flex items-center gap-1.5">
                            <div class="w-5 h-5 rounded" style="background:{{ $p }}"></div>
                            <div class="h-2.5 w-16 rounded" style="background:{{ in_array($theme->slug,['dark-mode-elegant','portfolio-showcase']) ? 'rgba(255,255,255,0.6)' : '#334155' }}"></div>
                        </div>
                        {{-- Nav links --}}
                        <div class="hidden sm:flex items-center gap-2">
                            @for($i=0;$i<4;$i++)
                            <div class="h-1.5 w-8 rounded-full opacity-50"
                                 style="background:{{ in_array($theme->slug,['dark-mode-elegant','portfolio-showcase','landing-page']) ? 'rgba(255,255,255,0.7)' : '#64748b' }}"></div>
                            @endfor
                        </div>
                        {{-- CTA --}}
                        <div class="h-5 w-14 rounded-lg text-[0px]" style="background:{{ $s }}">.</div>
                    </div>

                    {{-- Hero section mock --}}
                    <div class="px-4 py-3"
                         style="{{ $theme->slug === 'landing-page' ? "background:linear-gradient(135deg,{$p},{$s});padding-top:1.5rem" : ($theme->slug === 'dark-mode-elegant' || $theme->slug === 'portfolio-showcase' ? 'background:transparent' : 'background:white') }}">
                        {{-- Fake heading --}}
                        <div class="h-4 w-3/5 rounded mb-2" style="
                            @if($theme->slug === 'tech-startup')
                                background: linear-gradient(90deg, {{ $p }}, {{ $s }});
                            @elseif(in_array($theme->slug,['dark-mode-elegant','portfolio-showcase','landing-page']))
                                background: rgba(255,255,255,0.85);
                            @else
                                background: #1e293b;
                            @endif
                        "></div>
                        <div class="h-2.5 w-4/5 rounded mb-1.5 opacity-50"
                             style="background:{{ in_array($theme->slug,['dark-mode-elegant','portfolio-showcase','landing-page']) ? 'rgba(255,255,255,0.5)' : '#94a3b8' }}"></div>
                        <div class="h-2.5 w-2/3 rounded mb-4 opacity-50"
                             style="background:{{ in_array($theme->slug,['dark-mode-elegant','portfolio-showcase','landing-page']) ? 'rgba(255,255,255,0.4)' : '#94a3b8' }}"></div>
                        {{-- Fake buttons --}}
                        <div class="flex gap-2">
                            <div class="h-6 w-20 rounded-lg" style="background:{{ $p }}"></div>
                            <div class="h-6 w-16 rounded-lg border-2 opacity-60"
                                 style="border-color:{{ in_array($theme->slug,['dark-mode-elegant','portfolio-showcase','landing-page']) ? 'rgba(255,255,255,0.5)' : $p }}"></div>
                        </div>
                    </div>

                    {{-- Feature cards row --}}
                    <div class="flex gap-2 px-4 pb-3 pt-2">
                        @for($i=0;$i<3;$i++)
                        <div class="flex-1 rounded-lg p-1.5"
                             style="{{ in_array($theme->slug,['dark-mode-elegant','portfolio-showcase']) ? 'background:rgba(255,255,255,0.06);border:1px solid rgba(255,255,255,0.08)' : 'background:#f8fafc;border:1px solid #e2e8f0' }}">
                            <div class="w-5 h-5 rounded mb-1" style="background:{{ $p }};opacity:0.7"></div>
                            <div class="h-1.5 w-full rounded" style="background:{{ in_array($theme->slug,['dark-mode-elegant','portfolio-showcase']) ? 'rgba(255,255,255,0.3)' : '#cbd5e1' }}"></div>
                            <div class="h-1 w-4/5 rounded mt-1 opacity-60" style="background:{{ in_array($theme->slug,['dark-mode-elegant','portfolio-showcase']) ? 'rgba(255,255,255,0.2)' : '#e2e8f0' }}"></div>
                        </div>
                        @endfor
                    </div>

                    {{-- Active badge --}}
                    @if($theme->is_active)
                    <div class="absolute top-2 right-2 px-2.5 py-1 bg-primary text-white text-[10px] font-bold rounded-full shadow-md uppercase tracking-wider">
                        ✓ Aktif
                    </div>
                    @endif

                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-all duration-200 flex items-center justify-center">
                        <span class="opacity-0 group-hover:opacity-100 transition-opacity bg-white text-slate-800 text-xs font-bold px-3 py-1.5 rounded-lg shadow-lg">
                            👁 Lihat Preview
                        </span>
                    </div>
                </div>

                {{-- Theme Info --}}
                <div class="p-4 bg-white">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <h3 class="text-sm font-bold text-slate-800 leading-tight">{{ $theme->name }}</h3>
                        {{-- Color swatches --}}
                        <div class="flex items-center gap-1 shrink-0">
                            <div class="w-5 h-5 rounded-full border-2 border-white shadow-sm ring-1 ring-slate-200"
                                 style="background:{{ $p }}" title="Primary: {{ $p }}"></div>
                            <div class="w-5 h-5 rounded-full border-2 border-white shadow-sm ring-1 ring-slate-200"
                                 style="background:{{ $s }}" title="Secondary: {{ $s }}"></div>
                        </div>
                    </div>
                    <p class="text-[11px] text-slate-500 leading-relaxed mb-3 line-clamp-2">{{ $theme->description }}</p>

                    {{-- Font tags --}}
                    <div class="flex flex-wrap gap-1 mb-3">
                        <span class="text-[10px] font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">{{ $fh }}</span>
                        @if($fh !== $fb)
                        <span class="text-[10px] font-medium bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $fb }}</span>
                        @endif
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        @if(!$theme->is_active)
                        <form method="POST" action="{{ route('admin.themes.activate', $theme) }}" class="flex-1">
                            @csrf
                            <button type="submit"
                                    class="w-full px-3 py-2 text-white text-xs font-bold rounded-xl hover:opacity-90 transition-all"
                                    style="background: linear-gradient(135deg, {{ $p }}, {{ $s }})">
                                Aktifkan
                            </button>
                        </form>
                        @else
                        <a href="{{ route('admin.themes.customize', $theme) }}"
                           class="flex-1 px-3 py-2 text-white text-xs font-bold rounded-xl text-center hover:opacity-90 transition-all"
                           style="background: linear-gradient(135deg, {{ $p }}, {{ $s }})">
                            Customize ✏️
                        </a>
                        @endif
                        <a href="{{ route('admin.themes.preview', $theme) }}" target="_blank"
                           class="px-3 py-2 border border-slate-200 text-slate-600 text-xs font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-colors">
                            Preview
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@if(session('success'))
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof showToast !== 'undefined') showToast('{{ session('success') }}', 'success');
});
</script>
@endif
@endsection
