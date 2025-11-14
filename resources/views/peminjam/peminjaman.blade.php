@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ 'Peminjaman' }}
    </h2>
@endsection
@section('content')
    <div class="min-h-screen bg-gray-50 pt-20">
        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-2xl p-8">
            <h2 class="text-2xl font-bold text-indigo-600 mb-6 text-center">Formulir Peminjaman Barang</h2>

            {{-- Notifikasi sukses --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- <form action="{{ route('peminjaman.store') }}" method="POST" class="space-y-6"> --}}
            @csrf
            <div class="my-2">
                <label for="nama" class="block font-semibold text-gray-700 mb-2">Nama Peminjam</label>
                <input type="text" name="nama" id="nama"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('nama')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="my-2">
                <label for="email" class="block font-semibold text-gray-700 mb-2">Email</label>
                <input type="email" name="email" id="email"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="my-2">
                <label for="telepon" class="block font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                <input type="text" name="telepon" id="telepon"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('telepon')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="my-2">
                <label for="barang" class="block font-semibold text-gray-700 mb-2">Barang yang Dipinjam</label>
                <input type="text" name="barang" id="barang"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Contoh: Proyektor" required>
                @error('barang')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="my-2">
                <label for="tanggal_pinjam" class="block font-semibold text-gray-700 mb-2">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('tanggal_pinjam')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="my-2">
                <label for="tanggal_kembali" class="block font-semibold text-gray-700 mb-2">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali" id="tanggal_kembali"
                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    required>
                @error('tanggal_kembali')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="text-center mt-6">
                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700">
                    Ajukan Peminjaman
                </button>
            </div>
            </form>
        </div>
    </div>
@endsection
