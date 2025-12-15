@extends('layouts.app')

@section('title', 'Tambah Ruang')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-[420px] bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12 space-y-6">
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route(($routePrefix ?? 'pegawai') . '.ruang.index') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Ruang</p>
                    <h1 class="text-3xl font-bold leading-tight mt-2">Tambah ruang</h1>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <form action="{{ route(($routePrefix ?? 'pegawai') . '.ruang.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama_ruang" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Nama Ruang <span class="text-rose-300">*</span>
                            </label>
                            <input type="text" 
                                   id="nama_ruang" 
                                   name="nama_ruang" 
                                   value="{{ old('nama_ruang') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('nama_ruang') ? 'border-rose-400' : '' }}"
                                   placeholder="Masukkan nama ruang"
                                   maxlength="100"
                                   required>
                            @error('nama_ruang')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nama_gedung" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Nama Gedung <span class="text-rose-300">*</span>
                            </label>
                            <select id="nama_gedung" 
                                    name="nama_gedung" 
                                    class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('nama_gedung') ? 'border-rose-400' : '' }}"
                                    required>
                                <option value="">-- Pilih Gedung --</option>
                                <option value="Gedung A" {{ old('nama_gedung') == 'Gedung A' ? 'selected' : '' }}>Gedung A</option>
                                <option value="Gedung B" {{ old('nama_gedung') == 'Gedung B' ? 'selected' : '' }}>Gedung B</option>
                                <option value="Gedung C" {{ old('nama_gedung') == 'Gedung C' ? 'selected' : '' }}>Gedung C</option>
                                <option value="Gedung D" {{ old('nama_gedung') == 'Gedung D' ? 'selected' : '' }}>Gedung D</option>
                            </select>
                            @error('nama_gedung')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nama_lantai" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Nama Lantai <span class="text-rose-300">*</span>
                            </label>
                            <select id="nama_lantai" 
                                    name="nama_lantai" 
                                    class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('nama_lantai') ? 'border-rose-400' : '' }}"
                                    required>
                                <option value="">-- Pilih Lantai --</option>
                                <option value="Lantai 1" {{ old('nama_lantai') == 'Lantai 1' ? 'selected' : '' }}>Lantai 1</option>
                                <option value="Lantai 2" {{ old('nama_lantai') == 'Lantai 2' ? 'selected' : '' }}>Lantai 2</option>
                                <option value="Lantai 3" {{ old('nama_lantai') == 'Lantai 3' ? 'selected' : '' }}>Lantai 3</option>
                                <option value="Basement" {{ old('nama_lantai') == 'Basement' ? 'selected' : '' }}>Basement</option>
                            </select>
                            @error('nama_lantai')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="keterangan" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Keterangan
                            </label>
                            <textarea id="keterangan" 
                                      name="keterangan" 
                                      rows="3"
                                      class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('keterangan') ? 'border-rose-400' : '' }}"
                                      placeholder="Masukkan keterangan (opsional)"
                                      maxlength="255">{{ old('keterangan') }}</textarea>
                            @error('keterangan')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.ruang.index') }}" 
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
@endsection
