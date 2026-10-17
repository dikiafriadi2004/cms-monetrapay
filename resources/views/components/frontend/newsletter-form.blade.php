@props(['variant' => 'inline'])

<div x-data="{ submitted: {{ session('newsletter_success') ? 'true' : 'false' }} }" class="newsletter-form">
    @if(session('newsletter_success'))
    <div class="mb-3 p-3 rounded-lg bg-green-500/10 border border-green-500/20 text-green-200 text-sm">
        ✓ {{ session('newsletter_success') }}
    </div>
    @endif
    @if(session('newsletter_error'))
    <div class="mb-3 p-3 rounded-lg bg-red-500/10 border border-red-500/20 text-red-200 text-sm">
        {{ session('newsletter_error') }}
    </div>
    @endif

    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="flex flex-col sm:flex-row gap-2">
        @csrf
        <div style="position:absolute; left:-9999px;" aria-hidden="true">
            <input type="text" name="website" tabindex="-1" autocomplete="off">
        </div>
        <input type="email" name="email" required value="{{ old('email') }}" placeholder="email@example.com"
               class="flex-1 px-4 py-3 rounded-xl border border-white/15 bg-white/5 text-white placeholder-slate-500 focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/30 text-sm">
        <button type="submit"
                class="px-5 py-3 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-lg shadow-primary/20 whitespace-nowrap">
            Subscribe
        </button>
    </form>
    @error('email')
        <p class="text-xs text-red-300 mt-2">{{ $message }}</p>
    @enderror
    <p class="text-xs text-slate-500 mt-2">Tidak akan kami spam. Unsubscribe kapan saja.</p>
</div>
