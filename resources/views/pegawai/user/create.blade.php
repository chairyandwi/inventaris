@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
@php
    $routePrefix = request()->routeIs('admin.*') ? 'admin.' : 'pegawai.';
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-x-0 top-0 h-[420px] bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12 space-y-6">
            <div class="flex items-center gap-3 flex-wrap">
                <a href="{{ route($routePrefix . 'user.index') }}" 
                   class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">User</p>
                    <h1 class="text-3xl font-bold leading-tight mt-2">Tambah user</h1>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <form action="{{ route($routePrefix . 'user.store') }}" method="POST" class="p-6 space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Nama <span class="text-rose-300">*</span>
                            </label>
                            <input type="text" id="nama" name="nama" 
                                   value="{{ old('nama') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('nama') ? 'border-rose-400' : '' }}"
                                   placeholder="Masukkan nama lengkap" required>
                            @error('nama')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="username" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Username <span class="text-rose-300">*</span>
                            </label>
                            <input type="text" id="username" name="username" 
                                   value="{{ old('username') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('username') ? 'border-rose-400' : '' }}"
                                   placeholder="Masukkan username" required>
                            @error('username')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Email <span class="text-rose-300">*</span>
                            </label>
                            <input type="email" id="email" name="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('email') ? 'border-rose-400' : '' }}"
                                   placeholder="Masukkan email" required>
                            @error('email')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nohp" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Nomor HP
                            </label>
                            <input type="text" id="nohp" name="nohp" 
                                   value="{{ old('nohp') }}"
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('nohp') ? 'border-rose-400' : '' }}"
                                   placeholder="Masukkan nomor HP">
                            @error('nohp')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Role <span class="text-rose-300">*</span>
                            </label>
                            <select id="role" name="role" 
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('role') ? 'border-rose-400' : '' }}" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="kabag" {{ old('role') == 'kabag' ? 'selected' : '' }}>Kabag</option>
                                <option value="pegawai" {{ old('role') == 'pegawai' ? 'selected' : '' }}>Pegawai</option>
                                <option value="peminjam" {{ old('role') == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                            </select>
                            @error('role')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Password <span class="text-rose-300">*</span>
                            </label>
                            <input type="password" id="password" name="password" 
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('password') ? 'border-rose-400' : '' }}"
                                   placeholder="Minimal 6 karakter" required>
                            @error('password')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-indigo-100 mb-2">
                                Konfirmasi Password <span class="text-rose-300">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" 
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent {{ $errors->has('password_confirmation') ? 'border-rose-400' : '' }}"
                                   placeholder="Ulangi password" required>
                            @error('password_confirmation')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-2">
                        <a href="{{ route($routePrefix . 'user.index') }}" 
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
