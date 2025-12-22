@php
    $tipeAwal = old('tipe_peminjam', $user->tipe_peminjam);
    $prodiOptions = [
        'Teknik Industri',
        'Administrasi Publik',
        'Manajemen',
        'Psikologi',
        'Hukum',
        'Teknologi Informasi',
        'Teknik Lingkungan',
        'Teknik Perminyakan',
        'Teknik Mesin',
    ];
    $tahunAngkatan = range(date('Y'), date('Y') - 10);
@endphp
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12 space-y-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Profil Peminjam</p>
                    <h1 class="text-3xl font-bold mt-2">Lengkapi data profil</h1>
                    <p class="text-sm text-indigo-100/80 mt-2">Data yang lengkap mempercepat proses persetujuan peminjaman dan request barang.</p>
                </div>
                <a href="{{ route('peminjam.index') }}" class="px-4 py-2 rounded-xl bg-white/10 border border-white/10 text-indigo-50 hover:bg-white/20 transition">
                    Kembali ke Dashboard
                </a>
            </div>

            @if(session('status') === 'profile-updated')
                <div class="rounded-2xl bg-emerald-500/10 border border-emerald-300/30 px-4 py-3 text-emerald-100">
                    Profil berhasil diperbarui.
                </div>
            @endif

            @if(session('error'))
                <div class="rounded-2xl bg-rose-500/10 border border-rose-300/30 px-4 py-3 text-rose-100">
                    {{ session('error') }}
                </div>
            @endif

            @if(!empty($missingFields))
                <div class="rounded-2xl bg-amber-500/10 border border-amber-300/30 px-4 py-3 text-amber-100">
                    <p class="font-semibold">Profil belum lengkap.</p>
                    <p class="text-sm text-amber-100/80 mt-1">Lengkapi: {{ implode(', ', $missingFields) }}.</p>
                </div>
            @endif

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/10 p-6 sm:p-8">
                <h2 class="text-lg font-semibold">Informasi Pribadi</h2>
                <p class="text-sm text-indigo-100/70 mt-1">Pastikan data sesuai identitas resmi.</p>

                <form method="POST" action="{{ route('peminjam.profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6" x-data="{ tipe: @js($tipeAwal) }">
                    @csrf
                    @method('patch')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-sm font-semibold text-indigo-100 mb-2">Nama Lengkap</label>
                            <input id="nama" name="nama" type="text" value="{{ old('nama', $user->nama) }}" required
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            @error('nama')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="username" class="block text-sm font-semibold text-indigo-100 mb-2">Username</label>
                            <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}" required
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            @error('username')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-semibold text-indigo-100 mb-2">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            @error('email')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="nohp" class="block text-sm font-semibold text-indigo-100 mb-2">Nomor HP</label>
                            <input id="nohp" name="nohp" type="text" value="{{ old('nohp', $user->nohp) }}" placeholder="08xxxxxxxxxx"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            @error('nohp')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="tipe_peminjam" class="block text-sm font-semibold text-indigo-100 mb-2">Profil Peminjam</label>
                        <select id="tipe_peminjam" name="tipe_peminjam" x-model="tipe"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">-- Pilih Profil --</option>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="pegawai">Pegawai Kampus</option>
                        </select>
                        @error('tipe_peminjam')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="tipe === 'mahasiswa'" x-cloak class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label for="prodi" class="block text-sm font-semibold text-indigo-100 mb-2">Program Studi</label>
                            <select id="prodi" name="prodi"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                <option value="">-- Pilih Prodi --</option>
                                @foreach($prodiOptions as $prodi)
                                    <option value="{{ $prodi }}" @selected(old('prodi', $user->prodi) === $prodi)>{{ $prodi }}</option>
                                @endforeach
                            </select>
                            @error('prodi')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="angkatan" class="block text-sm font-semibold text-indigo-100 mb-2">Angkatan</label>
                            <select id="angkatan" name="angkatan"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                <option value="">-- Pilih Tahun --</option>
                                @foreach($tahunAngkatan as $tahun)
                                    <option value="{{ $tahun }}" @selected(old('angkatan', $user->angkatan) == $tahun)>{{ $tahun }}</option>
                                @endforeach
                            </select>
                            @error('angkatan')
                                <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div x-show="tipe === 'mahasiswa'" x-cloak>
                        <label for="nim" class="block text-sm font-semibold text-indigo-100 mb-2">NIM</label>
                        <input id="nim" name="nim" type="text" value="{{ old('nim', $user->nim) }}"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        @error('nim')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="tipe === 'pegawai'" x-cloak>
                        <label for="divisi" class="block text-sm font-semibold text-indigo-100 mb-2">Divisi / Bagian</label>
                        <input id="divisi" name="divisi" type="text" value="{{ old('divisi', $user->divisi) }}"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        @error('divisi')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="tipe === 'mahasiswa'" x-cloak>
                        <label for="foto_identitas_mahasiswa" class="block text-sm font-semibold text-indigo-100 mb-2">Foto KTM</label>
                        <input id="foto_identitas_mahasiswa" name="foto_identitas_mahasiswa" type="file" accept="image/*"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white file:mr-4 file:rounded-lg file:border-0 file:bg-white/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-white/20">
                        @if($user->foto_identitas_mahasiswa)
                            <p class="text-xs text-indigo-100/70 mt-2">Tersimpan: <a href="{{ asset('storage/'.$user->foto_identitas_mahasiswa) }}" target="_blank" class="underline">Lihat</a></p>
                        @endif
                        @error('foto_identitas_mahasiswa')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-show="tipe === 'pegawai'" x-cloak>
                        <label for="foto_identitas_pegawai" class="block text-sm font-semibold text-indigo-100 mb-2">Foto ID Card</label>
                        <input id="foto_identitas_pegawai" name="foto_identitas_pegawai" type="file" accept="image/*"
                            class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white file:mr-4 file:rounded-lg file:border-0 file:bg-white/10 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-white/20">
                        @if($user->foto_identitas_pegawai)
                            <p class="text-xs text-indigo-100/70 mt-2">Tersimpan: <a href="{{ asset('storage/'.$user->foto_identitas_pegawai) }}" target="_blank" class="underline">Lihat</a></p>
                        @endif
                        @error('foto_identitas_pegawai')
                            <p class="text-sm text-rose-300 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-white text-indigo-700 font-semibold shadow hover:shadow-indigo-500/40 transition">
                            Simpan Profil
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/10 p-6">
                    <h2 class="text-lg font-semibold">Ubah Password</h2>
                    <p class="text-sm text-indigo-100/70 mt-1">Gunakan password yang kuat agar akun aman.</p>

                    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
                        @csrf
                        @method('put')

                        <div>
                            <label for="current_password" class="block text-sm font-semibold text-indigo-100 mb-2">Password Saat Ini</label>
                            <input id="current_password" name="current_password" type="password"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            @if($errors->updatePassword->get('current_password'))
                                <p class="text-sm text-rose-300 mt-1">{{ $errors->updatePassword->first('current_password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-indigo-100 mb-2">Password Baru</label>
                            <input id="password" name="password" type="password"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            @if($errors->updatePassword->get('password'))
                                <p class="text-sm text-rose-300 mt-1">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-indigo-100 mb-2">Konfirmasi Password</label>
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            @if($errors->updatePassword->get('password_confirmation'))
                                <p class="text-sm text-rose-300 mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                            @endif
                        </div>

                        <button type="submit" class="px-5 py-2.5 rounded-xl bg-white/10 text-white font-semibold hover:bg-white/20 transition">
                            Update Password
                        </button>
                    </form>
                </div>

                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/10 p-6">
                    <h2 class="text-lg font-semibold">Hapus Akun</h2>
                    <p class="text-sm text-indigo-100/70 mt-1">Akun yang dihapus tidak bisa dikembalikan.</p>

                    <form method="POST" action="{{ route('profile.destroy') }}" class="mt-6 space-y-4">
                        @csrf
                        @method('delete')

                        <div>
                            <label for="delete_password" class="block text-sm font-semibold text-indigo-100 mb-2">Konfirmasi Password</label>
                            <input id="delete_password" name="password" type="password"
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-rose-400">
                            @if($errors->userDeletion->get('password'))
                                <p class="text-sm text-rose-300 mt-1">{{ $errors->userDeletion->first('password') }}</p>
                            @endif
                        </div>

                        <button type="submit" data-confirm="Yakin ingin menghapus akun?"
                            class="px-5 py-2.5 rounded-xl bg-rose-500/20 text-rose-100 font-semibold hover:bg-rose-500/30 transition">
                            Hapus Akun
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
