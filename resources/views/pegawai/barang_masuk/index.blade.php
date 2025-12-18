@extends('layouts.app')

@section('title', 'Barang Masuk')

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
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Barang Masuk</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Catat stok masuk dengan detail</h1>
                    <p class="mt-3 text-indigo-50/90 max-w-2xl">Pantau unit masuk, status baru/bekas, dan spesifikasi PC agar stok selalu akurat.</p>
                </div>
                <a href="{{ route(($routePrefix ?? 'pegawai') . '.barang_masuk.create') }}"
                    class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-6-6h12" />
                    </svg>
                    Tambah Barang Masuk
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-8">
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Entri</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalEntry'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Baris barang masuk</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400/30 to-indigo-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Unit</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalQty'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Stok bertambah</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-lime-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Barang Baru</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalBaru'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Status baru</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400/30 to-orange-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Barang Bekas</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalBekas'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Status bekas</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-400/30 to-indigo-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Unit PC</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalPc'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Dengan spesifikasi</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/70 border border-white/10 rounded-2xl shadow-lg shadow-indigo-500/10 backdrop-blur">
                <form method="GET" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Barang</label>
                            <select name="idbarang" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option class="text-slate-900" value="">Semua Barang</option>
                                @foreach($barangList ?? [] as $b)
                                    <option class="text-slate-900" value="{{ $b->idbarang }}" @selected(request('idbarang') == $b->idbarang)>{{ $b->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Status</label>
                            <select name="status_barang" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option class="text-slate-900" value="">Semua Status</option>
                                <option class="text-slate-900" value="baru" @selected(request('status_barang') === 'baru')>Baru</option>
                                <option class="text-slate-900" value="bekas" @selected(request('status_barang') === 'bekas')>Bekas</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Jenis</label>
                            <select name="is_pc" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option class="text-slate-900" value="">Semua</option>
                                <option class="text-slate-900" value="1" @selected(request('is_pc') === '1')>PC / dengan spesifikasi</option>
                                <option class="text-slate-900" value="0" @selected(request('is_pc') === '0')>Non PC</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Tanggal dari</label>
                            <input type="date" name="tgl_masuk_from" value="{{ request('tgl_masuk_from') }}"
                                   class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Tanggal sampai</label>
                            <input type="date" name="tgl_masuk_to" value="{{ request('tgl_masuk_to') }}"
                                   class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-indigo-100">Show</span>
                            <select name="per_page" class="rounded-xl bg-slate-800/60 border border-white/10 text-white px-3 py-2 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 w-24">
                                @foreach([10,25,50,100] as $pp)
                                    <option class="text-slate-900" value="{{ $pp }}" @selected(request('per_page',10)==$pp)>{{ $pp }}</option>
                                @endforeach
                            </select>
                            <span class="text-sm text-indigo-100">entries</span>
                        </div>
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <input type="text" name="search" id="searchInput"
                                class="flex-1 md:flex-none rounded-xl bg-slate-800/60 border border-white/10 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                                placeholder="Cari kode / nama / keterangan..." value="{{ request('search') }}">
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route(($routePrefix ?? 'pegawai') . '.barang_masuk.index') }}"
                               class="px-4 py-2 rounded-xl border border-white/15 text-sm font-semibold text-indigo-50 hover:bg-white/10 transition">
                                Reset
                            </a>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                                Terapkan Filter
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Tgl Masuk</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Kode Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Ruang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Spesifikasi</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
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
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 text-sm text-indigo-50">{{ $rowNumber }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-50">
                                        {{ $bm->tgl_masuk ?? ($bm->created_at?->format('Y-m-d')) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-mono text-white">
                                        {{ $bm->barang->kode_barang ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-white">
                                        {{ $bm->barang->nama_barang ?? '-' }}
                                    </td>
                                    @php
                                    $agg = $ruangAggregates[$bm->idbarang_masuk] ?? null;
                                    $ruangText = $agg?->kode_list ?: $agg?->ruang_list;

                                    $ruangDisplay = '-';
                                    if ($ruangText) {
                                        $ruangArray = array_filter(array_map('trim', explode(',', $ruangText)));
                                        if ((int) $bm->jumlah === 1) {
                                            $ruangDisplay = $ruangArray ? $ruangArray[0] : '-';
                                        } else {
                                            $ruangCount = count($ruangArray);
                                            $preview = array_slice($ruangArray, 0, 3);
                                            $ruangDisplay = implode(', ', $preview);
                                            if ($ruangCount > 3) {
                                                $ruangDisplay .= ' +' . ($ruangCount - 3);
                                            }
                                        }
                                    }
                                    @endphp
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">
                                        {{ $ruangDisplay }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $bm->status_barang === 'bekas' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                            {{ strtoupper($bm->status_barang ?? 'BARU') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-indigo-50">
                                        {{ $bm->jumlah }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">
                                        {{ $specText }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">
                                        {{ $bm->keterangan ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route(($routePrefix ?? 'pegawai') . '.barang_masuk.edit', $bm->idbarang_masuk) }}"
                                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-500/80 hover:bg-indigo-500 text-white shadow-md shadow-indigo-500/30 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2h11a2 2 0 0 0 2-2v-5m-1.414-9.414a2 2 0 1 1 2.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                                Edit
                                            </a>
                                            <button type="button" onclick="confirmDelete({{ $bm->idbarang_masuk }})"
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
                                    <td colspan="9" class="px-6 py-12 text-center text-sm text-indigo-100/80">
                                        <p class="text-lg font-semibold">Belum ada data barang masuk</p>
                                        <p class="text-sm text-indigo-100/70 mt-1">Tambah entri barang masuk untuk mulai mencatat pergerakan stok.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100 flex items-center justify-between">
                    <div>
                        Menampilkan {{ $barangMasuk->firstItem() ?? 0 }} - {{ $barangMasuk->lastItem() ?? 0 }} dari {{ $barangMasuk->total() }} entri
                    </div>
                    <div class="text-white">
                        {{ $barangMasuk->links() }}
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

    async function confirmDelete(id) {
        const ok = await window.smartConfirm?.('Apakah Anda yakin ingin menghapus entri barang masuk ini?');
        if (!ok) return;
        const form = document.getElementById('deleteForm');
        form.action = "{{ route(($routePrefix ?? 'pegawai').'.barang_masuk.destroy', ':id') }}".replace(':id', id);
        form.submit();
    }
</script>
@endsection
