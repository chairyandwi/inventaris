@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-10 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/70">Riwayat</p>
                    <h1 class="text-3xl font-bold mt-2">Riwayat Peminjaman</h1>
                    <p class="text-sm text-indigo-100/80 mt-2">Pantau status pengajuan barang yang pernah Anda ajukan.</p>
                </div>
                <a href="{{ route('peminjam.peminjaman.create') }}"
                   class="inline-flex items-center justify-center px-5 py-3 bg-white text-indigo-700 rounded-xl shadow-lg shadow-indigo-500/30 font-semibold hover:-translate-y-0.5 transition">
                    Ajukan Peminjaman
                </a>
            </div>

            @if(session('success'))
                <div class="rounded-2xl bg-emerald-500/10 border border-emerald-300/30 px-4 py-3 text-emerald-100">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl bg-rose-500/10 border border-rose-300/30 px-4 py-3 text-rose-100">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-slate-900/80 rounded-2xl border border-white/10 shadow-xl shadow-indigo-500/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr class="text-left text-xs uppercase tracking-wider text-indigo-100/70">
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Barang</th>
                                <th class="px-6 py-4">Kegiatan</th>
                                <th class="px-6 py-4">Jumlah</th>
                                <th class="px-6 py-4">Identitas</th>
                                <th class="px-6 py-4">Diajukan</th>
                                <th class="px-6 py-4">Rencana Pinjam</th>
                                <th class="px-6 py-4">Rencana Kembali</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($peminjaman as $index => $pinjam)
                                <tr class="hover:bg-white/5">
                                    <td class="px-6 py-4 text-sm text-slate-200">
                                        {{ $peminjaman->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-semibold text-white">
                                        {{ $pinjam->barang->nama_barang ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-200">
                                        <div class="space-y-1">
                                            <p class="font-semibold text-slate-100">{{ $pinjam->kegiatan === 'kampus' ? 'Kegiatan Kampus' : 'Kegiatan Luar Kampus' }}</p>
                                            <p class="text-xs text-slate-300">{{ $pinjam->keterangan_kegiatan ?? '-' }}</p>
                                            @if($pinjam->kegiatan === 'kampus')
                                                <p class="text-xs text-slate-400">Lokasi: {{ $pinjam->ruang->nama_ruang ?? '-' }}</p>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-200">
                                        {{ $pinjam->jumlah }} unit
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-200">
                                        @if($pinjam->foto_identitas)
                                            <a href="{{ asset('storage/'.$pinjam->foto_identitas) }}" target="_blank" class="text-indigo-200 hover:text-white text-xs font-semibold">Lihat</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-300">
                                        {{ $pinjam->created_at?->format('d M Y H:i') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-200">
                                        {{ $pinjam->tgl_pinjam_rencana?->format('d M Y') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-200">
                                        {{ $pinjam->tgl_kembali_rencana?->format('d M Y') ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <span @class([
                                            'px-3 py-1 rounded-full text-xs font-semibold',
                                            'bg-amber-500/20 text-amber-200' => $pinjam->status === 'pending',
                                            'bg-indigo-500/20 text-indigo-200' => $pinjam->status === 'disetujui',
                                            'bg-sky-500/20 text-sky-200' => $pinjam->status === 'dipinjam',
                                            'bg-emerald-500/20 text-emerald-200' => $pinjam->status === 'dikembalikan',
                                            'bg-rose-500/20 text-rose-200' => $pinjam->status === 'ditolak',
                                        ])>
                                            {{ ucfirst($pinjam->status) }}
                                        </span>
                                        @if($pinjam->status === 'disetujui')
                                            <p class="text-xs text-slate-300 mt-2">
                                                Silakan ambil barang pada {{ $pinjam->tgl_pinjam_rencana?->format('d M Y') ?? 'jadwal yang ditentukan' }}.
                                            </p>
                                            @if($pinjam->tgl_pinjam_rencana && now()->gt($pinjam->tgl_pinjam_rencana->startOfDay()))
                                                <p class="text-xs text-amber-200 mt-1">Jadwal pengambilan sudah tiba, segera konfirmasi ke petugas.</p>
                                            @endif
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('peminjam.peminjaman.show', $pinjam->idpeminjaman) }}"
                                           class="text-indigo-200 hover:text-white font-semibold">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-12 text-center text-slate-300">
                                        <p class="text-lg font-semibold text-white">Belum ada pengajuan peminjaman</p>
                                        <p class="text-sm mt-2 text-slate-400">Klik tombol "Ajukan Peminjaman" untuk memulai.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="px-6 py-4 bg-slate-900/60 border-t border-white/10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <p class="text-sm text-slate-300">
                        Menampilkan {{ $peminjaman->firstItem() ?? 0 }} - {{ $peminjaman->lastItem() ?? 0 }} dari {{ $peminjaman->total() }} data
                    </p>
                    <div class="text-slate-200">
                        {{ $peminjaman->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
