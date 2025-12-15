@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
    @php
        $routePrefix = (auth()->check() && auth()->user()->role === 'admin') ? 'admin.peminjaman' : 'pegawai.peminjaman';
        $homeRoute = (auth()->check() && auth()->user()->role === 'admin') ? 'admin.index' : 'pegawai.index';
    @endphp
    <div class="pt-24 container mx-auto px-4 py-6 min-h-screen">
        <!-- Header -->
        <div class="mb-6 flex items-center">
            <a href="{{ route($homeRoute) }}"
                class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800 ml-4">Laporan Peminjaman</h1>
            <div class="ml-auto flex items-center space-x-2">
                <!-- Filter -->
                <form method="GET" action="{{ route($routePrefix . '.laporan') }}" class="mb-6 flex flex-wrap gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                            <option value="">-- Semua --</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="dipinjam" {{ request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                            <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan
                            </option>
                            <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Awal</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                              </svg>                              
                            Filter
                        </button>
                        <button type="submit"
                            formaction="{{ route($routePrefix . '.cetak') }}"
                            formtarget="_blank"
                            class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-lg shadow-sm transition duration-150 ease-in-out">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-4 h-4 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                            </svg>
                            Cetak PDF
                        </button>
                    </div>
                </form>
            </div>
        </div>



        <!-- Tabel -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-[1200px] w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail Peminjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kegiatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Pinjam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tgl Kembali</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjaman as $index => $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $peminjaman->firstItem() + $index }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->barang->nama_barang ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->user->nama ?? $p->user->username ?? $p->user->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <div class="text-xs space-y-1">
                                        <p><span class="font-semibold text-gray-900">Profil:</span> {{ ucfirst($p->user->tipe_peminjam ?? 'Umum') }}</p>
                                        @if($p->user?->tipe_peminjam === 'mahasiswa')
                                            <p><span class="font-semibold text-gray-900">Prodi:</span> {{ $p->user->prodi ?? '-' }}</p>
                                            <p><span class="font-semibold text-gray-900">Angkatan:</span> {{ $p->user->angkatan ?? '-' }}</p>
                                            <p><span class="font-semibold text-gray-900">NIM:</span> {{ $p->user->nim ?? '-' }}</p>
                                        @elseif($p->user?->tipe_peminjam === 'pegawai')
                                            <p><span class="font-semibold text-gray-900">Divisi:</span> {{ $p->user->divisi ?? '-' }}</p>
                                        @endif
                                        <p>
                                            <span class="font-semibold text-gray-900">Foto:</span>
                                            @if($p->foto_identitas)
                                                <a href="{{ asset('storage/'.$p->foto_identitas) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat</a>
                                            @else
                                                <span class="text-gray-400">Belum diunggah</span>
                                            @endif
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <p class="font-semibold">{{ $p->kegiatan === 'kampus' ? 'Kampus' : 'Luar Kampus' }}</p>
                                    <p class="text-xs text-gray-500">{{ $p->keterangan_kegiatan ?? '-' }}</p>
                                    @if($p->kegiatan === 'kampus')
                                        <p class="text-xs text-gray-500">Ruang: {{ $p->ruang->nama_ruang ?? '-' }}</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $p->jumlah }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $p->tgl_pinjam ? $p->tgl_pinjam->format('d-m-Y H:i') : '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $p->tgl_kembali ? $p->tgl_kembali->format('d-m-Y H:i') : '-' }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <span @class([
                                        'px-2 py-1 text-xs rounded',
                                        'bg-yellow-100 text-yellow-800' => $p->status === 'pending',
                                        'bg-blue-100 text-blue-800' => $p->status === 'dipinjam',
                                        'bg-green-100 text-green-800' => $p->status === 'dikembalikan',
                                        'bg-red-100 text-red-800' => $p->status === 'ditolak',
                                    ])>
                                        {{ ucfirst($p->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center text-sm text-gray-500">Tidak ada data</td>
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
