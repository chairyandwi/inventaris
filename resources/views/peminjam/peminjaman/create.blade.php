@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
            <div class="bg-slate-900/80 rounded-2xl border border-white/10 shadow-xl shadow-indigo-500/10 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/70">Peminjaman</p>
                        <h1 class="text-2xl font-bold mt-2">Ajukan Peminjaman Barang</h1>
                        <p class="text-sm text-indigo-100/80 mt-2">Lengkapi form berikut untuk mengirim permintaan peminjaman.</p>
                    </div>
                    <a href="{{ route('peminjam.peminjaman.index') }}" class="text-indigo-200 hover:text-white text-sm font-semibold">
                        Lihat Riwayat
                    </a>
                </div>

                @if(session('error'))
                    <div class="mb-4 rounded-xl bg-rose-500/10 border border-rose-300/30 px-4 py-3 text-rose-100">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('peminjam.peminjaman.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <label for="idbarang" class="block text-sm font-semibold text-indigo-100 mb-2">Pilih Barang</label>
                        <select name="idbarang" id="idbarang"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                            required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($barang as $item)
                                <option value="{{ $item->idbarang }}" @selected(old('idbarang') == $item->idbarang)>
                                    {{ $item->nama_barang }} (Sisa dapat dipinjam: {{ $item->available_stok ?? max($item->stok - ($item->units_count ?? 0), 0) }})
                                </option>
                            @endforeach
                        </select>
                        @error('idbarang')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-indigo-100 mb-2">Kegiatan</label>
                        <select name="kegiatan" id="kegiatan"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent" required>
                            <option value="">-- Pilih Kegiatan --</option>
                            <option value="kampus" {{ old('kegiatan') === 'kampus' ? 'selected' : '' }}>Kegiatan di Kampus</option>
                            <option value="luar" {{ old('kegiatan') === 'luar' ? 'selected' : '' }}>Kegiatan di Luar Kampus</option>
                        </select>
                        @error('kegiatan')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan_kegiatan" class="block text-sm font-semibold text-indigo-100 mb-2">Kepentingan / Keterangan Kegiatan</label>
                        <textarea name="keterangan_kegiatan" id="keterangan_kegiatan" rows="3"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                            placeholder="Contoh: Presentasi tugas akhir, rapat UKM, dll." required>{{ old('keterangan_kegiatan') }}</textarea>
                        @error('keterangan_kegiatan')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="ruang-wrapper" class="{{ old('kegiatan') === 'kampus' ? '' : 'hidden' }}">
                        <label for="idruang" class="block text-sm font-semibold text-indigo-100 mb-2">Lokasi / Ruang Penggunaan</label>
                        <select name="idruang" id="idruang"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent">
                            <option value="">-- Pilih Ruang --</option>
                            @foreach($ruang as $item)
                                <option value="{{ $item->idruang }}" @selected(old('idruang') == $item->idruang)>
                                    {{ $item->nama_ruang }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-indigo-100/70 mt-2">Ruang hanya wajib diisi jika kegiatan berlangsung di kampus.</p>
                        @error('idruang')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tgl_pinjam_rencana" class="block text-sm font-semibold text-indigo-100 mb-2">Rencana Tanggal Peminjaman</label>
                        <input type="date" name="tgl_pinjam_rencana" id="tgl_pinjam_rencana"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                            value="{{ old('tgl_pinjam_rencana', now()->format('Y-m-d')) }}" required min="{{ now()->format('Y-m-d') }}">
                        @error('tgl_pinjam_rencana')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-indigo-100/70 mt-2">Tanggal rencana pengambilan/pemakaian barang.</p>
                    </div>

                    <div>
                        <label for="tgl_kembali_rencana" class="block text-sm font-semibold text-indigo-100 mb-2">Rencana Tanggal Pengembalian</label>
                        <input type="date" name="tgl_kembali_rencana" id="tgl_kembali_rencana"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                            value="{{ old('tgl_kembali_rencana') }}" required min="{{ old('tgl_pinjam_rencana', now()->format('Y-m-d')) }}">
                        @error('tgl_kembali_rencana')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-indigo-100/70 mt-2">Pastikan tanggal pengembalian sesuai kesepakatan penggunaan.</p>
                    </div>

                    <div>
                        <label for="jumlah" class="block text-sm font-semibold text-indigo-100 mb-2">Jumlah Barang</label>
                        <input type="number" min="1" name="jumlah" id="jumlah"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                            placeholder="Masukkan jumlah yang dibutuhkan"
                            value="{{ old('jumlah', 1) }}" required>
                        @error('jumlah')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-indigo-100/70 mt-2">Pastikan jumlah tidak melebihi stok barang yang tersedia.</p>
                    </div>

                    <div>
                        <label for="foto_identitas" class="block text-sm font-semibold text-indigo-100 mb-2">Unggah Foto Identitas (KTM/KTP/Kartu Pegawai)</label>
                        <input type="file" name="foto_identitas" id="foto_identitas"
                            accept="image/*"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white file:mr-4 file:rounded-lg file:border-0 file:bg-white/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-white/20"
                            required>
                        @error('foto_identitas')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-indigo-100/70 mt-2">Format jpg/png, ukuran maks 2MB.</p>
                    </div>

                    <div class="pt-4 flex items-center justify-between">
                        <a href="{{ route('peminjam.peminjaman.index') }}"
                           class="text-sm text-indigo-100/70 hover:text-white underline">Batal & kembali</a>
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 bg-white text-indigo-700 font-semibold rounded-xl shadow hover:shadow-indigo-500/40 transition">
                            Kirim Pengajuan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const kegiatanSelect = document.getElementById('kegiatan');
    const ruangWrapper = document.getElementById('ruang-wrapper');
    const ruangSelect = document.getElementById('idruang');
    const tglPinjamInput = document.getElementById('tgl_pinjam_rencana');
    const tglKembaliInput = document.getElementById('tgl_kembali_rencana');

    function toggleRuang() {
        if (kegiatanSelect.value === 'kampus') {
            ruangWrapper.classList.remove('hidden');
        } else {
            ruangWrapper.classList.add('hidden');
            ruangSelect.value = '';
        }
    }

    function syncTanggalKembali() {
        if (tglPinjamInput && tglKembaliInput) {
            tglKembaliInput.min = tglPinjamInput.value || tglKembaliInput.min;
            if (tglKembaliInput.value && tglKembaliInput.value < tglPinjamInput.value) {
                tglKembaliInput.value = '';
            }
        }
    }

    kegiatanSelect.addEventListener('change', toggleRuang);
    tglPinjamInput?.addEventListener('change', syncTanggalKembali);

    toggleRuang();
    syncTanggalKembali();
});
</script>
@endsection
