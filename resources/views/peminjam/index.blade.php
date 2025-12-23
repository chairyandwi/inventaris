@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden pb-24 pt-28">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500 opacity-90"></div>
        <div class="absolute -left-10 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-300/20 blur-3xl rounded-full"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
                <div class="flex-1">
                    <p class="text-sm uppercase tracking-[0.25em] text-indigo-100/80">Dashboard Peminjam</p>
                    <h2 class="text-4xl sm:text-5xl font-bold leading-tight mt-2">Halo, {{ auth()->user()->nama }}</h2>
                    <p class="mt-4 text-indigo-100/90 max-w-3xl">Lihat status permintaan, ajukan peminjaman baru, dan ketahui progres barang yang Anda pinjam secara ringkas.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('peminjam.peminjaman.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                            Ajukan Peminjaman
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('peminjam.barang-habis-pakai.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                            Request Barang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('peminjam.peminjaman.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 transition">
                            <span class="text-sm font-semibold">Lihat Riwayat</span>
                        </a>
                    </div>
                </div>
                <div class="w-full lg:w-auto">
                    <div class="bg-white/10 backdrop-blur rounded-2xl border border-white/20 p-4 sm:p-6 shadow-xl shadow-indigo-500/20">
                        <p class="text-xs uppercase tracking-[0.2em] text-indigo-100/70">Ringkasan</p>
                        <h3 class="text-2xl font-semibold mt-2">Snapshot Peminjaman</h3>
                        <div class="mt-4 space-y-3 text-sm text-indigo-50">
                            <div class="flex items-center justify-between">
                                <span>Total permintaan</span>
                                <span class="font-semibold">{{ $totalPeminjaman ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Pending</span>
                                <span class="font-semibold">{{ $pendingPeminjaman ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Sedang dipinjam</span>
                                <span class="font-semibold">{{ $dipinjamPeminjaman ?? 0 }}</span>
                            </div>
                        <div class="flex items-center justify-between">
                            <span>Ditolak</span>
                            <span class="font-semibold">{{ $ditolakPeminjaman ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Request habis pakai</span>
                            <span class="font-semibold">{{ $requestHabisPakaiTotal ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span>Unit habis pakai</span>
                            <span class="font-semibold">{{ $requestHabisPakaiUnit ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    <div class="relative -mt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @php
                    $cards = [
                        ['title' => 'Total Peminjaman', 'value' => $totalPeminjaman ?? 0, 'color' => 'from-indigo-500 to-blue-500', 'route' => route('peminjam.peminjaman.index')],
                        ['title' => 'Pending', 'value' => $pendingPeminjaman ?? 0, 'color' => 'from-amber-500 to-orange-500', 'route' => route('peminjam.peminjaman.index')],
                        ['title' => 'Sedang Dipinjam', 'value' => $dipinjamPeminjaman ?? 0, 'color' => 'from-emerald-500 to-teal-500', 'route' => route('peminjam.peminjaman.index')],
                        ['title' => 'Ditolak', 'value' => $ditolakPeminjaman ?? 0, 'color' => 'from-rose-500 to-pink-500', 'route' => route('peminjam.peminjaman.index')],
                        ['title' => 'Request Habis Pakai', 'value' => $requestHabisPakaiTotal ?? 0, 'color' => 'from-sky-500 to-cyan-500', 'route' => route('peminjam.barang-habis-pakai.index')],
                        ['title' => 'Request Bulan Ini', 'value' => $requestHabisPakaiBulan ?? 0, 'color' => 'from-fuchsia-500 to-pink-500', 'route' => route('peminjam.barang-habis-pakai.index')],
                    ];
                @endphp
                @foreach ($cards as $card)
                    <a href="{{ $card['route'] }}" class="group relative overflow-hidden rounded-2xl border border-white/10 bg-slate-900/70 backdrop-blur p-5 min-h-[120px] shadow-lg shadow-indigo-500/10 block transition hover:-translate-y-0.5">
                        <div class="absolute inset-0 bg-gradient-to-br {{ $card['color'] }} opacity-20 group-hover:opacity-30 transition"></div>
                        <div class="relative flex items-start justify-between">
                            <p class="text-[11px] uppercase tracking-[0.3em] text-white/70">{{ $card['title'] }}</p>
                            <span class="w-9 h-9 rounded-xl bg-white/10 border border-white/10 text-white/80 group-hover:text-white flex items-center justify-center transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                        <div class="relative mt-4">
                            <p class="text-3xl font-bold">{{ $card['value'] }}</p>
                            <p class="text-xs text-white/60 mt-1">Lihat detail</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-2 bg-slate-900/70 border border-white/10 rounded-2xl p-6 shadow-lg shadow-indigo-500/10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-indigo-200/70">Ketersediaan</p>
                            <h3 class="text-xl font-semibold">Barang Tersedia</h3>
                        </div>
                        <a href="{{ route('peminjam.peminjaman.create') }}" class="text-xs px-3 py-1 rounded-full bg-white/10 text-indigo-50 hover:bg-white/20 transition">Mulai ajukan</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                            <p class="text-xs text-indigo-100/70">Barang tersedia</p>
                            <p class="text-2xl font-bold mt-1">{{ $barangTersedia ?? 0 }}</p>
                            <p class="text-xs text-indigo-100/70 mt-1">Bisa dipinjam</p>
                        </div>
                        <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                            <p class="text-xs text-indigo-100/70">Selesai</p>
                            <p class="text-2xl font-bold mt-1">{{ $selesaiPeminjaman ?? 0 }}</p>
                            <p class="text-xs text-indigo-100/70 mt-1">Sudah dikembalikan</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <canvas id="statusChart" class="w-full h-60"></canvas>
                        <div id="statusChartFallback" class="hidden mt-4 space-y-2 text-indigo-100/80">
                            <p class="text-sm font-semibold">Grafik belum tersedia. Gunakan ringkasan di atas.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-900/80 text-white rounded-2xl shadow-xl shadow-indigo-500/10 p-6 border border-white/10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Aksi Cepat</h3>
                        <span class="text-xs text-indigo-200 font-semibold">Prioritas</span>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route('peminjam.peminjaman.create') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition">
                            <div>
                                <p class="text-sm font-semibold text-indigo-100">Ajukan Peminjaman</p>
                                <p class="text-xs text-indigo-200/70">Proses langkah demi langkah</p>
                            </div>
                            <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('peminjam.profile.edit') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition">
                            <div>
                                <p class="text-sm font-semibold text-indigo-100">Lengkapi Profil</p>
                                <p class="text-xs text-indigo-200/70">Isi data & upload identitas</p>
                            </div>
                            <svg class="w-4 h-4 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('peminjam.peminjaman.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition">
                            <div>
                                <p class="text-sm font-semibold text-emerald-100">Pantau Status</p>
                                <p class="text-xs text-emerald-200/70">Pending, disetujui, selesai</p>
                            </div>
                            <svg class="w-4 h-4 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('peminjam.peminjaman.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition">
                            <div>
                                <p class="text-sm font-semibold text-amber-100">Riwayat Permintaan</p>
                                <p class="text-xs text-amber-200/70">Cetak/lihat detail</p>
                            </div>
                            <svg class="w-4 h-4 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('peminjam.barang-habis-pakai.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 transition">
                            <div>
                                <p class="text-sm font-semibold text-slate-100">Request Habis Pakai</p>
                                <p class="text-xs text-slate-300">Tissue, spidol, dll.</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @if(isset($profilLengkap) && !$profilLengkap)
                <div class="mt-6 rounded-2xl bg-amber-500/10 border border-amber-300/30 px-5 py-4 text-amber-100">
                    <p class="font-semibold">Profil belum lengkap.</p>
                    <p class="text-sm text-amber-100/80 mt-1">
                        Lengkapi profil Anda agar bisa mengajukan peminjaman dan request barang.
                        @if(!empty($missingFields))
                            Bagian yang perlu diisi: {{ implode(', ', $missingFields) }}.
                        @endif
                    </p>
                    <a href="{{ route('peminjam.profile.edit') }}" class="inline-flex mt-3 items-center gap-2 px-4 py-2 rounded-xl bg-white/10 border border-white/20 text-amber-50 hover:bg-white/20 transition text-sm font-semibold">
                        Lengkapi Profil
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('statusChart');
    const fallback = document.getElementById('statusChartFallback');

    if (typeof Chart === 'undefined' || !canvas) {
        if (fallback) fallback.classList.remove('hidden');
        return;
    }

    try {
        const ctx = canvas.getContext('2d');
        const total = {{ $totalPeminjaman ?? 0 }};
        const pending = {{ $pendingPeminjaman ?? 0 }};
        const dipinjam = {{ $dipinjamPeminjaman ?? 0 }};
        const selesai = {{ $selesaiPeminjaman ?? 0 }};
        const ditolak = {{ $ditolakPeminjaman ?? 0 }};

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total', 'Pending', 'Dipinjam', 'Selesai', 'Ditolak'],
                datasets: [{
                    label: 'Peminjaman',
                    data: [total, pending, dipinjam, selesai, ditolak],
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(148, 163, 184, 0.7)',
                        'rgba(239, 68, 68, 0.7)',
                    ],
                    borderRadius: 12,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        borderColor: '#1e293b',
                        borderWidth: 1,
                        padding: 10,
                        titleColor: '#e2e8f0',
                        bodyColor: '#e2e8f0',
                    }
                },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#cbd5e1', font: { weight: '600' } } },
                    y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { color: '#cbd5e1' } }
                }
            }
        });
    } catch (error) {
        console.error('Gagal membuat chart status:', error);
        canvas.style.display = 'none';
        fallback.classList.remove('hidden');
    }
});
</script>
@endsection
