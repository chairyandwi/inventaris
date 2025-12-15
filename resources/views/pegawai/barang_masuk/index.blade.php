@extends('layouts.app')

@section('title', 'Barang Masuk')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
@endphp
<div class="pt-24 container mx-auto px-4 py-6 min-h-screen">
    <div class="mb-6 flex items-center">
        @php
            $backRoute = (auth()->check() && auth()->user()->role === 'admin') ? 'admin.index' : 'pegawai.index';
        @endphp
        <a href="{{ route($backRoute) }}"
            class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Dashboard
        </a>
        <h1 class="text-2xl font-bold text-gray-800 ml-4">Barang Masuk</h1>

        <div class="ml-auto flex items-center space-x-2">
            <a href="{{ route(($routePrefix ?? 'pegawai') . '.barang_masuk.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Barang Masuk
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">Total Entri</p>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['totalEntry'] ?? 0 }}</p>
            <p class="text-xs text-gray-500">Jumlah baris barang masuk</p>
        </div>
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">Total Unit Masuk</p>
            <p class="text-2xl font-bold text-indigo-700 mt-1">{{ $stats['totalQty'] ?? 0 }}</p>
            <p class="text-xs text-gray-500">Akumulasi stok bertambah</p>
        </div>
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">Barang Baru</p>
            <p class="text-2xl font-bold text-green-700 mt-1">{{ $stats['totalBaru'] ?? 0 }}</p>
            <p class="text-xs text-gray-500">Unit berstatus baru</p>
        </div>
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">Barang Bekas</p>
            <p class="text-2xl font-bold text-amber-700 mt-1">{{ $stats['totalBekas'] ?? 0 }}</p>
            <p class="text-xs text-gray-500">Unit berstatus bekas</p>
        </div>
        <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-4">
            <p class="text-xs font-semibold text-gray-500 uppercase">Unit PC Masuk</p>
            <p class="text-2xl font-bold text-blue-700 mt-1">{{ $stats['totalPc'] ?? 0 }}</p>
            <p class="text-xs text-gray-500">PC/Laptop dengan detail komponen</p>
        </div>
    </div>

    <!-- Card wrapper -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <!-- Controls Section -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex flex-wrap gap-4 justify-between items-center">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-700">Show</span>
                    <select
                        class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-16"
                        onchange="handlePerPageChange(this.value)">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                    <span class="text-sm text-gray-700">entries</span>
                </div>

                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-700">Search:</span>
                    <input type="text"
                        class="border border-gray-300 rounded px-3 py-1 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Cari kode/nama/keterangan..." value="{{ request('search') }}" id="searchInput">
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Masuk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spesifikasi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($barangMasuk as $index => $bm)
                        @php
                            $rowNumber = ($barangMasuk->currentPage() - 1) * $barangMasuk->perPage() + $index + 1;
                            $isPc = $bm->is_pc || str_contains(strtolower(optional($bm->barang?->kategori)->nama_kategori ?? ''), 'pc');
                            $specParts = [];
                            if ($bm->ram_capacity_gb) {
                                $brand = $bm->ram_brand ? ' (' . $bm->ram_brand . ')' : '';
                                $specParts[] = 'RAM ' . $bm->ram_capacity_gb . 'GB' . $brand;
                            }
                            if ($bm->storage_type && $bm->storage_capacity_gb) {
                                $specParts[] = $bm->storage_type . ' ' . $bm->storage_capacity_gb . 'GB';
                            }
                            if ($bm->processor) {
                                $specParts[] = 'CPU ' . $bm->processor;
                            }
                            $specText = $specParts ? implode(' • ', $specParts) : ($isPc ? 'Spesifikasi PC belum lengkap' : '-');
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $rowNumber }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $bm->tgl_masuk ?? ($bm->created_at?->format('Y-m-d')) }}
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-900">
                                {{ $bm->barang->kode_barang ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $bm->barang->nama_barang ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $bm->status_barang === 'bekas' ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700' }}">
                                    {{ strtoupper($bm->status_barang ?? 'BARU') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                {{ $bm->jumlah }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                {{ $specText }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $bm->keterangan ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <div class="flex items-center space-x-2">
                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.barang_masuk.edit', $bm->idbarang_masuk) }}"
                                        class="inline-flex items-center px-2 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded transition">
                                        Edit
                                    </a>
                                    <button type="button" onclick="confirmDelete({{ $bm->idbarang_masuk }})"
                                        class="inline-flex items-center px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-sm text-gray-500">
                                <p class="text-lg font-medium">Belum ada data barang masuk</p>
                                <p class="text-gray-500">Tambah entri barang masuk untuk mulai mencatat pergerakan stok.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing {{ $barangMasuk->firstItem() ?? 0 }} to {{ $barangMasuk->lastItem() ?? 0 }} of
                    {{ $barangMasuk->total() }} entries
                </div>
                <div>
                    {{ $barangMasuk->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<script>
    const searchInput = document.getElementById('searchInput');
    function handlePerPageChange(value) {
        const currentUrl = new URL(window.location);
        currentUrl.searchParams.set('per_page', value);
        currentUrl.searchParams.delete('page');
        window.location.href = currentUrl.toString();
    }
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const searchTerm = e.target.value;
            const currentUrl = new URL(window.location);
            if (searchTerm) {
                currentUrl.searchParams.set('search', searchTerm);
            } else {
                currentUrl.searchParams.delete('search');
            }
            currentUrl.searchParams.delete('page');
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => {
                window.location.href = currentUrl.toString();
            }, 400);
        });
    }

    function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus entri barang masuk ini?')) {
            const form = document.getElementById('deleteForm');
            form.action = "{{ route(($routePrefix ?? 'pegawai').'.barang_masuk.destroy', ':id') }}".replace(':id', id);
            form.submit();
        }
    }
</script>
@endsection
