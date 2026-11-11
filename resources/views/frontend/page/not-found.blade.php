@extends('frontend.layouts.app')

@section('title', 'Halaman Tidak Ditemukan')

@section('seo')
<meta name="robots" content="noindex, nofollow">
@endsection

@section('content')
<section class="min-h-[60vh] flex items-center justify-center py-20">
    <div class="max-w-lg mx-auto px-4 text-center">
        <div class="w-20 h-20 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 mb-3">Halaman Sedang Dipersiapkan</h1>
        <p class="text-slate-500 mb-8 leading-relaxed">
            Halaman ini belum tersedia atau sedang dalam persiapan. Silakan kembali lagi nanti.
        </p>
        <a href="{{ url('/') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-primary text-white font-semibold rounded-xl hover:bg-primary-dark transition-colors shadow-md shadow-primary/20">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Beranda
        </a>
    </div>
</section>
@endsection
