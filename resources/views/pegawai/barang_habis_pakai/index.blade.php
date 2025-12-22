@extends('layouts.app')

@section('title', 'Barang Habis Pakai')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Barang Habis Pakai</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Pantau stok barang habis pakai</h1>
                    <p class="mt-3 text-indigo-50/90 max-w-2xl">Kelola stok tissue, spidol, dan perlengkapan sejenis yang keluar setelah disetujui petugas.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-8">
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Jenis</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalJenis'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Barang habis pakai terdaftar</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400/30 to-indigo-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Stok</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalStok'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Unit tersedia</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/70 border border-white/10 rounded-2xl shadow-lg shadow-indigo-500/10 backdrop-blur">
                <form method="GET" class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <input type="text" name="search"
                            class="w-full md:w-72 rounded-xl bg-slate-800/60 border border-white/10 text-white px-4 py-2 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                            placeholder="Cari kode / nama / keterangan..." value="{{ request('search') }}">
                    </div>
                    <div class="flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                            Terapkan
                        </button>
                        <a href="{{ route($routePrefix . '.barang-habis-pakai.index') }}"
                           class="px-4 py-2 rounded-xl border border-white/15 text-sm font-semibold text-indigo-50 hover:bg-white/10 transition">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full table-fixed divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide w-1/5">Kode</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide w-2/5">Nama Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide w-2/5">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide w-24">Stok</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($barang as $item)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 text-sm font-mono text-white truncate">{{ $item->kode_barang }}</td>
                                    <td class="px-6 py-4 text-sm text-white truncate">{{ $item->nama_barang }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80 truncate">{{ $item->kategori?->nama_kategori ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-left text-indigo-50">{{ $item->stok }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-sm text-indigo-100/80">
                                        Belum ada barang habis pakai.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100 flex items-center justify-between">
                    <div>
                        Menampilkan {{ $barang->firstItem() ?? 0 }} - {{ $barang->lastItem() ?? 0 }} dari {{ $barang->total() }} barang
                    </div>
                    <div class="text-white">
                        {{ $barang->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
