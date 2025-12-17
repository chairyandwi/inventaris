@extends('layouts.app')

@section('title', 'Tambah Inventaris Ruang')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden pb-10">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-24 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-28">
            <div class="flex items-center gap-3 text-indigo-100">
                <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.index') }}" class="inline-flex items-center text-sm font-semibold hover:text-white transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <span class="text-sm opacity-70">/</span>
                <span class="text-sm opacity-80">Tambah Inventaris Ruang</span>
            </div>
            <h1 class="mt-3 text-3xl sm:text-4xl font-bold leading-tight text-white">Catat Inventaris Ruang</h1>
            <p class="my-4 text-indigo-100/80 max-w-2xl">Masukkan barang tetap beserta ruang penempatan dan jumlah unit. Kode unit akan dibuat otomatis mengikuti format inventaris.</p>
        </div>
    </div>

    <div class="relative -mt-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-2xl shadow-indigo-500/20 backdrop-blur p-8">
                <form action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Barang Tetap</label>
                            <select name="idbarang" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" required>
                                <option class="text-slate-900" value="">Pilih barang tetap</option>
                                @foreach($barang as $item)
                                    <option class="text-slate-900" value="{{ $item->idbarang }}" @selected(old('idbarang') == $item->idbarang)>
                                        {{ $item->nama_barang }} ({{ $item->kode_barang }})
                                    </option>
                                @endforeach
                            </select>
                            @error('idbarang')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Ruang</label>
                            <select name="idruang" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" required>
                                <option class="text-slate-900" value="">Pilih ruang</option>
                                @foreach($ruang as $r)
                                    <option class="text-slate-900" value="{{ $r->idruang }}" @selected(old('idruang') == $r->idruang)>
                                        {{ $r->nama_ruang }} • Gedung {{ $r->nama_gedung }}{{ $r->nama_lantai ? ' Lt. '.$r->nama_lantai : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idruang')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Jumlah Unit</label>
                            <input type="number" name="jumlah" min="1" max="100" value="{{ old('jumlah', 1) }}"
                                class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" required>
                            @error('jumlah')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Catatan / Keterangan</label>
                            <textarea name="keterangan" rows="3" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400" placeholder="Opsional">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.index') }}" class="px-5 py-3 rounded-xl border border-white/15 text-sm font-semibold text-indigo-50 hover:bg-white/10 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-3 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                            Simpan Inventaris
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
