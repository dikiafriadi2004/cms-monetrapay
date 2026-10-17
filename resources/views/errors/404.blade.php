@extends('errors.layout')
@section('code', '404')
@section('title', 'Halaman Tidak Ditemukan')
@section('icon')
<svg class="w-7 h-7 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
@endsection
@section('message')
Halaman yang Anda cari tidak ditemukan. Mungkin sudah dipindahkan, dihapus, atau alamatnya salah.
@endsection
@section('extra')
<div class="mt-8 pt-6 border-t border-slate-200">
    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3">Coba juga</p>
    <div class="flex items-center justify-center gap-2 flex-wrap text-sm">
        <a href="{{ url('/blog') }}" class="text-primary hover:underline font-semibold">Blog</a>
        <span class="text-slate-300">·</span>
        <a href="{{ url('/layanan') }}" class="text-primary hover:underline font-semibold">Layanan</a>
        <span class="text-slate-300">·</span>
        <a href="{{ url('/kontak') }}" class="text-primary hover:underline font-semibold">Kontak</a>
        <span class="text-slate-300">·</span>
        <a href="{{ url('/search') }}" class="text-primary hover:underline font-semibold">Pencarian</a>
    </div>
</div>
@endsection
