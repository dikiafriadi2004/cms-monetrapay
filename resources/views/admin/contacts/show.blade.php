@extends('admin.layouts.app')
@section('title', 'Kontak: ' . $contact->name)
@section('page-title', 'Kontak')

@section('content')
<div x-data="{ showDeleteModal: false, deleteUrl: '{{ route('admin.contacts.destroy', $contact) }}', deleteItemName: '{{ addslashes($contact->name) }}' }" class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.contacts.index') }}"
               class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-primary transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-800">{{ $contact->name }}</h1>
                <p class="text-xs text-slate-500">{{ $contact->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <form method="POST" action="{{ route('admin.contacts.toggle-read', $contact) }}">
                @csrf
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-lg hover:bg-slate-50 transition-colors">
                    {{ $contact->is_read ? 'Tandai Belum Dibaca' : 'Tandai Sudah Dibaca' }}
                </button>
            </form>
            <button type="button"
                    @click="showDeleteModal = true"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 border border-red-200 text-red-700 text-sm font-semibold rounded-lg hover:bg-red-600 hover:text-white hover:border-red-600 transition-colors">
                Hapus
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        {{-- Main info --}}
        <div class="lg:col-span-2 space-y-5">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-1 h-4 bg-primary rounded-full"></span>
                        Detail Kontak
                    </h2>
                </div>
                <dl class="divide-y divide-slate-100">
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama Lengkap</dt>
                        <dd class="col-span-2 text-sm text-slate-800">{{ $contact->name }}</dd>
                    </div>
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wide">WhatsApp</dt>
                        <dd class="col-span-2">
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $contact->phone)) }}" target="_blank"
                               class="inline-flex items-center gap-2 text-sm text-green-700 font-mono font-semibold hover:text-green-800">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                {{ $contact->phone }}
                                <span class="text-xs">↗</span>
                            </a>
                        </dd>
                    </div>
                    @if($contact->email)
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Email</dt>
                        <dd class="col-span-2"><a href="mailto:{{ $contact->email }}" class="text-sm text-primary hover:underline">{{ $contact->email }}</a></dd>
                    </div>
                    @endif
                    @if($contact->city)
                    <div class="px-6 py-4 grid grid-cols-3 gap-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Kota</dt>
                        <dd class="col-span-2 text-sm text-slate-800">{{ $contact->city }}</dd>
                    </div>
                    @endif
                    @if($contact->message)
                    <div class="px-6 py-4">
                        <dt class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-2">Pesan</dt>
                        <dd class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed bg-slate-50 p-4 rounded-lg border border-slate-100">{{ $contact->message }}</dd>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- Previous reply --}}
            @if($contact->hasBeenReplied())
            <div class="bg-white border border-green-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-green-100 bg-green-50 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                    <h3 class="text-sm font-bold text-green-800">Balasan Terkirim</h3>
                    <span class="ml-auto text-xs text-green-600">{{ $contact->replied_at->translatedFormat('d M Y, H:i') }}</span>
                </div>
                <div class="px-6 py-4">
                    <p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed">{{ $contact->admin_reply }}</p>
                    <p class="text-xs text-slate-400 mt-3">Dibalas oleh {{ $contact->repliedBy?->name ?? 'Admin' }}</p>
                </div>
            </div>
            @endif

            {{-- Reply form (only if has email) --}}
            @if($contact->email)
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                        <span class="w-1 h-4 bg-secondary rounded-full"></span>
                        {{ $contact->hasBeenReplied() ? 'Kirim Balasan Lagi' : 'Balas Pesan Ini' }}
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Balasan akan dikirim ke: <strong class="text-slate-700">{{ $contact->email }}</strong></p>
                </div>

                @if($errors->any())
                <div class="mx-6 mt-4 p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('admin.contacts.reply', $contact) }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label for="reply_message" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Pesan Balasan
                        </label>
                        <textarea name="reply_message" id="reply_message" rows="6"
                                  required minlength="5" maxlength="5000"
                                  placeholder="Tulis balasan Anda di sini...&#10;&#10;Pesan ini akan dikirim langsung ke email {{ $contact->email }}">{{ old('reply_message') }}</textarea>
                        <p class="mt-1 text-[11px] text-slate-500">Email balasan akan otomatis menyertakan pesan asli pengirim sebagai referensi.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Balasan
                        </button>
                        @if($contact->hasBeenReplied())
                            <span class="text-xs text-slate-500">Sudah dibalas {{ $contact->replied_at->diffForHumans() }} — mengirim lagi akan menimpa riwayat sebelumnya.</span>
                        @endif
                    </div>
                </form>
            </div>
            @else
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5 flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <p class="text-sm text-amber-800">Kontak ini tidak menyertakan email — tidak bisa dibalas via email. Gunakan nomor WhatsApp di atas untuk menghubungi.</p>
            </div>
            @endif
        </div>

        {{-- Meta --}}
        <div class="space-y-4">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Status</h3>
                </div>
                <div class="p-5 space-y-3">
                    <div>
                        <p class="text-xs text-slate-500">Status Baca</p>
                        @if($contact->is_read)
                            <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-1 bg-slate-100 text-slate-700 rounded-full text-xs font-semibold">Sudah dibaca</span>
                        @else
                            <span class="inline-flex items-center gap-1.5 mt-1 px-2.5 py-1 bg-primary/10 text-primary rounded-full text-xs font-semibold">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full"></span> Baru
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs text-slate-500">Diterima</p>
                        <p class="text-sm font-medium text-slate-800">{{ $contact->created_at->translatedFormat('d F Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $contact->created_at->translatedFormat('H:i') }} WIB · {{ $contact->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50">
                    <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Metadata</h3>
                </div>
                <div class="p-5 space-y-2 text-xs">
                    <div>
                        <p class="text-slate-500">IP Address</p>
                        <p class="font-mono text-slate-700">{{ $contact->ip_address ?? '—' }}</p>
                    </div>
                    @if($contact->user_agent)
                    <div>
                        <p class="text-slate-500">User Agent</p>
                        <p class="text-slate-600 break-all">{{ $contact->user_agent }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @include('components.admin.delete-modal')
</div>
@endsection
