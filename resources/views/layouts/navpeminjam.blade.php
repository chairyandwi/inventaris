@php
    $appConfig = $globalAppConfig ?? null;
    $useLayoutConfig = $appConfig && $appConfig->apply_layout;
    $brandName = $useLayoutConfig && $appConfig->nama_kampus ? $appConfig->nama_kampus : 'Inventaris Kampus';
    $brandLogo = $useLayoutConfig && $appConfig->logo ? asset('storage/'.$appConfig->logo) : asset('images/inven.png');
@endphp
<nav x-data="{ open: false, menu: false }" class="bg-white shadow-md fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <img src="{{ $brandLogo }}" class="w-12 h-12 object-contain" alt="Logo">
            <a href="{{ route('peminjam.index') }}" class="text-xl font-bold text-indigo-600">{{ $brandName }}</a>
        </div>

        <div class="md:hidden">
            <button @click="open = !open" class="text-gray-700 hover:text-indigo-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                        stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="hidden md:flex items-center space-x-6">
            <a href="{{ route('peminjam.index') }}" class="text-gray-700 hover:text-indigo-600">Dashboard</a>
            <a href="{{ route('peminjam.peminjaman.create') }}" class="text-gray-700 hover:text-indigo-600">Ajukan</a>
            <a href="{{ route('peminjam.peminjaman.index') }}" class="text-gray-700 hover:text-indigo-600">Riwayat</a>

            <div class="relative" x-data="{ userMenu: false }">
                <button @click="userMenu = !userMenu" class="flex items-center text-gray-700 hover:text-indigo-600">
                    <span class="mr-2 text-sm font-semibold">{{ Auth::user()->nama }}</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="userMenu" @click.away="userMenu = false"
                    class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg py-2 z-50">
                    <a href="{{ route('peminjam.profile.edit') }}"
                        class="block px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">Profil</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="md:hidden" x-show="open" @click.away="open = false">
        <div class="px-4 pb-4 space-y-2">
            <a href="{{ route('peminjam.index') }}" class="block text-gray-700 hover:text-indigo-600">Dashboard</a>
            <a href="{{ route('peminjam.peminjaman.create') }}" class="block text-gray-700 hover:text-indigo-600">Ajukan Peminjaman</a>
            <a href="{{ route('peminjam.peminjaman.index') }}" class="block text-gray-700 hover:text-indigo-600">Riwayat Peminjaman</a>
            <a href="{{ route('peminjam.profile.edit') }}" class="block text-gray-700 hover:text-indigo-600">Profil</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full text-left px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                    Logout
                </button>
            </form>
        </div>
    </div>
</nav>
