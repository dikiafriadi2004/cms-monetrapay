@include('frontend.theme-layout')
@extends($themeLayout)
@section('title', 'Unsubscribed')
@section('seo')
<meta name="robots" content="noindex,nofollow">
@endsection

@section('content')
<section class="min-h-[60vh] flex items-center justify-center px-4 py-16">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 mx-auto mb-6 bg-slate-100 rounded-2xl flex items-center justify-center">
            <svg class="w-10 h-10 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </div>
        <h1 class="text-2xl font-extrabold text-slate-800 mb-3">Berhenti Langganan</h1>
        <p class="text-slate-600 mb-8 leading-relaxed">
            <strong>{{ $subscriber->email }}</strong> telah berhenti dari newsletter kami. Kami akan merindukanmu!
        </p>
        <a href="{{ url('/') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-slate-100 border border-slate-200 text-slate-700 text-sm font-bold rounded-xl hover:bg-slate-200 transition-colors">
            Kembali ke Beranda
        </a>
    </div>
</section>
@endsection
