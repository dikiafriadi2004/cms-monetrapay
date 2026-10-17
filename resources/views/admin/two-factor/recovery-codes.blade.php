@extends('admin.layouts.app')
@section('title', '2FA — Recovery Codes')
@section('page-title', '2FA Aktif')

@section('content')
<div class="max-w-xl space-y-5">

    <div class="bg-white border-2 border-green-200 rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 bg-green-50 border-b border-green-100 flex items-start gap-3">
            <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h1 class="text-base font-extrabold text-green-900">2FA Berhasil Diaktifkan!</h1>
                <p class="text-sm text-green-700 mt-0.5">Login berikutnya akan meminta kode dari aplikasi authenticator Anda.</p>
            </div>
        </div>
        <div class="p-6">
            <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl mb-5">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                    <div>
                        <p class="text-sm font-bold text-amber-900">Simpan Recovery Codes Sekarang</p>
                        <p class="text-xs text-amber-800 mt-1">Kode ini hanya ditampilkan <strong>satu kali</strong>. Simpan di tempat aman — dibutuhkan jika Anda kehilangan akses ke aplikasi authenticator.</p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-xl p-5 mb-4">
                <div class="grid grid-cols-2 gap-2">
                    @foreach($recoveryCodes as $code)
                    <code class="text-sm font-mono font-bold text-green-400 tracking-widest">{{ $code }}</code>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3 flex-wrap">
                <button onclick="copyRecoveryCodes()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 text-white text-xs font-bold rounded-lg hover:bg-slate-700 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    Salin Semua
                </button>
                <a href="{{ route('admin.profile.edit') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary-dark transition-colors">
                    Lanjut ke Profil →
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function copyRecoveryCodes() {
    const codes = @json($recoveryCodes);
    navigator.clipboard.writeText(codes.join('\n')).then(() => {
        alert('Recovery codes berhasil disalin ke clipboard.');
    });
}
</script>
@endsection
