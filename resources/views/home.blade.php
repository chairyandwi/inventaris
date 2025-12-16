@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
<div class="pt-24 min-h-screen">
    <!-- Hero -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_20%,rgba(255,255,255,0.08),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(59,130,246,0.18),transparent_30%),radial-gradient(circle_at_50%_80%,rgba(147,51,234,0.22),transparent_30%)]"></div>
        <div class="relative container mx-auto px-4 py-14 flex flex-col lg:flex-row gap-10 items-center">
            <div class="flex-1 space-y-4 text-white">
                <p class="text-xs uppercase tracking-[0.4em] text-indigo-200">Sistem Inventaris Kampus</p>
                <h1 class="text-4xl lg:text-5xl font-bold leading-tight">Kelola aset, ruang, dan peminjaman dalam satu dashboard.</h1>
                <p class="text-lg text-indigo-100/90">Pantau stok, catat barang masuk, urus peminjaman, dan cetak laporan tanpa pindah tempat. Data rapi, status jelas, dan mudah ditemukan.</p>
                <div class="flex flex-wrap gap-3 pt-2">
                    <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl transition">
                        Masuk Dashboard
                    </a>
                    <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-3 rounded-xl border border-white/30 text-white font-semibold hover:bg-white/10 transition">
                        Daftar Akun
                    </a>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-4">
                    <div class="futur-card p-4 text-white">
                        <p class="text-xs text-indigo-100 uppercase">Peminjaman</p>
                        <p class="text-xl font-bold">Status Jelas</p>
                    </div>
                    <div class="futur-card p-4 text-white">
                        <p class="text-xs text-indigo-100 uppercase">Barang Masuk</p>
                        <p class="text-xl font-bold">Tercatat</p>
                    </div>
                    <div class="futur-card p-4 text-white">
                        <p class="text-xs text-indigo-100 uppercase">Inventaris</p>
                        <p class="text-xl font-bold">Per Ruang</p>
                    </div>
                </div>
            </div>
            <div class="flex-1 w-full">
                <div class="futur-card border-white/20 bg-white/10 p-6 text-white">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xs uppercase tracking-[0.3em] text-indigo-200">Ringkasan</p>
                            <h3 class="text-2xl font-bold">Apa yang bisa dilakukan?</h3>
                        </div>
                        <span class="px-3 py-1 rounded-full bg-white/15 text-xs font-semibold">Live</span>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                            <p class="font-semibold">Kelola Barang & Barang Masuk</p>
                            <p class="text-indigo-100/80 mt-1">Tambah barang baru, catat barang masuk, stok bertambah otomatis.</p>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                            <p class="font-semibold">Peminjaman Transparan</p>
                            <p class="text-indigo-100/80 mt-1">Ajukan, approve, tolak, dan kembalikan dengan status realtime.</p>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                            <p class="font-semibold">Inventaris per Ruang</p>
                            <p class="text-indigo-100/80 mt-1">Kunci kode unit, pantau distribusi aset per lokasi.</p>
                        </div>
                        <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                            <p class="font-semibold">Akses Multi-Role</p>
                            <p class="text-indigo-100/80 mt-1">Admin, pegawai, dan peminjam berbagi UI seragam dan aman.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fitur -->
    <section id="fitur" class="container mx-auto px-4 py-14 text-white">
        <div class="text-center mb-10">
            <p class="text-xs uppercase tracking-[0.3em] text-indigo-200">Fitur Utama</p>
            <h2 class="text-3xl font-bold mt-2">Semua alur aset dalam satu tempat</h2>
            <p class="text-indigo-100/80 mt-2">Tambah barang, catat ruang, kelola peminjaman, dan unduh laporan tanpa pindah gaya.</p>
        </div>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="futur-card p-6 border border-white/10">
                <h4 class="text-lg font-bold text-white mb-2">Data Inti Lengkap</h4>
                <p class="text-indigo-100/80">Barang, ruang, kategori, dan user tersusun rapi dengan tampilan konsisten.</p>
            </div>
            <div class="futur-card p-6 border border-white/10">
                <h4 class="text-lg font-bold text-white mb-2">Peminjaman Terkontrol</h4>
                <p class="text-indigo-100/80">Status jelas (pending/dipinjam/kembali) dan approve/return bisa cepat.</p>
            </div>
            <div class="futur-card p-6 border border-white/10">
                <h4 class="text-lg font-bold text-white mb-2">Monitoring & Laporan</h4>
                <p class="text-indigo-100/80">Pantau stok, barang masuk, peminjaman, lalu cetak PDF siap dibagikan.</p>
            </div>
        </div>
    </section>

    <!-- Cara -->
    <section id="cara" class="container mx-auto px-4 py-14">
        <div class="futur-card border border-white/10 p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                <div>
                    <p class="text-xs uppercase tracking-[0.3em] text-indigo-200">Alur Peminjaman</p>
                    <h3 class="text-2xl font-bold">Empat langkah singkat</h3>
                </div>
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow hover:shadow-lg transition">
                    Mulai Login
                </a>
            </div>
            <div class="grid md:grid-cols-4 gap-4 text-sm text-indigo-50">
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-indigo-200 mb-1">01</p>
                    <p class="font-semibold text-white">Login / Daftar akun</p>
                    <p class="text-indigo-100/80 mt-1">Akses sesuai role (admin/pegawai/peminjam).</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-indigo-200 mb-1">02</p>
                    <p class="font-semibold text-white">Pilih barang & ruang</p>
                    <p class="text-indigo-100/80 mt-1">Cari stok, lihat ketersediaan dan lokasi.</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-indigo-200 mb-1">03</p>
                    <p class="font-semibold text-white">Ajukan & disetujui</p>
                    <p class="text-indigo-100/80 mt-1">Pegawai/admin memproses approval.</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-xl p-4">
                    <p class="text-xs text-indigo-200 mb-1">04</p>
                    <p class="font-semibold text-white">Ambil & kembalikan</p>
                    <p class="text-indigo-100/80 mt-1">Status realtime, cetak laporan bila perlu.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="container mx-auto px-4 pb-16">
        <div class="futur-card border border-white/10 p-8 text-white text-center">
            <p class="text-xs uppercase tracking-[0.3em] text-indigo-200">Butuh bantuan?</p>
            <h3 class="text-2xl font-bold mt-2">Hubungi Admin Inventaris</h3>
            @php
                $appConfig = $globalAppConfig ?? null;
                $email = ($appConfig && $appConfig->email) ? $appConfig->email : 'admin@kampus.ac.id';
                $telepon = ($appConfig && $appConfig->telepon) ? $appConfig->telepon : '(0274) 485535';
            @endphp
            <p class="text-indigo-100/80 mt-2">Email: {{ $email }} · Telepon: {{ $telepon }}</p>
            <div class="mt-4 flex justify-center gap-3">
                <a href="{{ route('login') }}" class="inline-flex items-center px-5 py-3 rounded-xl bg-gradient-to-r from-indigo-500 to-purple-600 text-white font-semibold shadow-lg hover:shadow-xl transition">
                    Masuk Sekarang
                </a>
                <a href="{{ route('register') }}" class="inline-flex items-center px-5 py-3 rounded-xl border border-white/30 text-white font-semibold hover:bg-white/10 transition">
                    Buat Akun
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
