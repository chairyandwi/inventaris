@extends('layouts.app')

@section('content')
<div class="pt-24 min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Riwayat Peminjaman</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau status pengajuan barang yang pernah Anda ajukan.</p>
            </div>
            <a href="{{ route('peminjam.peminjaman.create') }}"
               class="inline-flex items-center justify-center px-5 py-3 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700 transition">
                Ajukan Peminjaman
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Identitas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diajukan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rencana Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rencana Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjaman as $index => $pinjam)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $peminjaman->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ $pinjam->barang->nama_barang ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    <div>
                                        <p class="font-semibold">{{ $pinjam->kegiatan === 'kampus' ? 'Kegiatan Kampus' : 'Kegiatan Luar Kampus' }}</p>
                                        <p class="text-xs text-gray-500">{{ $pinjam->keterangan_kegiatan ?? '-' }}</p>
                                        @if($pinjam->kegiatan === 'kampus')
                                            <p class="text-xs text-gray-500">Lokasi: {{ $pinjam->ruang->nama_ruang ?? '-' }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $pinjam->jumlah }} unit
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    @if($pinjam->foto_identitas)
                                        <a href="{{ asset('storage/'.$pinjam->foto_identitas) }}" target="_blank" class="text-indigo-600 hover:underline text-xs">Lihat</a>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $pinjam->created_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $pinjam->tgl_pinjam_rencana?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ $pinjam->tgl_kembali_rencana?->format('d M Y') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span @class([
                                        'px-3 py-1 rounded-full text-xs font-semibold',
                                        'bg-yellow-100 text-yellow-700' => $pinjam->status === 'pending',
                                        'bg-blue-100 text-blue-700' => $pinjam->status === 'dipinjam',
                                        'bg-green-100 text-green-700' => $pinjam->status === 'dikembalikan',
                                        'bg-red-100 text-red-700' => $pinjam->status === 'ditolak',
                                    ])>
                                        {{ ucfirst($pinjam->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('peminjam.peminjaman.show', $pinjam->idpeminjaman) }}"
                                       class="text-indigo-600 hover:text-indigo-800 font-semibold">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                    <p class="text-lg font-semibold">Belum ada pengajuan peminjaman</p>
                                    <p class="text-sm mt-2">Klik tombol "Ajukan Peminjaman" untuk memulai.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <p class="text-sm text-gray-600">
                    Menampilkan {{ $peminjaman->firstItem() ?? 0 }} - {{ $peminjaman->lastItem() ?? 0 }} dari {{ $peminjaman->total() }} data
                </p>
                <div>
                    {{ $peminjaman->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
