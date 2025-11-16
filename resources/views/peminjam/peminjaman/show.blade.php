@extends('layouts.app')

@section('content')
<div class="pt-24 min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <a href="{{ route('peminjam.peminjaman.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">
                    &larr; Kembali ke Riwayat
                </a>
                <h1 class="text-2xl font-bold text-gray-900 mt-2">Detail Peminjaman</h1>
            </div>
            <span @class([
                'px-4 py-2 rounded-xl text-sm font-semibold',
                'bg-yellow-100 text-yellow-700' => $peminjaman->status === 'pending',
                'bg-blue-100 text-blue-700' => $peminjaman->status === 'dipinjam',
                'bg-green-100 text-green-700' => $peminjaman->status === 'dikembalikan',
                'bg-red-100 text-red-700' => $peminjaman->status === 'ditolak',
            ])>
                Status: {{ ucfirst($peminjaman->status) }}
            </span>
        </div>

        <div class="bg-white rounded-2xl shadow divide-y divide-gray-100">
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Barang</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $peminjaman->barang->nama_barang ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Jumlah</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $peminjaman->jumlah }} unit</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Ruang Penggunaan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $peminjaman->ruang->nama_ruang ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $peminjaman->created_at?->format('d M Y H:i') ?? '-' }}</p>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Disetujui</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $peminjaman->tgl_pinjam?->format('d M Y H:i') ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pengembalian</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $peminjaman->tgl_kembali?->format('d M Y H:i') ?? '-' }}</p>
                </div>
            </div>

            @if($peminjaman->status === 'ditolak' && $peminjaman->alasan_penolakan)
                <div class="p-6 bg-rose-50 rounded-b-2xl">
                    <p class="text-sm text-rose-500 font-semibold mb-1">Alasan Penolakan</p>
                    <p class="text-sm text-rose-700">{{ $peminjaman->alasan_penolakan }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
