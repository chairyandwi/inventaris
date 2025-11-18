<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Inventory Management System') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @auth
            @if (Auth::user()->role === 'admin')
                @include('layouts.navadmin')
            @elseif(Auth::user()->role === 'kabag')
                @include('layouts.navkabag')
            @elseif(Auth::user()->role === 'pegawai')
                @include('layouts.navpegawai')
            @elseif(Auth::user()->role === 'peminjam')
                @include('layouts.navpeminjam')
            @else
                @include('layouts.navguest')
            @endif
        @else
            @include('layouts.navguest')
        @endauth

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow fixed top-0 w-full">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @elseif (View::hasSection('header'))
            <header class="bg-white shadow fixed top-0 w-full">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>

        @include('layouts.footer-guest')
    </div>

    @stack('scripts')
</body>

</html>
