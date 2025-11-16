@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <section class="pt-24 pb-14 bg-gradient-to-r from-indigo-600 to-blue-500 text-white text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl font-extrabold mb-4">Halo {{ auth()->user()->nama }} 👋</h2>
            <p class="text-lg mb-6">Pantau status permintaan, ajukan peminjaman baru, dan ketahui progres barang yang Anda pinjam.</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('peminjam.peminjaman.create') }}" class="px-6 py-3 bg-white text-indigo-700 font-semibold rounded-full shadow hover:bg-gray-100 transition">
                    Ajukan Peminjaman
                </a>
                <a href="{{ route('peminjam.peminjaman.index') }}" class="px-6 py-3 border border-white rounded-full hover:bg-white/10 transition">
                    Lihat Riwayat
                </a>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-indigo-600 uppercase tracking-wide">Total Peminjaman</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $totalPeminjaman ?? 0 }}</p>
                    </div>
                    <a href="{{ route('peminjam.peminjaman.create') }}" class="p-3 bg-indigo-100 hover:bg-indigo-200 rounded-lg transition">
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </a>
                </div>
                <div class="mt-4 h-1 bg-indigo-500 rounded-full"></div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-600 uppercase tracking-wide">Menunggu Persetujuan</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $pendingPeminjaman ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-amber-100 rounded-lg">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-amber-500 rounded-full"></div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-emerald-600 uppercase tracking-wide">Sedang Dipinjam</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $dipinjamPeminjaman ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-lg">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-emerald-500 rounded-full"></div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-rose-600 uppercase tracking-wide">Peminjaman Ditolak</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $ditolakPeminjaman ?? 0 }}</p>
                    </div>
                    <div class="p-3 bg-rose-100 rounded-lg">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-rose-500 rounded-full"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <p class="text-sm font-medium text-blue-600 uppercase tracking-wide">Barang Tersedia</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $barangTersedia ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-2">Barang yang bisa Anda ajukan untuk dipinjam.</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <p class="text-sm font-medium text-purple-600 uppercase tracking-wide">Ruang Terdaftar</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $ruangTersedia ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-2">Ruang yang dapat dipilih sebagai lokasi penggunaan.</p>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <p class="text-sm font-medium text-slate-600 uppercase tracking-wide">Peminjaman Selesai</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $selesaiPeminjaman ?? 0 }}</p>
                <p class="text-sm text-gray-500 mt-2">Barang yang sudah dikembalikan dengan sukses.</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-indigo-600">Ringkasan Status Peminjaman</h2>
                <a href="{{ route('peminjam.peminjaman.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">Lihat detail</a>
            </div>

            <div class="flex flex-col items-center">
                <h3 class="text-center text-lg font-semibold text-gray-800 mb-8">Grafik Status</h3>
                <div class="w-full max-w-md">
                    <canvas id="statusChart" width="400" height="400"></canvas>
                </div>
                <div id="statusChartFallback" class="hidden text-center w-full max-w-md mt-8">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-indigo-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-indigo-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Total Peminjaman</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $totalPeminjaman ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-amber-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-amber-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Pending</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $pendingPeminjaman ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-emerald-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-emerald-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Dipinjam</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $dipinjamPeminjaman ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-slate-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Selesai</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $selesaiPeminjaman ?? 0 }}</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-rose-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-rose-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Ditolak</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $ditolakPeminjaman ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('statusChart');
    const fallback = document.getElementById('statusChartFallback');

    if (typeof Chart === 'undefined' || !canvas) {
        if (canvas) {
            canvas.style.display = 'none';
        }
        if (fallback) {
            fallback.classList.remove('hidden');
        }
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
            type: 'doughnut',
            data: {
                labels: ['Total', 'Pending', 'Dipinjam', 'Selesai', 'Ditolak'],
                datasets: [{
                    data: [total, pending, dipinjam, selesai, ditolak],
                    backgroundColor: ['#6366F1', '#F59E0B', '#10B981', '#475569', '#EF4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    }
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
