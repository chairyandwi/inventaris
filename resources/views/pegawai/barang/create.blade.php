@extends('layouts.app')

@section('title', 'Tambah Barang')

@section('content')
@php
    $routePrefix = request()->routeIs('admin.*') ? 'admin.' : 'pegawai.';
@endphp
<div class="pt-24 container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route($routePrefix . 'barang.index') }}" 
               class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Barang</h1>
        </div>
    </div>

    <!-- Card wrapper -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route($routePrefix . 'barang.store') }}" method="POST">
            @csrf
            
            <div class="px-6 py-4">
                <!-- Kode Barang -->
                <div class="mb-6">
                    <label for="kode_barang" class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Barang <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="kode_barang" 
                           name="kode_barang" 
                           value="{{ old('kode_barang') }}"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            {{ $errors->has('kode_barang') ? 'border-red-500' : 'border-gray-300' }}"
                           placeholder="Masukkan kode unik barang"
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
                           value="{{ old('nama_barang') }}"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            {{ $errors->has('nama_barang') ? 'border-red-500' : 'border-gray-300' }}"
                           placeholder="Masukkan nama barang"
                           maxlength="100"
                           required>
                    @error('nama_barang')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="jenis_barang" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Barang <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis_barang" name="jenis_barang"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent {{ $errors->has('jenis_barang') ? 'border-red-500' : 'border-gray-300' }}"
                        required>
                        <option value="pinjam" {{ old('jenis_barang') === 'pinjam' ? 'selected' : '' }}>Barang Pinjam (stok keluar-masuk)</option>
                        <option value="tetap" {{ old('jenis_barang') === 'tetap' ? 'selected' : '' }}>Barang Tetap (inventaris ruang)</option>
                    </select>
                    @error('jenis_barang')
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
                           value="{{ old('stok', 0) }}"
                           class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                            {{ $errors->has('stok') ? 'border-red-500' : 'border-gray-300' }}"
                           placeholder="Masukkan jumlah stok"
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
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                        Keterangan
                    </label>
                    <textarea id="keterangan" 
                              name="keterangan" 
                              rows="4"
                              class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                {{ $errors->has('keterangan') ? 'border-red-500' : 'border-gray-300' }}"
                              placeholder="Masukkan keterangan barang (opsional)"
                              maxlength="500">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="inventaris-fields" class="mb-6 border border-dashed border-indigo-200 rounded-xl p-5 bg-indigo-50/30 {{ old('jenis_barang', 'pinjam') === 'tetap' ? '' : 'hidden' }}">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-sm font-semibold text-indigo-700">Distribusi Inventaris per Ruang</p>
                        <button type="button" id="tambahDistribusi" class="text-xs text-indigo-600 hover:text-indigo-700 font-semibold">+ Tambah Ruang</button>
                    </div>
                    @error('distribusi_ruang.*')
                        <p class="text-sm text-red-600 mb-2">{{ $message }}</p>
                    @enderror
                    @error('distribusi_jumlah.*')
                        <p class="text-sm text-red-600 mb-2">{{ $message }}</p>
                    @enderror
                    @error('distribusi_catatan.*')
                        <p class="text-sm text-red-600 mb-2">{{ $message }}</p>
                    @enderror
                    <div id="listDistribusi" class="space-y-3">
                        @php
                            $oldDistribusi = collect(old('distribusi_ruang', [null]))->map(function($value, $index) {
                                return [
                                    'ruang' => $value,
                                    'jumlah' => old('distribusi_jumlah.' . $index, 1),
                                    'catatan' => old('distribusi_catatan.' . $index),
                                ];
                            });
                        @endphp
                        @foreach($oldDistribusi as $idx => $row)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 distribusi-item">
                                <div>
                                    <label class="text-xs text-gray-600">Ruang</label>
                                    <select name="distribusi_ruang[]" data-inventaris data-required="true" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                        <option value="">-- Pilih Ruang --</option>
                                        @foreach($ruang as $r)
                                            <option value="{{ $r->idruang }}" @selected($row['ruang'] == $r->idruang)>{{ $r->nama_ruang }} ({{ $r->nama_gedung }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600">Jumlah Unit</label>
                                    <input type="number" name="distribusi_jumlah[]" min="1" max="500" value="{{ $row['jumlah'] }}" data-inventaris data-required="true" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="text-xs text-gray-600">Catatan</label>
                                    <div class="flex items-center space-x-2">
                                        <input type="text" name="distribusi_catatan[]" value="{{ $row['catatan'] }}" data-inventaris class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Opsional">
                                        <button type="button" class="text-red-500 hover:text-red-600 text-xs remove-distribusi" title="Hapus">&times;</button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route('pegawai.barang.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const jenisSelect = document.getElementById('jenis_barang');
        const inventarisFields = document.getElementById('inventaris-fields');

        const listDistribusi = document.getElementById('listDistribusi');
        const tambahDistribusi = document.getElementById('tambahDistribusi');

        function applyInventarisState() {
            const aktif = jenisSelect.value === 'tetap';
            inventarisFields.classList.toggle('hidden', !aktif);

            listDistribusi.querySelectorAll('[data-inventaris]').forEach(el => {
                if (aktif) {
                    if (el.dataset.required === 'true') {
                        el.setAttribute('required', 'required');
                    }
                    el.removeAttribute('disabled');
                } else {
                    el.removeAttribute('required');
                    el.setAttribute('disabled', 'disabled');
                }
            });
        }

        function addDistribusiRow(data = {}) {
            const template = document.createElement('div');
            template.className = 'grid grid-cols-1 md:grid-cols-3 gap-3 distribusi-item';
            template.innerHTML = `
                <div>
                    <label class="text-xs text-gray-600">Ruang</label>
                    <select name="distribusi_ruang[]" data-inventaris data-required="true" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Pilih Ruang --</option>
                        @foreach($ruang as $r)
                            <option value="{{ $r->idruang }}">{{ $r->nama_ruang }} ({{ $r->nama_gedung }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs text-gray-600">Jumlah Unit</label>
                    <input type="number" name="distribusi_jumlah[]" min="1" max="500" value="1" data-inventaris data-required="true"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="text-xs text-gray-600">Catatan</label>
                    <div class="flex items-center space-x-2">
                        <input type="text" name="distribusi_catatan[]" data-inventaris class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" placeholder="Opsional">
                        <button type="button" class="text-red-500 hover:text-red-600 text-xs remove-distribusi">&times;</button>
                    </div>
                </div>
            `;

            listDistribusi.appendChild(template);

            if (data.ruang) template.querySelector('select').value = data.ruang;
            if (data.jumlah) template.querySelector('input[name="distribusi_jumlah[]"]').value = data.jumlah;
            if (data.catatan) template.querySelector('input[name="distribusi_catatan[]"]').value = data.catatan;

            applyInventarisState();
        }

        listDistribusi.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-distribusi')) {
                const item = e.target.closest('.distribusi-item');
                if (listDistribusi.children.length > 1) {
                    item.remove();
                } else {
                    item.querySelectorAll('select, input').forEach(el => el.value = '');
                    item.querySelector('input[name="distribusi_jumlah[]"]').value = 1;
                }
            }
        });

        tambahDistribusi.addEventListener('click', function () {
            addDistribusiRow();
        });

        if (listDistribusi.children.length === 0) {
            addDistribusiRow();
        }

        jenisSelect.addEventListener('change', applyInventarisState);
        applyInventarisState();
    });
</script>
@endsection
