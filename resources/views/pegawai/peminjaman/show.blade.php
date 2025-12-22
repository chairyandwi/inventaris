@extends('layouts.app')

@section('content')
@php
    $routePrefix = (auth()->check() && auth()->user()->role === 'admin') ? 'admin.peminjaman' : 'pegawai.peminjaman';
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-14 space-y-8">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route($routePrefix . '.index') }}"
                        class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Peminjaman</p>
                        <h1 class="text-3xl font-bold leading-tight">Detail peminjam & peminjaman</h1>
                    </div>
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
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 p-6 space-y-4">
                    <h2 class="text-lg font-semibold">Informasi Peminjam</h2>
                    <div class="space-y-3 text-sm text-indigo-100/80">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-indigo-200/80">Nama</p>
                            <p class="text-base font-semibold text-white">{{ $peminjaman->user->nama ?? '-' }}</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">Email</p>
                                <p>{{ $peminjaman->user->email ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">No HP</p>
                                <p>{{ $peminjaman->user->nohp ?? '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-indigo-200/80">Profil</p>
                            <p class="font-semibold text-white">{{ ucfirst($peminjaman->user->tipe_peminjam ?? 'Tidak diketahui') }}</p>
                        </div>
                        @if($peminjaman->user?->tipe_peminjam === 'mahasiswa')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-indigo-200/80">Program Studi</p>
                                    <p>{{ $peminjaman->user->prodi ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-indigo-200/80">Angkatan</p>
                                    <p>{{ $peminjaman->user->angkatan ?? '-' }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">NIM</p>
                                <p>{{ $peminjaman->user->nim ?? '-' }}</p>
                            </div>
                        @elseif($peminjaman->user?->tipe_peminjam === 'pegawai')
                            <div>
                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">Divisi</p>
                                <p>{{ $peminjaman->user->divisi ?? '-' }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 p-6 space-y-4">
                    <h2 class="text-lg font-semibold">Informasi Peminjaman</h2>
                    <dl class="space-y-3 text-sm text-indigo-100/80">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Barang</dt>
                            <dd class="text-base font-semibold text-white">{{ $peminjaman->barang->nama_barang ?? '-' }}</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Jumlah</dt>
                                <dd class="text-white">{{ $peminjaman->jumlah }} unit</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Kegiatan</dt>
                                <dd class="text-white">{{ $peminjaman->kegiatan === 'kampus' ? 'Kegiatan Kampus' : 'Kegiatan Luar Kampus' }}</dd>
                            </div>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Keterangan Kegiatan</dt>
                            <dd class="text-indigo-50">{{ $peminjaman->keterangan_kegiatan ?? '-' }}</dd>
                        </div>
                        @if($peminjaman->kegiatan === 'kampus')
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Lokasi / Ruang</dt>
                                <dd class="text-white">{{ $peminjaman->ruang->nama_ruang ?? '-' }}</dd>
                            </div>
                        @endif
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Rencana Pinjam</dt>
                                <dd class="text-white">{{ $peminjaman->tgl_pinjam_rencana?->format('d M Y') ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Rencana Kembali</dt>
                                <dd class="text-white">{{ $peminjaman->tgl_kembali_rencana?->format('d M Y') ?? '-' }}</dd>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Tanggal Pinjam</dt>
                                <dd class="text-white">{{ $peminjaman->tgl_pinjam?->format('d M Y H:i') ?? '-' }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Tanggal Kembali Aktual</dt>
                                <dd class="text-white">{{ $peminjaman->tgl_kembali?->format('d M Y H:i') ?? '-' }}</dd>
                            </div>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 p-6">
                <h2 class="text-lg font-semibold mb-4">Dokumen Identitas</h2>
                @if($peminjaman->foto_identitas)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <img src="{{ asset('storage/'.$peminjaman->foto_identitas) }}" alt="Foto Identitas"
                            class="w-full sm:w-60 rounded-xl border border-white/10 object-cover bg-white/10">
                        <div class="text-sm text-indigo-100/80">
                            <p>Foto identitas peminjam seperti KTP/Kartu mahasiswa.</p>
                            <a href="{{ asset('storage/'.$peminjaman->foto_identitas) }}" target="_blank"
                                class="inline-flex items-center mt-3 px-4 py-2 bg-white text-indigo-700 text-xs font-semibold rounded-full hover:-translate-y-0.5 transition shadow-lg shadow-indigo-500/30">
                                Buka di Tab Baru
                                <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7m0 0v7m0-7L10 14" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5v14h14" />
                                </svg>
                            </a>
                        </div>
                    </div>
                @else
                    <div class="text-sm text-indigo-100/80">Belum ada foto identitas yang diunggah.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
