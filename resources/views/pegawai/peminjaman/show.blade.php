@extends('layouts.app')

@section('content')
@php
    $routePrefix = (auth()->check() && auth()->user()->role === 'admin') ? 'admin.peminjaman' : 'pegawai.peminjaman';
@endphp
<div class="pt-24 min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <a href="{{ route($routePrefix . '.index') }}"
                    class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-2xl font-bold text-gray-900">Detail Peminjam</h1>
            </div>
            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold
                @class([
                    'bg-yellow-100 text-yellow-700' => $peminjaman->status === 'pending',
                    'bg-blue-100 text-blue-700' => $peminjaman->status === 'dipinjam',
                    'bg-green-100 text-green-700' => $peminjaman->status === 'dikembalikan',
                    'bg-red-100 text-red-700' => $peminjaman->status === 'ditolak',
                ])">
                Status: {{ ucfirst($peminjaman->status) }}
            </span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl shadow p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Informasi Peminjam</h2>
                <div class="space-y-3 text-sm text-gray-700">
                    <div>
                        <span class="text-gray-500 block text-xs uppercase">Nama</span>
                        <p class="text-base font-semibold text-gray-900">{{ $peminjaman->user->nama ?? '-' }}</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <span class="text-gray-500 block text-xs uppercase">Email</span>
                            <p>{{ $peminjaman->user->email ?? '-' }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-xs uppercase">No HP</span>
                            <p>{{ $peminjaman->user->nohp ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-500 block text-xs uppercase">Profil</span>
                        <p class="font-semibold">{{ ucfirst($peminjaman->user->tipe_peminjam ?? 'umum') }}</p>
                    </div>
                    @if($peminjaman->user?->tipe_peminjam === 'mahasiswa')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <span class="text-gray-500 block text-xs uppercase">Program Studi</span>
                                <p>{{ $peminjaman->user->prodi ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 block text-xs uppercase">Angkatan</span>
                                <p>{{ $peminjaman->user->angkatan ?? '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500 block text-xs uppercase">NIM</span>
                            <p>{{ $peminjaman->user->nim ?? '-' }}</p>
                        </div>
                    @elseif($peminjaman->user?->tipe_peminjam === 'pegawai')
                        <div>
                            <span class="text-gray-500 block text-xs uppercase">Divisi</span>
                            <p>{{ $peminjaman->user->divisi ?? '-' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow p-6 space-y-4">
                <h2 class="text-lg font-semibold text-gray-900">Informasi Peminjaman</h2>
                <dl class="space-y-3 text-sm text-gray-700">
                    <div>
                        <dt class="text-gray-500 text-xs uppercase">Barang</dt>
                        <dd class="text-base font-semibold text-gray-900">{{ $peminjaman->barang->nama_barang ?? '-' }}</dd>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <dt class="text-gray-500 text-xs uppercase">Jumlah</dt>
                            <dd>{{ $peminjaman->jumlah }} unit</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 text-xs uppercase">Kegiatan</dt>
                            <dd>{{ $peminjaman->kegiatan === 'kampus' ? 'Kegiatan Kampus' : 'Kegiatan Luar Kampus' }}</dd>
                        </div>
                    </div>
                    <div>
                        <dt class="text-gray-500 text-xs uppercase">Keterangan Kegiatan</dt>
                        <dd class="text-gray-800">{{ $peminjaman->keterangan_kegiatan ?? '-' }}</dd>
                    </div>
                    @if($peminjaman->kegiatan === 'kampus')
                        <div>
                            <dt class="text-gray-500 text-xs uppercase">Lokasi / Ruang</dt>
                            <dd>{{ $peminjaman->ruang->nama_ruang ?? '-' }}</dd>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <dt class="text-gray-500 text-xs uppercase">Rencana Pinjam</dt>
                            <dd>{{ $peminjaman->tgl_pinjam_rencana?->format('d M Y') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 text-xs uppercase">Rencana Kembali</dt>
                            <dd>{{ $peminjaman->tgl_kembali_rencana?->format('d M Y') ?? '-' }}</dd>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <dt class="text-gray-500 text-xs uppercase">Tanggal Pinjam</dt>
                            <dd>{{ $peminjaman->tgl_pinjam?->format('d M Y H:i') ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 text-xs uppercase">Tanggal Kembali Aktual</dt>
                            <dd>{{ $peminjaman->tgl_kembali?->format('d M Y H:i') ?? '-' }}</dd>
                        </div>
                    </div>
                </dl>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Dokumen Identitas</h2>
            @if($peminjaman->foto_identitas)
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <img src="{{ asset('storage/'.$peminjaman->foto_identitas) }}" alt="Foto Identitas"
                        class="w-full sm:w-60 rounded-xl border border-gray-200 object-cover">
                    <div class="text-sm text-gray-600">
                        <p>Foto identitas peminjam seperti KTP/Kartu mahasiswa.</p>
                        <a href="{{ asset('storage/'.$peminjaman->foto_identitas) }}" target="_blank"
                            class="inline-flex items-center mt-3 px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-full hover:bg-indigo-700 transition">
                            Buka di Tab Baru
                            <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7m0 0v7m0-7L10 14" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v14h14" />
                            </svg>
                        </a>
                    </div>
                </div>
            @else
                <div class="text-sm text-gray-500">Belum ada foto identitas yang diunggah.</div>
            @endif
        </div>
    </div>
</div>
@endsection
