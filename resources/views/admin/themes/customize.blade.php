@extends('admin.layouts.app')

@section('title', 'Customize Theme - ' . $theme->name)
@section('page-title', 'Customize Theme: ' . $theme->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Customization Form --}}
    <div class="lg:col-span-1">
        <form method="POST" action="{{ route('admin.themes.update', $theme) }}" x-data="themeCustomizer" class="bg-white rounded-2xl border border-slate-200 p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4">Colors</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Primary Color</label>
                        <div class="flex gap-2">
                            <input type="color" name="config[primary_color]" value="{{ $theme->config['primary_color'] ?? '#3D9E7A' }}" x-model="primaryColor" class="w-12 h-12 rounded-lg border-2 border-slate-200">
                            <input type="text" x-model="primaryColor" class="flex-1 px-3 py-2 text-sm font-mono">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Secondary Color</label>
                        <div class="flex gap-2">
                            <input type="color" name="config[secondary_color]" value="{{ $theme->config['secondary_color'] ?? '#FF6B35' }}" x-model="secondaryColor" class="w-12 h-12 rounded-lg border-2 border-slate-200">
                            <input type="text" x-model="secondaryColor" class="flex-1 px-3 py-2 text-sm font-mono">
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4">Typography</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Heading Font</label>
                        <select name="config[font_heading]" class="w-full">
                            <option value="Plus Jakarta Sans" {{ ($theme->config['font_heading'] ?? '') == 'Plus Jakarta Sans' ? 'selected' : '' }}>Plus Jakarta Sans</option>
                            <option value="Inter" {{ ($theme->config['font_heading'] ?? '') == 'Inter' ? 'selected' : '' }}>Inter</option>
                            <option value="Poppins" {{ ($theme->config['font_heading'] ?? '') == 'Poppins' ? 'selected' : '' }}>Poppins</option>
                            <option value="Montserrat" {{ ($theme->config['font_heading'] ?? '') == 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                            <option value="Roboto" {{ ($theme->config['font_heading'] ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                            <option value="Open Sans" {{ ($theme->config['font_heading'] ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Body Font</label>
                        <select name="config[font_body]" class="w-full">
                            <option value="Inter" {{ ($theme->config['font_body'] ?? '') == 'Inter' ? 'selected' : '' }}>Inter</option>
                            <option value="Roboto" {{ ($theme->config['font_body'] ?? '') == 'Roboto' ? 'selected' : '' }}>Roboto</option>
                            <option value="Open Sans" {{ ($theme->config['font_body'] ?? '') == 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                            <option value="Lato" {{ ($theme->config['font_body'] ?? '') == 'Lato' ? 'selected' : '' }}>Lato</option>
                            <option value="Nunito" {{ ($theme->config['font_body'] ?? '') == 'Nunito' ? 'selected' : '' }}>Nunito</option>
                        </select>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-slate-800 mb-4">Layout</h3>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Header Style</label>
                        <select name="config[header_style]" class="w-full">
                            <option value="fixed" {{ ($theme->config['header_style'] ?? '') == 'fixed' ? 'selected' : '' }}>Fixed</option>
                            <option value="sticky" {{ ($theme->config['header_style'] ?? '') == 'sticky' ? 'selected' : '' }}>Sticky</option>
                            <option value="transparent" {{ ($theme->config['header_style'] ?? '') == 'transparent' ? 'selected' : '' }}>Transparent</option>
                            <option value="minimal" {{ ($theme->config['header_style'] ?? '') == 'minimal' ? 'selected' : '' }}>Minimal</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Footer Style</label>
                        <select name="config[footer_style]" class="w-full">
                            <option value="dark" {{ ($theme->config['footer_style'] ?? '') == 'dark' ? 'selected' : '' }}>Dark</option>
                            <option value="light" {{ ($theme->config['footer_style'] ?? '') == 'light' ? 'selected' : '' }}>Light</option>
                            <option value="minimal" {{ ($theme->config['footer_style'] ?? '') == 'minimal' ? 'selected' : '' }}>Minimal</option>
                            <option value="widget" {{ ($theme->config['footer_style'] ?? '') == 'widget' ? 'selected' : '' }}>Widget</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Sidebar Position</label>
                        <select name="config[sidebar_position]" class="w-full">
                            <option value="none" {{ ($theme->config['sidebar_position'] ?? '') == 'none' ? 'selected' : '' }}>None</option>
                            <option value="left" {{ ($theme->config['sidebar_position'] ?? '') == 'left' ? 'selected' : '' }}>Left</option>
                            <option value="right" {{ ($theme->config['sidebar_position'] ?? '') == 'right' ? 'selected' : '' }}>Right</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">Layout Width</label>
                        <select name="config[layout_width]" class="w-full">
                            <option value="boxed" {{ ($theme->config['layout_width'] ?? '') == 'boxed' ? 'selected' : '' }}>Boxed</option>
                            <option value="wide" {{ ($theme->config['layout_width'] ?? '') == 'wide' ? 'selected' : '' }}>Wide</option>
                            <option value="full" {{ ($theme->config['layout_width'] ?? '') == 'full' ? 'selected' : '' }}>Full Width</option>
                            <option value="narrow" {{ ($theme->config['layout_width'] ?? '') == 'narrow' ? 'selected' : '' }}>Narrow</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200">
                <button type="submit" class="w-full px-4 py-3 bg-primary text-white font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    {{-- Live Preview --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Live Preview</h3>
            <div class="border-2 border-slate-200 rounded-lg overflow-hidden" style="height: 600px;">
                <iframe src="{{ route('home') }}" class="w-full h-full"></iframe>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('themeCustomizer', () => ({
        primaryColor: '{{ $theme->config['primary_color'] ?? '#3D9E7A' }}',
        secondaryColor: '{{ $theme->config['secondary_color'] ?? '#FF6B35' }}',
    }));
});
</script>
@endsection
