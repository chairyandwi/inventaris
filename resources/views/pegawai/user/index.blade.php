@extends('layouts.app')

@section('title', 'User')

@section('content')
    <div class="pt-24 container mx-auto px-4 py-6 min-h-screen">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('pegawai.index') }}" 
                    class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold text-gray-800">User</h1>
                <a href="{{ route('pegawai.user.create') }}"
                    class="ml-auto inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah
                </a>
            </div>
        </div>

        <!-- Card wrapper -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Controls Section -->
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <!-- Show entries dropdown -->
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-700">Show</span>
                    <select class="border border-gray-300 rounded px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-16"
                        onchange="changePerPage(this)">
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
                    <input type="text" id="searchInput"
                        class="border border-gray-300 rounded px-3 py-1 text-sm w-64 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Cari user..." value="{{ request('search') }}">
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hover:bg-gray-100">
                                <a href="{{ route('pegawai.user.index', [
                                    'sort_by' => 'id',
                                    'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc'
                                ]) }}" class="flex items-center space-x-1">
                                    <span>No</span>

                                    {{-- Default (tidak ada sort_by) tampilkan dua panah abu-abu --}}
                                    @if(request('sort_by') !== 'id')
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No HP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($users as $index => $u)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $users->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $u->nama }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $u->username }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $u->email }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $u->nohp ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ ucfirst($u->role) }}</td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('pegawai.user.edit', $u->id) }}"
                                            class="inline-flex items-center px-1 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs font-medium rounded transition duration-150 ease-in-out">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        <button type="button" onclick="confirmDelete({{ $u->id }})"
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
                                <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                    Tidak ada user
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-between items-center">
                <div class="text-sm text-gray-700">
                    Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} entries
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

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

            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => {
                window.location.href = currentUrl.toString();
            }, 500);
        });

        // Per page change
        function changePerPage(e) {
            const perPage = e.value;
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('per_page', perPage);
            currentUrl.searchParams.delete('page');
            window.location.href = currentUrl.toString();
        }

        // Delete confirmation
        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus user ini?')) {
                const form = document.getElementById('deleteForm');
                form.action = `/pegawai/user/${id}`;
                form.submit();
            }
        }
    </script>
@endsection
