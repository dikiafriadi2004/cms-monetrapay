@extends('admin.layouts.app')

@section('title', 'Kustomisasi Tema — ' . $theme->name)

@php
    $cfg     = $theme->config ?? [];
    $primary   = $cfg['primary_color']   ?? '#3D9E7A';
    $secondary = $cfg['secondary_color'] ?? '#FF6B35';
    $fontHead  = $cfg['font_heading']    ?? 'Plus Jakarta Sans';
    $fontBody  = $cfg['font_body']       ?? 'Inter';
    $headerStyle    = $cfg['header_style']    ?? 'sticky';
    $footerStyle    = $cfg['footer_style']    ?? 'dark';
    $sidebarPos     = $cfg['sidebar_position'] ?? 'none';
    $layoutWidth    = $cfg['layout_width']     ?? 'boxed';
    $borderRadius   = $cfg['border_radius']    ?? 'medium';
    $buttonStyle    = $cfg['button_style']     ?? 'rounded';

    // Signed preview URL so iframe shows this theme regardless of active theme
    $previewUrl = app(\App\Services\ThemeService::class)->previewUrl($theme, '/');

    // Font options grouped
    $fontOptions = [
        'Sans-serif' => ['Plus Jakarta Sans','Inter','Poppins','Montserrat','Roboto','Open Sans','Nunito','Lato','Raleway','Space Grotesk'],
        'Serif'      => ['Georgia','Merriweather','Lora','Playfair Display','Times New Roman'],
        'Display'    => ['Oswald','Bebas Neue'],
        'Mono'       => ['Fira Code','JetBrains Mono'],
    ];

    // Default presets per theme slug
    $presets = [
        'modern-corporate'  => ['primary_color'=>'#3D9E7A','secondary_color'=>'#FF6B35','font_heading'=>'Plus Jakarta Sans','font_body'=>'Inter'],
        'creative-agency'   => ['primary_color'=>'#E53E3E','secondary_color'=>'#1a1a2e','font_heading'=>'Montserrat','font_body'=>'Open Sans'],
        'minimalist-blog'   => ['primary_color'=>'#2C3E50','secondary_color'=>'#E74C3C','font_heading'=>'Lora','font_body'=>'Source Sans 3'],
        'ecommerce-style'   => ['primary_color'=>'#15803D','secondary_color'=>'#D97706','font_heading'=>'Roboto','font_body'=>'Roboto'],
        'magazine-layout'   => ['primary_color'=>'#C53030','secondary_color'=>'#2C3E50','font_heading'=>'Playfair Display','font_body'=>'Open Sans'],
        'dark-mode-elegant' => ['primary_color'=>'#9F7AEA','secondary_color'=>'#63B3ED','font_heading'=>'Poppins','font_body'=>'Nunito'],
        'landing-page'      => ['primary_color'=>'#000000','secondary_color'=>'#EAB308','font_heading'=>'Raleway','font_body'=>'Open Sans'],
        'portfolio-showcase'=> ['primary_color'=>'#1a1a1a','secondary_color'=>'#B7860D','font_heading'=>'Oswald','font_body'=>'Roboto'],
        'tech-startup'      => ['primary_color'=>'#1E3A5F','secondary_color'=>'#3B82F6','font_heading'=>'Space Grotesk','font_body'=>'Inter'],
        'classic-business'  => ['primary_color'=>'#14532D','secondary_color'=>'#7C2D12','font_heading'=>'Merriweather','font_body'=>'Lato'],
    ];
    $defaultPreset = $presets[$theme->slug] ?? ['primary_color'=>$primary,'secondary_color'=>$secondary,'font_heading'=>$fontHead,'font_body'=>$fontBody];
@endphp

