@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Konfigurasi Aplikasi</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Identitas kampus & tampilan sistem</h1>
                    <p class="mt-3 text-indigo-50/90 max-w-2xl">Perbarui nama kampus, logo, dan preferensi penggunaan di layout, PDF, serta email.</p>
                </div>
                <a href="{{ route('admin.index') }}" class="inline-flex items-center gap-2 px-4 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
            </div>

            @if(session('success'))
                <div class="mt-6 rounded-xl bg-emerald-500/15 border border-emerald-400/40 px-4 py-3 text-emerald-50 shadow shadow-emerald-500/20">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-8">
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Nama Kampus</p>
                        <p class="text-xl font-semibold mt-2">{{ $config->nama_kampus ?? 'Belum diisi' }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Ditampilkan di header</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400/30 to-orange-500/40 opacity-70"></div>
                    <div class="relative space-y-2">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Status Penerapan</p>
                        <div class="flex flex-wrap gap-2 text-xs">
                            <span class="px-3 py-1 rounded-full bg-white/15 border border-white/20 text-white">Layout: {{ ($config->apply_layout ?? false) ? 'Aktif' : 'Off' }}</span>
                            <span class="px-3 py-1 rounded-full bg-white/15 border border-white/20 text-white">PDF: {{ ($config->apply_pdf ?? false) ? 'Aktif' : 'Off' }}</span>
                            <span class="px-3 py-1 rounded-full bg-white/15 border border-white/20 text-white">Email: {{ ($config->apply_email ?? false) ? 'Aktif' : 'Off' }}</span>
                        </div>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400/30 to-indigo-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Logo</p>
                        @if(!empty($config->logo))
                            <img src="{{ asset('storage/'.$config->logo) }}" alt="Logo Kampus" class="h-14 mt-3 object-contain bg-white/60 rounded-xl px-3 py-2 border border-white/20">
                        @else
                            <p class="text-sm text-indigo-100/80 mt-2">Belum ada logo</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 p-6 sm:p-8 backdrop-blur">
                <form action="{{ route('admin.aplikasi.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Nama Kampus</label>
                            <input type="text" name="nama_kampus" value="{{ old('nama_kampus', $config->nama_kampus ?? '') }}"
                                class="w-full rounded-xl bg-slate-800/70 border border-white/10 text-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400" required>
                            @error('nama_kampus') <p class="text-sm text-rose-300 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Website</label>
                            <input type="text" name="website" value="{{ old('website', $config->website ?? '') }}"
                                class="w-full rounded-xl bg-slate-800/70 border border-white/10 text-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400">
                            @error('website') <p class="text-sm text-rose-300 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Telepon</label>
                            <input type="text" name="telepon" value="{{ old('telepon', $config->telepon ?? '') }}"
                                class="w-full rounded-xl bg-slate-800/70 border border-white/10 text-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400">
                            @error('telepon') <p class="text-sm text-rose-300 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $config->email ?? '') }}"
                                class="w-full rounded-xl bg-slate-800/70 border border-white/10 text-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400">
                            @error('email') <p class="text-sm text-rose-300 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-indigo-100 mb-2">Alamat</label>
                        <input type="text" name="alamat" value="{{ old('alamat', $config->alamat ?? '') }}"
                            class="w-full rounded-xl bg-slate-800/70 border border-white/10 text-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400">
                        @error('alamat') <p class="text-sm text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-indigo-100 mb-2">Profil Kampus</label>
                        <textarea name="profil" rows="4"
                            class="w-full rounded-xl bg-slate-800/70 border border-white/10 text-white focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400"
                            placeholder="Deskripsi singkat tentang kampus">{{ old('profil', $config->profil ?? '') }}</textarea>
                        @error('profil') <p class="text-sm text-rose-300 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Logo</label>
                            <input type="file" name="logo" accept="image/*"
                                class="w-full rounded-xl bg-slate-800/70 border border-white/10 text-indigo-100 focus:border-indigo-400 focus:ring-2 focus:ring-indigo-400">
                            @error('logo') <p class="text-sm text-rose-300 mt-1">{{ $message }}</p> @enderror
                        </div>
                        @if(!empty($config->logo))
                            <div class="flex items-end">
                                <div class="w-full">
                                    <p class="text-xs text-indigo-100/80 mb-2">Logo saat ini:</p>
                                    <img src="{{ asset('storage/'.$config->logo) }}" alt="Logo Kampus" class="h-16 object-contain bg-white/60 rounded-xl px-3 py-2 border border-white/20">
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <label class="flex items-start space-x-3 bg-white/5 border border-white/10 rounded-xl p-4 text-white">
                            <input type="checkbox" name="apply_layout" value="1" class="mt-1 text-indigo-400 rounded border-white/20 bg-slate-800/70"
                                {{ old('apply_layout', $config->apply_layout ?? false) ? 'checked' : '' }}>
                            <span>
                                <span class="text-sm font-semibold">Terapkan ke Layout</span>
                                <p class="text-xs text-indigo-100/80">Gunakan nama & logo ini di navigasi dan footer.</p>
                            </span>
                        </label>

                        <label class="flex items-start space-x-3 bg-white/5 border border-white/10 rounded-xl p-4 text-white">
                            <input type="checkbox" name="apply_pdf" value="1" class="mt-1 text-indigo-400 rounded border-white/20 bg-slate-800/70"
                                {{ old('apply_pdf', $config->apply_pdf ?? false) ? 'checked' : '' }}>
                            <span>
                                <span class="text-sm font-semibold">Terapkan ke Laporan PDF</span>
                                <p class="text-xs text-indigo-100/80">Gunakan identitas kampus ini di header laporan.</p>
                            </span>
                        </label>

                        <label class="flex items-start space-x-3 bg-white/5 border border-white/10 rounded-xl p-4 text-white">
                            <input type="checkbox" name="apply_email" value="1" class="mt-1 text-indigo-400 rounded border-white/20 bg-slate-800/70"
                                {{ old('apply_email', $config->apply_email ?? false) ? 'checked' : '' }}>
                            <span>
                                <span class="text-sm font-semibold">Terapkan ke Email</span>
                                <p class="text-xs text-indigo-100/80">Gunakan nama & email ini sebagai pengirim notifikasi.</p>
                            </span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.index') }}" class="px-5 py-3 rounded-xl border border-white/10 text-sm text-indigo-100 hover:bg-white/10 transition">Batal</a>
                        <button type="submit" class="px-5 py-3 bg-white text-indigo-700 rounded-xl shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
