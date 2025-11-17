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

            <form action="{{ route('peminjam.peminjaman.store') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <div>
                    <label for="idbarang" class="block text-sm font-semibold text-gray-700 mb-2">Pilih Barang</label>
                    <select name="idbarang" id="idbarang"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barang as $item)
                            <option value="{{ $item->idbarang }}" @selected(old('idbarang') == $item->idbarang)>
                                {{ $item->nama_barang }} (Sisa dapat dipinjam: {{ $item->available_stok ?? max($item->stok - ($item->units_count ?? 0), 0) }})
                            </option>
                        @endforeach
                    </select>
                    @error('idbarang')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kegiatan</label>
                    <select name="kegiatan" id="kegiatan"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                        <option value="">-- Pilih Kegiatan --</option>
                        <option value="kampus" {{ old('kegiatan') === 'kampus' ? 'selected' : '' }}>Kegiatan di Kampus</option>
                        <option value="luar" {{ old('kegiatan') === 'luar' ? 'selected' : '' }}>Kegiatan di Luar Kampus</option>
                    </select>
                    @error('kegiatan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="keterangan_kegiatan" class="block text-sm font-semibold text-gray-700 mb-2">Kepentingan / Keterangan Kegiatan</label>
                    <textarea name="keterangan_kegiatan" id="keterangan_kegiatan" rows="3"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Contoh: Presentasi tugas akhir, rapat UKM, dll." required>{{ old('keterangan_kegiatan') }}</textarea>
                    @error('keterangan_kegiatan')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="ruang-wrapper" class="{{ old('kegiatan') === 'kampus' ? '' : 'hidden' }}">
                    <label for="idruang" class="block text-sm font-semibold text-gray-700 mb-2">Lokasi / Ruang Penggunaan</label>
                    <select name="idruang" id="idruang"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Ruang --</option>
                        @foreach($ruang as $item)
                            <option value="{{ $item->idruang }}" @selected(old('idruang') == $item->idruang)>
                                {{ $item->nama_ruang }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Ruang hanya wajib diisi jika kegiatan berlangsung di kampus.</p>
                    @error('idruang')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tgl_pinjam_rencana" class="block text-sm font-semibold text-gray-700 mb-2">Rencana Tanggal Peminjaman</label>
                    <input type="date" name="tgl_pinjam_rencana" id="tgl_pinjam_rencana"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('tgl_pinjam_rencana', now()->format('Y-m-d')) }}" required min="{{ now()->format('Y-m-d') }}">
                    @error('tgl_pinjam_rencana')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Tanggal rencana pengambilan/pemakaian barang.</p>
                </div>

                <div>
                    <label for="tgl_kembali_rencana" class="block text-sm font-semibold text-gray-700 mb-2">Rencana Tanggal Pengembalian</label>
                    <input type="date" name="tgl_kembali_rencana" id="tgl_kembali_rencana"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        value="{{ old('tgl_kembali_rencana') }}" required min="{{ old('tgl_pinjam_rencana', now()->format('Y-m-d')) }}">
                    @error('tgl_kembali_rencana')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Pastikan tanggal pengembalian sesuai kesepakatan penggunaan.</p>
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

                <div>
                    <label for="foto_identitas" class="block text-sm font-semibold text-gray-700 mb-2">Unggah Foto Identitas (KTM/KTP/Kartu Pegawai)</label>
                    <input type="file" name="foto_identitas" id="foto_identitas"
                        accept="image/*"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white" required>
                    @error('foto_identitas')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-2">Format jpg/png, ukuran maks 2MB.</p>
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
