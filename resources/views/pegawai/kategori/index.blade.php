@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    @php
        $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
    @endphp
    <div class="pt-24 container mx-auto px-4 py-6 min-h-screen">
        <!-- Header dengan tombol Tambah -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route($homeRoute ?? 'pegawai.index') }}" 
                    class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Kategori</h1>
                <a href="{{ route(($routePrefix ?? 'pegawai') . '.kategori.create') }}"
                    class="ml-auto inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-4">
                <p class="text-xs font-semibold text-gray-500 uppercase">Total Kategori</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] ?? 0 }}</p>
                <p class="text-xs text-gray-500">Kategori aktif di sistem</p>
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
                            placeholder="Cari kategori..." value="{{ request('search') }}" id="searchInput">
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                                <a href="{{ route(($routePrefix ?? 'pegawai') . '.kategori.index', [
                                    'sort_by' => 'idkategori',
                                    'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'
                                ]) }}" class="flex items-center space-x-1">
                                    <span>No</span>

                                    {{-- Default (tidak ada sort_by) tampilkan dua panah abu-abu --}}
                                    @if(request('sort_by') !== 'idkategori')
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8v12m0 0l4-4m-4 4l-4-4"/>
                                        </svg>
                                    @elseif(request('sort_direction') === 'asc')
                                        {{-- Asc: tampilkan panah atas saja --}}
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4"/>
                                        </svg>
                                    @else
                                        {{-- Desc: tampilkan panah bawah saja --}}
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8v12m0 0l4-4m-4 4l-4-4"/>
                                        </svg>
                                    @endif
                                </a>
                            </th>

                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center space-x-1">
                                    <span>Nama Kategori</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100">
                                <div class="flex items-center space-x-1">
                                    <span>Keterangan</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1">
                                    <span>Aksi</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kategori as $index => $k)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $kategori->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $k->nama_kategori }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $k->keterangan ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <!-- Edit Button -->
                                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.kategori.edit', $k->idkategori) }}"
                                            class="inline-flex items-center px-1 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded transition duration-150 ease-in-out">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>

                                        <!-- Delete Button -->
                                        <button type="button" onclick="confirmDelete({{ $k->idkategori }})"
                                            class="inline-flex items-center px-1 py-1 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded transition duration-150 ease-in-out">
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
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 mb-1">Tidak ada data</p>
                                        <p class="text-gray-500">Belum ada kategori yang ditambahkan</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ $kategori->firstItem() ?? 0 }} to {{ $kategori->lastItem() ?? 0 }} of
                        {{ $kategori->total() }} entries
                    </div>

                    <div class="flex items-center space-x-1">
                        <!-- Previous Button -->
                        @if ($kategori->onFirstPage())
                            <span
                                class="px-3 py-2 text-sm text-gray-500 bg-gray-200 rounded cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $kategori->previousPageUrl() }}"
                                class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                                Previous
                            </a>
                        @endif

                        <!-- Page Numbers -->
                        @foreach ($kategori->getUrlRange(1, $kategori->lastPage()) as $page => $url)
                            @if ($page == $kategori->currentPage())
                                <span
                                    class="px-3 py-2 text-sm text-white bg-blue-500 rounded font-medium">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}"
                                    class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach

                        <!-- Next Button -->
                        @if ($kategori->hasMorePages())
                            <a href="{{ $kategori->nextPageUrl() }}"
                                class="px-3 py-2 text-sm text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                                Next
                            </a>
                        @else
                            <span
                                class="px-3 py-2 text-sm text-gray-500 bg-gray-200 rounded cursor-not-allowed">Next</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Form (Hidden) -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- JavaScript -->
    <script>
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value;
            const currentUrl = new URL(window.location);

            if (searchTerm) {
                currentUrl.searchParams.set('search', searchTerm);
            } else {
                currentUrl.searchParams.delete('search');
            }

            // Debounce search
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => {
                window.location.href = currentUrl.toString();
            }, 500);
        });

        // Per page change
        document.querySelector('select').addEventListener('change', function(e) {
            const perPage = e.target.value;
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('per_page', perPage);
            currentUrl.searchParams.delete('page'); // Reset to first page
            window.location.href = currentUrl.toString();
        });

        // Delete confirmation
        function confirmDelete(id) {
        if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
            const form = document.getElementById('deleteForm');
            form.action = "{{ route(($routePrefix ?? 'pegawai').'.kategori.destroy', ':id') }}".replace(':id', id); // arahkan ke route destroy
            form.submit();
        }
    }

        // Auto-hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 3000);
            });
        });
    </script>
@endsection
