@extends('layouts.app')

@section('title', 'Riwayat Pemindahan Inventaris')

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
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Inventaris Ruang</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Riwayat Pemindahan Unit</h1>
                    <p class="mt-3 text-indigo-50/90 max-w-2xl">Pantau perpindahan unit antar ruang untuk kebutuhan audit dan laporan.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-white/20 text-white/90 font-semibold hover:bg-white/10 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <button type="submit"
                        form="riwayat-filter"
                        formaction="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.riwayat.cetak') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                        </svg>
                        Cetak PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="relative -mt-6 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/70 border border-white/10 rounded-2xl shadow-lg shadow-indigo-500/10 backdrop-blur">
                <form id="riwayat-filter" method="GET" action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.riwayat') }}" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Barang</label>
                            <select name="idbarang" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option class="text-slate-900" value="">Semua Barang</option>
                                @foreach($barang as $b)
                                    <option class="text-slate-900" value="{{ $b->idbarang }}" @selected(request('idbarang') == $b->idbarang)>{{ $b->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Ruang Asal</label>
                            <select name="idruang_asal" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option class="text-slate-900" value="">Semua Ruang</option>
                                @foreach($ruang as $r)
                                    <option class="text-slate-900" value="{{ $r->idruang }}" @selected(request('idruang_asal') == $r->idruang)>{{ $r->nama_ruang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Ruang Tujuan</label>
                            <select name="idruang_tujuan" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option class="text-slate-900" value="">Semua Ruang</option>
                                @foreach($ruang as $r)
                                    <option class="text-slate-900" value="{{ $r->idruang }}" @selected(request('idruang_tujuan') == $r->idruang)>{{ $r->nama_ruang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Tanggal Awal</label>
                            <input type="date" name="start_date" value="{{ request('start_date') }}"
                                class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Tanggal Akhir</label>
                            <input type="date" name="end_date" value="{{ request('end_date') }}"
                                class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2 bg-slate-800/60 border border-white/10 rounded-xl px-3 py-2">
                            <label class="text-sm font-semibold text-indigo-100">Tampil</label>
                            <select name="per_page" class="select-perpage rounded-lg bg-slate-900/60 border border-white/10 text-white px-2 py-1 pr-7 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                @foreach([10,15,25,50,100] as $pp)
                                    <option class="text-slate-900" value="{{ $pp }}" @selected(request('per_page',15)==$pp)>{{ $pp }}</option>
                                @endforeach
                            </select>
                            <span class="text-sm text-indigo-100/80">data</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                                Terapkan
                            </button>
                            <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.riwayat') }}"
                                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-white/15 text-sm font-semibold text-indigo-50 hover:bg-white/10 transition">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-[1000px] w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Unit</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Ruang Asal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Ruang Tujuan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Petugas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($moves as $index => $move)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 text-sm text-indigo-50">{{ $moves->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 text-sm text-white">{{ $move->barangUnit?->kode_unit ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-white">{{ $move->barang?->nama_barang ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $move->ruangAsal?->nama_ruang ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $move->ruangTujuan?->nama_ruang ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $move->moved_at?->format('d-m-Y H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $move->petugas?->nama ?? $move->petugas?->username ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-16 text-center text-sm text-indigo-100/70">
                                        <div class="flex flex-col items-center gap-3">
                                            <div class="h-12 w-12 rounded-full bg-white/10 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M3 12h18M3 17h9" />
                                                </svg>
                                            </div>
                                            <p class="text-base font-semibold text-white">Belum ada riwayat pemindahan</p>
                                            <p>Riwayat akan muncul setelah ada pemindahan unit.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100 flex items-center justify-between">
                    <div>
                        Menampilkan {{ $moves->firstItem() ?? 0 }} - {{ $moves->lastItem() ?? 0 }} dari {{ $moves->total() }} entri
                    </div>
                    <div class="text-white">
                        {{ $moves->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .select-perpage {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 2.5rem;
        background-image: url("data:image/svg+xml,%3Csvg fill='none' stroke='%23cbd5e1' stroke-width='2' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.65rem center;
        background-size: 0.9rem;
    }
</style>
@endpush
