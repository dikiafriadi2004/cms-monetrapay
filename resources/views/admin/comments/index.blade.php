@extends('admin.layouts.app')
@section('title', 'Komentar')
@section('page-title', 'Moderasi Komentar')

@section('content')
<div x-data="{
        showDeleteModal: false, deleteUrl: '', deleteItemName: '',
        selectedIds: [],
        toggleAll(e) { this.selectedIds = e.target.checked ? Array.from(document.querySelectorAll('[data-bulk-row]')).map(el => parseInt(el.dataset.id)) : []; }
     }" class="space-y-5">

    <div>
        <h1 class="text-xl font-bold text-slate-800">Moderasi Komentar</h1>
        <p class="text-sm text-slate-500 mt-0.5">Kelola komentar pengunjung di artikel blog</p>
    </div>

    {{-- Status filter tabs --}}
    @php $current = request('status', 'all'); @endphp
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-2 flex items-center gap-1 flex-wrap">
        @foreach([
            ['key'=>'all',     'label'=>'Semua',     'count'=>array_sum($counts)],
            ['key'=>'pending', 'label'=>'Pending',   'count'=>$counts['pending'],   'badge'=>'amber'],
            ['key'=>'approved','label'=>'Disetujui', 'count'=>$counts['approved'],  'badge'=>'green'],
            ['key'=>'spam',    'label'=>'Spam',      'count'=>$counts['spam'],      'badge'=>'red'],
            ['key'=>'trash',   'label'=>'Trash',     'count'=>$counts['trash'],     'badge'=>'slate'],
        ] as $tab)
        @php $active = $current === $tab['key'] || ($tab['key']==='all' && !request('status')); @endphp
        <a href="{{ $tab['key']==='all' ? route('admin.comments.index') : route('admin.comments.index', ['status'=>$tab['key']]) }}"
           class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-bold transition-colors
                  {{ $active ? 'bg-slate-800 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            {{ $tab['label'] }}
            <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] rounded-full
                {{ $active ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-600' }}">{{ $tab['count'] }}</span>
        </a>
        @endforeach
    </div>

    {{-- Bulk actions --}}
    <div x-show="selectedIds.length > 0" x-cloak x-transition
         class="bg-primary text-white rounded-2xl shadow-lg p-4 flex items-center justify-between flex-wrap gap-3 sticky top-2 z-20">
        <span class="text-sm font-bold"><span x-text="selectedIds.length"></span> komentar dipilih</span>
        <form method="POST" action="{{ route('admin.comments.bulk') }}" class="flex items-center gap-2">
            @csrf
            <template x-for="id in selectedIds" :key="id"><input type="hidden" name="ids[]" :value="id"></template>
            <button type="submit" name="action" value="approve" class="px-3 py-1.5 bg-white/15 hover:bg-white/25 rounded-lg text-xs font-bold">Setujui</button>
            <button type="submit" name="action" value="spam" class="px-3 py-1.5 bg-white/15 hover:bg-white/25 rounded-lg text-xs font-bold">Tandai Spam</button>
            <button type="submit" name="action" value="delete" onclick="return confirm('Hapus komentar terpilih?')"
                    class="px-3 py-1.5 bg-red-500 hover:bg-red-600 rounded-lg text-xs font-bold">Hapus</button>
        </form>
    </div>

    {{-- Comments table --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-5 py-3 border-b border-slate-100 flex items-center gap-3">
            <input type="checkbox" @change="toggleAll($event)" :checked="selectedIds.length > 0 && selectedIds.length === document.querySelectorAll('[data-bulk-row]').length">
            <form method="GET" action="{{ route('admin.comments.index') }}" class="flex-1 flex items-center gap-2">
                @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, isi komentar..."
                       class="flex-1 max-w-md">
                <button type="submit" class="px-3 py-2 bg-slate-100 text-slate-700 rounded-lg text-xs font-semibold hover:bg-slate-200">Cari</button>
            </form>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse($comments as $c)
            @php
                $statusBadge = match($c->status) {
                    'pending' => 'bg-amber-100 text-amber-700',
                    'approved' => 'bg-green-100 text-green-700',
                    'spam' => 'bg-red-100 text-red-700',
                    'trash' => 'bg-slate-100 text-slate-600',
                    default => 'bg-slate-100',
                };
            @endphp
            <div class="p-5 hover:bg-slate-50/50 transition-colors flex gap-4 {{ !in_array($c->status, ['approved']) ? 'border-l-4 border-amber-300' : '' }}">
                <div class="pt-1">
                    <input type="checkbox" data-bulk-row data-id="{{ $c->id }}"
                           :checked="selectedIds.includes({{ $c->id }})"
                           @change="if ($event.target.checked) selectedIds.push({{ $c->id }}); else selectedIds = selectedIds.filter(i => i !== {{ $c->id }})">
                </div>
                <img src="{{ $c->gravatarUrl(40) }}" alt="" class="w-10 h-10 rounded-full bg-slate-100 shrink-0">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap mb-1">
                        <span class="text-sm font-bold text-slate-800">{{ $c->author_name }}</span>
                        <span class="text-xs text-slate-400">{{ $c->author_email }}</span>
                        <span class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $statusBadge }}">{{ $c->status }}</span>
                        @if($c->parent_id)
                            <span class="text-[11px] text-slate-400">↪ replying to <strong>{{ $c->parent?->author_name }}</strong></span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-700 leading-relaxed mb-2 break-words">{{ $c->content }}</p>
                    <div class="flex items-center gap-3 text-[11px] text-slate-400">
                        @if($c->blog)
                            <a href="{{ route('blog.show', $c->blog->slug) }}#comment-{{ $c->id }}" target="_blank" class="hover:text-primary truncate max-w-xs">
                                pada: {{ $c->blog->title }}
                            </a>
                        @endif
                        <span>· {{ $c->created_at->diffForHumans() }}</span>
                        <span class="font-mono">· {{ $c->ip_address }}</span>
                    </div>
                </div>
                <div class="flex flex-col gap-1 shrink-0">
                    @if($c->status !== 'approved')
                    <form method="POST" action="{{ route('admin.comments.approve', $c) }}">
                        @csrf
                        <button class="text-xs px-2.5 py-1 rounded bg-green-50 text-green-700 hover:bg-green-600 hover:text-white font-semibold transition-colors">Setujui</button>
                    </form>
                    @endif
                    @if($c->status !== 'spam')
                    <form method="POST" action="{{ route('admin.comments.spam', $c) }}">
                        @csrf
                        <button class="text-xs px-2.5 py-1 rounded bg-amber-50 text-amber-700 hover:bg-amber-600 hover:text-white font-semibold transition-colors">Spam</button>
                    </form>
                    @endif
                    <button type="button"
                            @click="deleteUrl = '{{ route('admin.comments.destroy', $c) }}'; deleteItemName = '{{ addslashes('komentar dari ' . $c->author_name) }}'; showDeleteModal = true"
                            class="text-xs px-2.5 py-1 rounded bg-red-50 text-red-700 hover:bg-red-600 hover:text-white font-semibold transition-colors">Hapus</button>
                </div>
            </div>
            @empty
            <div class="text-center py-16">
                <p class="text-sm text-slate-500">Belum ada komentar.</p>
            </div>
            @endforelse
        </div>

        @if($comments->hasPages())
            <div class="px-5 py-4 border-t border-slate-100">{{ $comments->links() }}</div>
        @endif
    </div>

    @include('components.admin.delete-modal')
</div>
@endsection