@section('content')
<div x-data="themeCustomizer()" x-init="init()" class="space-y-5">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between gap-4 flex-wrap">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.themes.index') }}"
               class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div class="min-w-0">
                <h1 class="text-lg font-bold text-slate-800 truncate">Kustomisasi: {{ $theme->name }}</h1>
                <p class="text-xs text-slate-500">Ubah warna, font, dan gaya tampilan</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button type="button" @click="resetToDefault()"
                    class="px-3 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                ↺ Reset Default
            </button>
            <a href="{{ route('admin.themes.preview', $theme) }}" target="_blank"
               class="px-3 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors inline-flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Preview Penuh
            </a>
            <button @click="saveChanges()" :disabled="saving"
                    class="px-4 py-2 text-sm font-bold text-white bg-primary rounded-lg hover:bg-primary-dark transition-colors disabled:opacity-60 inline-flex items-center gap-2">
                <svg x-show="saving" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <svg x-show="!saving" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span x-text="saving ? 'Menyimpan...' : 'Simpan'"></span>
            </button>
        </div>
    </div>

    {{-- Success / Error message --}}
    <div x-show="message" x-transition x-cloak
         :class="msgType === 'success' ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'"
         class="flex items-center gap-3 p-3.5 rounded-xl border text-sm font-medium">
        <svg x-show="msgType === 'success'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <svg x-show="msgType !== 'success'" class="w-5 h-5 shrink-0 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <span x-text="message"></span>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-5">

        {{-- ── LEFT PANEL: Controls ── --}}
        <div class="xl:col-span-2 space-y-4">

            {{-- WARNA --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-primary rounded-full"></span>
                    Warna
                </h3>
                <div class="space-y-4">
                    {{-- Primary --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2">Warna Utama (Primary)</label>
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <input type="color" x-model="primaryColor" @input="updatePreview()"
                                       class="w-12 h-12 rounded-xl border-2 border-slate-200 cursor-pointer p-1">
                            </div>
                            <input type="text" x-model="primaryColor" @input="validateHex('primary', $event); updatePreview()"
                                   class="flex-1 px-3 py-2.5 text-sm font-mono rounded-lg border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary"
                                   placeholder="#3D9E7A" maxlength="7">
                            <div class="w-8 h-8 rounded-lg border border-slate-200 shrink-0"
                                 :style="`background:${primaryColor}`"></div>
                        </div>
                        <p x-show="errors.primary" x-cloak class="text-xs text-red-600 mt-1" x-text="errors.primary"></p>
                    </div>
                    {{-- Secondary --}}
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2">Warna Aksen (Secondary)</label>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="secondaryColor" @input="updatePreview()"
                                   class="w-12 h-12 rounded-xl border-2 border-slate-200 cursor-pointer p-1">
                            <input type="text" x-model="secondaryColor" @input="validateHex('secondary', $event); updatePreview()"
                                   class="flex-1 px-3 py-2.5 text-sm font-mono rounded-lg border border-slate-200 focus:border-primary focus:ring-1 focus:ring-primary"
                                   placeholder="#FF6B35" maxlength="7">
                            <div class="w-8 h-8 rounded-lg border border-slate-200 shrink-0"
                                 :style="`background:${secondaryColor}`"></div>
                        </div>
                        <p x-show="errors.secondary" x-cloak class="text-xs text-red-600 mt-1" x-text="errors.secondary"></p>
                    </div>
                    {{-- Color palette quick-picks --}}
                    <div>
                        <p class="text-xs font-semibold text-slate-500 mb-2">Palet Cepat</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach([
                                ['#3D9E7A','#FF6B35'],['#E53E3E','#1a1a2e'],['#1E3A5F','#3B82F6'],
                                ['#14532D','#7C2D12'],['#C53030','#2C3E50'],['#9F7AEA','#63B3ED'],
                                ['#000000','#EAB308'],['#1a1a1a','#B7860D'],['#2C3E50','#E74C3C'],
                                ['#15803D','#D97706'],
                            ] as [$p, $s])
                            <button type="button" @click="primaryColor='{{ $p }}'; secondaryColor='{{ $s }}'; updatePreview()"
                                    class="flex rounded-lg overflow-hidden border-2 border-slate-200 hover:border-slate-400 transition-colors w-8 h-8"
                                    title="{{ $p }} / {{ $s }}">
                                <div class="w-1/2 h-full" style="background:{{ $p }}"></div>
                                <div class="w-1/2 h-full" style="background:{{ $s }}"></div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- TIPOGRAFI --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-secondary rounded-full"></span>
                    Tipografi
                </h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2">Font Heading (Judul)</label>
                        <select x-model="fontHeading" @change="updatePreview()"
                                class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2.5 focus:border-primary focus:ring-1 focus:ring-primary">
                            @foreach($fontOptions as $group => $fonts)
                            <optgroup label="{{ $group }}">
                                @foreach($fonts as $font)
                                <option value="{{ $font }}" {{ $fontHead === $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        <p class="mt-1.5 text-xs text-slate-500">Preview:
                            <span :style="`font-family:'${fontHeading}',sans-serif;font-weight:700`"
                                  class="text-slate-800 ml-1">Judul Halaman</span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-2">Font Body (Teks)</label>
                        <select x-model="fontBody" @change="updatePreview()"
                                class="w-full rounded-lg border border-slate-200 text-sm px-3 py-2.5 focus:border-primary focus:ring-1 focus:ring-primary">
                            @foreach($fontOptions as $group => $fonts)
                            <optgroup label="{{ $group }}">
                                @foreach($fonts as $font)
                                <option value="{{ $font }}" {{ $fontBody === $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        <p class="mt-1.5 text-xs text-slate-500">Preview:
                            <span :style="`font-family:'${fontBody}',sans-serif`"
                                  class="text-slate-600 ml-1">Teks paragraf biasa</span>
                        </p>
                    </div>
                </div>
            </div>

            {{-- GAYA --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-slate-400 rounded-full"></span>
                    Gaya Tampilan
                </h3>
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Header</label>
                            <select x-model="headerStyle" class="w-full text-sm rounded-lg border border-slate-200 px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="sticky">Sticky</option>
                                <option value="fixed">Fixed</option>
                                <option value="transparent">Transparan</option>
                                <option value="minimal">Minimal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Footer</label>
                            <select x-model="footerStyle" class="w-full text-sm rounded-lg border border-slate-200 px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="dark">Gelap</option>
                                <option value="light">Terang</option>
                                <option value="minimal">Minimal</option>
                                <option value="primary">Warna Utama</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Tombol</label>
                            <select x-model="buttonStyle" class="w-full text-sm rounded-lg border border-slate-200 px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="rounded">Rounded</option>
                                <option value="pill">Pill</option>
                                <option value="square">Square</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1.5">Border Radius</label>
                            <select x-model="borderRadius" class="w-full text-sm rounded-lg border border-slate-200 px-3 py-2 focus:border-primary focus:ring-1 focus:ring-primary">
                                <option value="none">Kotak</option>
                                <option value="small">Kecil</option>
                                <option value="medium">Sedang</option>
                                <option value="large">Besar</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1.5">Lebar Layout</label>
                        <div class="grid grid-cols-4 gap-1.5">
                            @foreach(['narrow'=>'Sempit','boxed'=>'Boxed','wide'=>'Lebar','full'=>'Full'] as $val => $lbl)
                            <button type="button" @click="layoutWidth='{{ $val }}'"
                                    :class="layoutWidth === '{{ $val }}' ? 'bg-primary text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'"
                                    class="px-2 py-2 text-xs font-semibold rounded-lg transition-colors">
                                {{ $lbl }}
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- PRATAMPIL KOMPONEN --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm">
                <h3 class="text-sm font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <span class="w-1 h-4 bg-violet-400 rounded-full"></span>
                    Pratampil Komponen
                </h3>
                <div class="space-y-3 text-sm">
                    {{-- Button preview --}}
                    <div>
                        <p class="text-xs text-slate-500 mb-2">Tombol</p>
                        <div class="flex gap-2 flex-wrap">
                            <button class="px-4 py-2 text-xs font-bold text-white transition-all"
                                    :style="`background:${primaryColor};border-radius:${getBorderRadius()}`">
                                Tombol Utama
                            </button>
                            <button class="px-4 py-2 text-xs font-bold text-white transition-all"
                                    :style="`background:${secondaryColor};border-radius:${getBorderRadius()}`">
                                Tombol Aksen
                            </button>
                            <button class="px-4 py-2 text-xs font-bold border-2 transition-all"
                                    :style="`color:${primaryColor};border-color:${primaryColor};border-radius:${getBorderRadius()};background:transparent`">
                                Outline
                            </button>
                        </div>
                    </div>
                    {{-- Badge preview --}}
                    <div>
                        <p class="text-xs text-slate-500 mb-2">Badge & Aksen</p>
                        <div class="flex gap-2 items-center flex-wrap">
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full text-white"
                                  :style="`background:${primaryColor}`">Kategori</span>
                            <span class="px-2.5 py-1 text-xs font-bold rounded-full"
                                  :style="`background:${primaryColor}20;color:${primaryColor}`">Tag</span>
                            <div class="h-px flex-1 min-w-12"
                                 :style="`background:${primaryColor}`"></div>
                        </div>
                    </div>
                    {{-- Heading preview --}}
                    <div class="p-3 bg-slate-50 rounded-lg">
                        <h4 class="font-bold text-slate-800 mb-1 text-sm"
                            :style="`font-family:'${fontHeading}',sans-serif;color:${primaryColor}`">
                            Judul Artikel Contoh
                        </h4>
                        <p class="text-xs text-slate-500 leading-relaxed"
                           :style="`font-family:'${fontBody}',sans-serif`">
                            Ini adalah contoh teks paragraf yang menampilkan font body yang dipilih.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── RIGHT PANEL: Live Preview ── --}}
        <div class="xl:col-span-3">
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden sticky top-4" style="height: calc(100vh - 8rem)">
                {{-- Preview toolbar --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50 shrink-0">
                    <p class="text-xs font-semibold text-slate-600">Preview Langsung — {{ $theme->name }}</p>
                    <div class="flex items-center gap-2">
                        {{-- Device switcher --}}
                        <div class="flex items-center gap-1 bg-white border border-slate-200 rounded-lg p-0.5">
                            <button @click="previewDevice='desktop'" :class="previewDevice==='desktop'?'bg-slate-100':''"
                                    class="p-1.5 rounded-md transition-colors" title="Desktop">
                                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </button>
                            <button @click="previewDevice='tablet'" :class="previewDevice==='tablet'?'bg-slate-100':''"
                                    class="p-1.5 rounded-md transition-colors" title="Tablet">
                                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                            </button>
                            <button @click="previewDevice='mobile'" :class="previewDevice==='mobile'?'bg-slate-100':''"
                                    class="p-1.5 rounded-md transition-colors" title="Mobile">
                                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a1 1 0 001-1V4a1 1 0 00-1-1H8a1 1 0 00-1 1v16a1 1 0 001 1z"/></svg>
                            </button>
                        </div>
                        {{-- Refresh --}}
                        <button @click="reloadPreview()" class="p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors" title="Refresh">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                </div>
                {{-- Note: preview shows saved state of this theme --}}
                <div x-show="previewNeedsRefresh" x-cloak
                     class="px-4 py-2 bg-amber-50 border-b border-amber-100 text-xs text-amber-700 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Simpan perubahan lalu klik Refresh untuk melihat hasil terbaru.
                </div>
                {{-- iframe container with device sizing --}}
                <div class="flex-1 flex items-center justify-center bg-slate-100 overflow-hidden"
                     style="height: calc(100% - 3rem)"
                     :style="previewDevice === 'desktop' ? '' : ''">
                    <div :class="{
                            'w-full h-full': previewDevice === 'desktop',
                            'w-[768px] h-full mx-auto shadow-2xl': previewDevice === 'tablet',
                            'w-[390px] h-full mx-auto shadow-2xl': previewDevice === 'mobile',
                         }"
                         class="bg-white overflow-hidden transition-all duration-300">
                        <iframe id="preview-frame"
                                src="{{ $previewUrl }}"
                                class="w-full h-full border-0"
                                title="Preview tema {{ $theme->name }}">
                        </iframe>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
const THEME_CONFIG_DEFAULT = @json($defaultPreset);
const SAVE_URL  = "{{ route('admin.themes.update', $theme) }}";
const CSRF      = "{{ csrf_token() }}";

function themeCustomizer() {
    return {
        primaryColor:  '{{ $primary }}',
        secondaryColor:'{{ $secondary }}',
        fontHeading:   '{{ $fontHead }}',
        fontBody:      '{{ $fontBody }}',
        headerStyle:   '{{ $headerStyle }}',
        footerStyle:   '{{ $footerStyle }}',
        sidebarPos:    '{{ $sidebarPos }}',
        layoutWidth:   '{{ $layoutWidth }}',
        borderRadius:  '{{ $borderRadius }}',
        buttonStyle:   '{{ $buttonStyle }}',

        saving: false,
        message: '',
        msgType: 'success',
        errors: {},
        previewDevice: 'desktop',
        previewNeedsRefresh: false,

        init() {
            // inject CSS vars into current admin page so component preview works
            this.injectVars();
        },

        injectVars() {
            let style = document.getElementById('preview-vars');
            if (!style) {
                style = document.createElement('style');
                style.id = 'preview-vars';
                document.head.appendChild(style);
            }
            style.textContent = `:root {
                --theme-primary: ${this.primaryColor};
                --theme-secondary: ${this.secondaryColor};
            }`;
        },

        updatePreview() {
            this.injectVars();
            this.previewNeedsRefresh = true;
        },

        reloadPreview() {
            const frame = document.getElementById('preview-frame');
            if (frame) { frame.src = frame.src; }
            this.previewNeedsRefresh = false;
        },

        getBorderRadius() {
            const map = { none: '0', small: '4px', medium: '8px', large: '16px' };
            return map[this.borderRadius] || '8px';
        },

        validateHex(field, event) {
            const val = event.target.value;
            if (!/^#[0-9A-Fa-f]{6}$/.test(val)) {
                this.errors[field] = 'Harus berformat hex (#RRGGBB)';
            } else {
                delete this.errors[field];
                if (field === 'primary') this.primaryColor = val;
                else this.secondaryColor = val;
            }
        },

        resetToDefault() {
            if (!confirm('Reset ke pengaturan default tema ini?')) return;
            this.primaryColor   = THEME_CONFIG_DEFAULT.primary_color;
            this.secondaryColor = THEME_CONFIG_DEFAULT.secondary_color;
            this.fontHeading    = THEME_CONFIG_DEFAULT.font_heading;
            this.fontBody       = THEME_CONFIG_DEFAULT.font_body;
            this.updatePreview();
            this.showMsg('Pengaturan direset ke default. Klik Simpan untuk menyimpan.', 'success');
        },

        async saveChanges() {
            if (Object.keys(this.errors).length > 0) {
                this.showMsg('Perbaiki error sebelum menyimpan.', 'error');
                return;
            }

            this.saving = true;
            this.message = '';

            const payload = {
                config: {
                    primary_color:   this.primaryColor,
                    secondary_color: this.secondaryColor,
                    font_heading:    this.fontHeading,
                    font_body:       this.fontBody,
                    header_style:    this.headerStyle,
                    footer_style:    this.footerStyle,
                    sidebar_position:this.sidebarPos,
                    layout_width:    this.layoutWidth,
                    border_radius:   this.borderRadius,
                    button_style:    this.buttonStyle,
                },
                _method: 'PUT',
            };

            try {
                const res = await fetch(SAVE_URL, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                const data = await res.json().catch(() => ({}));

                if (res.ok && data.success) {
                    this.showMsg(data.message || 'Tersimpan!', 'success');
                    this.reloadPreview();
                } else if (res.status === 422 && data.errors) {
                    const msgs = Object.values(data.errors).flat().join(', ');
                    this.showMsg('Validasi gagal: ' + msgs, 'error');
                } else {
                    this.showMsg(data.message || 'Gagal menyimpan.', 'error');
                }
            } catch (e) {
                this.showMsg('Terjadi kesalahan koneksi.', 'error');
            }

            this.saving = false;
        },

        showMsg(text, type) {
            this.message = text;
            this.msgType = type;
            setTimeout(() => { this.message = ''; }, 5000);
        },
    };
}
</script>
@endsection
