@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden pb-24 pt-28">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-90"></div>
        <div class="absolute -left-10 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-start lg:items-center gap-6">
                <div class="flex-1">
                    <p class="text-sm uppercase tracking-[0.25em] text-indigo-100/80">Dashboard Admin</p>
                    <h2 class="text-4xl sm:text-5xl font-bold leading-tight mt-2">Halo, {{ auth()->user()->nama }} 👋</h2>
                    <p class="mt-4 text-indigo-100/90 max-w-3xl">Pantau aset, peminjaman, dan log aktivitas dengan tampilan ringkas dan futuristik. Semua fungsi penting dalam satu layar.</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('admin.barang.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                            Kelola Barang
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.peminjaman.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/15 hover:bg-white/25 border border-white/20 transition">
                            <span class="text-sm font-semibold">Status Peminjaman</span>
                        </a>
                    </div>
                </div>
                <div class="w-full lg:w-auto">
                    <div class="bg-white/10 backdrop-blur rounded-2xl border border-white/20 p-4 sm:p-6 shadow-xl shadow-indigo-500/20">
                        <p class="text-xs uppercase tracking-[0.2em] text-indigo-100/70">Ringkasan</p>
                        <h3 class="text-2xl font-semibold mt-2">Snapshot Sistem</h3>
                        <div class="mt-4 space-y-3 text-sm text-indigo-50">
                            <div class="flex items-center justify-between">
                                <span>Total Barang</span>
                                <span class="font-semibold">{{ $barang ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Dipinjam</span>
                                <span class="font-semibold">{{ $barangPinjam ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Barang Masuk</span>
                                <span class="font-semibold">{{ $barangMasuk ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Pending Approval</span>
                                <span class="font-semibold">{{ $peminjamanPending ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>User Terdaftar</span>
                                <span class="font-semibold">{{ $user ?? 0 }}</span>
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
                        ['title' => 'Kategori', 'value' => $kategori ?? 0, 'color' => 'from-lime-500 to-emerald-500', 'route' => route('admin.kategori.index')],
                        ['title' => 'Ruang', 'value' => $ruang ?? 0, 'color' => 'from-violet-500 to-fuchsia-500', 'route' => route('admin.ruang.index')],
                        ['title' => 'Barang', 'value' => $barang ?? 0, 'color' => 'from-sky-500 to-indigo-500', 'route' => route('admin.barang.index')],
                        ['title' => 'User', 'value' => $user ?? 0, 'color' => 'from-amber-500 to-orange-500', 'route' => route('admin.user.index')],
                        ['title' => 'Barang Masuk', 'value' => $barangMasuk ?? 0, 'color' => 'from-emerald-500 to-teal-500', 'route' => route('admin.barang_masuk.create')],
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

            <!-- Peminjaman status + quick insights -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-2 bg-slate-900/70 border border-white/10 rounded-2xl p-6 shadow-lg shadow-indigo-500/10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-indigo-200/70">Peminjaman</p>
                            <h3 class="text-xl font-semibold">Status & Beban Approval</h3>
                        </div>
                        <a href="{{ route('admin.peminjaman.index') }}" class="text-xs px-3 py-1 rounded-full bg-white/10 text-indigo-50 hover:bg-white/20 transition">Kelola</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
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
                        <div class="rounded-xl bg-white/5 border border-white/10 p-4">
                            <p class="text-xs text-emerald-200/80">Barang Masuk</p>
                            <p class="text-2xl font-bold mt-1">{{ $barangMasuk ?? 0 }}</p>
                        </div>
                    </div>
                    <div class="mt-6">
                        <canvas id="adminLoanChart" class="w-full h-60"></canvas>
                        <div id="adminLoanFallback" class="hidden mt-4 space-y-2 text-indigo-100/80">
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
                        <a href="{{ route('admin.barang.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-indigo-50 hover:bg-indigo-100 border border-indigo-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-indigo-900">Kelola Barang</p>
                                <p class="text-xs text-indigo-600">Tambah / ubah data</p>
                            </div>
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-emerald-900">Proses Peminjaman</p>
                                <p class="text-xs text-emerald-600">Approve / tolak</p>
                            </div>
                            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.logs.index') }}" class="flex items-center justify-between p-3 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-amber-900">Log Aktivitas</p>
                                <p class="text-xs text-amber-600">Audit perubahan</p>
                            </div>
                            <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="{{ route('admin.barang.laporan') }}" class="flex items-center justify-between p-3 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-100 transition">
                            <div>
                                <p class="text-sm font-semibold text-slate-900">Unduh Laporan</p>
                                <p class="text-xs text-slate-600">Barang / Peminjaman / Inventaris</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Log aktivitas -->
            <div class="bg-slate-900/70 border border-white/10 rounded-2xl p-6 shadow-lg shadow-indigo-500/10">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-indigo-200/70">Log</p>
                        <h3 class="text-xl font-semibold">Aktivitas Terbaru</h3>
                    </div>
                    <a href="{{ route('admin.logs.index') }}" class="text-xs px-3 py-1 rounded-full bg-white/10 text-indigo-50 hover:bg-white/20 transition">Lihat semua</a>
                </div>
                @if($pesanAktivitas->isEmpty())
                    <p class="text-sm text-indigo-100/80">Belum ada log aktivitas.</p>
                @else
                    <ul class="space-y-3">
                        @foreach($pesanAktivitas as $log)
                            <li class="flex items-start space-x-3 bg-white/5 border border-white/10 rounded-xl p-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold">
                                    {{ strtoupper(substr($log->user->nama ?? $log->user->email, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-white">{{ $log->aksi }}</p>
                                    <p class="text-xs text-indigo-100/80">{{ $log->deskripsi }}</p>
                                    <p class="text-xs text-indigo-200/70">{{ $log->created_at?->format('d M Y H:i') }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('adminLoanChart');
    const fallback = document.getElementById('adminLoanFallback');

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
