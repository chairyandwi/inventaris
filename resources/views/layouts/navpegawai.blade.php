<nav x-data="{ open: false }" class="bg-white shadow-md fixed top-0 left-0 right-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">

        <!-- Logo + Title -->
        <div class="flex items-center space-x-3">
            <img src="{{ asset('images/inven.png') }}" class="w-12 h-12 object-contain" alt="Logo">
            <a href="{{ route('pegawai.index') }}"><h1 class="text-xl font-bold text-indigo-600">Inventaris Kampus</h1></a>
        </div>

        <!-- Hamburger (Mobile) -->
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

        <!-- Navigation (Desktop) -->
        <div class="hidden md:flex items-center space-x-6">
            <!-- Dropdown Data -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-gray-700 hover:text-indigo-600 flex items-center">
                    Data
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-52 bg-white border rounded-lg shadow-lg py-2 z-50">
                    <a href="{{ route('pegawai.kategori.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">                       
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg> 
                        Kategori
                    </a>
                    <a href="{{ route('pegawai.ruang.index') }}"" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                          </svg>                          
                        Ruang
                    </a>
                    <a href="{{ route('pegawai.barang.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 50 50">
                            <g fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="#344054" stroke-width="2" d="M35.417 42.708h.208m-12.708 0h.208z"/>
                                <path stroke="#306cfe" stroke-width="2" d="M6.25 6.25h4.458a2.08 2.08 0 0 1 2.084 1.77l1 6.563l2.875 18.75l22.916-2.083l4.167-16.667H13.792"/>
                            </g>
                        </svg>                                                
                        Barang
                    </a>
                    <a href="{{ route('pegawai.user.index') }}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                        </svg>                                                                        
                        User
                    </a>
                </div>
            </div>

            <!-- Dropdown Transaksi -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" class="text-gray-700 hover:text-indigo-600 flex items-center">
                    Transaksi
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" @click.away="open = false"
                    class="absolute right-0 mt-2 w-56 bg-white border rounded-lg shadow-lg py-2 z-50">
                    <a href="{{route('pegawai.peminjaman.index')}}" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status Peminjaman
                    </a>
                    <a href="#" class="flex items-center px-4 py-2 text-gray-700 hover:bg-indigo-50 hover:text-indigo-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                        </svg>                          
                        Request Peminjaman
                    </a>
                </div>
            </div>

            <!-- Links -->
            <a href="#cara" class="text-gray-700 hover:text-indigo-600">Laporan</a>


            <!-- Logout -->
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg shadow hover:bg-indigo-700 transition-colors duration-200">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" class="md:hidden px-4 pb-4 space-y-2">
        <!-- Dropdown Data (Mobile) -->
        <div x-data="{ dataOpen: false }" class="border-b pb-2">
            <button @click="dataOpen = !dataOpen"
                class="w-full flex justify-between items-center text-gray-700 hover:text-indigo-600">
                Data
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 transform transition-transform duration-200"
                    :class="{ 'rotate-180': dataOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="dataOpen" class="mt-2 space-y-1 pl-4">
                <a href="#" class="flex items-center text-gray-700 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                    </svg> 
                    Kategori
                </a>
                <a href="#" class="flex items-center text-gray-700 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>   
                    Ruang
                </a>
                <a href="#" class="flex items-center text-gray-700 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 50 50">
                        <g fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="#344054" stroke-width="2" d="M35.417 42.708h.208m-12.708 0h.208z"/>
                            <path stroke="#306cfe" stroke-width="2" d="M6.25 6.25h4.458a2.08 2.08 0 0 1 2.084 1.77l1 6.563l2.875 18.75l22.916-2.083l4.167-16.667H13.792"/>
                        </g>
                    </svg> 
                    Barang
                </a>
                <a href="#" class="flex items-center text-gray-700 hover:text-indigo-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>  
                    User
                </a>
            </div>
        </div>

        <!-- Dropdown Transaksi (Mobile) -->
        <div x-data="{ transaksiOpen: false }" class="border-b pb-2">
            <button @click="transaksiOpen = !transaksiOpen"
                class="w-full flex justify-between items-center text-gray-700 hover:text-indigo-600">
                Transaksi
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 ml-2 transform transition-transform duration-200"
                    :class="{ 'rotate-180': transaksiOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="transaksiOpen" class="mt-2 space-y-1 pl-4">
                <a href="#" class="flex items-center text-gray-700 hover:text-indigo-600">
                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Barang Masuk
                </a>
                <a href="#" class="flex items-center text-gray-700 hover:text-indigo-600">
                    <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 12H6"/>
                    </svg>
                    Barang Keluar
                </a>
                <a href="#" class="flex items-center text-gray-700 hover:text-indigo-600">
                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Barang Dipinjam
                </a>
            </div>
        </div>

        <!-- Links -->
        <a href="#cara" class="block text-gray-700 hover:text-indigo-600">Laporan</a>


        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full text-left px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200">
                Logout
            </button>
        </form>
    </div>

</nav>