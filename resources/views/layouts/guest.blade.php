<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 text-slate-50">
        <div class="min-h-screen flex flex-col">
            <div class="flex-1 flex items-center justify-center px-4 py-10">
                <div class="w-full max-w-6xl grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="hidden md:flex flex-col justify-between rounded-3xl bg-white/5 border border-white/10 shadow-2xl p-8 backdrop-blur-lg">
                        <div>
                            <p class="text-xs uppercase tracking-[0.4em] text-indigo-200 mb-3">Sistem Inventaris Kampus</p>
                            <h1 class="text-3xl font-bold leading-tight text-white">Kelola aset, ruang, dan peminjaman dengan satu dashboard.</h1>
                            <p class="mt-3 text-slate-200 text-sm">Pantau stok, ajukan pinjam, dan ekspor laporan dalam sekali klik. Semua tersaji rapi dan konsisten.</p>
                        </div>
                        <div class="mt-8 grid grid-cols-2 gap-3 text-sm">
                            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                <p class="text-indigo-200 text-xs uppercase mb-1">Cepat</p>
                                <p class="text-white font-semibold">Tambah & setujui peminjaman tanpa pindah halaman.</p>
                            </div>
                            <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                <p class="text-indigo-200 text-xs uppercase mb-1">Aman</p>
                                <p class="text-white font-semibold">Role-based akses untuk admin, pegawai, dan peminjam.</p>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute inset-0 bg-white/10 blur-3xl rounded-3xl"></div>
                        <div class="relative bg-white rounded-3xl shadow-2xl border border-white/60 p-8">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 text-white flex items-center justify-center font-bold text-lg shadow-lg">UP</div>
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-widest">Universitas Proklamasi 45</p>
                                    <h2 class="text-lg font-bold text-gray-900">Inventaris Kampus</h2>
                                </div>
                            </div>
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @stack('scripts')
    </body>
</html>
