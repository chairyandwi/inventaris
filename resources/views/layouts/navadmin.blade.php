@php
    $appConfig = $globalAppConfig ?? null;
    $useLayoutConfig = $appConfig && $appConfig->apply_layout;
    $brandName = $useLayoutConfig && $appConfig->nama_kampus ? $appConfig->nama_kampus : 'Inventaris Kampus';
    $brandLogo = $useLayoutConfig && $appConfig->logo ? asset('storage/'.$appConfig->logo) : asset('images/inven.png');
@endphp
<nav x-data="{ open: false }" class="bg-white shadow-md fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <img src="{{ $brandLogo }}" class="w-12 h-12 object-contain" alt="Logo">
            <a href="{{ route('admin.index') }}" class="text-xl font-bold text-indigo-600">{{ $brandName }}</a>
        </div>

        <div class="hidden md:flex items-center space-x-6">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-gray-700 hover:text-indigo-600 flex items-center">
                    Data
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-52 bg-white border rounded-lg shadow-lg py-2 z-50">
                    <a href="{{ route('admin.kategori.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Kategori</a>
                    <a href="{{ route('admin.ruang.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Ruang</a>
                    <a href="{{ route('admin.barang.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Barang</a>
                    <a href="{{ route('admin.user.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">User</a>
                    <a href="{{ route('admin.inventaris-ruang.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Inventaris Ruang</a>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-gray-700 hover:text-indigo-600 flex items-center">
                    Transaksi
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-60 bg-white border rounded-lg shadow-lg py-2 z-50">
                    <a href="{{ route('admin.peminjaman.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Peminjaman</a>
                </div>
            </div>

            <a href="{{ route('admin.peminjaman.laporan') }}" class="text-gray-700 hover:text-indigo-600">Laporan</a>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-gray-700 hover:text-indigo-600 flex items-center">
                    Aplikasi
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white border rounded-lg shadow-lg py-2 z-50">
                    <a href="{{ route('admin.aplikasi.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Konfigurasi Aplikasi</a>
                    <a href="{{ route('admin.logs.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Log Aktivitas</a>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                    Logout
                </button>
            </form>
        </div>

        <div class="md:hidden">
            <button @click="open = !open" class="text-gray-700 hover:text-indigo-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div class="md:hidden border-t" x-show="open" @click.away="open = false">
        <div class="px-4 py-3 space-y-2">
            <a href="{{ route('admin.index') }}" class="block text-gray-700 hover:text-indigo-600 font-semibold">Dashboard</a>
            <a href="{{ route('admin.kategori.index') }}" class="block text-gray-700 hover:text-indigo-600">Kategori</a>
            <a href="{{ route('admin.ruang.index') }}" class="block text-gray-700 hover:text-indigo-600">Ruang</a>
            <a href="{{ route('admin.barang.index') }}" class="block text-gray-700 hover:text-indigo-600">Barang</a>
            <a href="{{ route('admin.user.index') }}" class="block text-gray-700 hover:text-indigo-600">User</a>
            <a href="{{ route('admin.inventaris-ruang.index') }}" class="block text-gray-700 hover:text-indigo-600">Inventaris Ruang</a>
            <a href="{{ route('admin.peminjaman.index') }}" class="block text-gray-700 hover:text-indigo-600">Peminjaman</a>
            <a href="{{ route('admin.aplikasi.index') }}" class="block text-gray-700 hover:text-indigo-600">Konfigurasi Aplikasi</a>
            <a href="{{ route('admin.logs.index') }}" class="block text-gray-700 hover:text-indigo-600">Log Aktivitas</a>
            <a href="{{ route('admin.peminjaman.laporan') }}" class="block text-gray-700 hover:text-indigo-600">Laporan</a>
        </div>
        <div class="border-t px-4 py-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">Logout</button>
            </form>
        </div>
    </div>
</nav>
