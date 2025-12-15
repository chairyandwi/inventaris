@extends('layouts.app')

@section('title', 'Inventaris Ruang')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Inventaris Ruang</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Mapping unit barang per ruang</h1>
                    <p class="mt-3 text-indigo-50/90 max-w-2xl">Pantau penyebaran aset tetap per ruang, unduh laporan, dan jaga keterisian ruang tetap terukur.</p>
                </div>
                <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-6-6h12" />
                    </svg>
                    Tambah Inventaris
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Unit</p>
                        <p class="text-3xl font-bold mt-2">{{ $summary['totalUnits'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Unit barang tetap tercatat</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400/30 to-orange-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Ruang Terisi</p>
                        <p class="text-3xl font-bold mt-2">{{ $summary['ruangTerisi'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Ruang yang memiliki unit</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400/30 to-indigo-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Barang Tetap</p>
                        <p class="text-3xl font-bold mt-2">{{ $summary['barangTetap'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Jenis barang siap inventaris</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/70 border border-white/10 rounded-2xl shadow-lg shadow-indigo-500/10 backdrop-blur">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
                    <div>
                        <label class="text-sm font-semibold text-indigo-100 mb-2 block">Filter Ruang</label>
                        <select name="idruang" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            <option class="text-slate-900" value="">Semua Ruang</option>
                            @foreach($ruang as $r)
                                <option class="text-slate-900" value="{{ $r->idruang }}" @selected(request('idruang') == $r->idruang)>{{ $r->nama_ruang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-indigo-100 mb-2 block">Filter Barang</label>
                        <select name="idbarang" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            <option class="text-slate-900" value="">Semua Barang</option>
                            @foreach($barang as $b)
                                <option class="text-slate-900" value="{{ $b->idbarang }}" @selected(request('idbarang') == $b->idbarang)>{{ $b->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-end gap-3">
                        <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-3 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                            Terapkan Filter
                        </button>
                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.laporan', request()->only(['idruang','idbarang'])) }}"
                           class="w-full md:w-auto inline-flex items-center justify-center px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 text-slate-900 font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition">
                            Unduh Laporan
                        </a>
                    </div>
                </form>

                @if(request('idruang') || request('idbarang'))
                    <div class="px-6 pb-4 flex flex-wrap gap-2 text-sm text-indigo-100">
                        <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10">Filter aktif:</span>
                        @if(request('idruang'))
                            <span class="px-3 py-1 rounded-full bg-indigo-500/30 border border-indigo-200/30">Ruang: {{ $ruang->firstWhere('idruang', request('idruang'))?->nama_ruang ?? 'Dipilih' }}</span>
                        @endif
                        @if(request('idbarang'))
                            <span class="px-3 py-1 rounded-full bg-sky-500/30 border border-sky-200/30">Barang: {{ $barang->firstWhere('idbarang', request('idbarang'))?->nama_barang ?? 'Dipilih' }}</span>
                        @endif
                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.index') }}" class="px-3 py-1 rounded-full bg-white/10 border border-white/10 hover:bg-white/20 transition">Reset</a>
                    </div>
                @endif
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Kode Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Ruang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">No. Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($units as $unit)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 text-sm font-semibold text-white">
                                        <span class="px-3 py-1 rounded-lg bg-white/10 border border-white/10 inline-block">{{ $unit->kode_unit }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-indigo-50">{{ $unit->barang->nama_barang ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-50">{{ $unit->ruang->nama_ruang ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-50">Unit ke-{{ $unit->nomor_unit }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $unit->keterangan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <form action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.destroy', $unit) }}" method="POST" onsubmit="return confirm('Hapus unit ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-rose-500/80 hover:bg-rose-500 text-white shadow-md shadow-rose-500/30 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v2H5m14 0H5" />
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-sm text-indigo-100/80">
                                        <p class="text-lg font-semibold">Belum ada data inventaris ruang</p>
                                        <p class="text-sm text-indigo-100/70 mt-1">Catat unit baru untuk mulai memetakan aset per ruang.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100">
                    {{ $units->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
