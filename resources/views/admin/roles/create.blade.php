@extends('admin.layouts.app')

@section('title', 'Tambah Role')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.roles.index') }}" class="text-sm text-primary hover:text-primary-dark">&larr; Kembali ke daftar role</a>
    <h1 class="text-2xl font-bold text-slate-800 mt-2">Tambah Role Baru</h1>
</div>

<form method="POST" action="{{ route('admin.roles.store') }}">
    @csrf

    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        {{-- Role Name --}}
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-slate-700 mb-1.5">
                Nama Role <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
                maxlength="50"
                class="w-full max-w-md px-4 py-2.5 rounded-lg border border-slate-300 text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                placeholder="Contoh: Content Manager"
            >
            <p class="mt-1 text-xs text-slate-500">Maksimal 50 karakter. Hanya huruf, angka, dan spasi.</p>
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Permissions --}}
    <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4">Permissions <span class="text-red-500">*</span></h2>
        <p class="text-sm text-slate-500 mb-4">Pilih minimal 1 permission untuk role ini.</p>

        @error('permissions')
            <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200">
                <p class="text-sm text-red-600">{{ $message }}</p>
            </div>
        @enderror

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($permissions as $module => $modulePermissions)
                <div class="border border-slate-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-slate-700 uppercase mb-3 pb-2 border-b border-slate-100">
                        {{ ucfirst($module) }}
                    </h3>
                    <div class="space-y-2">
                        @foreach ($modulePermissions as $permission)
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="permissions[]"
                                    value="{{ $permission->name }}"
                                    {{ in_array($permission->name, old('permissions', [])) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                >
                                <span class="text-sm text-slate-600">{{ ucfirst(explode('.', $permission->name)[1]) }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Submit --}}
    <div class="flex items-center gap-3">
        <button type="submit"
                class="px-6 py-2.5 bg-primary hover:bg-primary-dark text-white font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary/40 focus:ring-offset-2">
            Simpan Role
        </button>
        <a href="{{ route('admin.roles.index') }}"
           class="px-6 py-2.5 bg-white border border-slate-300 text-slate-700 font-medium rounded-lg hover:bg-slate-50 transition-colors">
            Batal
        </a>
    </div>
</form>
@endsection
