@extends('layouts.app')

@section('title', 'Barang')

@section('content')
    @php
        $userRole = auth()->user()->role ?? 'pegawai';
        $routePrefix = $userRole === 'admin' ? 'admin.' : 'pegawai.';
    @endphp
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
            <div class="absolute -left-12 -top-20 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
            <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Barang & Stok</p>
                        <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Inventaris barang dengan insight cepat</h1>
                        <p class="mt-3 text-indigo-50/90 max-w-2xl">Kelola barang pinjam dan tetap, cek stok, serta ekspor laporan dengan tampilan futuristik.</p>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route($routePrefix . 'barang.laporan') }}"
                            class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-amber-100 text-amber-900 font-semibold shadow-lg shadow-amber-400/30 hover:-translate-y-0.5 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659"/>
                            </svg>
                            Export
                        </a>
                        <a href="{{ route($routePrefix . 'barang.create') }}"
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-6-6h12" />
                            </svg>
                            Tambah Barang
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Barang</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['total'] ?? 0 }}</p>
                            <p class="text-sm text-indigo-100/80 mt-1">Semua jenis terdaftar</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-sky-400/30 to-indigo-500/40 opacity-70"></div>
                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">Barang Tetap</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalTetap'] ?? 0 }}</p>
                            <p class="text-sm text-indigo-100/80 mt-1">Siap inventaris ruang</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-400/30 to-orange-500/40 opacity-70"></div>
                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">Barang Pinjam</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalPinjam'] ?? 0 }}</p>
                            <p class="text-sm text-indigo-100/80 mt-1">Keluar-masuk stok</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-lime-500/40 opacity-70"></div>
                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Stok</p>
                            <p class="text-3xl font-bold mt-2">{{ $stats['totalStok'] ?? 0 }}</p>
                            <p class="text-sm text-indigo-100/80 mt-1">Unit siap pakai</p>
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
                                placeholder="Cari kode / nama / kategori..." value="{{ request('search') }}">
                        </div>
                    </div>
                </div>

                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Kode Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Nama Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Kategori</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Stok</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Keterangan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($barang as $index => $b)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 text-sm text-indigo-50">{{ $barang->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 text-sm font-mono text-white">{{ $b->kode_barang }}</td>
                                        <td class="px-6 py-4 text-sm font-semibold text-white">{{ $b->nama_barang }}</td>
                                        <td class="px-6 py-4 text-sm text-indigo-50">{{ $b->kategori->nama_kategori ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-indigo-50">{{ $b->stok }}</td>
                                        <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $b->keterangan ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <div class="flex items-center gap-2">
                                                <a href="{{ route($routePrefix . 'barang.show', $b->idbarang) }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-white/15 border border-white/10 text-white hover:bg-white/25 transition" title="Detail">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                    Detail
                                                </a>
                                                <a href="{{ route($routePrefix . 'barang.edit', $b->idbarang) }}"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-500/80 hover:bg-indigo-500 text-white shadow-md shadow-indigo-500/30 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                    Edit
                                                </a>
                                                <button type="button" onclick="confirmDelete({{ $b->idbarang }})"
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
                                        <td colspan="7" class="px-6 py-12 text-center text-sm text-indigo-100/80">
                                            <p class="text-lg font-semibold">Tidak ada data barang</p>
                                            <p class="text-sm text-indigo-100/70 mt-1">Tambah barang baru untuk mulai mencatat stok.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100 flex items-center justify-between">
                        <div>
                            Menampilkan {{ $barang->firstItem() ?? 0 }} - {{ $barang->lastItem() ?? 0 }} dari {{ $barang->total() }} entri
                        </div>
                        <div class="text-white">
                            {{ $barang->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="deleteForm" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

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
            searchInput.addEventListener('input', (e) => {
                const url = syncUrl('search', e.target.value);
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(() => window.location.href = url, 400);
            });
        }

        if (perPageSelect) {
            perPageSelect.addEventListener('change', (e) => {
                window.location.href = syncUrl('per_page', e.target.value);
            });
        }

        function confirmDelete(id) {
            if (confirm('Apakah Anda yakin ingin menghapus barang ini?')) {
                const form = document.getElementById('deleteForm');
                form.action = "{{ route($routePrefix . 'barang.destroy', ':id') }}".replace(':id', id);
                form.submit();
            }
        }
    </script>
@endsection
