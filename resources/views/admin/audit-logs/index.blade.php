@extends('admin.layouts.app')
@section('title', 'Audit Log')
@section('page-title', 'Audit Log')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div>
        <h1 class="text-xl font-bold text-slate-800">Audit Log</h1>
        <p class="text-sm text-slate-500 mt-0.5">Riwayat aktivitas sistem dan tindakan pengguna</p>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Cari</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Action, user, IP...">
            </div>
            <div>
                <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Kategori</label>
                <select name="action">
                    <option value="">Semua</option>
                    @foreach($actionGroups as $group)
                        <option value="{{ $group }}" {{ request('action') === $group ? 'selected' : '' }}>{{ ucfirst($group) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Dari</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Sampai</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
            </div>
            <div class="md:col-span-4 flex items-center gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-800 text-white text-sm font-semibold rounded-xl hover:bg-slate-700 transition-colors">
                    Filter
                </button>
                <a href="{{ route('admin.audit-logs.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-100 text-slate-600 text-sm font-semibold rounded-xl hover:bg-slate-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Log entries --}}
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Waktu</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Pengguna</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Action</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Detail</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($logs as $log)
                    @php
                        $group = explode('.', $log->action)[0];
                        $type = explode('.', $log->action)[1] ?? '';
                        $badgeColor = match(true) {
                            str_contains($log->action, 'deleted') => 'bg-red-50 text-red-700',
                            str_contains($log->action, 'created') => 'bg-green-50 text-green-700',
                            str_contains($log->action, 'updated') => 'bg-blue-50 text-blue-700',
                            str_contains($log->action, 'login.failed') => 'bg-amber-50 text-amber-700',
                            str_contains($log->action, 'login.locked') => 'bg-red-50 text-red-700',
                            str_contains($log->action, 'login.success') => 'bg-green-50 text-green-700',
                            str_contains($log->action, 'logout') => 'bg-slate-100 text-slate-700',
                            default => 'bg-slate-100 text-slate-700',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="px-5 py-3 whitespace-nowrap text-xs text-slate-500">
                            <div>{{ $log->created_at->translatedFormat('d M Y') }}</div>
                            <div class="text-slate-400">{{ $log->created_at->translatedFormat('H:i:s') }}</div>
                        </td>
                        <td class="px-5 py-3">
                            @if($log->user)
                                <div class="text-sm font-semibold text-slate-700">{{ $log->user->name }}</div>
                                <div class="text-xs text-slate-400">{{ $log->user->email }}</div>
                            @elseif(isset($log->payload['email']))
                                <div class="text-sm text-slate-600 italic">{{ $log->payload['email'] }}</div>
                                <div class="text-xs text-slate-400">guest</div>
                            @else
                                <span class="text-xs text-slate-400 italic">system</span>
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold {{ $badgeColor }}">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-xs text-slate-600 max-w-md">
                            @if($log->subject_type)
                                <div class="text-slate-500">{{ class_basename($log->subject_type) }} #{{ $log->subject_id }}</div>
                            @endif
                            @if($log->payload)
                                <details class="mt-1">
                                    <summary class="cursor-pointer text-primary text-xs hover:underline">Lihat payload</summary>
                                    <pre class="mt-1.5 p-2 bg-slate-50 rounded text-[11px] text-slate-700 overflow-x-auto max-w-md font-mono">{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</pre>
                                </details>
                            @endif
                        </td>
                        <td class="px-5 py-3 whitespace-nowrap font-mono text-xs text-slate-500">{{ $log->ip_address ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-14 text-center">
                            <p class="text-sm text-slate-500">Belum ada aktivitas tercatat.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-5 border-t border-slate-100">
            <x-admin.pagination :paginator="$logs" />
        </div>
    </div>
</div>
@endsection
