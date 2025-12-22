@extends('layouts.app')

@section('title', 'Edit Barang Masuk')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
    $ruangOptions = $ruang ?? [];
    $oldDistribusi = collect(old('distribusi_ruang', []));
    if ($oldDistribusi->isEmpty() && isset($distribusi) && $distribusi->count()) {
        $oldDistribusi = $distribusi->pluck('idruang');
    }
    $oldDistribusi = $oldDistribusi->map(function ($value, $index) use ($distribusi) {
        $jumlah = old('distribusi_jumlah.' . $index);
        $catatan = old('distribusi_catatan.' . $index);
        if (is_null($jumlah) && isset($distribusi[$index])) {
            $jumlah = $distribusi[$index]['jumlah'] ?? 1;
        }
        if (is_null($catatan) && isset($distribusi[$index])) {
            $catatan = $distribusi[$index]['catatan'] ?? null;
        }
        return [
            'ruang' => $value,
            'jumlah' => $jumlah ?? 1,
            'catatan' => $catatan,
        ];
    });
    if ($oldDistribusi->isEmpty()) {
        $oldDistribusi = collect([['ruang' => null, 'jumlah' => $barangMasuk->jumlah ?? 1, 'catatan' => null]]);
    }
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
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2" for="idkategori">Kategori</label>
                            <select id="idkategori" class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent">
                                <option value="">-- Semua Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->idkategori }}" @selected(old('idkategori', optional($barangMasuk->barang)->idkategori) == $kat->idkategori)>{{ $kat->nama_kategori }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-indigo-100 mb-2" for="idbarang">Pilih Barang</label>
                            <select name="idbarang" id="idbarang" required
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent {{ $errors->has('idbarang') ? 'border-rose-400' : '' }}">
                                <option value="">-- Pilih Barang --</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->idbarang }}"
                                            data-kategori="{{ $item->idkategori }}"
                                            @selected(old('idbarang', $barangMasuk->idbarang) == $item->idbarang)>
                                        {{ $item->kode_barang }} - {{ $item->nama_barang }} ({{ $item->kategori->nama_kategori ?? '-' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('idbarang')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-sm font-semibold text-indigo-100 mb-2" for="jenis_barang">Jenis Barang</label>
                            <select name="jenis_barang" id="jenis_barang"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent {{ $errors->has('jenis_barang') ? 'border-rose-400' : '' }}">
                                <option value="pinjam" {{ old('jenis_barang', $barangMasuk->jenis_barang) === 'pinjam' ? 'selected' : '' }}>Barang Pinjam (bisa dipinjam)</option>
                                <option value="tetap" {{ old('jenis_barang', $barangMasuk->jenis_barang) === 'tetap' ? 'selected' : '' }}>Barang Tetap (hak unit, tidak dipinjam)</option>
                                <option value="habis_pakai" {{ old('jenis_barang', $barangMasuk->jenis_barang) === 'habis_pakai' ? 'selected' : '' }}>Barang Habis Pakai</option>
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

                    <div class="border border-white/10 rounded-xl p-5 bg-white/5 hidden" id="inventaris-fields">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm font-semibold text-white">Distribusi Barang Tetap per Ruang</p>
                                <p class="text-xs text-indigo-100/80">Atur ulang alokasi unit kepemilikan per ruang.</p>
                            </div>
                            <button type="button" id="tambahDistribusi" class="text-xs text-indigo-200 hover:text-white font-semibold">+ Tambah Ruang</button>
                        </div>
                        @error('distribusi_ruang.*')
                            <p class="text-sm text-rose-300 mb-2">{{ $message }}</p>
                        @enderror
                        @error('distribusi_jumlah.*')
                            <p class="text-sm text-rose-300 mb-2">{{ $message }}</p>
                        @enderror
                        @error('distribusi_catatan.*')
                            <p class="text-sm text-rose-300 mb-2">{{ $message }}</p>
                        @enderror
                        <div id="listDistribusi" class="space-y-3">
                            @foreach($oldDistribusi as $idx => $row)
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 distribusi-item">
                                    <div>
                                        <label class="text-xs text-indigo-100/80">Ruang</label>
                                        <select name="distribusi_ruang[]" data-inventaris data-required="true" class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
                                            <option value="">-- Pilih Ruang --</option>
                                            @foreach($ruangOptions as $r)
                                                <option value="{{ $r->idruang }}" @selected($row['ruang'] == $r->idruang)>{{ $r->nama_ruang }} ({{ $r->nama_gedung }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs text-indigo-100/80">Jumlah Unit</label>
        <input type="number" name="distribusi_jumlah[]" min="1" max="500" value="{{ $row['jumlah'] ?? 1 }}" data-inventaris data-required="true"
            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="text-xs text-indigo-100/80">Catatan</label>
                                        <div class="flex items-center space-x-2">
                                            <input type="text" name="distribusi_catatan[]" value="{{ $row['catatan'] }}" data-inventaris class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent" placeholder="Opsional">
                                            <button type="button" class="text-rose-400 hover:text-rose-300 text-xs remove-distribusi" title="Hapus">&times;</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
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
                            <label class="block text-sm font-semibold text-indigo-100 mb-2" for="merk">Merk</label>
                            <input type="text" name="merk" id="merk" value="{{ old('merk', $barangMasuk->merk) }}"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent"
                                placeholder="Contoh: Toyota, Honda, Lenovo">
                            @error('merk')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
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
    const selectKategori = document.getElementById('idkategori');
    const jenisBarang = document.getElementById('jenis_barang');
    const inventarisWrapper = document.getElementById('inventaris-fields');
    const listDistribusi = document.getElementById('listDistribusi');
    const tambahDistribusi = document.getElementById('tambahDistribusi');
    const submitBtn = document.querySelector('button[type="submit"]');

    function filterBarangByKategori() {
        const selectedKategori = selectKategori?.value || '';
        Array.from(selectBarang.options).forEach(opt => {
            if (!opt.value) return;
            const match = !selectedKategori || opt.dataset.kategori === selectedKategori;
            opt.hidden = !match;
            if (!match && opt.selected) {
                opt.selected = false;
            }
        });
        if (!selectBarang.value) {
            jenisBarang.value = 'pinjam';
        }
        toggleDistribusi();
    }

    function toggleDistribusi() {
        const aktif = jenisBarang.value === 'tetap';
        inventarisWrapper?.classList.toggle('hidden', !aktif);
        listDistribusi?.querySelectorAll('[data-inventaris]').forEach(el => {
            if (aktif) {
                if (el.dataset.required === 'true') el.setAttribute('required', 'required');
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
                <label class="text-xs text-indigo-100/80">Ruang</label>
                <select name="distribusi_ruang[]" data-inventaris data-required="true" class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
                    <option value="">-- Pilih Ruang --</option>
                    @foreach($ruangOptions as $r)
                        <option value="{{ $r->idruang }}">{{ $r->nama_ruang }} ({{ $r->nama_gedung }})</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-xs text-indigo-100/80">Jumlah Unit</label>
                <input type="number" name="distribusi_jumlah[]" min="1" max="500" value="1" data-inventaris data-required="true"
                    class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
            </div>
            <div>
                <label class="text-xs text-indigo-100/80">Catatan</label>
                <div class="flex items-center space-x-2">
                    <input type="text" name="distribusi_catatan[]" data-inventaris class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus-border-transparent" placeholder="Opsional">
                    <button type="button" class="text-rose-400 hover:text-rose-300 text-xs remove-distribusi">&times;</button>
                </div>
            </div>
        `;
        listDistribusi.appendChild(template);
        if (data.ruang) template.querySelector('select').value = data.ruang;
        if (data.jumlah) template.querySelector('input[name="distribusi_jumlah[]"]').value = data.jumlah;
        if (data.catatan) template.querySelector('input[name="distribusi_catatan[]"]').value = data.catatan;
        toggleDistribusi();
    }

    function hitungDistribusi() {
        const target = parseInt(document.getElementById('jumlah')?.value || '0', 10) || 0;
        let total = 0;
        listDistribusi?.querySelectorAll('input[name="distribusi_jumlah[]"]').forEach(el => {
            total += parseInt(el.value || '0', 10) || 0;
        });
        const isTetap = jenisBarang?.value === 'tetap';
        const valid = !isTetap || (target > 0 && total === target);
        if (submitBtn) submitBtn.disabled = isTetap && target > 0 ? !valid : false;
    }

    if (listDistribusi && tambahDistribusi) {
        listDistribusi.addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-distribusi')) {
                const item = e.target.closest('.distribusi-item');
                if (listDistribusi.children.length > 1) {
                    item.remove();
                } else {
                    item.querySelectorAll('select, input').forEach(el => el.value = '');
                    item.querySelector('input[name="distribusi_jumlah[]"]').value = 1;
                }
                hitungDistribusi();
            }
        });

        tambahDistribusi.addEventListener('click', function () {
            addDistribusiRow();
            hitungDistribusi();
        });
    }

    filterBarangByKategori();
    toggleDistribusi();
    hitungDistribusi();
    selectBarang?.addEventListener('change', toggleDistribusi);
    jenisBarang?.addEventListener('change', toggleDistribusi);
    selectKategori?.addEventListener('change', filterBarangByKategori);
</script>
@endsection
