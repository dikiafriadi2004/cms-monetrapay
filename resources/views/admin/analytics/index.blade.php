@extends('admin.layouts.app')

@section('title', 'Analytics Dashboard')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Analytics Dashboard</h1>
            <p class="text-sm text-slate-500 mt-1">Data diambil dari database lokal</p>
        </div>
    </div>

    @if ($error)
        {{-- Error Message --}}
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-red-700">{{ $error }}</p>
            </div>
        </div>
    @else
        {{-- Summary Cards --}}
        @if ($summary)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Total Subscriber Aktif --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Total Subscriber Aktif</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($summary['users']) }}</p>
                    </div>
                </div>
            </div>

            {{-- Total Konten Publik --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Total Konten Publik</p>
                        <p class="text-2xl font-bold text-slate-800">{{ number_format($summary['pageviews']) }}</p>
                    </div>
                </div>
            </div>

            {{-- Bounce Rate --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Bounce Rate</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $summary['bounce_rate'] }}%</p>
                    </div>
                </div>
            </div>

            {{-- Avg Session Duration --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 uppercase tracking-wide">Avg. Duration</p>
                        <p class="text-2xl font-bold text-slate-800">
                            {{ floor($summary['avg_session_duration'] / 60) }}m {{ $summary['avg_session_duration'] % 60 }}s
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- DB Stats Section --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Blog Stats --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Total Blog</p>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Published</span>
                    <span class="font-bold text-green-600">{{ number_format($summary['total_blogs_published']) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-1">
                    <span class="text-slate-500">Draft</span>
                    <span class="font-bold text-amber-600">{{ number_format($summary['total_blogs_draft']) }}</span>
                </div>
            </div>

            {{-- Comment Stats --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Total Komentar</p>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Approved</span>
                    <span class="font-bold text-green-600">{{ number_format($summary['total_comments_approved']) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-1">
                    <span class="text-slate-500">Pending</span>
                    <span class="font-bold text-amber-600">{{ number_format($summary['total_comments_pending']) }}</span>
                </div>
            </div>

            {{-- Contact Stats --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-rose-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Pesan Kontak</p>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Total</span>
                    <span class="font-bold text-slate-700">{{ number_format($summary['total_contacts_total']) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-1">
                    <span class="text-slate-500">Belum Dibaca</span>
                    <span class="font-bold text-rose-600">{{ number_format($summary['total_contacts_unread']) }}</span>
                </div>
            </div>

            {{-- Subscriber Stats --}}
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Total Subscriber</p>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Aktif</span>
                    <span class="font-bold text-green-600">{{ number_format($summary['total_subscribers_active']) }}</span>
                </div>
                <div class="flex items-center justify-between text-sm mt-1">
                    <span class="text-slate-500">Total</span>
                    <span class="font-bold text-slate-700">{{ number_format($summary['total_subscribers_total']) }}</span>
                </div>
            </div>
        </div>
        @endif

        {{-- Daily Trend Chart --}}
        @if ($dailyTrend)
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">Artikel Dipublikasikan (30 Hari Terakhir)</h2>
            <div class="relative" style="height: 300px;">
                <canvas id="dailyTrendChart"></canvas>
            </div>
        </div>
        @endif

        {{-- Top Pages --}}
        @if ($topPages)
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4">5 Artikel Terbaru</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-4 font-medium text-slate-600">#</th>
                            <th class="text-left py-3 px-4 font-medium text-slate-600">Judul Artikel</th>
                            <th class="text-right py-3 px-4 font-medium text-slate-600">Peringkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($topPages as $index => $page)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 px-4 text-slate-500">{{ $index + 1 }}</td>
                            <td class="py-3 px-4 text-slate-800">{{ $page['title'] }}</td>
                            <td class="py-3 px-4 text-right font-medium text-slate-700">{{ number_format($page['pageviews']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    @endif
</div>
@endsection

@section('scripts')
@if ($dailyTrend && !$error)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('dailyTrendChart');
    if (!ctx) return;

    const data = @json($dailyTrend);
    const labels = data.map(item => {
        const date = new Date(item.date);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
    });
    const visitors = data.map(item => item.visitors);

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Artikel Dipublikasikan',
                data: visitors,
                borderColor: '#3b82f6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointRadius: 2,
                pointHoverRadius: 5,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        maxTicksLimit: 10,
                        font: { size: 11 }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 11 },
                        stepSize: 1,
                        precision: 0
                    }
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            }
        }
    });
});
</script>
@endif
@endsection
