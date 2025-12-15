@extends('layouts.app')

@section('title', 'Tambah Inventaris Ruang')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
@endphp
<div class="pt-24 container mx-auto px-4 py-6 min-h-screen">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Catat Inventaris Ruang</h1>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-8 max-w-4xl">
        <form action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Barang Tetap</label>
                <select name="idbarang" class="w-full border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500" required>
                    <option value="">-- Pilih Barang Tetap --</option>
                    @foreach($barang as $item)
                        <option value="{{ $item->idbarang }}" @selected(old('idbarang') == $item->idbarang)>
                            {{ $item->nama_barang }} (Kode: {{ $item->kode_barang }})
                        </option>
                    @endforeach
                </select>
                @error('idbarang')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ruang</label>
                    <select name="idruang" class="w-full border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">-- Pilih Ruang --</option>
                        @foreach($ruang as $r)
                            <option value="{{ $r->idruang }}" @selected(old('idruang') == $r->idruang)>
                                {{ $r->nama_ruang }} (Gedung {{ $r->nama_gedung }})
                            </option>
                        @endforeach
                    </select>
                    @error('idruang')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Unit</label>
                    <input type="number" name="jumlah" min="1" max="100" value="{{ old('jumlah', 1) }}"
                        class="w-full border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500" required>
                    @error('jumlah')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan / Keterangan</label>
                <textarea name="keterangan" rows="3" class="w-full border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500" placeholder="Opsional">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.index') }}" class="px-5 py-3 border border-gray-300 rounded-xl text-sm text-gray-600 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-5 py-3 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700">
                    Simpan Inventaris
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
