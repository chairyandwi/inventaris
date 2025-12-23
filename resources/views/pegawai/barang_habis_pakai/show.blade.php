@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-14 space-y-8">
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="flex items-center gap-3">
                    <a href="{{ route($routePrefix . '.barang-habis-pakai.request') }}"
                        class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Kembali
                    </a>
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Barang Keluar</p>
                        <h1 class="text-3xl font-bold leading-tight">Detail pengguna & transaksi</h1>
                    </div>
                </div>
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold
                    @class([
                        'bg-yellow-100 text-yellow-700' => $barangKeluar->status === 'pending',
                        'bg-green-100 text-green-700' => $barangKeluar->status === 'approved',
                        'bg-red-100 text-red-700' => $barangKeluar->status === 'rejected',
                    ])">
                    Status: {{ ucfirst($barangKeluar->status) }}
                </span>
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 p-6 space-y-4">
                    <h2 class="text-lg font-semibold">Informasi Pengguna</h2>
                    <div class="space-y-3 text-sm text-indigo-100/80">
                        <div>
                            <p class="text-xs uppercase tracking-wide text-indigo-200/80">Nama</p>
                            <p class="text-base font-semibold text-white">{{ $barangKeluar->user?->nama ?? '-' }}</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">Email</p>
                                <p>{{ $barangKeluar->user?->email ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">No HP</p>
                                <p>{{ $barangKeluar->user?->nohp ?? '-' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-wide text-indigo-200/80">Profil</p>
                            <p class="font-semibold text-white">{{ ucfirst($barangKeluar->user?->tipe_peminjam ?? 'Tidak diketahui') }}</p>
                        </div>
                        @if($barangKeluar->user?->tipe_peminjam === 'mahasiswa')
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-indigo-200/80">Program Studi</p>
                                    <p>{{ $barangKeluar->user?->prodi ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-indigo-200/80">Angkatan</p>
                                    <p>{{ $barangKeluar->user?->angkatan ?? '-' }}</p>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">NIM</p>
                                <p>{{ $barangKeluar->user?->nim ?? '-' }}</p>
                            </div>
                        @elseif($barangKeluar->user?->tipe_peminjam === 'pegawai')
                            <div>
                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">Divisi</p>
                                <p>{{ $barangKeluar->user?->divisi ?? '-' }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 p-6 space-y-4">
                    <h2 class="text-lg font-semibold">Informasi Transaksi</h2>
                    <dl class="space-y-3 text-sm text-indigo-100/80">
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Barang</dt>
                            <dd class="text-base font-semibold text-white">{{ $barangKeluar->barang?->nama_barang ?? '-' }}</dd>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Jumlah</dt>
                                <dd class="text-white">{{ $barangKeluar->jumlah }} unit</dd>
                            </div>
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Tanggal Request</dt>
                                <dd class="text-white">{{ $barangKeluar->tgl_keluar ?? '-' }}</dd>
                            </div>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Rencana Ambil</dt>
                            <dd class="text-white">{{ $barangKeluar->tgl_pengambilan_rencana ?? '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Tanggal Diterima</dt>
                            <dd class="text-white">{{ $barangKeluar->tgl_diterima ? \Illuminate\Support\Carbon::parse($barangKeluar->tgl_diterima)->format('d M Y H:i') : '-' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Keterangan</dt>
                            <dd class="text-indigo-50">{{ $barangKeluar->keterangan ?? '-' }}</dd>
                        </div>
                        @if($barangKeluar->status === 'rejected' && $barangKeluar->alasan_penolakan)
                            <div>
                                <dt class="text-xs uppercase tracking-wide text-indigo-200/80">Alasan Penolakan</dt>
                                <dd class="text-rose-200">{{ $barangKeluar->alasan_penolakan }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 p-6">
                <h2 class="text-lg font-semibold mb-4">Dokumen Identitas</h2>
                @if($fotoIdentitas)
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <img src="{{ asset('storage/'.$fotoIdentitas) }}" alt="Foto Identitas"
                            class="w-full sm:w-60 rounded-xl border border-white/10 object-cover bg-white/10">
                        <div class="text-sm text-indigo-100/80">
                            <p>Dokumen identitas dari profil peminjam.</p>
                            <a href="{{ asset('storage/'.$fotoIdentitas) }}" target="_blank"
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
                    <div class="text-sm text-indigo-100/80">Belum ada foto identitas di profil.</div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
