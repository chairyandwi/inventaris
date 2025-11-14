@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <section class="pt-24 pb-14 bg-gradient-to-r from-indigo-600 to-blue-500 flex justify-center items-center text-white text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl font-extrabold mb-4 pt-4">Selamat Datang {{ auth()->user()->nama }}!</h2>
            <p class="text-lg mb-4">Anda dapat mengelola aset, ruang, dan peminjaman barang kampus dengan mudah, cepat, dan transparan.</p>
        </div>
    </section>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <!-- Stats Data Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Barang Keluar Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-lime-600 uppercase tracking-wide">DATA KATEGORI</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $kategori }}</p>
                    </div>
                    <a href="{{ route('pegawai.kategori.create') }}" 
                    class="p-3 bg-lime-100 hover:bg-lime-200 rounded-lg transition">
                     <svg class="w-6 h-6 text-lime-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                               d="M12 4v16m8-8H4" />
                     </svg>
                 </a>
                </div>
                <div class="mt-4 h-1 bg-lime-500 rounded-full"></div>
            </div>

            <!-- Barang Masuk Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-violet-600 uppercase tracking-wide">DATA RUANG</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $ruang }}</p>
                    </div>
                    <a href="{{ route('pegawai.ruang.create') }}" 
                    class="p-3 bg-violet-100 hover:bg-violet-200 rounded-lg transition">
                     <svg class="w-6 h-6 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                               d="M12 4v16m8-8H4" />
                     </svg>
                 </a>
                </div>
                <div class="mt-4 h-1 bg-violet-500 rounded-full"></div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-pink-600 uppercase tracking-wide">DATA BARANG</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $barang }}</p>
                    </div>
                    <a href="{{ route('pegawai.barang.create') }}" 
                    class="p-3 bg-pink-100 hover:bg-pink-200 rounded-lg transition">
                     <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                               d="M12 4v16m8-8H4" />
                     </svg>
                 </a>
                </div>
                <div class="mt-4 h-1 bg-pink-500 rounded-full"></div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-amber-600 uppercase tracking-wide">DATA USER</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $user }}</p>
                    </div>
                    <a href="{{ route('pegawai.user.create') }}" 
                    class="p-3 bg-amber-100 hover:bg-amber-200 rounded-lg transition">
                     <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                               d="M12 4v16m8-8H4" />
                     </svg>
                 </a>
                </div>
                <div class="mt-4 h-1 bg-amber-500 rounded-full"></div>
            </div>
        </div>

        <!-- Stats Cards Transaction -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Barang Keluar Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-green-600 uppercase tracking-wide">BARANG KELUAR</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $barangKeluar }}</p>
                    </div>
                    <div class="p-3 bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-green-500 rounded-full"></div>
            </div>

            <!-- Barang Masuk Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-blue-600 uppercase tracking-wide">BARANG MASUK</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $barangMasuk }}</p>
                    </div>
                    <div class="p-3 bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-blue-500 rounded-full"></div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-red-600 uppercase tracking-wide">BARANG DIPINJAM</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $barangPinjam }}</p>
                    </div>
                    <div class="p-3 bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-red-500 rounded-full"></div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-blue-600">Grafik Total Data Keseluruhan</h2>
                <button class="p-2 hover:bg-gray-100 rounded-lg">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                    </svg>
                </button>
            </div>

            <div class="flex flex-col items-center">
                <h3 class="text-center text-lg font-semibold text-gray-800 mb-8">Grafik</h3>
                
                <!-- Chart Container -->
                <div class="w-full max-w-md">
                    <canvas id="donutChart" width="400" height="400"></canvas>
                </div>

                <!-- Fallback if Chart doesn't load -->
                <div id="chartFallback" class="hidden text-center">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Barang Keluar</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $barangKeluar }} items</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Barang Masuk</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $barangMasuk }} items</span>
                        </div>
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-4 h-4 bg-red-500 rounded-full"></div>
                                <span class="text-sm font-medium text-gray-700">Barang Dipinjam</span>
                            </div>
                            <span class="text-lg font-bold text-gray-900">{{ $barangPinjam }} items</span>
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
    const canvas = document.getElementById('donutChart');
    const fallback = document.getElementById('chartFallback');
    
    // Check if Chart.js loaded and canvas exists
    if (typeof Chart === 'undefined' || !canvas) {
        console.warn('Chart.js tidak tersedia, menampilkan fallback');
        canvas.style.display = 'none';
        fallback.classList.remove('hidden');
        return;
    }

    try {
        const ctx = canvas.getContext('2d');
        const kategori = {{ $kategori ?? 0 }};
        const ruang = {{ $ruang ?? 0 }};
        const barang = {{ $barang ?? 0 }};
        const user = {{ $user ?? 0 }};
        const barangKeluar = {{ $barangKeluar ?? 0 }};
        const barangMasuk = {{ $barangMasuk ?? 0 }};
        const barangPinjam = {{ $barangPinjam ?? 0 }};


        const chart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Kategori', 'Ruang','Barang','User','Barang Keluar', 'Barang Masuk', 'Barang Dipinjam', ],
                datasets: [{
                    data: [kategori,ruang, barang,user,barangKeluar, barangMasuk, barangPinjam],
                    backgroundColor: ['#CDDC39', '#9C27B0', '#E91E63', '#FFC107', '#10B981', '#3B82F6', '#EF4444' ],
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
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
        
        console.log('Chart berhasil dibuat');
    } catch (error) {
        console.error('Error membuat chart:', error);
        canvas.style.display = 'none';
        fallback.classList.remove('hidden');
    }
});
</script>
@endsection