@extends('frontend.layouts.app')
@section('title', 'Kontak - ' . ($siteName ?? 'MonetraPay'))
@section('seo')
<x-seo-meta :title="$metaTags['title']" :description="$metaTags['description']" :keywords="$metaTags['keywords']" :canonicalUrl="$canonicalUrl" :ogTags="$ogTags" :twitterTags="$twitterTags" :structuredData="$structuredData"/>
@endsection

@section('content')
{{-- Hero --}}
<section class="relative bg-secondary overflow-hidden py-20 md:py-28">
    <div class="absolute inset-0" style="background-image: radial-gradient(circle, rgba(255,255,255,0.06) 1px, transparent 1px); background-size: 28px 28px;"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -translate-x-1/2 translate-y-1/2"></div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <span class="inline-block text-xs font-bold text-white/70 bg-white/10 border border-white/20 px-4 py-1.5 rounded-full mb-5 uppercase tracking-widest">Hubungi Kami</span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white mb-5 leading-tight">Kami Siap Membantu Anda</h1>
        <p class="text-white/80 text-lg max-w-xl mx-auto">Punya pertanyaan atau ingin bergabung? Tim kami siap membantu 24/7.</p>
    </div>
</section>

{{-- Contact Content --}}
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">

            {{-- Contact Info --}}
            <div class="animate-on-scroll">
                <h2 class="text-2xl md:text-3xl font-extrabold text-slate-800 mb-3">Informasi Kontak</h2>
                <div class="w-12 h-1 bg-secondary rounded-full mb-8"></div>

                <div class="space-y-6">
                    @php $email = \App\Models\Setting::get('contact_email', 'general'); @endphp
                    @if($email)
                    <div class="flex items-start gap-4 p-5 bg-slate-50 rounded-2xl">
                        <div class="w-12 h-12 bg-primary rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Email</p>
                            <a href="mailto:{{ $email }}" class="text-slate-800 font-semibold hover:text-primary transition-colors">{{ $email }}</a>
                        </div>
                    </div>
                    @endif

                    @php $phone = \App\Models\Setting::get('contact_phone', 'general'); @endphp
                    @if($phone)
                    <div class="flex items-start gap-4 p-5 bg-slate-50 rounded-2xl">
                        <div class="w-12 h-12 bg-secondary rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Telepon / WhatsApp</p>
                            <a href="tel:{{ $phone }}" class="text-slate-800 font-semibold hover:text-primary transition-colors">{{ $phone }}</a>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-start gap-4 p-5 bg-slate-50 rounded-2xl">
                        <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Jam Operasional</p>
                            <p class="text-slate-800 font-semibold">24 Jam / 7 Hari</p>
                            <p class="text-sm text-slate-500">Layanan tersedia sepanjang waktu</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Registration Form --}}
            <div class="animate-on-scroll delay-200">
                <div class="bg-white rounded-2xl border border-slate-100 shadow-lg p-8">
                    <h2 class="text-xl font-bold text-slate-800 mb-2">Daftar Menjadi Agen</h2>
                    <p class="text-slate-500 text-sm mb-6">Isi formulir di bawah dan tim kami akan menghubungi Anda segera.</p>

                    @if(session('contact_success'))
                        <div class="mb-5 p-4 rounded-xl bg-green-50 border border-green-200 flex items-start gap-3">
                            <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-green-800 font-medium">{{ session('contact_success') }}</p>
                        </div>
                    @endif

                    @if(session('contact_error'))
                        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200 flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-sm text-red-800 font-medium">{{ session('contact_error') }}</p>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-5 p-4 rounded-xl bg-red-50 border border-red-200">
                            <ul class="text-sm text-red-700 list-disc list-inside space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="space-y-5" action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        {{-- Honeypot (hidden from real users) --}}
                        <div style="position:absolute;left:-9999px;" aria-hidden="true">
                            <label>Website (jangan diisi)</label>
                            <input type="text" name="website" tabindex="-1" autocomplete="off">
                        </div>
                        {{-- Time check --}}
                        <input type="hidden" name="_t" value="{{ time() }}">

                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required maxlength="100" placeholder="Masukkan nama lengkap Anda"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary bg-slate-50 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nomor WhatsApp <span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone') }}" required maxlength="30" placeholder="Contoh: 08123456789"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary bg-slate-50 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}" maxlength="255" placeholder="email@example.com"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary bg-slate-50 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Kota / Domisili</label>
                            <input type="text" name="city" value="{{ old('city') }}" maxlength="100" placeholder="Kota Anda"
                                   class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary bg-slate-50 transition-colors">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pesan (Opsional)</label>
                            <textarea name="message" rows="3" maxlength="1000" placeholder="Pertanyaan atau informasi tambahan..."
                                      class="w-full px-4 py-3 rounded-xl border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-primary/25 focus:border-primary bg-slate-50 transition-colors resize-none">{{ old('message') }}</textarea>
                        </div>
                        <button type="submit"
                                class="w-full py-3.5 bg-secondary text-white font-bold rounded-xl hover:bg-secondary-dark transition-all duration-200 shadow-lg shadow-secondary/25 hover:-translate-y-0.5">
                            Kirim Pendaftaran
                        </button>
                        <p class="text-xs text-slate-400 text-center">Dengan mendaftar, Anda menyetujui <a href="{{ url('/syarat-ketentuan') }}" class="text-primary hover:underline">Syarat & Ketentuan</a> kami.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
