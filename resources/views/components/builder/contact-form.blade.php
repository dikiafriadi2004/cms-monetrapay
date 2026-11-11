{{-- Contact Form Component --}}
{{-- Fields match ContactController validation exactly:
     required: name, phone
     optional:  email, city, message
     hidden:    website (honeypot), _t (timestamp check) --}}
@php
    $title       = $content['title']           ?? 'Hubungi Kami';
    $subtitle    = $content['subtitle']        ?? 'Isi formulir di bawah dan tim kami akan segera menghubungi Anda.';
    $showPhone   = ($content['show_phone']     ?? true)  !== false;
    $showCity    = ($content['show_city']      ?? false) !== false;
    $showSubject = ($content['show_subject']   ?? false) !== false;
    $btnText     = $content['button_text']     ?? 'Kirim Pesan';
    $successMsg  = $content['success_message'] ?? 'Terima kasih! Kami akan segera menghubungi Anda.';

    // Unique ID so multiple contact forms on the same page don't clash
    $formId = 'contact-form-' . uniqid();
@endphp

<section class="py-16 md:py-24 bg-slate-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            {{-- Left: Info --}}
            <div>
                <span class="inline-block text-xs font-bold tracking-widest text-primary uppercase mb-4 bg-primary/10 px-3 py-1 rounded-full">Kontak</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 mb-4 leading-tight">
                    {{ $title }}
                </h2>
                <p class="text-lg text-slate-500 mb-8 leading-relaxed">
                    {{ $subtitle }}
                </p>

                {{-- Info cards --}}
                <div class="space-y-4">
                    @php $contactEmail = \App\Models\Setting::get('contact_email', 'general'); @endphp
                    @php $contactPhone = \App\Models\Setting::get('contact_phone', 'general'); @endphp

                    @if($contactEmail)
                    <div class="flex items-start gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</p>
                            <a href="mailto:{{ $contactEmail }}" class="text-sm font-medium text-slate-700 hover:text-primary transition-colors">{{ $contactEmail }}</a>
                        </div>
                    </div>
                    @endif

                    @if($contactPhone)
                    <div class="flex items-start gap-4 p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-secondary/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">WhatsApp / Telepon</p>
                            <a href="tel:{{ $contactPhone }}" class="text-sm font-medium text-slate-700 hover:text-primary transition-colors">{{ $contactPhone }}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right: Form --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-100 p-8 md:p-10">

                {{-- Success message --}}
                @if(session('contact_success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-2xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-green-800">{{ session('contact_success', $successMsg) }}</p>
                </div>
                @endif

                {{-- Error message --}}
                @if(session('contact_error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm font-medium text-red-800">{{ session('contact_error') }}</p>
                </div>
                @endif

                {{-- Validation errors --}}
                @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl">
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                        <li class="text-sm text-red-700 flex items-center gap-2">
                            <span class="w-1 h-1 rounded-full bg-red-500 shrink-0"></span>
                            {{ $error }}
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form id="{{ $formId }}" action="{{ route('contact.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    {{-- Honeypot: must stay empty --}}
                    <div class="absolute opacity-0 pointer-events-none h-0 overflow-hidden" aria-hidden="true" tabindex="-1">
                        <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                    </div>

                    {{-- Timestamp: form must take >= 3s to fill --}}
                    <input type="hidden" name="_t" value="{{ time() }}">

                    {{-- Name --}}
                    <div>
                        <label for="{{ $formId }}-name" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="{{ $formId }}-name" name="name"
                               value="{{ old('name') }}" required
                               placeholder="Masukkan nama lengkap Anda"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm text-slate-800 placeholder-slate-400 @error('name') border-red-400 bg-red-50 @enderror">
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone (required by controller) --}}
                    <div>
                        <label for="{{ $formId }}-phone" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="{{ $formId }}-phone" name="phone"
                               value="{{ old('phone') }}" required
                               placeholder="08xx-xxxx-xxxx"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm text-slate-800 placeholder-slate-400 @error('phone') border-red-400 bg-red-50 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email (optional) --}}
                    <div>
                        <label for="{{ $formId }}-email" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Email <span class="text-xs text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input type="email" id="{{ $formId }}-email" name="email"
                               value="{{ old('email') }}"
                               placeholder="email@contoh.com"
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm text-slate-800 placeholder-slate-400 @error('email') border-red-400 bg-red-50 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- City (optional, controlled by component config) --}}
                    @if($showCity)
                    <div>
                        <label for="{{ $formId }}-city" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Kota / Kabupaten <span class="text-xs text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input type="text" id="{{ $formId }}-city" name="city"
                               value="{{ old('city') }}"
                               placeholder="Jakarta, Surabaya, ..."
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm text-slate-800 placeholder-slate-400">
                    </div>
                    @endif

                    {{-- Message --}}
                    <div>
                        <label for="{{ $formId }}-message" class="block text-sm font-semibold text-slate-700 mb-1.5">
                            Pesan <span class="text-xs text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <textarea id="{{ $formId }}-message" name="message" rows="4"
                                  placeholder="Ceritakan kebutuhan Anda..."
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all text-sm text-slate-800 placeholder-slate-400 resize-none @error('message') border-red-400 bg-red-50 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-dark transition-all duration-200 shadow-lg shadow-primary/25 hover:shadow-primary/40 hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        {{ $btnText }}
                    </button>

                    <p class="text-center text-xs text-slate-400">
                        Dengan mengirim formulir ini, Anda menyetujui
                        <a href="{{ url('/kebijakan-privasi') }}" class="text-primary hover:underline">Kebijakan Privasi</a> kami.
                    </p>
                </form>
            </div>
        </div>
    </div>
</section>
