@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="pt-24 container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center space-x-4">
            <a href="{{ route(($routePrefix ?? 'pegawai') . '.kategori.index') }}" 
               class="inline-flex items-center px-3 py-2 text-sm text-gray-600 hover:text-gray-900 transition duration-150 ease-in-out">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Kembali
            </a>
            <h1 class="text-2xl font-bold text-gray-800">Tambah Kategori</h1>
        </div>
    </div>

    <!-- Card wrapper -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <form action="{{ route(($routePrefix ?? 'pegawai') . '.kategori.store') }}" method="POST">
            @csrf
            
            <div class="px-6 py-4 space-y-4">
                <div class="border border-dashed border-indigo-200 rounded-xl p-4 bg-indigo-50/40">
                    <!-- Nama Kategori -->
                    <div class="mb-4">
                        <label for="nama_kategori" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kategori <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="nama_kategori" 
                               name="nama_kategori" 
                               value="{{ old('nama_kategori') }}"
                               class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                {{ $errors->has('nama_kategori') ? 'border-red-500' : 'border-gray-300' }}"
                               placeholder="Masukkan nama kategori"
                               maxlength="100"
                               required>
                        @error('nama_kategori')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="mb-2">
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">
                            Keterangan
                        </label>
                        <textarea id="keterangan" 
                                  name="keterangan" 
                                  rows="4"
                                  class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent
                                    {{ $errors->has('keterangan') ? 'border-red-500' : 'border-gray-300' }}"
                                  placeholder="Masukkan keterangan kategori (opsional)"
                                  maxlength="500">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Maksimal 500 karakter</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                <div class="flex items-center justify-end space-x-3">
                    <a href="{{ route(($routePrefix ?? 'pegawai') . '.kategori.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Character counter for keterangan
document.getElementById('keterangan').addEventListener('input', function(e) {
    const maxLength = 500;
    const currentLength = e.target.value.length;
    const remaining = maxLength - currentLength;
    
    // Find or create counter element
    let counter = e.target.parentElement.querySelector('.char-counter');
    if (!counter) {
        counter = document.createElement('p');
        counter.className = 'char-counter mt-1 text-sm text-gray-500';
        e.target.parentElement.appendChild(counter);
    }
    
    counter.textContent = `${currentLength}/${maxLength} karakter`;
    
    // Change color when approaching limit
    if (remaining < 50) {
        counter.className = 'char-counter mt-1 text-sm text-red-500';
    } else {
        counter.className = 'char-counter mt-1 text-sm text-gray-500';
    }
});

// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const nama = document.getElementById('nama_kategori').value.trim();
    
    if (!nama) {
        e.preventDefault();
        alert('Nama kategori wajib diisi');
        document.getElementById('nama_kategori').focus();
        return false;
    }
    
    if (nama.length > 100) {
        e.preventDefault();
        alert('Nama kategori maksimal 100 karakter');
        document.getElementById('nama_kategori').focus();
        return false;
    }
});
</script>
@endsection
