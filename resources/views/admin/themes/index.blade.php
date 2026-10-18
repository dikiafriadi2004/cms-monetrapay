@extends('admin.layouts.app')

@section('title', 'Themes')
@section('page-title', 'Theme Management')

@section('content')
<div class="space-y-6">

    {{-- Active Theme Banner --}}
    @if($activeTheme)
    <div class="bg-gradient-to-r from-primary to-primary-dark text-white rounded-2xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-semibold opacity-90 mb-1">ACTIVE THEME</p>
                <h2 class="text-2xl font-bold">{{ $activeTheme->name }}</h2>
                <p class="text-sm opacity-90 mt-2">{{ $activeTheme->description }}</p>
            </div>
            <a href="{{ route('admin.themes.customize', $activeTheme) }}" class="px-6 py-3 bg-white text-primary rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                Customize
            </a>
        </div>
    </div>
    @endif

    {{-- Theme Grid --}}
    <div class="bg-white rounded-2xl border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-bold text-slate-800">Available Themes ({{ $themes->count() }})</h2>
            <p class="text-sm text-slate-600">Click to preview, then activate your favorite theme</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($themes as $theme)
            <div class="group border-2 {{ $theme->is_active ? 'border-primary bg-primary/5' : 'border-slate-200 hover:border-slate-300' }} rounded-xl overflow-hidden transition-all">
                {{-- Thumbnail --}}
                <div class="aspect-video bg-gradient-to-br {{ $theme->is_active ? 'from-primary/20 to-secondary/20' : 'from-slate-100 to-slate-200' }} relative overflow-hidden">
                    @if($theme->thumbnail && file_exists(public_path($theme->thumbnail)))
                        <img src="{{ $theme->thumbnail }}" alt="{{ $theme->name }}" class="w-full h-full object-cover">
                    @else
                        {{-- Placeholder with theme colors --}}
                        <div class="w-full h-full flex items-center justify-center text-6xl font-bold text-white" style="background: linear-gradient(135deg, {{ $theme->config['primary_color'] ?? '#3D9E7A' }} 0%, {{ $theme->config['secondary_color'] ?? '#FF6B35' }} 100%);">
                            {{ substr($theme->name, 0, 1) }}
                        </div>
                    @endif
                    
                    {{-- Active Badge --}}
                    @if($theme->is_active)
                    <div class="absolute top-3 right-3 px-3 py-1.5 bg-white text-primary text-xs font-bold rounded-full shadow-lg">
                        ACTIVE
                    </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="p-4">
                    <h3 class="text-lg font-bold text-slate-800 mb-1">{{ $theme->name }}</h3>
                    <p class="text-xs text-slate-600 mb-4 line-clamp-2">{{ $theme->description }}</p>

                    {{-- Color Preview --}}
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 rounded-lg border-2 border-white shadow-sm" style="background-color: {{ $theme->config['primary_color'] ?? '#3D9E7A' }}" title="Primary"></div>
                        <div class="w-8 h-8 rounded-lg border-2 border-white shadow-sm" style="background-color: {{ $theme->config['secondary_color'] ?? '#FF6B35' }}" title="Secondary"></div>
                        <div class="flex-1 text-xs text-slate-500">
                            <span class="font-mono">{{ $theme->config['font_heading'] ?? 'Default' }}</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        @if(!$theme->is_active)
                        <form method="POST" action="{{ route('admin.themes.activate', $theme) }}" class="flex-1">
                            @csrf
                            @method('POST')
                            <button type="submit" class="w-full px-4 py-2 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                                Activate
                            </button>
                        </form>
                        @else
                        <a href="{{ route('admin.themes.customize', $theme) }}" class="flex-1 px-4 py-2 bg-secondary text-white text-sm font-semibold rounded-lg hover:bg-secondary-dark transition-colors text-center">
                            Customize
                        </a>
                        @endif
                        
                        <a href="{{ route('admin.themes.preview', $theme) }}" class="px-4 py-2 border-2 border-slate-200 text-slate-700 text-sm font-semibold rounded-lg hover:border-slate-300 hover:bg-slate-50 transition-colors" target="_blank">
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
    if (typeof toast !== 'undefined') {
        toast('{{ session('success') }}', 'success');
    }
});
</script>
@endif
@endsection
