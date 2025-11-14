@extends('layouts.app')

@section('title', 'Barang')

@section('content')
    <div class="pt-24 container mx-auto px-4 py-6 min-h-screen">
        <!-- Header dengan tombol Tambah -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('pegawai.index') }}" 
                    class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold text-gray-800 ml-4">Barang</h1>
        
                <div class="ml-auto flex items-center space-x-2">
                        <!-- Export PDF -->
                        <a href="{{ route('pegawai.barang.laporan') }}"
                            class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                            </svg>                              
                            Export
                        </a>
        
                    <!-- Tambah -->
                    <a href="{{ route('pegawai.barang.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah
                    </a>
                </div>
            </div>
        </div>
        

        <!-- Card wrapper -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Controls Section -->
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <!-- Show entries dropdown -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-700">Show</span>
                        <select
                            class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-16">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                        <span class="text-sm text-gray-700">entries</span>
                    </div>

                    <!-- Search -->
                    <div class="flex items-center space-x-2">
                        <span class="text-sm text-gray-700">Search:</span>
                        <input type="text"
                            class="border border-gray-300 rounded px-3 py-1 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Cari barang..." value="{{ request('search') }}" id="searchInput">
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stok</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($barang as $index => $b)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $barang->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $b->kode_barang }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $b->nama_barang }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $b->kategori->nama_kategori ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $b->stok }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $b->keterangan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <!-- Edit -->
                                        <a href="{{ route('pegawai.barang.edit', $b->idbarang) }}"
                                            class="inline-flex items-center px-1 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <!-- Delete -->
                                        <button type="button" onclick="confirmDelete({{ $b->idbarang }})"
                                            class="inline-flex items-center px-1 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                    <p class="text-lg font-medium">Tidak ada data barang</p>
                                    <p class="text-gray-500">Belum ada barang yang ditambahkan</p>
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
                        Showing {{ $barang->firstItem() ?? 0 }} to {{ $barang->lastItem() ?? 0 }} of
                        {{ $barang->total() }} entries
                    </div>
                    <div>
                        {{ $barang->links() }}
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
        // Search
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value;
            const currentUrl = new URL(window.location);

            if (searchTerm) {
                currentUrl.searchParams.set('search', searchTerm);
            } else {
                currentUrl.searchParams.delete('search');
            }

            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => {
                window.location.href = currentUrl.toString();
            }, 500);
        });

        // Per page
        document.querySelector('select').addEventListener('change', function(e) {
            const perPage = e.target.value;
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('per_page', perPage);
            currentUrl.searchParams.delete('page');
            window.location.href = currentUrl.toString();
        });

        // Delete confirm
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
                const form = document.getElementById('deleteForm');
                form.action = "{{ route('pegawai.barang.destroy', ':id') }}".replace(':id', id);
                form.submit();
            }
        }
    </script>
@endsection
