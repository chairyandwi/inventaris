@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12 space-y-6">
            <div class="flex items-center justify-between gap-4 flex-wrap">
                <div>
                    <a href="{{ route('peminjam.peminjaman.index') }}" class="text-sm text-indigo-200 hover:text-white font-semibold">
                        &larr; Kembali ke Riwayat
                    </a>
                    <h1 class="text-2xl font-bold mt-2">Detail Peminjaman</h1>
                </div>
                <span @class([
                    'px-4 py-2 rounded-xl text-sm font-semibold',
                    'bg-amber-500/20 text-amber-200' => $peminjaman->status === 'pending',
                    'bg-indigo-500/20 text-indigo-200' => $peminjaman->status === 'disetujui',
                    'bg-sky-500/20 text-sky-200' => $peminjaman->status === 'dipinjam',
                    'bg-emerald-500/20 text-emerald-200' => $peminjaman->status === 'dikembalikan',
                    'bg-rose-500/20 text-rose-200' => $peminjaman->status === 'ditolak',
                ])>
                    Status: {{ ucfirst($peminjaman->status) }}
                </span>
            </div>

            @if($peminjaman->status === 'disetujui')
                <div class="p-4 rounded-2xl bg-indigo-500/10 border border-indigo-300/30 text-sm text-indigo-100">
                    Pengajuan Anda sudah disetujui. Silakan ambil barang pada {{ $peminjaman->tgl_pinjam_rencana?->format('d M Y') ?? 'jadwal yang ditentukan' }} dan konfirmasi kepada petugas saat pengambilan.
                    @if($peminjaman->tgl_pinjam_rencana && now()->gt($peminjaman->tgl_pinjam_rencana->startOfDay()))
                        <span class="block text-amber-200 mt-2">Jadwal pengambilan sudah tiba, segera hubungi petugas untuk mengambil barang.</span>
                    @endif
                </div>
            @endif

            <div class="bg-slate-900/80 rounded-2xl border border-white/10 shadow-xl shadow-indigo-500/10 divide-y divide-white/10">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-indigo-100/70 mb-1">Barang</p>
                        <p class="text-lg font-semibold text-white">{{ $peminjaman->barang->nama_barang ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-indigo-100/70 mb-1">Jumlah</p>
                        <p class="text-lg font-semibold text-white">{{ $peminjaman->jumlah }} unit</p>
                    </div>
                    <div>
                        <p class="text-sm text-indigo-100/70 mb-1">Jenis Kegiatan</p>
                        <p class="text-lg font-semibold text-white">
                            {{ $peminjaman->kegiatan === 'kampus' ? 'Kegiatan di Kampus' : 'Kegiatan di Luar Kampus' }}
                        </p>
                        <p class="text-sm text-slate-300">Kepentingan: {{ $peminjaman->keterangan_kegiatan ?? '-' }}</p>
                        @if($peminjaman->kegiatan === 'kampus')
                            <p class="text-sm text-slate-300">Lokasi: {{ $peminjaman->ruang->nama_ruang ?? '-' }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-indigo-100/70 mb-1">Tanggal Pengajuan</p>
                        <p class="text-lg font-semibold text-white">{{ $peminjaman->created_at?->format('d M Y H:i') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-indigo-100/70 mb-1">Rencana Peminjaman</p>
                        <p class="text-lg font-semibold text-white">{{ $peminjaman->tgl_pinjam_rencana?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-indigo-100/70 mb-1">Rencana Pengembalian</p>
                        <p class="text-lg font-semibold text-white">{{ $peminjaman->tgl_kembali_rencana?->format('d M Y') ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-indigo-100/70 mb-2">Foto Identitas</p>
                        @if($peminjaman->foto_identitas)
                            <img src="{{ asset('storage/'.$peminjaman->foto_identitas) }}" alt="Foto Identitas" class="rounded-xl border border-white/10 max-h-80 object-contain">
                        @else
                            <p class="text-sm text-slate-300">Tidak ada foto identitas.</p>
                        @endif
                    </div>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-indigo-100/70 mb-1">Tanggal Disetujui</p>
                        <p class="text-lg font-semibold text-white">{{ $peminjaman->tgl_pinjam?->format('d M Y H:i') ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-indigo-100/70 mb-1">Konfirmasi Pengembalian</p>
                        <p class="text-lg font-semibold text-white">{{ $peminjaman->tgl_kembali?->format('d M Y H:i') ?? '-' }}</p>
                    </div>
                </div>

                @if($peminjaman->status === 'ditolak' && $peminjaman->alasan_penolakan)
                    <div class="p-6 bg-rose-500/10 rounded-b-2xl">
                        <p class="text-sm text-rose-200 font-semibold mb-1">Alasan Penolakan</p>
                        <p class="text-sm text-rose-100">{{ $peminjaman->alasan_penolakan }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
