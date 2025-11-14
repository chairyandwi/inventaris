@extends('layouts.app')

@section('title', 'Edit Ruang')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-4">
                <a href="{{ route('pegawai.ruang.index') }}"
                    class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold text-gray-800">Edit Ruang</h1>
            </div>
        </div>

        <!-- Card wrapper -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <form action="{{ route('pegawai.ruang.update', $ruang->idruang) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="px-6 py-4">
                    <!-- Nama Ruang -->
                    <div class="mb-6">
                        <label for="nama_ruang" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Ruang <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_ruang" name="nama_ruang"
                            value="{{ old('nama_ruang', $ruang->nama_ruang) }}"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            {{ $errors->has('nama_ruang') ? 'border-red-500' : 'border-gray-300' }}"
                            placeholder="Masukkan nama ruang" maxlength="100" required>
                        @error('nama_ruang')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                        <!-- Nama Gedung (Dropdown) -->
                    <div class="mb-6">
                        <label for="nama_gedung" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Gedung <span class="text-red-500">*</span>
                        </label>
                        <select id="nama_gedung" 
                                name="nama_gedung" 
                                class="flex items-center w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                {{ $errors->has('nama_gedung') ? 'border-red-500' : 'border-gray-300' }}"
                                required>
                            <option value="">-- Pilih Gedung --</option>
                            <option value="Gedung A" {{ old('nama_gedung', $ruang->nama_gedung) == 'Gedung A' ? 'selected' : '' }}>Gedung A</option>
                            <option value="Gedung B" {{ old('nama_gedung', $ruang->nama_gedung) == 'Gedung B' ? 'selected' : '' }}>Gedung B</option>
                            <option value="Gedung C" {{ old('nama_gedung', $ruang->nama_gedung) == 'Gedung C' ? 'selected' : '' }}>Gedung C</option>
                            <option value="Gedung D" {{ old('nama_gedung', $ruang->nama_gedung) == 'Gedung D' ? 'selected' : '' }}>Gedung D</option>
                        </select>
                        @error('nama_gedung')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Lantai -->
                    <div class="mb-6">
                        <label for="nama_lantai" class="block text-sm font-medium text-gray-700 mb-2">
                            Lantai <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama_lantai" name="nama_lantai"
                            value="{{ old('nama_lantai', $ruang->nama_lantai) }}"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            {{ $errors->has('nama_lantai') ? 'border-red-500' : 'border-gray-300' }}"
                            placeholder="Masukkan nama lantai" maxlength="100" required>
                        @error('nama_lantai')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-6">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="4"
                            class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            {{ $errors->has('keterangan') ? 'border-red-500' : 'border-gray-300' }}"
                            placeholder="Masukkan keterangan ruang (opsional)" maxlength="255">{{ old('keterangan', $ruang->keterangan) }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('pegawai.ruang.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                </path>
                            </svg>
                            Update
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
