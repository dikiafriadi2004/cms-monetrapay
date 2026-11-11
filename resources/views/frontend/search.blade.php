@extends('frontend.layouts.app')

@section('title', $query ? 'Pencarian: ' . $query : 'Pencarian')

@section('seo')
<meta name="robots" content="noindex,follow">
<title>{{ $query ? 'Pencarian: ' . $query : 'Pencarian' }} - {{ $siteName ?? config('app.name') }}</title>
<link rel="canonical" href="{{ url('/search') }}">
@endsection

@section('content')
{{-- Hero search --}}
<section class="bg-slate-50 pt-0 pb-12 md:pb-16 border-b border-slate-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 mb-2">Pencarian</h1>
        <p class="text-slate-500 text-sm mb-6">Cari artikel, halaman, atau topik yang Anda butuhkan</p>

        <form action="{{ route('search') }}" method="GET" class="relative">
            <div class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <input type="search" name="q" value="{{ $query }}" placeholder="Ketik kata kunci minimal 3 karakter..."
                   class="w-full pl-14 pr-32 py-4 rounded-2xl border-2 border-slate-200 bg-white text-slate-800 placeholder-slate-400 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/15 transition-all shadow-sm text-base"
                   minlength="3" autofocus>
            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-dark transition-colors shadow-sm">
                Cari
            </button>
        </form>

        @if(strlen($query) >= 3)
            {{-- Filter tabs --}}
            <div class="flex items-center gap-2 mt-5">
                <a href="{{ route('search', ['q' => $query, 'type' => 'all']) }}"
                   class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold transition-colors
                          {{ ($type ?? 'all') === 'all' ? 'bg-slate-800 text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    Semua
                </a>
                <a href="{{ route('search', ['q' => $query, 'type' => 'blog']) }}"
                   class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold transition-colors
                          {{ ($type ?? 'all') === 'blog' ? 'bg-primary text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    Artikel
                </a>
                <a href="{{ route('search', ['q' => $query, 'type' => 'page']) }}"
                   class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold transition-colors
                          {{ ($type ?? 'all') === 'page' ? 'bg-secondary text-white' : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200' }}">
                    Halaman
                </a>
            </div>
        @endif
    </div>
</section>

{{-- Results --}}
<section class="py-10 md:py-14">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

        @if(strlen($query) === 0)
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-5 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h2 class="text-lg font-bold text-slate-700 mb-2">Mulai mencari</h2>
                <p class="text-sm text-slate-500">Masukkan kata kunci di atas untuk mencari konten</p>
            </div>
        @elseif(strlen($query) < 3)
            <div class="p-5 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
                <p class="text-sm text-amber-800 font-medium">Masukkan minimal 3 karakter untuk melakukan pencarian.</p>
            </div>
        @elseif($total === 0)
            <div class="text-center py-16">
                <div class="w-20 h-20 mx-auto mb-5 bg-slate-100 rounded-2xl flex items-center justify-center">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/></svg>
                </div>
                <h2 class="text-lg font-bold text-slate-700 mb-2">Tidak ada hasil</h2>
                <p class="text-sm text-slate-500">Tidak ditemukan apa pun untuk "<strong>{{ $query }}</strong>". Coba gunakan kata kunci yang berbeda.</p>
            </div>
        @else
            <p class="text-sm text-slate-500 mb-5">Menampilkan <strong class="text-slate-700">{{ $total }}</strong> hasil untuk "<strong class="text-slate-700">{{ $query }}</strong>"</p>

            <div class="space-y-4">
                @foreach($results as $r)
                    <a href="{{ $r->url }}"
                       class="group flex gap-4 p-5 bg-white border border-slate-100 rounded-2xl hover:border-primary/40 hover:shadow-md transition-all">
                        @if($r->image)
                            <div class="hidden sm:block w-32 h-24 shrink-0 rounded-xl overflow-hidden bg-slate-100">
                                <img src="{{ Storage::url($r->image) }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @else
                            <div class="hidden sm:flex w-32 h-24 shrink-0 rounded-xl bg-primary/10 items-center justify-center">
                                <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                @if($r->type === 'blog')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-primary/10 text-primary">Artikel</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-secondary/10 text-secondary-dark">Halaman</span>
                                @endif
                                @foreach(array_slice($r->categories, 0, 2) as $cat)
                                    <span class="text-[11px] text-slate-500 font-medium">· {{ $cat }}</span>
                                @endforeach
                            </div>
                            <h3 class="text-base font-bold text-slate-800 group-hover:text-primary transition-colors mb-1.5 line-clamp-1">
                                {!! preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark class="bg-yellow-100 text-slate-800 px-0.5 rounded">$1</mark>', e($r->title)) !!}
                            </h3>
                            @if($r->snippet)
                                <p class="text-sm text-slate-600 line-clamp-2 leading-relaxed">
                                    {!! preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark class="bg-yellow-100 text-slate-800 px-0.5 rounded">$1</mark>', e($r->snippet)) !!}
                                </p>
                            @endif
                            <div class="flex items-center gap-3 mt-2 text-[11px] text-slate-400">
                                @if($r->author)<span>{{ $r->author }}</span>@endif
                                @if($r->author && $r->date)<span>·</span>@endif
                                @if($r->date)<time>{{ $r->date->translatedFormat('d M Y') }}</time>@endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            @if(isset($paginator) && $paginator->hasPages())
            <div class="mt-6">
                {{ $paginator->links('vendor.pagination.frontend') }}
            </div>
            @endif
        @endif
    </div>
</section>
@endsection
