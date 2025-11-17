@extends('layouts.app')

@section('content')
<div class="pt-24 min-h-screen bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl shadow p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Konfigurasi Aplikasi</h1>
                    <p class="text-sm text-gray-500">Perbarui informasi kampus dan tampilan sistem.</p>
                </div>
                <a href="{{ route('admin.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">&larr; Kembali</a>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.aplikasi.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Kampus</label>
                    <input type="text" name="nama_kampus" value="{{ old('nama_kampus', $config->nama_kampus ?? '') }}"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
                    @error('nama_kampus') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon', $config->telepon ?? '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('telepon') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', $config->email ?? '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Website</label>
                        <input type="text" name="website" value="{{ old('website', $config->website ?? '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('website') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $config->alamat ?? '') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        @error('alamat') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Profil Kampus</label>
                    <textarea name="profil" rows="4"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Deskripsi singkat tentang kampus">{{ old('profil', $config->profil ?? '') }}</textarea>
                    @error('profil') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Logo</label>
                    <input type="file" name="logo" accept="image/*"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 bg-white">
                    @error('logo') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
                    @if(!empty($config->logo))
                        <p class="text-xs text-gray-500 mt-2">Logo saat ini:</p>
                        <img src="{{ asset('storage/'.$config->logo) }}" alt="Logo Kampus" class="h-20 mt-2 object-contain border rounded-lg">
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-start space-x-3 bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <input type="checkbox" name="apply_layout" value="1" class="mt-1 text-indigo-600 rounded border-gray-300"
                            {{ old('apply_layout', $config->apply_layout ?? false) ? 'checked' : '' }}>
                        <span>
                            <span class="text-sm font-semibold text-gray-900">Terapkan ke Layout</span>
                            <p class="text-xs text-gray-500">Gunakan nama & logo ini di navigasi dan footer.</p>
                        </span>
                    </label>

                    <label class="flex items-start space-x-3 bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <input type="checkbox" name="apply_pdf" value="1" class="mt-1 text-indigo-600 rounded border-gray-300"
                            {{ old('apply_pdf', $config->apply_pdf ?? false) ? 'checked' : '' }}>
                        <span>
                            <span class="text-sm font-semibold text-gray-900">Terapkan ke Laporan PDF</span>
                            <p class="text-xs text-gray-500">Gunakan identitas kampus ini di header laporan.</p>
                        </span>
                    </label>

                    <label class="flex items-start space-x-3 bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <input type="checkbox" name="apply_email" value="1" class="mt-1 text-indigo-600 rounded border-gray-300"
                            {{ old('apply_email', $config->apply_email ?? false) ? 'checked' : '' }}>
                        <span>
                            <span class="text-sm font-semibold text-gray-900">Terapkan ke Email</span>
                            <p class="text-xs text-gray-500">Gunakan nama & email ini sebagai pengirim notifikasi.</p>
                        </span>
                    </label>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.index') }}" class="px-5 py-3 border border-gray-300 rounded-xl text-sm text-gray-600 hover:bg-gray-50">Batal</a>
                    <button type="submit" class="px-5 py-3 bg-indigo-600 text-white rounded-xl shadow hover:bg-indigo-700">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
