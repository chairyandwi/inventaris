@php
    $appConfig = $globalAppConfig ?? null;
    $useLayoutConfig = $appConfig && $appConfig->apply_layout;
    $brandName = $useLayoutConfig && $appConfig->nama_kampus ? $appConfig->nama_kampus : 'Inventaris Kampus';
    $brandLogo = $useLayoutConfig && $appConfig->logo ? asset('storage/'.$appConfig->logo) : asset('images/inven.png');
@endphp
<nav x-data="{ open: false }" class="fixed inset-x-0 top-0 z-50 bg-gradient-to-r from-slate-900/90 via-indigo-900/80 to-slate-900/90 backdrop-blur border-b border-indigo-500/30 shadow-lg shadow-indigo-500/10 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="p-2 rounded-xl bg-white/10 ring-1 ring-indigo-300/40 shadow-md shadow-indigo-500/20">
                <img src="{{ $brandLogo }}" class="w-10 h-10 sm:w-12 sm:h-12 object-contain" alt="Logo">
            </div>
            <div>
                <a href="{{ route('kabag.index') }}"><h1 class="text-lg sm:text-xl font-semibold tracking-tight hover:text-indigo-200 transition">{{ $brandName }}</h1></a>
                <div class="text-xs text-indigo-200/80 uppercase tracking-[0.25em]">Kabag</div>
            </div>
        </div>

        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('kabag.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75 12 4l9 5.75M4.5 10.5v8.25A1.25 1.25 0 0 0 5.75 20h12.5a1.25 1.25 0 0 0 1.25-1.25V10.5"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                    <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12M8 11h12m-7 4h7M5 7h.01M5 11h.01M5 15h.01" />
                    </svg>
                    <span>Laporan</span>
                    <svg class="w-4 h-4 text-indigo-200/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-3 w-64 rounded-xl bg-slate-900/95 border border-indigo-500/30 shadow-xl shadow-indigo-500/20 backdrop-blur p-2 space-y-1">
                    <a href="{{ route('kabag.barang.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <span>Laporan Barang</span>
                    </a>
                    <a href="{{ route('kabag.barang_masuk.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <span>Laporan Barang Masuk</span>
                    </a>
                    <a href="{{ route('kabag.inventaris-ruang.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <span>Laporan Inventaris Ruang</span>
                    </a>
                    <a href="{{ route('kabag.inventaris-ruang.riwayat') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <span>Riwayat Perpindahan</span>
                    </a>
                    <a href="{{ route('kabag.peminjaman.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <span>Laporan Peminjaman</span>
                    </a>
                    <a href="{{ route('kabag.barang-habis-pakai.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <span>Laporan Barang Keluar</span>
                    </a>
                    <a href="{{ route('kabag.barang-pinjam.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <span>Laporan Barang Pinjam</span>
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="ml-1">
                @csrf
                <button type="submit" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-sky-500 text-white font-semibold shadow-md shadow-indigo-500/40 hover:shadow-lg hover:-translate-y-px transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3-3-3-3m3 3-3 3m3-3H9" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>

        <div class="md:hidden">
            <button @click="open = !open" class="p-2 rounded-lg bg-white/10 border border-white/10 hover:bg-white/20 focus:outline-none transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div x-show="open" class="md:hidden border-t border-indigo-500/30 bg-slate-900/95 backdrop-blur px-4 pb-4 space-y-3" @click.away="open = false">
        <a href="{{ route('kabag.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75 12 4l9 5.75M4.5 10.5v8.25A1.25 1.25 0 0 0 5.75 20h12.5a1.25 1.25 0 0 0 1.25-1.25V10.5"/>
            </svg>
            Dashboard
        </a>

        <div x-data="{ laporanOpen: false }" class="border border-white/5 rounded-lg">
            <button @click="laporanOpen = !laporanOpen"
                class="w-full flex justify-between items-center px-3 py-2 text-white">
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12M8 11h12m-7 4h7M5 7h.01M5 11h.01M5 15h.01" />
                    </svg>
                    Laporan
                </span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform transition-transform duration-200"
                    :class="{ 'rotate-180': laporanOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="laporanOpen" class="border-t border-white/5 px-3 py-2 space-y-2 text-white">
                <a href="{{ route('kabag.barang.laporan') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">Laporan Barang</a>
                <a href="{{ route('kabag.barang_masuk.laporan') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">Laporan Barang Masuk</a>
                <a href="{{ route('kabag.inventaris-ruang.laporan') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">Laporan Inventaris Ruang</a>
                <a href="{{ route('kabag.inventaris-ruang.riwayat') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">Riwayat Perpindahan</a>
                <a href="{{ route('kabag.peminjaman.laporan') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">Laporan Peminjaman</a>
                <a href="{{ route('kabag.barang-habis-pakai.laporan') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">Laporan Barang Keluar</a>
                <a href="{{ route('kabag.barang-pinjam.laporan') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">Laporan Barang Pinjam</a>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-sky-500 text-white rounded-lg shadow-md shadow-indigo-500/40 hover:shadow-lg transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3-3-3-3m3 3-3 3m3-3H9" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</nav>
