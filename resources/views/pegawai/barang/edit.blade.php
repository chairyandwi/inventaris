@extends('layouts.app')

@section('title', 'Edit Barang')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route('pegawai.barang.index') }}" 
               class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Edit Barang</h1>
        </div>
    </div>

    <!-- Card wrapper -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route('pegawai.barang.update', $barang->idbarang) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="px-6 py-4">
                <!-- Kode Barang -->
                <div class="mb-6">
                    <label for="kode_barang" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="kode_barang" 
                           name="kode_barang" 
                           value="{{ old('kode_barang', $barang->kode_barang) }}"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            {{ $errors->has('kode_barang') ? 'border-red-500' : 'border-gray-300' }}"
                           maxlength="20"
                           required>
                    @error('kode_barang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama Barang -->
                <div class="mb-6">
                    <label for="nama_barang" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama_barang" 
                           name="nama_barang" 
                           value="{{ old('nama_barang', $barang->nama_barang) }}"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            {{ $errors->has('nama_barang') ? 'border-red-500' : 'border-gray-300' }}"
                           maxlength="100"
                           required>
                    @error('nama_barang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Stok -->
                <div class="mb-6">
                    <label for="stok" class="block text-sm font-medium text-gray-700 mb-2">
                        Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           id="stok" 
                           name="stok" 
                           value="{{ old('stok', $barang->stok) }}"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            {{ $errors->has('stok') ? 'border-red-500' : 'border-gray-300' }}"
                           min="0"
                           required>
                    @error('stok')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div class="mb-6">
                    <label for="idkategori" class="block text-sm font-medium text-gray-700 mb-2">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="idkategori" 
                            name="idkategori" 
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                {{ $errors->has('idkategori') ? 'border-red-500' : 'border-gray-300' }}"
                            required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $kat)
                            <option value="{{ $kat->idkategori }}" 
                                {{ old('idkategori', $barang->idkategori) == $kat->idkategori ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('idkategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Keterangan -->
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea id="keterangan" 
                              name="keterangan" 
                              rows="4"
                              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            {{ $errors->has('keterangan') ? 'border-red-500' : 'border-gray-300' }}"
                              maxlength="500">{{ old('keterangan', $barang->keterangan) }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center space-x-3">
                    <a href="{{ route('pegawai.barang.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
