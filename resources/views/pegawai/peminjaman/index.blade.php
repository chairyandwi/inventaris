@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
    <div class="pt-24 container mx-auto px-4 py-6 min-h-screen">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center">
                <a href="{{ route('pegawai.index') }}"
                    class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold text-gray-800 ml-4">Status Peminjaman</h1>
                <div class="ml-auto flex items-center space-x-2">
                    <!-- Export PDF -->
                    <a href="{{ route('pegawai.peminjaman.laporan') }}"
                        class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                        </svg>
                        Laporan
                    </a>
                </div>
            </div>
        </div>

        @error('alasan_penolakan')
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-lg">
                {{ $message }}
            </div>
        @enderror

        <!-- Card wrapper -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Identitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rencana Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rencana Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali Aktual</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjaman as $index => $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $peminjaman->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $p->barang->nama_barang }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $p->user->nama ?? $p->user->username ?? $p->user->email ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div>
                                        <p class="font-semibold">{{ $p->kegiatan === 'kampus' ? 'Kegiatan Kampus' : 'Kegiatan Luar Kampus' }}</p>
                                        <p class="text-xs text-gray-500">{{ $p->keterangan_kegiatan ?? '-' }}</p>
                                        @if($p->kegiatan === 'kampus')
                                            <p class="text-xs text-gray-500">Lokasi: {{ $p->ruang->nama_ruang ?? '-' }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $p->jumlah }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    @if($p->foto_identitas)
                                        <a href="{{ asset('storage/'.$p->foto_identitas) }}" target="_blank" class="text-indigo-600 hover:underline text-xs">Lihat</a>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $p->tgl_pinjam_rencana ? $p->tgl_pinjam_rencana->format('d-m-Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $p->tgl_pinjam ? $p->tgl_pinjam->format('d-m-Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $p->tgl_kembali_rencana ? $p->tgl_kembali_rencana->format('d-m-Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $p->tgl_kembali ? $p->tgl_kembali->format('d-m-Y H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span @class([
                                        'px-2 py-1 text-xs rounded',
                                        'bg-yellow-100 text-yellow-800' => $p->status === 'pending',
                                        'bg-indigo-100 text-indigo-800' => $p->status === 'disetujui',
                                        'bg-blue-100 text-blue-800' => $p->status === 'dipinjam',
                                        'bg-green-100 text-green-800' => $p->status === 'dikembalikan',
                                        'bg-red-100 text-red-800' => $p->status === 'ditolak',
                                    ])>
                                        {{ ucfirst($p->status) }}
                                    </span>
                                    @if($p->status === 'ditolak' && $p->alasan_penolakan)
                                        <p class="text-xs text-rose-600 mt-1">Alasan: {{ $p->alasan_penolakan }}</p>
                                    @elseif($p->status === 'disetujui')
                                        <p class="text-xs text-gray-600 mt-1">
                                            @if($p->tgl_pinjam_rencana)
                                                Jadwal: {{ $p->tgl_pinjam_rencana->format('d-m-Y') }}
                                            @else
                                                Menunggu jadwal penjemputan.
                                            @endif
                                        </p>
                                        @if($p->tgl_pinjam_rencana && now()->gt($p->tgl_pinjam_rencana->startOfDay()))
                                            <p class="text-xs text-amber-600 mt-1">Sudah lewat jadwal, konfirmasi pengambilan.</p>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex flex-wrap gap-2 w-full">
                                        @if ($p->status == 'pending')
                                            <!-- Approve -->
                                            <form action="{{ route('pegawai.peminjaman.approve', $p->idpeminjaman) }}"
                                                method="POST" class="flex-1 min-w-[130px]">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full px-3 py-1.5 bg-green-500 text-white text-xs rounded text-center shadow hover:bg-green-600 transition">
                                                    Approve
                                                </button>
                                            </form>
                                            <!-- Reject -->
                                            <button type="button"
                                                class="flex-1 min-w-[130px] w-full px-3 py-1.5 bg-red-500 text-white text-xs rounded text-center shadow hover:bg-red-600 transition open-reject-modal"
                                                data-action="{{ route('pegawai.peminjaman.reject', $p->idpeminjaman) }}"
                                                data-barang="{{ $p->barang->nama_barang }}"
                                                data-peminjam="{{ $p->user->nama ?? $p->user->username ?? $p->user->email ?? '-' }}">
                                                Reject
                                            </button>
                                        @elseif($p->status == 'disetujui')
                                            @php
                                                $bolehMulai = !$p->tgl_pinjam_rencana || now()->greaterThanOrEqualTo($p->tgl_pinjam_rencana->startOfDay());
                                            @endphp
                                            <form action="{{ route('pegawai.peminjaman.pickup', $p->idpeminjaman) }}"
                                                method="POST" class="flex-1 min-w-[160px]">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full px-3 py-1.5 text-xs rounded text-center shadow transition {{ $bolehMulai ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-gray-200 text-gray-500 cursor-not-allowed' }}"
                                                    {{ $bolehMulai ? '' : 'disabled' }}>
                                                    {{ $bolehMulai ? 'Mulai Peminjaman' : 'Menunggu Jadwal' }}
                                                </button>
                                            </form>
                                        @elseif($p->status == 'dipinjam')
                                            <form action="{{ route('pegawai.peminjaman.return', $p->idpeminjaman) }}"
                                                method="POST" class="space-y-2">
                                                @csrf
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Tanggal pengembalian aktual</label>
                                                    <input type="datetime-local" name="tgl_kembali_real"
                                                        class="w-full border-gray-300 rounded focus:border-indigo-500 focus:ring-indigo-500 text-xs"
                                                        value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                                </div>
                                                <label class="flex items-center text-xs text-gray-600 space-x-2">
                                                    <input type="checkbox" name="konfirmasi_pengembalian" required class="text-indigo-600 border-gray-300 rounded">
                                                    <span>Barang sudah dikembalikan</span>
                                                </label>
                                                <button type="submit"
                                                    class="w-full px-2 py-1 bg-blue-500 text-white text-xs rounded">Konfirmasi</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-6 py-12 text-center text-sm text-gray-500">
                                    <p class="text-lg font-medium">Tidak ada data peminjaman</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ $peminjaman->firstItem() ?? 0 }} to {{ $peminjaman->lastItem() ?? 0 }} of
                        {{ $peminjaman->total() }} entries
                    </div>
                    <div>
                        {{ $peminjaman->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Modal Reject -->
<div id="reject-modal" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold text-gray-900">Tolak Peminjaman</h2>
                <p class="text-sm text-gray-500" id="reject-meta"></p>
            </div>
            <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl leading-none" id="close-reject-modal">&times;</button>
        </div>
        <form id="reject-form" method="POST">
            @csrf
            <textarea name="alasan_penolakan" id="alasan-penolakan"
                class="w-full border-gray-300 rounded-lg focus:border-rose-500 focus:ring-rose-500"
                rows="4" placeholder="Tuliskan alasan penolakan" required>{{ old('alasan_penolakan') }}</textarea>
            <div class="mt-4 flex items-center justify-end gap-3">
                <button type="button" id="cancel-reject"
                    class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold hover:bg-red-700">
                    Tolak Permintaan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('reject-modal');
    const meta = document.getElementById('reject-meta');
    const textarea = document.getElementById('alasan-penolakan');
    const form = document.getElementById('reject-form');
    const closeButtons = [document.getElementById('close-reject-modal'), document.getElementById('cancel-reject')];

    function openModal(action, barang, peminjam) {
        form.action = action;
        meta.textContent = `${peminjam ?? '-' } - ${barang ?? '-'}`;
        textarea.value = '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        textarea.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.querySelectorAll('.open-reject-modal').forEach(button => {
        button.addEventListener('click', () => {
            openModal(
                button.dataset.action,
                button.dataset.barang,
                button.dataset.peminjam
            );
        });
    });

    closeButtons.forEach(btn => btn.addEventListener('click', closeModal));
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>
@endpush
