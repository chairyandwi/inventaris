@extends('layouts.app')

@section('title', 'Dashboard Kabag')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden pb-24 pt-28">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-800 via-indigo-800 to-sky-700 opacity-90"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="text-xs uppercase tracking-[0.3em] text-indigo-100/80">Dashboard Kabag</p>
            <h1 class="text-3xl sm:text-4xl font-bold mt-2">Pusat laporan dan ringkasan</h1>
            <p class="mt-3 text-indigo-100/90 max-w-2xl">Pantau kondisi inventaris dan tren transaksi sebelum membuka laporan detail.</p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('kabag.peminjaman.laporan') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                    Laporan Peminjaman
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
                <a href="{{ route('kabag.barang-habis-pakai.laporan') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 transition">
                    <span class="text-sm font-semibold">Laporan Barang Keluar</span>
                </a>
            </div>
        </div>
    </div>

    <div class="relative mt-6 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">
                @php
                    $statCards = [
                        ['title' => 'Total Barang', 'value' => $barang ?? 0, 'accent' => 'from-sky-500/40 to-indigo-500/40'],
                        ['title' => 'Barang Masuk', 'value' => $barangMasuk ?? 0, 'accent' => 'from-emerald-500/40 to-teal-500/40'],
                        ['title' => 'Total Ruang', 'value' => $ruang ?? 0, 'accent' => 'from-fuchsia-500/40 to-violet-500/40'],
                        ['title' => 'Total Kategori', 'value' => $kategori ?? 0, 'accent' => 'from-amber-500/40 to-orange-500/40'],
                    ];
                @endphp
                @foreach ($statCards as $card)
                    <div class="relative overflow-hidden rounded-2xl border border-indigo-400/20 bg-gradient-to-br from-slate-950/70 via-indigo-950/60 to-slate-900/70 backdrop-blur shadow-lg shadow-indigo-500/15">
                        <div class="absolute inset-0 bg-gradient-to-br {{ $card['accent'] }} opacity-60"></div>
                        <div class="relative p-5">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/80">{{ $card['title'] }}</p>
                            <p class="text-3xl font-bold mt-2">{{ $card['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            @php
                $totalPeminjaman = ($peminjamanPending ?? 0)
                    + ($peminjamanDipinjam ?? 0)
                    + ($peminjamanDikembalikan ?? 0)
                    + ($peminjamanDitolak ?? 0);
                $peminjamanSelesaiRate = $totalPeminjaman > 0
                    ? round((($peminjamanDikembalikan ?? 0) / $totalPeminjaman) * 100)
                    : 0;
                $peminjamanPendingRate = $totalPeminjaman > 0
                    ? round((($peminjamanPending ?? 0) / $totalPeminjaman) * 100)
                    : 0;
                $requestTotal = ($requestBarangPending ?? 0)
                    + ($requestBarangApproved ?? 0)
                    + ($requestBarangRejected ?? 0);
                $requestApprovedRate = $requestTotal > 0
                    ? round((($requestBarangApproved ?? 0) / $requestTotal) * 100)
                    : 0;
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
                <div class="lg:col-span-7 xl:col-span-8 space-y-6">
                    <div class="bg-slate-900/70 border border-white/10 rounded-2xl p-5 sm:p-6 shadow-lg shadow-indigo-500/10">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.2em] text-indigo-200/70">Ringkasan</p>
                                <h3 class="text-xl font-semibold">Status Peminjaman</h3>
                            </div>
                            <a href="{{ route('kabag.peminjaman.laporan') }}" class="text-xs px-3 py-1 rounded-full bg-white/10 text-indigo-50 hover:bg-white/20 transition">Lihat laporan</a>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                                <p class="text-xs text-amber-200/80">Pending</p>
                                <p class="text-2xl font-bold mt-1">{{ $peminjamanPending ?? 0 }}</p>
                            </div>
                            <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                                <p class="text-xs text-sky-200/80">Dipinjam</p>
                                <p class="text-2xl font-bold mt-1">{{ $peminjamanDipinjam ?? 0 }}</p>
                            </div>
                            <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                                <p class="text-xs text-emerald-200/80">Dikembalikan</p>
                                <p class="text-2xl font-bold mt-1">{{ $peminjamanDikembalikan ?? 0 }}</p>
                            </div>
                            <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                                <p class="text-xs text-rose-200/80">Ditolak</p>
                                <p class="text-2xl font-bold mt-1">{{ $peminjamanDitolak ?? 0 }}</p>
                            </div>
                        </div>
                        <div class="mt-6">
                            <canvas id="kabagLoanChart" class="w-full h-60"></canvas>
                            <div id="kabagLoanFallback" class="hidden mt-4 space-y-2 text-indigo-100/80">
                                <p class="text-sm font-semibold">Grafik belum tersedia, gunakan ringkasan di atas.</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900/70 border border-white/10 rounded-2xl p-5 sm:p-6 shadow-lg shadow-indigo-500/10">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-xs uppercase tracking-[0.2em] text-indigo-200/70">Insight</p>
                                <h3 class="text-xl font-semibold">Ringkasan Operasional</h3>
                            </div>
                            <span class="text-xs text-indigo-100/70">Update realtime</span>
                        </div>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="rounded-xl border border-white/10 bg-white/5 p-4 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs uppercase tracking-[0.2em] text-indigo-200/70">Peminjaman</span>
                                    <span class="text-xs text-indigo-100/70">Total: {{ $totalPeminjaman }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">Selesai</p>
                                    <div class="mt-2 h-2 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-full bg-emerald-400/80" style="width: {{ $peminjamanSelesaiRate }}%"></div>
                                    </div>
                                    <p class="mt-2 text-xs text-indigo-100/70">{{ $peminjamanSelesaiRate }}% peminjaman sudah dikembalikan.</p>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">Pending</p>
                                    <div class="mt-2 h-2 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-full bg-amber-400/80" style="width: {{ $peminjamanPendingRate }}%"></div>
                                    </div>
                                    <p class="mt-2 text-xs text-indigo-100/70">{{ $peminjamanPendingRate }}% menunggu keputusan.</p>
                                </div>
                        </div>
                    </div>
                </div>
                </div>

                <div class="lg:col-span-5 xl:col-span-4 space-y-6">
                    <div class="bg-slate-900/80 text-white rounded-2xl shadow-xl shadow-indigo-500/10 p-5 sm:p-6 border border-white/10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Ringkas Request</h3>
                            <span class="text-xs text-indigo-200 font-semibold">Barang Habis Pakai</span>
                        </div>
                        <div class="space-y-3 text-sm">
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/10">
                                <span class="text-white/80">Pending</span>
                                <span class="font-semibold text-amber-200">{{ $requestBarangPending ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/10">
                                <span class="text-white/80">Approved</span>
                                <span class="font-semibold text-emerald-200">{{ $requestBarangApproved ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 rounded-xl bg-white/5 border border-white/10">
                                <span class="text-white/80">Rejected</span>
                                <span class="font-semibold text-rose-200">{{ $requestBarangRejected ?? 0 }}</span>
                            </div>
                        </div>
                        <a href="{{ route('kabag.barang-habis-pakai.laporan') }}" class="mt-3 inline-flex items-center gap-2 text-xs font-semibold text-indigo-100 hover:text-white transition">
                            Lihat laporan barang keluar
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                    <div class="bg-slate-900/80 text-white rounded-2xl shadow-xl shadow-indigo-500/10 p-5 sm:p-6 border border-white/10">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold">Akses Laporan</h3>
                            <span class="text-xs text-indigo-200 font-semibold">Lengkap</span>
                        </div>
                        <div class="space-y-2 text-sm">
                            <a href="{{ route('kabag.barang.laporan') }}" class="flex items-center justify-between rounded-lg px-3 py-2 bg-indigo-500/15 hover:bg-indigo-500/25 transition">
                                <span>Laporan Barang</span>
                                <span class="text-indigo-200 font-semibold">PDF</span>
                            </a>
                            <a href="{{ route('kabag.barang_masuk.laporan') }}" class="flex items-center justify-between rounded-lg px-3 py-2 bg-white/5 hover:bg-white/10 transition">
                                <span>Laporan Barang Masuk</span>
                                <span class="text-slate-200 font-semibold">PDF</span>
                            </a>
                            <a href="{{ route('kabag.inventaris-ruang.laporan') }}" class="flex items-center justify-between rounded-lg px-3 py-2 bg-white/5 hover:bg-white/10 transition">
                                <span>Laporan Inventaris Ruang</span>
                                <span class="text-slate-200 font-semibold">PDF</span>
                            </a>
                            <a href="{{ route('kabag.inventaris-ruang.riwayat') }}" class="flex items-center justify-between rounded-lg px-3 py-2 bg-white/5 hover:bg-white/10 transition">
                                <span>Riwayat Perpindahan</span>
                                <span class="text-slate-200 font-semibold">View</span>
                            </a>
                            <a href="{{ route('kabag.peminjaman.laporan') }}" class="flex items-center justify-between rounded-lg px-3 py-2 bg-emerald-500/15 hover:bg-emerald-500/25 transition">
                                <span>Laporan Peminjaman</span>
                                <span class="text-emerald-200 font-semibold">PDF</span>
                            </a>
                            <a href="{{ route('kabag.barang-habis-pakai.laporan') }}" class="flex items-center justify-between rounded-lg px-3 py-2 bg-amber-500/15 hover:bg-amber-500/25 transition">
                                <span>Laporan Barang Keluar</span>
                                <span class="text-amber-200 font-semibold">PDF</span>
                            </a>
                            <a href="{{ route('kabag.barang-pinjam.laporan') }}" class="flex items-center justify-between rounded-lg px-3 py-2 bg-white/5 hover:bg-white/10 transition">
                                <span>Laporan Barang Pinjam</span>
                                <span class="text-slate-200 font-semibold">PDF</span>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('kabagLoanChart');
    const fallback = document.getElementById('kabagLoanFallback');

    if (!canvas || typeof Chart === 'undefined') {
        if (fallback) fallback.classList.remove('hidden');
        return;
    }

    try {
        const ctx = canvas.getContext('2d');
        const dataPoints = {
            pending: {{ $peminjamanPending ?? 0 }},
            dipinjam: {{ $peminjamanDipinjam ?? 0 }},
            kembali: {{ $peminjamanDikembalikan ?? 0 }},
            ditolak: {{ $peminjamanDitolak ?? 0 }},
        };

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Dipinjam', 'Dikembalikan', 'Ditolak'],
                datasets: [{
                    label: 'Status Peminjaman',
                    data: [dataPoints.pending, dataPoints.dipinjam, dataPoints.kembali, dataPoints.ditolak],
                    backgroundColor: [
                        'rgba(251, 191, 36, 0.7)',
                        'rgba(56, 189, 248, 0.7)',
                        'rgba(74, 222, 128, 0.7)',
                        'rgba(248, 113, 113, 0.7)',
                    ],
                    borderRadius: 12,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: '#cbd5e1', font: { weight: '600' } } },
                    y: { grid: { color: 'rgba(255,255,255,0.06)' }, ticks: { color: '#cbd5e1' } }
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
