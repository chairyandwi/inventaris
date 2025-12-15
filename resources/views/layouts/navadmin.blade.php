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
                <a href="{{ route('admin.index') }}" class="text-lg sm:text-xl font-semibold tracking-tight hover:text-indigo-200 transition">{{ $brandName }}</a>
                <div class="text-xs text-indigo-200/80 uppercase tracking-[0.25em]">Admin Panel</div>
            </div>
        </div>

        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('admin.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75 12 4l9 5.75M4.5 10.5v8.25A1.25 1.25 0 0 0 5.75 20h12.5a1.25 1.25 0 0 0 1.25-1.25V10.5"/>
                </svg>
                <span>Dashboard</span>
            </a>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                    <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h7m-7 6h7m-7 6h7M14 6h6M14 12h6m-6 6h6"/>
                    </svg>
                    <span>Data</span>
                    <svg class="w-4 h-4 text-indigo-200/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-3 w-60 rounded-xl bg-slate-900/95 border border-indigo-500/30 shadow-xl shadow-indigo-500/20 backdrop-blur p-2 space-y-1">
                    <a href="{{ route('admin.kategori.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.25h4.75c.69 0 1.25.56 1.25 1.25V11H4.5a2 2 0 0 1-2-2V7.25a2 2 0 0 1 2-2Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 5.25h5a1.75 1.75 0 0 1 1.75 1.75v1.75A1.75 1.75 0 0 1 18.5 10.5H13.5V6.5c0-.69.56-1.25 1.25-1.25Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 13.5h4.75c.69 0 1.25.56 1.25 1.25v3.25H4.5a2 2 0 0 1-2-2v-.25a2 2 0 0 1 2-2Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 13.5h5a1.75 1.75 0 0 1 1.75 1.75v1.75a1.75 1.75 0 0 1-1.75 1.75H13.5v-4.5c0-.69.56-1.25 1.25-1.25Z" />
                        </svg>
                        <span>Kategori</span>
                    </a>
                    <a href="{{ route('admin.ruang.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5V8.25L12 4l7.5 4.25V19.5m-11.25 0v-6h6.5v6" />
                        </svg>
                        <span>Ruang</span>
                    </a>
                    <a href="{{ route('admin.barang.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 7.5 8.25-4.5 8.25 4.5L12 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5v9l8.25 4.5 8.25-4.5v-9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 12 8.25 4.5 8.25-4.5" />
                        </svg>
                        <span>Barang</span>
                    </a>
                    <a href="{{ route('admin.user.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                        <span>User</span>
                    </a>
                    <a href="{{ route('admin.inventaris-ruang.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6.75h16M4 12h16M4 17.25h10" />
                        </svg>
                        <span>Inventaris Ruang</span>
                    </a>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                    <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8" />
                    </svg>
                    <span>Transaksi</span>
                    <svg class="w-4 h-4 text-indigo-200/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-3 w-56 rounded-xl bg-slate-900/95 border border-indigo-500/30 shadow-xl shadow-indigo-500/20 backdrop-blur p-2 space-y-1">
                    <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-emerald-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-6-6h12" />
                        </svg>
                        <span>Peminjaman</span>
                    </a>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                    <svg class="w-5 h-5 text-sky-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12M8 11h12m-7 4h7M5 7h.01M5 11h.01M5 15h.01" />
                    </svg>
                    <span>Laporan</span>
                    <svg class="w-4 h-4 text-indigo-200/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-3 w-64 rounded-xl bg-slate-900/95 border border-indigo-500/30 shadow-xl shadow-indigo-500/20 backdrop-blur p-2 space-y-1">
                    <a href="{{ route('admin.barang.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 0 1 2-2h8" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l3-3m0 0 3 3m-3-3v12" />
                        </svg>
                        <span>Laporan Barang</span>
                    </a>
                    <a href="{{ route('admin.inventaris-ruang.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h10" />
                        </svg>
                        <span>Laporan Inventaris Ruang</span>
                    </a>
                    <a href="{{ route('admin.peminjaman.laporan') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12M8 11h12m-7 4h7M5 7h.01M5 11h.01M5 15h.01" />
                        </svg>
                        <span>Laporan Peminjaman</span>
                    </a>
                </div>
            </div>

            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                    <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5h10.5a1.5 1.5 0 0 1 1.5 1.5v7.25a2.25 2.25 0 0 1-2.25 2.25H7.5A2.25 2.25 0 0 1 5.25 16V9a1.5 1.5 0 0 1 1.5-1.5Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 10.5h6m-6 3h3m-3-6V6.75A1.75 1.75 0 0 1 10.75 5h2.5A1.75 1.75 0 0 1 15 6.75V7.5" />
                    </svg>
                    <span>Aplikasi</span>
                    <svg class="w-4 h-4 text-indigo-200/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-3 w-64 rounded-xl bg-slate-900/95 border border-indigo-500/30 shadow-xl shadow-indigo-500/20 backdrop-blur p-2 space-y-1">
                    <a href="{{ route('admin.aplikasi.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3h4.5m-4.5 0H7.5A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h9a2.25 2.25 0 0 0 2.25-2.25V8.25l-4.5-5.25h-4.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 11.25h4.5m-4.5 3h4.5" />
                        </svg>
                        <span>Konfigurasi Aplikasi</span>
                    </a>
                    <a href="{{ route('admin.logs.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5h7.5m-7.5 15h7.5m-10.5-3h13.5a1.5 1.5 0 0 0 1.5-1.5V9a1.5 1.5 0 0 0-1.5-1.5H5.25A1.5 1.5 0 0 0 3.75 9v5.25a1.5 1.5 0 0 0 1.5 1.5Z" />
                        </svg>
                        <span>Log Aktivitas</span>
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
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div class="md:hidden border-t border-indigo-500/30 bg-slate-900/95 backdrop-blur" x-show="open" @click.away="open = false">
        <div class="px-4 py-3 space-y-3 text-white">
            <a href="{{ route('admin.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-white/10 transition">
                <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75 12 4l9 5.75M4.5 10.5v8.25A1.25 1.25 0 0 0 5.75 20h12.5a1.25 1.25 0 0 0 1.25-1.25V10.5"/>
                </svg>
                Dashboard
            </a>

            <div x-data="{ dataOpen: false }" class="border border-white/5 rounded-lg">
                <button @click="dataOpen = !dataOpen"
                    class="w-full flex justify-between items-center px-3 py-2 text-white">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h7m-7 6h7m-7 6h7M14 6h6M14 12h6m-6 6h6"/>
                        </svg>
                        Data
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform transition-transform duration-200"
                        :class="{ 'rotate-180': dataOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="dataOpen" class="border-t border-white/5 px-3 py-2 space-y-2 text-white">
                    <a href="{{ route('admin.kategori.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 5.25h4.75c.69 0 1.25.56 1.25 1.25V11H4.5a2 2 0 0 1-2-2V7.25a2 2 0 0 1 2-2Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 5.25h5a1.75 1.75 0 0 1 1.75 1.75v1.75A1.75 1.75 0 0 1 18.5 10.5H13.5V6.5c0-.69.56-1.25 1.25-1.25Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 13.5h4.75c.69 0 1.25.56 1.25 1.25v3.25H4.5a2 2 0 0 1-2-2v-.25a2 2 0 0 1 2-2Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 13.5h5a1.75 1.75 0 0 1 1.75 1.75v1.75a1.75 1.75 0 0 1-1.75 1.75H13.5v-4.5c0-.69.56-1.25 1.25-1.25Z" />
                        </svg>
                        Kategori
                    </a>
                    <a href="{{ route('admin.ruang.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 19.5V8.25L12 4l7.5 4.25V19.5m-11.25 0v-6h6.5v6" />
                        </svg>
                        Ruang
                    </a>
                    <a href="{{ route('admin.barang.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 7.5 8.25-4.5 8.25 4.5L12 12z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 7.5v9l8.25 4.5 8.25-4.5v-9" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 12 8.25 4.5 8.25-4.5" />
                        </svg>
                        Barang
                    </a>
                    <a href="{{ route('admin.user.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                        User
                    </a>
                    <a href="{{ route('admin.inventaris-ruang.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6.75h16M4 12h16M4 17.25h10" />
                        </svg>
                        Inventaris Ruang
                    </a>
                </div>
            </div>

            <div x-data="{ transaksiOpen: false }" class="border border-white/5 rounded-lg">
                <button @click="transaksiOpen = !transaksiOpen"
                    class="w-full flex justify-between items-center px-3 py-2 text-white">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v8m-4-4h8" />
                        </svg>
                        Transaksi
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform transition-transform duration-200"
                        :class="{ 'rotate-180': transaksiOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="transaksiOpen" class="border-t border-white/5 px-3 py-2 space-y-2 text-white">
                    <a href="{{ route('admin.barang_masuk.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-6-6h12" />
                        </svg>
                        Barang Masuk
                    </a>
                    <a href="{{ route('admin.peminjaman.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                        </svg>
                        Peminjaman
                    </a>
                </div>
            </div>

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
                    <a href="{{ route('admin.barang.laporan') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-amber-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6a2 2 0 0 1 2-2h8" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l3-3m0 0 3 3m-3-3v12" />
                        </svg>
                        Laporan Barang
                    </a>
                    <a href="{{ route('admin.inventaris-ruang.laporan') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h10M4 18h10" />
                        </svg>
                        Laporan Inventaris Ruang
                    </a>
                    <a href="{{ route('admin.peminjaman.laporan') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12M8 11h12m-7 4h7M5 7h.01M5 11h.01M5 15h.01" />
                        </svg>
                        Laporan Peminjaman
                    </a>
                </div>
            </div>

            <div x-data="{ aplikasiOpen: false }" class="border border-white/5 rounded-lg">
                <button @click="aplikasiOpen = !aplikasiOpen"
                    class="w-full flex justify-between items-center px-3 py-2 text-white">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 7.5h10.5a1.5 1.5 0 0 1 1.5 1.5v7.25a2.25 2.25 0 0 1-2.25 2.25H12A2.25 2.25 0 0 1 9.75 16V9a1.5 1.5 0 0 1 1.5-1.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 10.5h6m-6 3h3m-3-6V6.75A1.75 1.75 0 0 1 10.75 5h2.5A1.75 1.75 0 0 1 15 6.75V7.5" />
                        </svg>
                        Aplikasi
                    </span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 transform transition-transform duration-200"
                        :class="{ 'rotate-180': aplikasiOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="aplikasiOpen" class="border-t border-white/5 px-3 py-2 space-y-2 text-white">
                    <a href="{{ route('admin.aplikasi.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3h4.5m-4.5 0H7.5A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h9a2.25 2.25 0 0 0 2.25-2.25V8.25l-4.5-5.25h-4.5Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 11.25h4.5m-4.5 3h4.5" />
                        </svg>
                        Konfigurasi Aplikasi
                    </a>
                    <a href="{{ route('admin.logs.index') }}" class="flex items-center gap-2 rounded-lg px-2 py-1 hover:bg-white/10 transition">
                        <svg class="w-5 h-5 text-amber-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5h7.5m-7.5 15h7.5m-10.5-3h13.5a1.5 1.5 0 0 0 1.5-1.5V9a1.5 1.5 0 0 0-1.5-1.5H5.25A1.5 1.5 0 0 0 3.75 9v5.25a1.5 1.5 0 0 0 1.5 1.5Z" />
                        </svg>
                        Log Aktivitas
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-indigo-500/30 px-4 py-3">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-sky-500 text-white font-semibold shadow-md shadow-indigo-500/40 hover:shadow-lg transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3-3-3-3m3 3-3 3m3-3H9" />
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>
