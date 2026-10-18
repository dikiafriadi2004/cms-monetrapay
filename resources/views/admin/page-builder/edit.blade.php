@extends('admin.layouts.app')

@section('title', 'Page Builder - ' . $page->title)

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Page Builder: {{ $page->title }}</h1>
            <p class="text-sm text-slate-600 mt-1">Tambah dan atur komponen halaman Anda</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ url($page->slug) }}" target="_blank"
               class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                Preview
            </a>
            <a href="{{ route('admin.pages.edit', $page) }}"
               class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                Kembali ke Edit
            </a>
        </div>
    </div>

    {{-- Info Alert --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-blue-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div class="flex-1">
            <h3 class="text-sm font-semibold text-blue-900">Cara Menggunakan Page Builder</h3>
            <ul class="mt-2 text-sm text-blue-800 space-y-1 list-disc list-inside">
                <li>Pilih komponen yang ingin ditambahkan dari daftar di bawah</li>
                <li>Isi konten komponen sesuai kebutuhan</li>
                <li>Klik "Add Component" untuk menambahkan</li>
                <li>Atur urutan komponen dengan tombol ↑ ↓</li>
                <li>Preview hasilnya dengan klik tombol "Preview"</li>
            </ul>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Component List --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-800 mb-4">Pilih Komponen</h2>
                
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @php
                        $components = [
                            ['type' => 'hero', 'name' => 'Hero', 'icon' => '🚀', 'desc' => 'Banner besar'],
                            ['type' => 'text', 'name' => 'Text', 'icon' => '📝', 'desc' => 'Rich content'],
                            ['type' => 'contact-form', 'name' => 'Contact Form', 'icon' => '📧', 'desc' => 'Form kontak'],
                            ['type' => 'features', 'name' => 'Features', 'icon' => '✨', 'desc' => 'Grid fitur'],
                            ['type' => 'cta', 'name' => 'CTA', 'icon' => '🎯', 'desc' => 'Call-to-action'],
                            ['type' => 'testimonials', 'name' => 'Testimonials', 'icon' => '💬', 'desc' => 'Review'],
                            ['type' => 'team', 'name' => 'Team', 'icon' => '👥', 'desc' => 'Team members'],
                            ['type' => 'gallery', 'name' => 'Gallery', 'icon' => '🖼️', 'desc' => 'Image gallery'],
                            ['type' => 'video', 'name' => 'Video', 'icon' => '🎬', 'desc' => 'Embed video'],
                            ['type' => 'pricing', 'name' => 'Pricing', 'icon' => '💰', 'desc' => 'Tabel harga'],
                            ['type' => 'social', 'name' => 'Social', 'icon' => '🔗', 'desc' => 'Social links'],
                            ['type' => 'newsletter', 'name' => 'Newsletter', 'icon' => '📬', 'desc' => 'Email signup'],
                            ['type' => 'faq', 'name' => 'FAQ', 'icon' => '❓', 'desc' => 'Accordion Q&A'],
                            ['type' => 'map', 'name' => 'Map', 'icon' => '📍', 'desc' => 'Google Maps'],
                            ['type' => 'html', 'name' => 'Custom HTML', 'icon' => '💻', 'desc' => 'Raw HTML'],
                        ];
                    @endphp

                    @foreach($components as $component)
                        <button type="button" 
                                onclick="selectComponent('{{ $component['type'] }}')"
                                class="component-button p-4 bg-slate-50 hover:bg-blue-50 border-2 border-transparent hover:border-blue-500 rounded-lg text-center transition-all cursor-pointer">
                            <div class="text-3xl mb-2">{{ $component['icon'] }}</div>
                            <div class="font-semibold text-sm text-slate-800">{{ $component['name'] }}</div>
                            <div class="text-xs text-slate-500">{{ $component['desc'] }}</div>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Component Form (Hidden by default) --}}
            <div id="component-form" class="bg-white rounded-lg shadow-sm border border-slate-200 p-6 hidden">
                <h2 class="text-lg font-semibold text-slate-800 mb-4">Tambah Komponen: <span id="form-component-name"></span></h2>
                
                <form id="add-component-form" action="{{ route('admin.page-builder.save', $page) }}" method="POST">
                    @csrf
                    <input type="hidden" name="component_type" id="form-component-type">
                    
                    <div id="form-fields" class="space-y-4">
                        <!-- Dynamic fields will be inserted here -->
                    </div>

                    <div class="flex items-center gap-3 mt-6">
                        <button type="submit" class="px-5 py-2.5 bg-primary text-white text-sm font-semibold rounded-lg hover:bg-primary-dark transition-colors">
                            Add Component
                        </button>
                        <button type="button" onclick="cancelComponent()" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Components Added --}}
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <h2 class="text-lg font-semibold text-slate-800 mb-4">Komponen Aktif</h2>
                
                @if($page->components->isEmpty())
                    <div class="text-center py-8 text-slate-500">
                        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v7a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 16a1 1 0 011-1h4a1 1 0 011 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-3zM14 13a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <p class="text-sm">Belum ada komponen</p>
                        <p class="text-xs mt-1">Pilih komponen di sebelah kiri</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($page->components->sortBy('sort_order') as $component)
                            <div class="p-3 bg-slate-50 rounded-lg border border-slate-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="font-semibold text-sm text-slate-800">
                                            {{ ucfirst(str_replace('-', ' ', $component->component_type)) }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1">
                                            Order: {{ $component->sort_order }}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <button class="p-1.5 text-slate-600 hover:bg-slate-200 rounded">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </button>
                                        <form action="{{ route('admin.page-builder.delete-component', $component) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded" onclick="return confirm('Hapus komponen ini?')">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function selectComponent(type) {
    const form = document.getElementById('component-form');
    const formName = document.getElementById('form-component-name');
    const formType = document.getElementById('form-component-type');
    const formFields = document.getElementById('form-fields');
    
    formName.textContent = type.charAt(0).toUpperCase() + type.slice(1).replace('-', ' ');
    formType.value = type;
    
    // Generate form fields based on component type
    let fields = getComponentFields(type);
    formFields.innerHTML = fields;
    
    form.classList.remove('hidden');
    form.scrollIntoView({ behavior: 'smooth' });
}

function cancelComponent() {
    document.getElementById('component-form').classList.add('hidden');
    document.getElementById('add-component-form').reset();
}

function getComponentFields(type) {
    const commonFields = `
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Content (JSON)</label>
            <textarea name="content" rows="6" class="w-full rounded-lg border-slate-300 text-sm" placeholder='{"title": "Your Title", "subtitle": "Your Subtitle"}'></textarea>
            <p class="text-xs text-slate-500 mt-1">Enter component data in JSON format</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Sort Order</label>
            <input type="number" name="sort_order" value="{{ ($page->components->max('sort_order') ?? 0) + 1 }}" class="w-full rounded-lg border-slate-300 text-sm">
        </div>
        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_visible" value="1" checked class="rounded border-slate-300">
            <label class="text-sm text-slate-700">Visible</label>
        </div>
    `;

    return commonFields;
}
</script>
@endsection
