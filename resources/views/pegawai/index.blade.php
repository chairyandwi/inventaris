@extends('layouts.app')

@section('content')
@php
    $routePrefix = $routePrefix ?? ((auth()->check() && auth()->user()->role === 'admin') ? 'admin' : 'pegawai');
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden pb-24 pt-28">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 via-blue-600 to-sky-500 opacity-90"></div>
        <div class="absolute -left-10 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-300/20 blur-3xl rounded-full"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
                <div class="flex-1">
                    <p class="text-sm uppercase tracking-[0.25em] text-indigo-100/80">Dashboard Pegawai</p>
                    <h2 class="text-4xl sm:text-5xl font-bold leading-tight mt-2">Hai, {{ auth()->user()->nama }} 👋</h2>
                    <p class="mt-4 text-indigo-100/90 max-w-2xl">Kelola aset, ruang, dan peminjaman dengan visual yang lebih jelas, insight cepat, dan aksi singkat di satu layar.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route($routePrefix . '.barang.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 transition">
                            <span class="text-sm font-semibold">Tambah Barang</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                        <a href="{{ route($routePrefix . '.peminjaman.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                            Lihat Peminjaman
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="w-full lg:w-auto">
                    <div class="bg-white/10 backdrop-blur rounded-2xl border border-white/20 p-4 sm:p-6 shadow-xl shadow-indigo-500/20">
                        @php
                            $totalAset = max(($barang ?? 0) + ($kategori ?? 0) + ($ruang ?? 0), 1);
                            $rasioPinjam = ($barang ?? 0) > 0 ? min(100, round((($peminjamanDipinjam ?? 0) / $barang) * 100)) : 0;
                            $rasioMasuk = ($barang ?? 0) > 0 ? min(100, round((($barangMasuk ?? 0) / $barang) * 100)) : 0;
                        @endphp
                        <p class="text-xs uppercase tracking-[0.2em] text-indigo-100/70">Ringkasan</p>
                        <h3 class="text-2xl font-semibold mt-2">Snapshot Aset</h3>
                        <div class="mt-4 space-y-3 text-sm text-indigo-50">
                            <div class="flex items-center justify-between">
                                <span>Item aktif</span>
                                <span class="font-semibold">{{ $barang ?? 0 }} barang</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Dipinjam</span>
                                <span class="font-semibold">{{ $peminjamanDipinjam ?? 0 }} ({{ $rasioPinjam }}%)</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                                <div class="h-full bg-amber-300 rounded-full" style="width: {{ $rasioPinjam }}%"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Masuk (12 bln)</span>
                                <span class="font-semibold">{{ $barangMasuk ?? 0 }} ({{ $rasioMasuk }}%)</span>
                            </div>
                            <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                                <div class="h-full bg-emerald-300 rounded-full" style="width: {{ $rasioMasuk }}%"></div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Total entitas</span>
                                <span class="font-semibold">{{ $totalAset }} data</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative -mt-20 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Highlight cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
                @php
                    $cards = [
                        ['title' => 'Kategori', 'value' => $kategori ?? 0, 'color' => 'from-lime-500 to-emerald-500', 'route' => route($routePrefix . '.kategori.create')],
                        ['title' => 'Ruang', 'value' => $ruang ?? 0, 'color' => 'from-violet-500 to-fuchsia-500', 'route' => route($routePrefix . '.ruang.create')],
                        ['title' => 'Barang', 'value' => $barang ?? 0, 'color' => 'from-sky-500 to-indigo-500', 'route' => route($routePrefix . '.barang.create')],
                        ['title' => 'Barang Masuk', 'value' => $barangMasuk ?? 0, 'color' => 'from-emerald-500 to-teal-500', 'route' => route($routePrefix . '.barang_masuk.create')],
                        ['title' => 'User', 'value' => $user ?? 0, 'color' => 'from-amber-500 to-orange-500', 'route' => route($routePrefix . '.user.create')],
                    ];
                @endphp
                @foreach ($cards as $card)
                    <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 backdrop-blur shadow-lg shadow-indigo-500/10">
                        <div class="absolute inset-0 bg-gradient-to-br {{ $card['color'] }} opacity-60 group-hover:opacity-80 transition"></div>
                        <div class="relative p-5 flex items-center justify-between">
                            <div>
                                <p class="text-xs uppercase tracking-[0.25em] text-white/80">{{ $card['title'] }}</p>
                                <p class="text-3xl font-bold mt-2">{{ $card['value'] }}</p>
                            </div>
                            <a href="{{ $card['route'] }}" class="p-3 rounded-xl bg-white/20 text-white hover:bg-white/30 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Activity & Quick actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-2 bg-slate-900/70 border border-white/10 rounded-2xl p-6 shadow-lg shadow-indigo-500/10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-indigo-200/70">Pergerakan</p>
                            <h3 class="text-xl font-semibold">Logistik & Peminjaman</h3>
                        </div>
                        <span class="text-xs px-3 py-1 rounded-full bg-white/10 text-indigo-50">Realtime</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                            <p class="text-xs text-indigo-100/70">Pending Approval</p>
                            <p class="text-2xl font-bold mt-1">{{ $peminjamanPending ?? 0 }}</p>
                            <p class="text-xs text-indigo-100/70 mt-1">Antrian persetujuan</p>
                        </div>
                        <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                            <p class="text-xs text-indigo-100/70">Barang Masuk</p>
                            <p class="text-2xl font-bold mt-1">{{ $barangMasuk ?? 0 }}</p>
                            <p class="text-xs text-indigo-100/70 mt-1">Pastikan stok tercatat</p>
                        </div>
                        <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                            <p class="text-xs text-indigo-100/70">Sedang Dipinjam</p>
                            <p class="text-2xl font-bold mt-1">{{ $peminjamanDipinjam ?? 0 }}</p>
                            <p class="text-xs text-indigo-100/70 mt-1">Pantau jadwal kembali</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <canvas id="metricChart" class="w-full h-60"></canvas>
                        <div id="metricFallback" class="hidden mt-4 space-y-2 text-indigo-100/80">
                            <p class="text-sm font-semibold">Data grafik belum tersedia, gunakan ringkasan di atas.</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white text-slate-900 rounded-2xl shadow-xl shadow-indigo-500/10 p-6 border border-indigo-50">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Aksi Cepat</h3>
                        <span class="text-xs text-indigo-600 font-semibold">Prioritas</span>
                    </div>
                    <div class="space-y-3">
                        <a href="{{ route($routePrefix . '.barang_masuk.create') }}" class="flex items-center justify-between p-3 rounded-xl bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-indigo-900">Catat Barang Masuk</p>
                                <p class="text-xs text-indigo-600">Percepat registrasi stok baru</p>
                            </div>
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route($routePrefix . '.peminjaman.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-emerald-900">Kelola Peminjaman</p>
                                <p class="text-xs text-emerald-600">Setujui / tolak cepat</p>
                            </div>
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route($routePrefix . '.barang.laporan') }}" class="flex items-center justify-between p-3 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-amber-900">Unduh Laporan</p>
                                <p class="text-xs text-amber-600">PDF barang & peminjaman</p>
                            </div>
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('metricChart');
    const fallback = document.getElementById('metricFallback');

    if (!canvas || typeof Chart === 'undefined') {
        if (fallback) fallback.classList.remove('hidden');
        return;
    }

    try {
        const ctx = canvas.getContext('2d');
        const dataPoints = {
            pending: {{ $peminjamanPending ?? 0 }},
            masuk: {{ $barangMasuk ?? 0 }},
            dipinjam: {{ $peminjamanDipinjam ?? 0 }},
        };

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Masuk', 'Dipinjam'],
                datasets: [{
                    label: 'Pergerakan Barang',
                    data: [dataPoints.pending, dataPoints.masuk, dataPoints.dipinjam],
                    backgroundColor: [
                        'rgba(244, 114, 182, 0.6)',
                        'rgba(74, 222, 128, 0.6)',
                        'rgba(56, 189, 248, 0.6)'
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
                    x: {
                        grid: { display: false },
                        ticks: { color: '#cbd5e1', font: { weight: '600' } }
                    },
                    y: {
                        grid: { color: 'rgba(255,255,255,0.06)' },
                        ticks: { color: '#cbd5e1' }
                    }
                }
            }
        });
    } catch (err) {
        console.error('Gagal memuat chart', err);
        canvas.style.display = 'none';
        if (fallback) fallback.classList.remove('hidden');
    }
});
</script>
@endsection
