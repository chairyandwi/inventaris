@extends('layouts.app')

@section('title', 'Inventaris Ruang')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
@endphp
<div class="pt-24 container mx-auto px-4 py-6 min-h-screen">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Inventaris Ruang</h1>
            <p class="text-sm text-gray-500">Monitoring barang tetap yang tercatat pada setiap ruang.</p>
        </div>
        <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.create') }}"
           class="inline-flex items-center px-5 py-3 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700 transition">
            Tambah Inventaris
        </a>
    </div>

        <div class="bg-white rounded-xl shadow mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 p-6">
            <div>
                <label class="text-sm font-semibold text-gray-600 mb-2 block">Filter Ruang</label>
                <select name="idruang" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Ruang</option>
                    @foreach($ruang as $r)
                        <option value="{{ $r->idruang }}" @selected(request('idruang') == $r->idruang)>{{ $r->nama_ruang }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-gray-600 mb-2 block">Filter Barang</label>
                <select name="idbarang" class="w-full border-gray-300 rounded-lg focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Semua Barang</option>
                    @foreach($barang as $b)
                        <option value="{{ $b->idbarang }}" @selected(request('idbarang') == $b->idbarang)>{{ $b->nama_barang }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-3">
                <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-3 bg-amber-500 text-white font-semibold rounded-lg hover:bg-amber-600">
                    Terapkan Filter
                </button>
                <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.laporan', request()->only(['idruang','idbarang'])) }}"
                   class="w-full md:w-auto inline-flex items-center justify-center px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">
                    Unduh Laporan
                </a>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Unit</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($units as $unit)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-semibold text-gray-900">{{ $unit->kode_unit }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $unit->barang->nama_barang ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $unit->ruang->nama_ruang ?? '-' }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $unit->nomor_unit }}</td>
                            <td class="px-6 py-4 text-gray-500 text-sm">{{ $unit->keterangan ?? '-' }}</td>
                            <td class="px-6 py-4 text-sm">
                                <form action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.destroy', $unit) }}" method="POST" onsubmit="return confirm('Hapus unit ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500">
                                Belum ada data inventaris ruang.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $units->links() }}
        </div>
    </div>
</div>
@endsection
