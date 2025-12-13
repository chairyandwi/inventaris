@extends('layouts.app')

@section('title', 'Tambah Barang Masuk')

@section('content')
<div class="pt-24 container mx-auto px-4 py-6 min-h-screen">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('pegawai.barang_masuk.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Tambah Barang Masuk</h1>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <form action="{{ route('pegawai.barang_masuk.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="idbarang">Pilih Barang</label>
                    <select name="idbarang" id="idbarang" required
                        class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $item)
                            <option value="{{ $item->idbarang }}" @selected(old('idbarang') == $item->idbarang)>
                                {{ $item->kode_barang }} - {{ $item->nama_barang }} ({{ $item->kategori->nama_kategori ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    @error('idbarang')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="tgl_masuk">Tanggal Masuk</label>
                    <input type="date" name="tgl_masuk" id="tgl_masuk" value="{{ old('tgl_masuk', now()->toDateString()) }}"
                        class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    @error('tgl_masuk')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="jumlah">Jumlah Masuk</label>
                    <input type="number" name="jumlah" id="jumlah" min="1" value="{{ old('jumlah', 1) }}"
                        class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                    @error('jumlah')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="status_barang">Status Barang</label>
                    <select name="status_barang" id="status_barang"
                        class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500" required>
                        <option value="baru" @selected(old('status_barang') === 'baru')>Baru</option>
                        <option value="bekas" @selected(old('status_barang') === 'bekas')>Bekas</option>
                    </select>
                    @error('status_barang')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="border border-dashed border-indigo-200 rounded-xl p-4 bg-indigo-50/40">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-800">Detail PC/Laptop (wajib untuk perangkat PC)</p>
                        <p class="text-xs text-gray-500">Centang jika barang adalah PC/Laptop, lalu isi spesifikasi utamanya.</p>
                    </div>
                    <label class="inline-flex items-center text-sm font-medium text-gray-700">
                        <input type="checkbox" id="is_pc" name="is_pc" value="1" class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                            {{ old('is_pc') ? 'checked' : '' }}>
                        <span class="ml-2">Perangkat PC/Laptop</span>
                    </label>
                </div>

                <div id="pc-specs" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4 {{ old('is_pc') ? '' : 'hidden' }}">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="ram_capacity_gb">RAM (GB)</label>
                        <input type="number" min="1" name="ram_capacity_gb" id="ram_capacity_gb" value="{{ old('ram_capacity_gb') }}"
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        @error('ram_capacity_gb')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="ram_brand">Merek RAM</label>
                        <input type="text" name="ram_brand" id="ram_brand" value="{{ old('ram_brand') }}"
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        @error('ram_brand')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="storage_type">Jenis Storage</label>
                        <select name="storage_type" id="storage_type"
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">-- Pilih --</option>
                            <option value="SSD" @selected(old('storage_type') === 'SSD')>SSD</option>
                            <option value="HDD" @selected(old('storage_type') === 'HDD')>HDD</option>
                        </select>
                        @error('storage_type')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="storage_capacity_gb">Kapasitas Storage (GB)</label>
                        <input type="number" min="1" name="storage_capacity_gb" id="storage_capacity_gb" value="{{ old('storage_capacity_gb') }}"
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        @error('storage_capacity_gb')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2" for="processor">Prosesor</label>
                        <input type="text" name="processor" id="processor" value="{{ old('processor') }}"
                            class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                        @error('processor')
                            <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @error('spesifikasi')
                    <p class="text-sm text-red-500 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="keterangan">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" rows="3"
                        class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Catatan kondisi / lokasi penempatan">{{ old('keterangan') }}</textarea>
                    @error('keterangan')
                        <p class="text-sm text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('pegawai.barang_masuk.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 shadow">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const isPcCheckbox = document.getElementById('is_pc');
    const pcSpecs = document.getElementById('pc-specs');
    if (isPcCheckbox) {
        isPcCheckbox.addEventListener('change', () => {
            pcSpecs.classList.toggle('hidden', !isPcCheckbox.checked);
        });
    }
</script>
@endsection
