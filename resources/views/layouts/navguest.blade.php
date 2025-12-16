@php
    $appConfig = $globalAppConfig ?? null;
    $useLayoutConfig = $appConfig && $appConfig->apply_layout;
    $brandName = $useLayoutConfig && $appConfig->nama_kampus ? $appConfig->nama_kampus : 'Inventaris Kampus';
    $brandLogo = $useLayoutConfig && $appConfig->logo ? asset('storage/'.$appConfig->logo) : asset('images/inven.png');
@endphp
<nav class="fixed top-0 left-0 right-0 z-50">
    <div class="mx-auto max-w-7xl px-4 py-3">
        <div class="flex items-center justify-between rounded-2xl bg-white/10 backdrop-blur-xl border border-white/15 shadow-lg px-4 py-2">
            <!-- Logo + Title -->
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 rounded-xl bg-white/10 border border-white/20 flex items-center justify-center overflow-hidden">
                    <img src="{{ $brandLogo }}" class="w-10 h-10 object-contain" alt="Logo">
                </div>
                <div>
                    <p class="text-xs uppercase tracking-[0.2em] text-white/70">Sistem Inventaris</p>
                    <h1 class="text-lg font-bold text-white">{{ $brandName }}</h1>
                </div>
            </div>

            <!-- Navigation -->
            <div class="hidden md:flex items-center space-x-6 text-sm font-semibold text-white/80">
                <a href="#fitur" class="hover:text-white transition">Fitur</a>
                <a href="#cara" class="hover:text-white transition">Cara</a>
                <a href="#kontak" class="hover:text-white transition">Kontak</a>
                <a href="/login" class="px-4 py-2 rounded-xl bg-white text-indigo-700 shadow hover:shadow-lg transition">Login</a>
            </div>

            <!-- Mobile -->
            <div class="md:hidden">
                <a href="/login" class="px-3 py-2 rounded-xl bg-white text-indigo-700 text-sm font-semibold shadow">Login</a>
            </div>
        </div>
    </div>
</nav>
