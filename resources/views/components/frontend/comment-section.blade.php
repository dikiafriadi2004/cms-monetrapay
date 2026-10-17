@props(['blog'])

@php
    $comments = \App\Models\Comment::with('replies.replies')
        ->where('blog_id', $blog->id)
        ->whereNull('parent_id')
        ->approved()
        ->orderBy('created_at')
        ->get();
    $totalCount = \App\Models\Comment::where('blog_id', $blog->id)->approved()->count();
@endphp

<section id="comments" class="mt-12 pt-10 border-t border-slate-200 scroll-mt-24" x-data="{ replyTo: null }">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
            Komentar
            <span class="inline-flex items-center justify-center min-w-[28px] h-7 px-2 text-xs font-bold text-primary bg-primary/10 rounded-full">{{ $totalCount }}</span>
        </h2>
    </div>

    {{-- Flash messages --}}
    @if(session('comment_success'))
    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm">
        ✓ {{ session('comment_success') }}
    </div>
    @endif
    @if(session('comment_error'))
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm">
        {{ session('comment_error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200">
        <ul class="text-sm text-red-700 list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
    @endif

    {{-- Comment form with localStorage memory --}}
    <div class="bg-slate-50 rounded-2xl border border-slate-100 p-5 mb-8"
         x-data="{
             submitting: false,
             savedName: '',
             savedEmail: '',
             init() {
                 try {
                     const saved = JSON.parse(localStorage.getItem('mp_commenter') || '{}');
                     this.savedName = saved.name || '';
                     this.savedEmail = saved.email || '';
                 } catch(e) {}
             },
             saveCommenter() {
                 try {
                     const n = document.getElementById('comment_name');
                     const e = document.getElementById('comment_email');
                     if (n && e) localStorage.setItem('mp_commenter', JSON.stringify({name: n.value, email: e.value}));
                 } catch(e) {}
             }
         }">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-slate-800" x-text="replyTo ? ('Balas komentar dari ' + replyTo.name) : 'Tinggalkan Komentar'"></h3>
            <button type="button" x-show="replyTo" x-cloak @click="replyTo = null"
                    class="text-xs text-slate-500 hover:text-slate-700 underline">Batal balas</button>
        </div>
        <form action="{{ route('comments.store', $blog) }}" method="POST" class="space-y-3"
              @submit="if (submitting) { $event.preventDefault(); return; } submitting = true; saveCommenter()">
            @csrf
            <input type="hidden" name="parent_id" :value="replyTo ? replyTo.id : ''">
            <div style="position:absolute; left:-9999px;" aria-hidden="true">
                <input type="text" name="website" tabindex="-1" autocomplete="off">
            </div>
            <input type="hidden" name="_t" value="{{ time() }}">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <input type="text" name="author_name" id="comment_name"
                       value="{{ old('author_name') }}"
                       :value="$el.value || savedName"
                       placeholder="Nama *" required maxlength="100"
                       class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15">
                <input type="email" name="author_email" id="comment_email"
                       value="{{ old('author_email') }}"
                       :value="$el.value || savedEmail"
                       placeholder="Email * (tidak ditampilkan)" required maxlength="255"
                       class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15">
            </div>
            <textarea name="content" required minlength="5" maxlength="2000" rows="4" placeholder="Tulis komentar Anda..."
                      class="w-full px-4 py-2.5 text-sm rounded-xl border border-slate-200 bg-white focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/15 resize-none">{{ old('content') }}</textarea>
            <div class="flex items-center justify-between gap-3">
                <p class="text-[11px] text-slate-500">Email tidak dipublikasikan. Nama & email diingat untuk komentar berikutnya.</p>
                <button type="submit"
                        :disabled="submitting"
                        class="px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-sm disabled:opacity-60 disabled:cursor-not-allowed inline-flex items-center gap-2">
                    <svg x-show="submitting" x-cloak class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span x-text="submitting ? 'Mengirim...' : 'Kirim Komentar'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- Comments list --}}
    @if($comments->isEmpty())
        <div class="text-center py-12">
            <div class="w-14 h-14 mx-auto mb-3 bg-slate-100 rounded-2xl flex items-center justify-center">
                <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <p class="text-sm text-slate-500">Belum ada komentar. Jadilah yang pertama!</p>
        </div>
    @else
        <div class="space-y-6">
            @foreach($comments as $comment)
                <x-frontend.comment-item :comment="$comment" />
            @endforeach
        </div>
    @endif
</section>
