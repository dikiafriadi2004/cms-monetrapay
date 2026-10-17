@extends('frontend.layouts.app')
@section('title', 'Subscription Confirmed')
@section('seo')
<meta name="robots" content="noindex,nofollow">
@endsection

@section('content')
<section class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto mb-6 bg-green-100 rounded-2xl flex items-center justify-center">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-800 mb-3">Berhasil Berlangganan!</h1>
        <p class="text-slate-600 mb-8 leading-relaxed">
            Terima kasih, <strong>{{ $subscriber->email }}</strong>. Anda akan menerima email update dari kami.
        </p>
        <a href="{{ url('/') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-lg shadow-primary/20">
            Kembali ke Beranda
        </a>
    </div>
</section>
@endsection
