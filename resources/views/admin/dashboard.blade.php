@extends('admin.layouts.app')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-5">

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-[#0f172a] p-6 text-white"
         style="background-image: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
        <div class="absolute inset-0 opacity-30"
             style="background-image: linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px;"></div>
        <div class="absolute top-0 right-0 w-48 h-48 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4 pointer-events-none"></div>
        <div class="absolute bottom-0 right-32 w-32 h-32 bg-secondary/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative">
            <p class="text-xs font-bold text-white/60 uppercase tracking-widest mb-1">{{ now()->translatedFormat('l, d F Y') }}</p>
            <h2 class="text-xl md:text-2xl font-bold text-white">Selamat datang kembali, {{ auth()->user()->name }} 👋</h2>
            <p class="text-slate-400 mt-1.5 text-sm">
                {{ $postsThisMonth }} artikel dipublikasi bulan ini
                · {{ $contactsThisMonth }} kontak masuk bulan ini
                · {{ $stats['contacts_unread'] }} belum dibaca
            </p>
            <div class="mt-5 flex flex-wrap gap-2">
                @can('blog.view')
                <a href="{{ route('admin.blogs.create') }}" class="inline-flex items-center gap-2 rounded-xl bg-white text-slate-900 px-4 py-2 text-xs font-bold hover:bg-slate-100 transition-colors">Tulis Artikel</a>
                @endcan
                @can('setting.view')
                <a href="{{ route('admin.contacts.index') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-4 py-2 text-xs font-bold text-white/85 hover:bg-white/10 transition-colors">Cek Kontak</a>
                <a href="{{ route('admin.settings.edit', 'general') }}" class="inline-flex items-center gap-2 rounded-xl border border-white/20 px-4 py-2 text-xs font-bold text-white/85 hover:bg-white/10 transition-colors">Pengaturan</a>
                @endcan
            </div>
        </div>
    </div>

    {{-- Stats Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $statCards = [
                ['label' => 'Artikel Publish', 'value' => $stats['blogs_published'], 'sub' => $stats['blogs_draft'].' draft', 'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', 'color' => 'blue'],
                ['label' => 'Halaman', 'value' => $stats['pages_published'], 'sub' => $stats['pages_total'].' total', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'green'],
                ['label' => 'Kontak Masuk', 'value' => $stats['contacts_total'], 'sub' => $stats['contacts_unread'].' belum dibaca', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'color' => 'orange'],
                ['label' => 'Pengguna', 'value' => $stats['users'], 'sub' => $stats['categories'].' kategori', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'color' => 'purple'],
            ];
            $colors = [
                'blue'   => ['bg' => 'bg-blue-50',   'icon' => 'text-blue-600',   'border' => 'border-blue-500'],
                'green'  => ['bg' => 'bg-green-50',  'icon' => 'text-green-600',  'border' => 'border-green-500'],
                'purple' => ['bg' => 'bg-purple-50', 'icon' => 'text-purple-600', 'border' => 'border-purple-500'],
                'orange' => ['bg' => 'bg-orange-50', 'icon' => 'text-orange-600', 'border' => 'border-orange-500'],
            ];
        @endphp
        @foreach($statCards as $stat)
            @php $c = $colors[$stat['color']]; @endphp
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5 border-t-4 {{ $c['border'] }} hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $stat['label'] }}</p>
                        <p class="text-3xl font-extrabold text-slate-800 mt-2">{{ $stat['value'] }}</p>
                        <p class="text-xs text-slate-400 mt-1">{{ $stat['sub'] }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl {{ $c['bg'] }} flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $stat['icon'] }}"/>
                        </svg>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Operational Health --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Subscriber Aktif</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-2">{{ $stats['newsletter_active'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Newsletter siap dikirim</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Komentar Pending</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-2">{{ $stats['comments_pending'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Butuh moderasi</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Draft Lama</p>
            <p class="text-2xl font-extrabold text-slate-800 mt-2">{{ $stats['old_drafts'] }}</p>
            <p class="text-xs text-slate-400 mt-1">Lebih dari 30 hari</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Health Check</p>
            <p class="text-2xl font-extrabold text-green-600 mt-2">OK</p>
            <p class="text-xs text-slate-400 mt-1">{{ $stats['media_total'] }} file media</p>
        </div>
    </div>

    {{-- Middle Row: Analytics + Recent Activity --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Monthly blog trend chart (always visible, no GA needed) --}}
        @can('blog.view')
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Artikel Dipublikasi</h3>
                    <p class="text-xs text-slate-500 mt-0.5">6 bulan terakhir</p>
                </div>
                @if($analyticsSummary)
                <a href="{{ route('admin.analytics.index') }}" class="text-xs font-semibold text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                    Analytics <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endif
            </div>

            @if($analyticsSummary)
            <div class="px-5 pt-4 pb-2 grid grid-cols-4 gap-2">
                @foreach([
                    ['Pengunjung', number_format($analyticsSummary['users'] ?? 0)],
                    ['Pageviews', number_format($analyticsSummary['pageviews'] ?? 0)],
                    ['Bounce', ($analyticsSummary['bounce_rate'] ?? 0).'%'],
                    ['Sesi', gmdate('i:s', $analyticsSummary['avg_session_duration'] ?? 0)],
                ] as $m)
                <div class="bg-slate-50 rounded-lg p-2.5 text-center border border-slate-100">
                    <p class="text-sm font-extrabold text-slate-800">{{ $m[1] }}</p>
                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wide">{{ $m[0] }}</p>
                </div>
                @endforeach
            </div>
            @endif

            <div class="px-5 pb-5 pt-2">
                @php
                    $maxCount = max(array_column($monthlyTrend->toArray(), 'count') ?: [1]);
                    $chartHeight = 140; // px, fixed chart area height
                @endphp
                {{-- Chart container --}}
                <div class="relative rounded-xl bg-slate-50 border border-slate-100 p-4 pt-3" style="height: {{ $chartHeight + 48 }}px">
                    {{-- Horizontal grid lines + Y labels --}}
                    @php $steps = [0, 25, 50, 75, 100]; @endphp
                    @foreach($steps as $step)
                        @php $bottom = ($step / 100) * $chartHeight; @endphp
                        <div class="absolute left-0 right-0 flex items-center" style="bottom: {{ $bottom + 32 }}px; padding: 0 1rem">
                            <span class="text-[9px] text-slate-300 font-medium w-5 text-right shrink-0">
                                {{ $step > 0 ? round($maxCount * $step / 100) : 0 }}
                            </span>
                            <div class="flex-1 border-t border-slate-200/80 ml-2 {{ $step === 0 ? 'border-slate-300' : 'border-dashed' }}"></div>
                        </div>
                    @endforeach

                    {{-- Bars --}}
                    <div class="absolute inset-x-0 bottom-8 top-2 flex items-end gap-2 px-8">
                        @foreach($monthlyTrend as $i => $m)
                            @php
                                $pct = $maxCount > 0 ? ($m['count'] / $maxCount) * 100 : 0;
                                $barH = max(4, $pct / 100 * $chartHeight);
                                $isMax = $m['count'] === $maxCount && $m['count'] > 0;
                            @endphp
                            <div class="flex-1 flex flex-col items-center gap-0 group relative">
                                {{-- Value label (always visible for max, hover for others) --}}
                                <div class="absolute -top-5 left-1/2 -translate-x-1/2 text-[10px] font-bold transition-opacity
                                    {{ $isMax ? 'text-primary opacity-100' : 'text-slate-500 opacity-0 group-hover:opacity-100' }}">
                                    {{ $m['count'] }}
                                </div>
                                {{-- Bar --}}
                                <div class="w-full rounded-t-md transition-all duration-300 cursor-default
                                    {{ $isMax ? 'bg-primary shadow-sm shadow-primary/30' : 'bg-primary/30 group-hover:bg-primary/60' }}"
                                    style="height: {{ $barH }}px"
                                    title="{{ $m['label'] }}: {{ $m['count'] }} artikel">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- X axis labels --}}
                    <div class="absolute bottom-0 inset-x-0 flex gap-2 px-8 pb-1.5">
                        @foreach($monthlyTrend as $m)
                            <div class="flex-1 text-center">
                                <span class="text-[10px] font-semibold text-slate-400">{{ $m['short'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Legend --}}
                <div class="mt-3 flex items-center gap-4 justify-end">
                    <div class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded bg-primary"></span>
                        <span class="text-[10px] text-slate-500 font-medium">Bulan terbanyak</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="inline-block w-3 h-3 rounded bg-primary/30"></span>
                        <span class="text-[10px] text-slate-500 font-medium">Bulan lainnya</span>
                    </div>
                    <div class="ml-auto text-[10px] font-semibold text-slate-400">
                        Total: {{ $monthlyTrend->sum('count') }} artikel
                    </div>
                </div>
            </div>
        </div>
        @endcan

        {{-- Recent contacts (sidebar) --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    Kontak Terbaru
                    @if($stats['contacts_unread'] > 0)
                        <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-secondary rounded-full">{{ $stats['contacts_unread'] }}</span>
                    @endif
                </h3>
                @can('setting.view')
                <a href="{{ route('admin.contacts.index') }}" class="text-xs font-semibold text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                    Semua <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
                @endcan
            </div>
            <div class="p-2">
                @forelse($recentContacts as $sub)
                <a href="{{ route('admin.contacts.show', $sub) }}"
                   class="flex items-start gap-3 p-3 rounded-lg hover:bg-slate-50 transition-colors {{ !$sub->is_read ? 'bg-primary/5' : '' }}">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-secondary to-secondary-dark flex items-center justify-center text-xs font-bold text-white shrink-0">
                        {{ strtoupper(substr($sub->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <p class="text-xs font-semibold text-slate-800 truncate">{{ $sub->name }}</p>
                            @if(!$sub->is_read)<span class="inline-block w-1.5 h-1.5 bg-primary rounded-full"></span>@endif
                        </div>
                        <p class="text-[11px] text-slate-500 truncate font-mono">{{ $sub->phone }}</p>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $sub->created_at->diffForHumans() }}</p>
                    </div>
                </a>
                @empty
                <div class="text-center py-8 px-3">
                    <p class="text-sm text-slate-400">Belum ada pendaftaran</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Bottom Row: Recent Posts + Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Recent Posts --}}
        @can('blog.view')
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-800">Artikel Terbaru</h3>
                <a href="{{ route('admin.blogs.index') }}" class="text-xs font-semibold text-primary hover:text-primary-dark transition-colors flex items-center gap-1">
                    Semua <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            @if($recentBlogs->isNotEmpty())
            <div class="divide-y divide-slate-50">
                @foreach($recentBlogs as $blog)
                <a href="{{ route('admin.blogs.edit', $blog) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-slate-50 transition-colors">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-700 truncate">{{ $blog->title }}</p>
                        <p class="text-[11px] text-slate-400 mt-0.5">
                            {{ $blog->author?->name ?? '—' }} · {{ $blog->created_at->diffForHumans() }}
                        </p>
                    </div>
                    <span class="text-[10px] px-2 py-0.5 rounded-full font-bold shrink-0 {{ $blog->status === 'published' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                        {{ $blog->status === 'published' ? 'Publish' : 'Draft' }}
                    </span>
                </a>
                @endforeach
            </div>
            @else
            <p class="text-sm text-slate-400 text-center py-8">Belum ada artikel.</p>
            @endif
        </div>
        @endcan

        {{-- Quick Actions --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 class="text-sm font-bold text-slate-800 mb-4">Aksi Cepat</h3>
            <div class="space-y-2">
                @can('blog.view')
                <a href="{{ route('admin.blogs.create') }}"
                   class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:border-primary hover:bg-primary-light/30 transition-all group">
                    <div class="w-9 h-9 bg-blue-50 rounded-lg flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-4 h-4 text-blue-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-700 group-hover:text-primary">Tulis Artikel</span>
                </a>
                @endcan
                @can('page.view')
                <a href="{{ route('admin.pages.create') }}"
                   class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:border-primary hover:bg-primary-light/30 transition-all group">
                    <div class="w-9 h-9 bg-green-50 rounded-lg flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-4 h-4 text-green-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-700 group-hover:text-primary">Buat Halaman</span>
                </a>
                @endcan
                @can('file.view')
                <a href="{{ route('admin.files.index') }}"
                   class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:border-primary hover:bg-primary-light/30 transition-all group">
                    <div class="w-9 h-9 bg-purple-50 rounded-lg flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-4 h-4 text-purple-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-700 group-hover:text-primary">Upload File</span>
                </a>
                @endcan
                @can('setting.view')
                <a href="{{ route('admin.settings.edit', 'general') }}"
                   class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:border-primary hover:bg-primary-light/30 transition-all group">
                    <div class="w-9 h-9 bg-orange-50 rounded-lg flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
                        <svg class="w-4 h-4 text-orange-600 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <span class="text-xs font-semibold text-slate-700 group-hover:text-primary">Settings</span>
                </a>
                @endcan
            </div>
        </div>
    </div>

</div>
@endsection
