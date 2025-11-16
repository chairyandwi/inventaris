@extends('layouts.app')

@section('title', 'Detail Barang')

@section('content')
<div class="pt-24 container mx-auto px-4 py-6 min-h-screen">
    <div class="mb-6 flex items-center gap-4">
        <a href="{{ route('pegawai.barang.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Detail Barang</h1>
    </div>

    <div class="bg-white rounded-2xl shadow p-6 mb-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500">Nama Barang</p>
                <p class="text-lg font-semibold text-gray-900">{{ $barang->nama_barang }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Kode Barang</p>
                <p class="text-lg font-semibold text-gray-900">{{ $barang->kode_barang }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Kategori</p>
                <p class="text-lg font-semibold text-gray-900">{{ $barang->kategori->nama_kategori ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Jenis</p>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $barang->jenis_barang === 'tetap' ? 'bg-indigo-50 text-indigo-700' : 'bg-green-50 text-green-700' }}">
                    {{ $barang->jenis_barang === 'tetap' ? 'Barang Tetap' : 'Barang Pinjam' }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-500">Stok</p>
                <p class="text-2xl font-bold text-gray-900">{{ $barang->stok }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">Keterangan</p>
                <p class="text-gray-700">{{ $barang->keterangan ?? '-' }}</p>
            </div>
        </div>
    </div>

    @if($barang->jenis_barang === 'tetap')
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Ringkasan Per Ruang</h2>
                    <span class="text-sm text-gray-500">Total Unit: {{ $units->count() }}</span>
                </div>
                @if($distribution->isEmpty())
                    <p class="text-sm text-gray-500">Belum ada inventaris tercatat untuk barang ini.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($distribution as $entry)
                            <div class="border border-gray-100 rounded-xl p-4 bg-gray-50">
                                <p class="text-sm text-gray-500">Ruang</p>
                                <p class="text-base font-semibold text-gray-900">{{ $entry['ruang'] }}</p>
                                <p class="text-xs text-gray-500 mb-3">Gedung {{ $entry['gedung'] }}</p>
                                <p class="text-sm text-gray-600">Jumlah unit:</p>
                                <p class="text-2xl font-bold text-indigo-600">{{ $entry['jumlah'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @if($units->isNotEmpty())
                <div class="bg-white rounded-2xl shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Detail Unit</h2>
                        <span class="text-sm text-gray-500">{{ $units->count() }} unit tercatat</span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode Unit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ruang</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gedung</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catatan</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($units as $unit)
                                    <tr>
                                        <td class="px-6 py-4 font-semibold text-gray-900">{{ $unit->kode_unit }}</td>
                                        <td class="px-6 py-4 text-gray-700">{{ $unit->ruang->nama_ruang ?? '-' }}</td>
                                        <td class="px-6 py-4 text-gray-700">{{ $unit->ruang->nama_gedung ?? '-' }}</td>
                                        <td class="px-6 py-4 text-gray-700">{{ $unit->nomor_unit }}</td>
                                        <td class="px-6 py-4 text-gray-500 text-sm">{{ $unit->keterangan ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection
