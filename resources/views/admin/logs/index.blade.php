@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
            @php
                $totalLogs = $logs->total();
                $pageCount = $logs->count();
            @endphp
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Log Aktivitas</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Jejak perubahan sistem</h1>
                    <p class="mt-3 text-indigo-50/90 max-w-2xl">Lihat siapa melakukan apa dan kapan, dengan tampilan futuristik dan tetap mudah dibaca.</p>
                </div>
                <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke Dashboard
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Log</p>
                        <p class="text-3xl font-bold mt-2">{{ $totalLogs }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Akumulasi catatan</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400/30 to-indigo-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Log di halaman</p>
                        <p class="text-3xl font-bold mt-2">{{ $pageCount }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Ditampilkan saat ini</p>
                    </div>
                </div>
                @if($logs->first())
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400/30 to-orange-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Terakhir</p>
                        <p class="text-lg font-semibold mt-2 text-white">{{ $logs->first()->aksi }}</p>
                        <p class="text-sm text-indigo-100/80">{{ $logs->first()->created_at?->format('d M Y H:i') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Waktu</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">User</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Aksi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($logs as $log)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 text-sm text-indigo-50">{{ $log->created_at?->format('d M Y H:i') }}</td>
                                    <td class="px-6 py-4 text-sm text-white">{{ optional($log->user)->nama ?? optional($log->user)->username ?? optional($log->user)->email ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-white">{{ $log->aksi }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $log->deskripsi ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-indigo-100/80">Belum ada log aktivitas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
