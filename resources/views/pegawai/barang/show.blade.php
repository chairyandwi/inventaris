@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
@php
    $routePrefix = request()->routeIs('admin.*') ? 'admin.' : 'pegawai.';
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-10 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
            <div class="flex items-center gap-3 text-sm text-indigo-100/90">
                <a href="{{ route($routePrefix . 'barang.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 hover:bg-white/20 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali ke daftar
                </a>
                <span class="text-xs uppercase tracking-[0.25em] text-indigo-100/70">Detail Barang</span>
            </div>

            <div class="mt-6 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-indigo-100/70">Inventaris</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">{{ $barang->nama_barang }}</h1>
                    <p class="mt-3 text-indigo-50/90 max-w-2xl">Pantau informasi lengkap barang, distribusi unit, dan detail ruang dengan tampilan yang konsisten dengan tema futuristik.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 border border-white/10 text-indigo-100">
                        <span class="text-xs uppercase tracking-[0.25em] text-indigo-100/70">Kode</span>
                        <span class="font-semibold text-white">{{ $barang->kode_barang }}</span>
                    </span>
                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 border border-white/10 text-indigo-100">
                        <span class="text-xs uppercase tracking-[0.25em] text-indigo-100/70">Kategori</span>
                        <span class="font-semibold text-white">{{ $barang->kategori->nama_kategori ?? '-' }}</span>
                    </span>
                    @php
                        $jenis = $effectiveJenis;
                        if ($jenis === 'tetap') {
                            $jenisLabel = 'Barang Tetap';
                            $jenisClass = 'bg-indigo-500/20 border-indigo-300/30 text-indigo-100';
                        } elseif ($jenis === 'pinjam') {
                            $jenisLabel = 'Barang Pinjam';
                            $jenisClass = 'bg-emerald-500/20 border-emerald-300/30 text-emerald-100';
                        } else {
                            $jenisLabel = 'Jenis Belum Ditentukan';
                            $jenisClass = 'bg-slate-500/20 border-slate-300/30 text-slate-100';
                        }
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-semibold border {{ $jenisClass }}">
                        {{ $jenisLabel }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400/30 to-orange-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Stok Tersedia</p>
                        <p class="text-3xl font-bold mt-2">{{ $barang->stok }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Unit siap dipinjam atau ditempatkan</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400/30 to-indigo-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Unit</p>
                        <p class="text-3xl font-bold mt-2">{{ $units->count() }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Unit terekam di sistem</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                    <div class="relative">
                    @php
                        $jenis = $effectiveJenis;
                        $stokValue = (int) $barang->stok;
                        if ($jenis === 'pinjam') {
                            $stokPinjam = $stokValue;
                            $notePinjam = $stokValue > 0
                                ? 'Barang bisa dipinjam.'
                                    : 'Stok kosong, belum bisa dipinjam.';
                            } elseif ($jenis === 'tetap') {
                                $stokPinjam = 0;
                                $notePinjam = 'Barang tetap difokuskan untuk inventaris ruang.';
                            } else {
                                $stokPinjam = 0;
                                $notePinjam = 'Jenis barang belum ditetapkan, atur saat barang masuk.';
                            }
                        @endphp
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Stok Bisa Dipinjam</p>
                        <p class="text-2xl font-bold text-white mt-2">{{ $stokPinjam }}</p>
                        <p class="text-sm text-indigo-50/90 mt-2 leading-relaxed">{{ $notePinjam }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @php
                $isPinjam = $effectiveJenis === 'pinjam';
                $merkCards = $merkStats ?? collect();
            @endphp
            @if($isPinjam || $merkCards->isNotEmpty())
                <div class="bg-slate-900/70 border border-white/10 rounded-2xl shadow-lg shadow-indigo-500/10 backdrop-blur p-6">
                    @php
                        if ($isPinjam) {
                            $statusPinjam = $dipinjamQty > 0 ? 'Sedang Dipinjam' : ($availablePinjam > 0 ? 'Dapat Dipinjam' : 'Stok Kosong');
                            $statusNote = $dipinjamQty > 0 ? 'Sebagian unit sedang dipinjam.' : ($availablePinjam > 0 ? 'Unit tersedia untuk dipinjam.' : 'Belum ada unit yang bisa dipinjam.');
                        } elseif ($effectiveJenis === 'tetap') {
                            $statusPinjam = 'Tidak Bisa Dipinjam';
                            $statusNote = 'Barang tetap dicatat sebagai inventaris ruang.';
                        } else {
                            $statusPinjam = 'Belum Ditentukan';
                            $statusNote = 'Jenis barang belum ditetapkan.';
                        }
                        $stokPinjam = $isPinjam ? $availablePinjam : 0;
                    @endphp
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-white">Barang Bisa Dipinjam</h2>
                        <span class="text-sm text-indigo-100/80">Stok tersedia: {{ $stokPinjam }}</span>
                    </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if($stokPinjam > 0)
                        @forelse($merkCards as $card)
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-indigo-100/70">Merk</p>
                                <p class="text-lg font-semibold text-white mt-2">{{ $card['merk'] }}</p>
                                <p class="text-sm text-indigo-100/70 mt-2">Kondisi: {{ $card['kondisi'] }}</p>
                                <p class="text-sm text-indigo-100/70">Jumlah masuk: {{ $card['total_masuk'] }}</p>
                            </div>
                        @empty
                            <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                                <p class="text-xs uppercase tracking-[0.2em] text-indigo-100/70">Merk</p>
                                <p class="text-lg font-semibold text-white mt-2">Belum diisi</p>
                                <p class="text-sm text-indigo-100/70 mt-2">Tambahkan merk di transaksi barang masuk.</p>
                            </div>
                        @endforelse
                    @endif
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.2em] text-indigo-100/70">Status Peminjaman</p>
                        <p class="text-lg font-semibold text-white mt-2">{{ $statusPinjam }}</p>
                        <p class="text-sm text-indigo-100/70 mt-2">{{ $statusNote }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-slate-900/70 border border-white/10 rounded-2xl shadow-lg shadow-indigo-500/10 backdrop-blur p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-white">Ringkasan Per Ruang</h2>
                    <span class="text-sm text-indigo-100/80">Total Unit: {{ $units->count() }}</span>
                </div>
                @if($distribution->isEmpty())
                    <p class="text-sm text-indigo-100/70">Belum ada inventaris tercatat untuk barang ini.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($distribution as $entry)
                            <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/5 p-4">
                                <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-70"></div>
                                <div class="relative">
                                    <p class="text-xs uppercase tracking-[0.2em] text-indigo-100/70">Ruang</p>
                                    <p class="text-base font-semibold text-white mt-1">{{ $entry['ruang'] }}</p>
                                    <p class="text-xs text-indigo-100/60 mb-3">Gedung {{ $entry['gedung'] }}</p>
                                    <p class="text-sm text-indigo-100/80">Jumlah unit</p>
                                    <p class="text-2xl font-bold text-amber-200">{{ $entry['jumlah'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($units->isNotEmpty())
                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                    <div class="flex items-center justify-between p-6 border-b border-white/10">
                        <div>
                            <h2 class="text-lg font-semibold text-white">Detail Unit</h2>
                            <p class="text-sm text-indigo-100/70 mt-1">{{ $units->count() }} unit tercatat</p>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Kode Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Ruang</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Gedung</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Nomor</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($units as $unit)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 font-semibold text-white">{{ $unit->kode_unit }}</td>
                                        <td class="px-6 py-4 text-indigo-50">{{ $unit->ruang->nama_ruang ?? '-' }}</td>
                                        <td class="px-6 py-4 text-indigo-50">{{ $unit->ruang->nama_gedung ?? '-' }}</td>
                                        <td class="px-6 py-4 text-indigo-50">{{ $unit->nomor_unit }}</td>
                                        <td class="px-6 py-4 text-indigo-100/80 text-sm">{{ $unit->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
