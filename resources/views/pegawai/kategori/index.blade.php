@extends('layouts.app')

@section('title', 'Kategori')

@section('content')
    @php
        $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
    @endphp
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
            <div class="absolute -left-12 -top-20 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
            <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Kategori Barang</p>
                        <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Klasifikasi rapi dalam satu layar</h1>
                        <p class="mt-3 text-indigo-50/90 max-w-2xl">Kelola nama kategori dan catatan singkat dengan tampilan rapi yang konsisten.</p>
                    </div>
                    <a href="{{ route(($routePrefix ?? 'pegawai') . '.kategori.create') }}"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-6-6h12" />
                        </svg>
                        Tambah Kategori
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Kategori</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total'] ?? 0 }}</p>
                            <p class="text-sm text-indigo-100/80 mt-1">Kategori aktif di sistem</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative -mt-10 pb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="bg-slate-900/70 border border-white/10 rounded-2xl shadow-lg shadow-indigo-500/10 backdrop-blur">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6">
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-indigo-100">Show</span>
                            <select id="perPage" class="rounded-xl bg-slate-800/60 border border-white/10 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 w-24">
                                <option class="text-slate-900" value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option class="text-slate-900" value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option class="text-slate-900" value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option class="text-slate-900" value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                            <span class="text-sm text-indigo-100">entries</span>
                        </div>

                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <input type="text" id="searchInput"
                                class="flex-1 md:flex-none rounded-xl bg-slate-800/60 border border-white/10 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                                placeholder="Cari kategori..." value="{{ request('search') }}">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">
                                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.kategori.index', [
                                            'sort_by' => 'idkategori',
                                            'sort_direction' => request('sort_direction') === 'asc' ? 'desc' : 'asc',
                                            'search' => request('search'),
                                            'per_page' => request('per_page')
                                        ]) }}" class="flex items-center gap-1">
                                            <span>No</span>
                                            @if(request('sort_by') !== 'idkategori')
                                                <svg class="w-4 h-4 text-indigo-200/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4 4 4M17 8v12m0 0 4-4m-4 4-4-4"/>
                                                </svg>
                                            @elseif(request('sort_direction') === 'asc')
                                                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4 4 4"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8v12m0 0 4-4m-4 4-4-4"/>
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Nama Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Keterangan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($kategori as $index => $k)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 text-sm text-indigo-50">
                                            {{ $kategori->firstItem() + $index }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-semibold text-white">
                                            {{ $k->nama_kategori }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-indigo-100/80">
                                            {{ $k->keterangan ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route(($routePrefix ?? 'pegawai') . '.kategori.edit', $k->idkategori) }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-500/80 hover:bg-indigo-500 text-white shadow-md shadow-indigo-500/30 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <button type="button" onclick="confirmDelete({{ $k->idkategori }})"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-rose-500/80 hover:bg-rose-500 text-white shadow-md shadow-rose-500/30 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-sm text-indigo-100/80">
                                            <p class="text-lg font-semibold">Tidak ada data kategori</p>
                                            <p class="text-sm text-indigo-100/70 mt-1">Tambah kategori untuk mulai mengelompokkan barang.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100 flex items-center justify-between">
                        <div>
                            Menampilkan {{ $kategori->firstItem() ?? 0 }} hingga {{ $kategori->lastItem() ?? 0 }} dari {{ $kategori->total() }} entri
                        </div>
                        <div class="text-white">
                            {{ $kategori->links() }}
                        </div>
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
        const searchInput = document.getElementById('searchInput');
        const perPageSelect = document.getElementById('perPage');

        function syncUrl(param, value) {
            const currentUrl = new URL(window.location);
            if (value) {
                currentUrl.searchParams.set(param, value);
            } else {
                currentUrl.searchParams.delete(param);
            }
            currentUrl.searchParams.delete('page');
            return currentUrl.toString();
        }

        if (searchInput) {
            searchInput.addEventListener('input', function(e) {
                const url = syncUrl('search', e.target.value);
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(() => {
                    window.location.href = url;
                }, 400);
            });
        }

        if (perPageSelect) {
            perPageSelect.addEventListener('change', function(e) {
                window.location.href = syncUrl('per_page', e.target.value);
            });
        }

        async function confirmDelete(id) {
            const ok = await window.smartConfirm?.('Apakah Anda yakin ingin menghapus kategori ini?');
            if (!ok) return;
            const form = document.getElementById('deleteForm');
            form.action = "{{ route(($routePrefix ?? 'pegawai').'.kategori.destroy', ':id') }}".replace(':id', id);
            form.submit();
        }
    </script>
@endsection
