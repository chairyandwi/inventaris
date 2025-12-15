@extends('layouts.app')

@section('title', 'Edit Barang Masuk')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12 space-y-6">
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route(($routePrefix ?? 'pegawai') . '.barang_masuk.index') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Barang Masuk</p>
                    <h1 class="text-3xl font-bold leading-tight mt-2">Edit barang masuk</h1>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <form action="{{ route(($routePrefix ?? 'pegawai') . '.barang_masuk.update', $barangMasuk->idbarang_masuk) }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2" for="idbarang">Pilih Barang</label>
                            <select name="idbarang" id="idbarang" required
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent {{ $errors->has('idbarang') ? 'border-rose-400' : '' }}">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->idbarang }}" data-jenis="{{ $item->jenis_barang ?? 'pinjam' }}" @selected(old('idbarang', $barangMasuk->idbarang) == $item->idbarang)>
                                        {{ $item->kode_barang }} - {{ $item->nama_barang }} ({{ $item->kategori->nama_kategori ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('idbarang')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2" for="jenis_barang">Jenis Barang</label>
                            <select name="jenis_barang" id="jenis_barang"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent {{ $errors->has('jenis_barang') ? 'border-rose-400' : '' }}">
                                <option value="pinjam" {{ old('jenis_barang', $barangMasuk->jenis_barang) === 'pinjam' ? 'selected' : '' }}>Barang Pinjam (bisa dipinjam)</option>
                                <option value="tetap" {{ old('jenis_barang', $barangMasuk->jenis_barang) === 'tetap' ? 'selected' : '' }}>Barang Tetap (hak unit, tidak dipinjam)</option>
                            </select>
                            <p class="text-xs text-indigo-100/70 mt-1">Nilai default mengikuti jenis barang terpilih, bisa disesuaikan.</p>
                            @error('jenis_barang')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2" for="tgl_masuk">Tanggal Masuk</label>
                            <input type="date" name="tgl_masuk" id="tgl_masuk" value="{{ old('tgl_masuk', $barangMasuk->tgl_masuk ?? now()->toDateString()) }}"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent {{ $errors->has('tgl_masuk') ? 'border-rose-400' : '' }}" required>
                            @error('tgl_masuk')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2" for="jumlah">Jumlah Masuk</label>
                            <input type="number" name="jumlah" id="jumlah" min="1" value="{{ old('jumlah', $barangMasuk->jumlah) }}"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent {{ $errors->has('jumlah') ? 'border-rose-400' : '' }}" required>
                            @error('jumlah')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2" for="status_barang">Status Barang</label>
                            <select name="status_barang" id="status_barang"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent {{ $errors->has('status_barang') ? 'border-rose-400' : '' }}" required>
                                <option value="baru" @selected(old('status_barang', $barangMasuk->status_barang) === 'baru')>Baru</option>
                                <option value="bekas" @selected(old('status_barang', $barangMasuk->status_barang) === 'bekas')>Bekas</option>
                            </select>
                            @error('status_barang')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="border border-white/10 rounded-xl p-5 bg-white/5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-white">Detail PC/Laptop (wajib untuk perangkat PC)</p>
                                <p class="text-xs text-indigo-100/80">Centang jika barang adalah PC/Laptop, lalu isi spesifikasi utamanya.</p>
                            </div>
                            <label class="inline-flex items-center text-sm font-medium text-indigo-100">
                                <input type="checkbox" id="is_pc" name="is_pc" value="1" class="h-4 w-4 text-indigo-400 border-white/20 rounded bg-slate-800/70"
                                    {{ old('is_pc', $barangMasuk->is_pc) ? 'checked' : '' }}>
                                <span class="ml-2">Perangkat PC/Laptop</span>
                            </label>
                        </div>

                        @php
                            $showSpecs = old('is_pc', $barangMasuk->is_pc);
                        @endphp
                        <div id="pc-specs" class="grid grid-cols-1 md:grid-cols-2 lg/grid-cols-3 gap-4 mt-4 {{ $showSpecs ? '' : 'hidden' }}">
                            <div>
                                <label class="block text-sm font-semibold text-indigo-100 mb-2" for="ram_capacity_gb">RAM (GB)</label>
                                <input type="number" min="1" name="ram_capacity_gb" id="ram_capacity_gb" value="{{ old('ram_capacity_gb', $barangMasuk->ram_capacity_gb) }}"
                                    class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent">
                                @error('ram_capacity_gb')
                                    <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-indigo-100 mb-2" for="ram_brand">Merek RAM</label>
                                <input type="text" name="ram_brand" id="ram_brand" value="{{ old('ram_brand', $barangMasuk->ram_brand) }}"
                                    class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent">
                                @error('ram_brand')
                                    <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-indigo-100 mb-2" for="storage_type">Jenis Storage</label>
                                <select name="storage_type" id="storage_type"
                                    class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent">
                                    <option value="">-- Pilih --</option>
                                    <option value="SSD" @selected(old('storage_type', $barangMasuk->storage_type) === 'SSD')>SSD</option>
                                    <option value="HDD" @selected(old('storage_type', $barangMasuk->storage_type) === 'HDD')>HDD</option>
                                </select>
                                @error('storage_type')
                                    <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-indigo-100 mb-2" for="storage_capacity_gb">Kapasitas Storage (GB)</label>
                                <input type="number" min="1" name="storage_capacity_gb" id="storage_capacity_gb" value="{{ old('storage_capacity_gb', $barangMasuk->storage_capacity_gb) }}"
                                    class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent">
                                @error('storage_capacity_gb')
                                    <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-indigo-100 mb-2" for="processor">Prosesor</label>
                                <input type="text" name="processor" id="processor" value="{{ old('processor', $barangMasuk->processor) }}"
                                    class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent">
                                @error('processor')
                                    <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        @error('spesifikasi')
                            <p class="text-sm text-rose-300 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2" for="keterangan">Keterangan</label>
                            <textarea name="keterangan" id="keterangan" rows="3"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent"
                                placeholder="Catatan kondisi / lokasi penempatan">{{ old('keterangan', $barangMasuk->keterangan) }}</textarea>
                            @error('keterangan')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.barang_masuk.index') }}" class="px-4 py-2 rounded-xl border border-white/10 text-indigo-100 text-sm hover:bg-white/10 transition">
                            Batal
                        </a>
                        <button type="submit" class="px-4 py-2 rounded-xl bg-white text-indigo-700 text-sm font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
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

    const selectBarang = document.getElementById('idbarang');
    const jenisBarang = document.getElementById('jenis_barang');

    function syncJenisFromBarang() {
        const selected = selectBarang.options[selectBarang.selectedIndex];
        if (selected?.dataset.jenis) {
            jenisBarang.value = selected.dataset.jenis;
        }
    }

    syncJenisFromBarang();
    selectBarang?.addEventListener('change', syncJenisFromBarang);
</script>
@endsection
