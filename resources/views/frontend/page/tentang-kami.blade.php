@extends('frontend.layouts.app')
@section('title', 'Tentang Kami - ' . ($siteName ?? 'MonetraPay'))
@section('seo')
<x-seo-meta :title="$metaTags['title']" :description="$metaTags['description']" :keywords="$metaTags['keywords']" :canonicalUrl="$canonicalUrl" :ogTags="$ogTags" :twitterTags="$twitterTags" :structuredData="$structuredData"/>
@endsection

@section('content')
{{-- Hero --}}
<section class="relative bg-slate-900 overflow-hidden py-20 md:py-28">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(61,158,122,0.08) 1px, transparent 1px); background-size: 28px 28px;"></div>
    <div class="absolute top-0 left-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block text-xs font-bold text-primary bg-primary/10 border border-primary/20 px-4 py-1.5 rounded-full mb-5 uppercase tracking-widest">Tentang Kami</span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-5 leading-tight">Kami Hadir untuk<br class="hidden md:block"> <span class="text-primary">Memberdayakan</span> Bisnis Anda</h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">MonetraPay adalah platform transaksi digital terpercaya yang telah melayani lebih dari 100.000 agen di seluruh Indonesia.</p>
    </div>
</section>

{{-- Story --}}
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div class="animate-on-scroll">
                <span class="inline-block text-sm font-semibold text-primary bg-primary-light px-4 py-1.5 rounded-full mb-5">Cerita Kami</span>
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-slate-800 mb-6">Dari Ide Sederhana Menjadi Platform Terdepan</h2>
                <div class="w-12 h-1 bg-secondary rounded-full mb-6"></div>
                <p class="text-slate-600 leading-relaxed mb-5">MonetraPay lahir dari kebutuhan nyata para pelaku bisnis digital di Indonesia. Kami memahami tantangan yang dihadapi agen pulsa dan PPOB dalam menjalankan bisnis mereka sehari-hari.</p>
                <p class="text-slate-600 leading-relaxed mb-8">Dengan teknologi terkini dan tim yang berpengalaman, kami membangun platform yang tidak hanya mudah digunakan, tetapi juga memberikan keuntungan nyata bagi setiap mitra kami.</p>
                <div class="grid grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-primary-light rounded-2xl">
                        <p class="text-2xl font-extrabold text-primary">2020</p>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Tahun Berdiri</p>
                    </div>
                    <div class="text-center p-4 bg-secondary-light rounded-2xl">
                        <p class="text-2xl font-extrabold text-secondary">100K+</p>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Agen Aktif</p>
                    </div>
                    <div class="text-center p-4 bg-slate-50 rounded-2xl">
                        <p class="text-2xl font-extrabold text-slate-800">34</p>
                        <p class="text-xs text-slate-500 mt-1 font-medium">Provinsi</p>
                    </div>
                </div>
            </div>
            <div class="animate-on-scroll delay-200">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-primary-light rounded-2xl p-6">
                        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">Terpercaya</h3>
                        <p class="text-sm text-slate-600">Sistem keamanan berlapis untuk setiap transaksi</p>
                    </div>
                    <div class="bg-secondary-light rounded-2xl p-6 mt-6">
                        <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">Cepat</h3>
                        <p class="text-sm text-slate-600">Proses transaksi instan kurang dari 30 detik</p>
                    </div>
                    <div class="bg-slate-50 rounded-2xl p-6">
                        <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">Menguntungkan</h3>
                        <p class="text-sm text-slate-600">Margin kompetitif untuk setiap produk</p>
                    </div>
                    <div class="bg-primary-light rounded-2xl p-6 mt-6">
                        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="font-bold text-slate-800 mb-2">Support 24/7</h3>
                        <p class="text-sm text-slate-600">Tim siap membantu kapan saja Anda butuhkan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Vision Mission --}}
<section class="py-16 md:py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="animate-on-scroll bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
                <div class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Visi</h3>
                <p class="text-slate-600 leading-relaxed">Menjadi platform transaksi digital terdepan di Indonesia yang memberdayakan jutaan agen untuk meraih kesuksesan finansial.</p>
            </div>
            <div class="animate-on-scroll delay-200 bg-white rounded-2xl p-8 border border-slate-100 shadow-sm">
                <div class="w-14 h-14 bg-secondary rounded-2xl flex items-center justify-center mb-6">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l2 2 4-4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-3">Misi</h3>
                <ul class="text-slate-600 space-y-2">
                    <li class="flex items-start gap-2"><span class="text-primary mt-1">✓</span> Menyediakan produk digital terlengkap dengan harga kompetitif</li>
                    <li class="flex items-start gap-2"><span class="text-primary mt-1">✓</span> Memberikan sistem yang stabil dan transaksi yang cepat</li>
                    <li class="flex items-start gap-2"><span class="text-primary mt-1">✓</span> Mendukung pertumbuhan bisnis agen dengan tools dan edukasi</li>
                </ul>
            </div>
        </div>
    </div>
</section>
@endsection
