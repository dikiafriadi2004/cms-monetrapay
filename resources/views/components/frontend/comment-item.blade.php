@props(['comment'])

<article id="comment-{{ $comment->id }}" class="flex gap-4">
    <img src="{{ $comment->gravatarUrl(48) }}" alt="" loading="lazy"
         class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-slate-100 shrink-0">
    <div class="flex-1 min-w-0">
        <div class="bg-white border border-slate-100 rounded-2xl rounded-tl-md p-4 shadow-sm">
            <div class="flex items-baseline gap-2 mb-1.5 flex-wrap">
                @if($comment->author_website)
                    <a href="{{ $comment->author_website }}" target="_blank" rel="nofollow noopener noreferrer"
                       class="text-sm font-bold text-slate-800 hover:text-primary">{{ $comment->author_name }}</a>
                @else
                    <span class="text-sm font-bold text-slate-800">{{ $comment->author_name }}</span>
                @endif
                <time class="text-xs text-slate-400" datetime="{{ $comment->created_at->toIso8601String() }}">
                    {{ $comment->created_at->translatedFormat('d M Y, H:i') }}
                </time>
            </div>
            <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-wrap break-words">{{ $comment->content }}</div>
            <button type="button"
                    @click="replyTo = {id: {{ $comment->id }}, name: @js($comment->author_name)}; document.querySelector('[name=content]').focus(); window.scrollTo({top: document.querySelector('#comments').offsetTop - 80, behavior: 'smooth'});"
                    class="mt-3 text-xs font-semibold text-primary hover:text-primary-dark inline-flex items-center gap-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                Balas
            </button>
        </div>

        @if($comment->replies->isNotEmpty())
            <div class="mt-4 pl-2 sm:pl-6 space-y-4 border-l-2 border-slate-100">
                @foreach($comment->replies as $reply)
                    <x-frontend.comment-item :comment="$reply" />
                @endforeach
            </div>
        @endif
    </div>
</article>
