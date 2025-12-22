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
                <a href="{{ route('peminjam.index') }}"><h1 class="text-lg sm:text-xl font-semibold tracking-tight hover:text-indigo-200 transition">{{ $brandName }}</h1></a>
                <div class="text-xs text-indigo-200/80 uppercase tracking-[0.25em]">Peminjam</div>
            </div>
        </div>

        <div class="hidden md:flex items-center gap-3">
            <a href="{{ route('peminjam.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75 12 4l9 5.75M4.5 10.5v8.25A1.25 1.25 0 0 0 5.75 20h12.5a1.25 1.25 0 0 0 1.25-1.25V10.5"/>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('peminjam.peminjaman.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-6-6h12" />
                </svg>
                <span>Ajukan</span>
            </a>
            <a href="{{ route('peminjam.barang-habis-pakai.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h7.5M6 9.75h12M6 12.75h12M8.25 15.75h7.5" />
                </svg>
                <span>Request Barang</span>
            </a>
            <a href="{{ route('peminjam.peminjaman.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                <svg class="w-5 h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v6.5a2.25 2.25 0 0 1-2.25 2.25h-9A2.25 2.25 0 0 1 5.25 14V7.5A2.25 2.25 0 0 1 7.5 5.25Z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9h7.5M10 12h3.5" />
                </svg>
                <span>Riwayat</span>
            </a>

            <div x-data="{ userOpen: false }" class="relative">
                <button @click="userOpen = !userOpen" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-white/5 hover:bg-white/10 transition border border-white/5 hover:border-indigo-300/40">
                    <span class="text-sm font-semibold text-indigo-100">{{ Auth::user()->nama }}</span>
                    <svg class="w-4 h-4 text-indigo-200/80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
                    </svg>
                </button>
                <div x-show="userOpen" @click.away="userOpen = false" class="absolute right-0 mt-3 w-48 rounded-xl bg-slate-900/95 border border-indigo-500/30 shadow-xl shadow-indigo-500/20 backdrop-blur p-2 space-y-1">
                    <a href="{{ route('peminjam.profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition">
                        <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25a7.5 7.5 0 0 1 15 0" />
                        </svg>
                        <span>Profil</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-indigo-900/40 transition text-left">
                            <svg class="w-5 h-5 text-rose-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3-3-3-3m3 3-3 3m3-3H9" />
                            </svg>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
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
        <a href="{{ route('peminjam.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9.75 12 4l9 5.75M4.5 10.5v8.25A1.25 1.25 0 0 0 5.75 20h12.5a1.25 1.25 0 0 0 1.25-1.25V10.5"/>
            </svg>
            Dashboard
        </a>
        <a href="{{ route('peminjam.peminjaman.create') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5 text-emerald-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-6-6h12" />
            </svg>
            Ajukan
        </a>
        <a href="{{ route('peminjam.barang-habis-pakai.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h7.5M6 9.75h12M6 12.75h12M8.25 15.75h7.5" />
            </svg>
            Request Barang
        </a>
        <a href="{{ route('peminjam.peminjaman.index') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v6.5a2.25 2.25 0 0 1-2.25 2.25h-9A2.25 2.25 0 0 1 5.25 14V7.5A2.25 2.25 0 0 1 7.5 5.25Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9h7.5M10 12h3.5" />
            </svg>
            Riwayat
        </a>
        <a href="{{ route('peminjam.profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-white hover:bg-white/10 transition">
            <svg class="w-5 h-5 text-indigo-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 20.25a7.5 7.5 0 0 1 15 0" />
            </svg>
            Profil
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-sky-500 text-white rounded-lg shadow-md shadow-indigo-500/40 hover:shadow-lg transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3-3-3-3m3 3-3 3m3-3H9" />
                </svg>
                Logout
            </button>
        </form>
    </div>
</nav>
