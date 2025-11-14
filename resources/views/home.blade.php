@extends('layouts.app') <!-- Hero Section -->
@section('content')
<section class="relative pt-24 pb-20 bg-gradient-to-br from-indigo-700 via-indigo-600 to-blue-500 text-white text-center overflow-hidden">
    <!-- Dekorasi background -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-0 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-60 h-60 bg-blue-400/20 rounded-full blur-3xl"></div>
    </div>

    <!-- Konten utama -->
    <div class="relative max-w-4xl mx-auto px-6">
        <h1 class="text-3xl font-extrabold mb-6 leading-tight">
            Selamat Datang di <span class="text-yellow-300">Sistem Inventaris & Peminjaman Barang Universitas Proklamasi 45</span>
        </h1>
        <p class="text-lg text-white/90 mb-8">
            Solusi modern untuk mengelola inventaris, ruang, dan peminjaman barang secara mudah, cepat, dan transparan.
        </p>
        <a href="{{ route('register') }}"
           class="inline-block px-8 py-3 bg-yellow-300 text-indigo-800 font-semibold rounded-xl shadow-lg hover:bg-yellow-400 transition duration-300">
            Belum Punya Akun? Daftar Sekarang
        </a>
    </div>
</section>



    <!-- Fitur -->
    <section id="fitur" class="py-16 max-w-6xl mx-auto px-4">
        <h3 class="text-2xl font-bold text-center mb-12">Fitur Utama</h3>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                <h4 class="text-lg font-semibold mb-2">Manajemen Barang</h4>
                <p class="text-gray-600">Data barang tersimpan rapi dengan kategori, ruang, kondisi, serta harga
                    pembelian.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                <h4 class="text-lg font-semibold mb-2">Peminjaman Mudah</h4>
                <p class="text-gray-600">Untuk meminjam barang, mahasiswa atau staf wajib login terlebih dahulu sebelum mengisi data diri.</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition">
                <h4 class="text-lg font-semibold mb-2">Integrasi IoT</h4>
                <p class="text-gray-600">Monitoring barang & ruang dengan sensor suhu, kelembaban, gerak, pintu, dan
                    RFID.</p>
            </div>
        </div>
    </section>

    <!-- Cara Pinjam -->
    <section id="cara" class="py-16 bg-gray-100">
        <div class="max-w-4xl mx-auto px-4">
            <h3 class="text-2xl font-bold text-center mb-12">Cara Peminjaman Barang</h3>
            <ol class="space-y-6">
                <li class="flex items-start">
                    <span class="bg-indigo-600 text-white rounded-full px-4 py-2 mr-4">1</span>
                    <p>Isi data peminjam (nama, identitas, kontak).</p>
                </li>
                <li class="flex items-start">
                    <span class="bg-indigo-600 text-white rounded-full px-4 py-2 mr-4">2</span>
                    <p>Pilih barang yang ingin dipinjam dari daftar inventaris.</p>
                </li>
                <li class="flex items-start">
                    <span class="bg-indigo-600 text-white rounded-full px-4 py-2 mr-4">3</span>
                    <p>Staff kampus memverifikasi & menyetujui peminjaman.</p>
                </li>
                <li class="flex items-start">
                    <span class="bg-indigo-600 text-white rounded-full px-4 py-2 mr-4">4</span>
                    <p>Barang bisa diambil dan status akan tercatat di sistem.</p>
                </li>
            </ol>
        </div>
    </section>

    <!-- Kontak -->
    <section id="kontak" class="py-16">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold mb-6">Hubungi Kami</h3>
            <p class="mb-4 text-gray-600">Untuk informasi lebih lanjut tentang peminjaman barang, silakan hubungi admin
                inventaris.</p>
            <p class="font-semibold text-indigo-600">Email: admin@kampus.ac.id</p>
            <p class="font-semibold text-indigo-600">Telepon: (021) 123-456</p>
        </div>
    </section>
@endsection
