@extends('errors.layout')
@section('code', '419')
@section('title', 'Sesi Kedaluwarsa')
@section('icon')
<svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
@endsection
@section('message')
Token keamanan halaman sudah kedaluwarsa. Klik tombol di bawah untuk memuat ulang halaman dan coba lagi.
@endsection
@section('extra')
<script>
// Auto redirect back after 3 seconds with a fresh page load
setTimeout(function() {
    var prev = document.referrer;
    if (prev && prev !== window.location.href) {
        window.location.href = prev;
    } else {
        window.location.reload();
    }
}, 3000);
</script>
<p class="text-xs text-slate-400 mt-4">Halaman akan dimuat ulang otomatis dalam <span id="countdown">3</span> detik...</p>
<script>
var c = 3;
var t = setInterval(function() {
    c--;
    var el = document.getElementById('countdown');
    if (el) el.textContent = c;
    if (c <= 0) clearInterval(t);
}, 1000);
</script>
@endsection
