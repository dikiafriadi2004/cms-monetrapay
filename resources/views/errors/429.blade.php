@extends('errors.layout')
@section('code', '429')
@section('title', 'Terlalu Banyak Permintaan')
@section('icon')
<svg class="w-7 h-7 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
@endsection
@section('message')
Anda mengirim terlalu banyak permintaan dalam waktu singkat. Tunggu beberapa saat dan coba lagi.
@endsection
