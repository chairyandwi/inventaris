@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
@php
    $routePrefix = request()->routeIs('admin.*') ? 'admin.' : 'pegawai.';
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-[420px] bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12 space-y-6">
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route($routePrefix . 'barang.index') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Barang</p>
                    <h1 class="text-3xl font-bold leading-tight mt-2">Tambah barang</h1>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <form action="{{ route($routePrefix . 'barang.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div class="space-y-6">
                        <!-- Kode Barang -->
                        <div>
                            <label for="kode_barang" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Kode Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="kode_barang" 
                                   name="kode_barang" 
                                   value="{{ old('kode_barang') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('kode_barang') ? 'border-rose-400' : '' }}"
                                   placeholder="Masukkan kode unik barang"
                                   maxlength="20"
                                   required>
                            @error('kode_barang')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Barang -->
                        <div>
                            <label for="nama_barang" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Nama Barang <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="nama_barang" 
                                   name="nama_barang" 
                                   value="{{ old('nama_barang') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('nama_barang') ? 'border-rose-400' : '' }}"
                                   placeholder="Masukkan nama barang"
                                   maxlength="100"
                                   required>
                            @error('nama_barang')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stok otomatis -->
                        <div class="p-4 rounded-xl bg-white/5 border border-white/10 text-sm text-indigo-50">
                            Stok akan dihitung otomatis dari transaksi <span class="font-semibold text-white">Barang Masuk</span> dan peminjaman. Tidak perlu mengisi atau membagi stok di sini.
                        </div>

                        <!-- Kategori -->
                        <div>
                            <label for="idkategori" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="idkategori" 
                                    name="idkategori" 
                                    class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('idkategori') ? 'border-rose-400' : '' }}"
                                    required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->idkategori }}" 
                                        {{ old('idkategori') == $kat->idkategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idkategori')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Keterangan -->
                        <div>
                            <label for="keterangan" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Keterangan
                            </label>
                            <textarea id="keterangan" 
                                      name="keterangan" 
                                      rows="4"
                                      class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('keterangan') ? 'border-rose-400' : '' }}"
                                      placeholder="Masukkan keterangan barang (opsional)"
                                      maxlength="500">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-white/5 border-t border-white/10 flex items-center justify-end gap-3 -mx-6 -mb-6 rounded-b-2xl">
                        <a href="{{ route($routePrefix . 'barang.index') }}" 
                           class="px-4 py-2 rounded-xl border border-white/10 text-sm text-indigo-100 hover:bg-white/10 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-indigo-700 text-sm font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
