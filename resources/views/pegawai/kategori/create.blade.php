@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-[420px] bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12 space-y-6">
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route(($routePrefix ?? 'pegawai') . '.kategori.index') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Kategori</p>
                    <h1 class="text-3xl font-bold leading-tight mt-2">Tambah kategori baru</h1>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <form action="{{ route(($routePrefix ?? 'pegawai') . '.kategori.store') }}" method="POST" class="space-y-5 p-6">
                    @csrf

                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="nama_kategori" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Nama Kategori <span class="text-rose-300">*</span>
                            </label>
                            <input type="text" 
                                   id="nama_kategori" 
                                   name="nama_kategori" 
                                   value="{{ old('nama_kategori') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('nama_kategori') ? 'border-rose-400' : '' }}"
                                   placeholder="Masukkan nama kategori"
                                   maxlength="100"
                                   required>
                            @error('nama_kategori')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="keterangan" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Keterangan
                            </label>
                            <textarea id="keterangan" 
                                      name="keterangan" 
                                      rows="4"
                                      class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('keterangan') ? 'border-rose-400' : '' }}"
                                      placeholder="Masukkan keterangan kategori (opsional)"
                                      maxlength="500">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-indigo-200/80">Maksimal 500 karakter</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.kategori.index') }}" 
                           class="px-4 py-2 rounded-xl border border-white/10 text-sm text-indigo-100 hover:bg-white/10 transition">
                            Batal
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-indigo-700 text-sm font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
        counter.className = 'char-counter mt-1 text-sm text-indigo-200/80';
        e.target.parentElement.appendChild(counter);
    }
    
    counter.textContent = `${currentLength}/${maxLength} karakter`;
    
    // Change color when approaching limit
    if (remaining < 50) {
        counter.className = 'char-counter mt-1 text-sm text-rose-300';
    } else {
        counter.className = 'char-counter mt-1 text-sm text-indigo-200/80';
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
