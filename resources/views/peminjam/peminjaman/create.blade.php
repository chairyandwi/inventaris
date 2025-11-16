@extends('layouts.app')

@section('content')
<div class="pt-24 min-h-screen bg-gray-50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Ajukan Peminjaman Barang</h1>
                    <p class="text-sm text-gray-500 mt-1">Lengkapi form berikut untuk mengirim permintaan peminjaman.</p>
                </div>
                <a href="{{ route('peminjam.peminjaman.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                    Lihat Riwayat
                </a>
            </div>

            @if(session('error'))
                <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('peminjam.peminjaman.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="idbarang" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Barang</label>
                    <select name="idbarang" id="idbarang"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $item)
                            <option value="{{ $item->idbarang }}" @selected(old('idbarang') == $item->idbarang)>
                                {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                            </option>
                        @endforeach
                    </select>
                    @error('idbarang')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="idruang" class="block text-sm font-semibold text-gray-700 mb-2">Lokasi / Ruang Penggunaan</label>
                    <select name="idruang" id="idruang"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="">-- Pilih Ruang --</option>
                        @foreach($ruang as $item)
                            <option value="{{ $item->idruang }}" @selected(old('idruang') == $item->idruang)>
                                {{ $item->nama_ruang }}
                            </option>
                        @endforeach
                    </select>
                    @error('idruang')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jumlah" class="block text-sm font-semibold text-gray-700 mb-2">Jumlah Barang</label>
                    <input type="number" min="1" name="jumlah" id="jumlah"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Masukkan jumlah yang dibutuhkan"
                        value="{{ old('jumlah', 1) }}" required>
                    @error('jumlah')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Pastikan jumlah tidak melebihi stok barang yang tersedia.</p>
                </div>

                <div class="pt-4 flex items-center justify-between">
                    <a href="{{ route('peminjam.peminjaman.index') }}"
                       class="text-sm text-gray-600 hover:text-gray-800 underline">Batal & kembali</a>
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl shadow hover:bg-indigo-700 transition">
                        Kirim Pengajuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
