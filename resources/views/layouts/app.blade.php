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

<body class="font-sans antialiased bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950">
    <div class="min-h-screen flex flex-col">
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
        <main class="flex-1">
            @isset($slot)
                {{ $slot }}
            @else
                @yield('content')
            @endisset
        </main>

        @include('layouts.footer-guest')
    </div>

    @php
        $flashTypes = [
            'success' => ['title' => 'Berhasil', 'color' => 'emerald'],
            'error' => ['title' => 'Gagal', 'color' => 'rose'],
            'warning' => ['title' => 'Peringatan', 'color' => 'amber'],
            'info' => ['title' => 'Info', 'color' => 'sky'],
        ];
        $flashMessages = collect($flashTypes)
            ->map(function ($meta, $key) {
                return ['type' => $key, 'meta' => $meta, 'message' => session($key)];
            })
            ->filter(fn ($item) => !empty($item['message']));
    @endphp

    @if($flashMessages->isNotEmpty())
        <div class="fixed top-4 right-4 z-50 space-y-3 max-w-sm">
            @foreach($flashMessages as $flash)
                @php $color = $flash['meta']['color']; @endphp
                <div class="flash-card rounded-2xl border border-{{ $color }}-300/40 bg-slate-900/95 shadow-xl shadow-{{ $color }}-500/20 backdrop-blur text-white p-4 flex items-start gap-3 animate-[fadeIn_0.3s_ease-out]">
                    <div class="mt-1">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-{{ $color }}-500/20 border border-{{ $color }}-300/40 text-{{ $color }}-200">
                            @if($flash['type'] === 'success')
                                ✓
                            @elseif($flash['type'] === 'error')
                                !
                            @elseif($flash['type'] === 'warning')
                                ⚠
                            @else
                                ℹ
                            @endif
                        </span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-{{ $color }}-100">{{ $flash['meta']['title'] }}</p>
                        <p class="text-sm text-slate-100/90 leading-relaxed">{{ $flash['message'] }}</p>
                    </div>
                    <button class="text-slate-400 hover:text-white transition flash-close" aria-label="Tutup">
                        ×
                    </button>
                </div>
            @endforeach
        </div>
    @endif

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.flash-card');
            cards.forEach((card) => {
                const closer = card.querySelector('.flash-close');
                const removeCard = () => card.remove();
                closer?.addEventListener('click', removeCard);
                setTimeout(removeCard, 4500);
            });
        });
    </script>

    <!-- Modern confirm modal -->
    <div id="confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
        <div class="relative w-full max-w-md mx-4 rounded-2xl bg-slate-900 border border-white/10 shadow-2xl shadow-indigo-900/40 p-6 space-y-4 text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 border border-amber-300/40 text-amber-200 flex items-center justify-center text-lg font-bold">!</div>
                <div>
                    <p class="text-sm uppercase tracking-[0.2em] text-indigo-200/70">Konfirmasi</p>
                    <p id="confirm-message" class="text-base font-semibold text-white mt-1"></p>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="confirm-cancel" class="px-4 py-2 rounded-xl bg-white/10 border border-white/15 text-slate-200 hover:bg-white/15 transition">Batal</button>
                <button type="button" id="confirm-ok" class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">Ya</button>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const modal = document.getElementById('confirm-modal');
            if (!modal) return;
            const msgEl = document.getElementById('confirm-message');
            const btnOk = document.getElementById('confirm-ok');
            const btnCancel = document.getElementById('confirm-cancel');
            let resolver = null;

            function openConfirm(message) {
                msgEl.textContent = message || 'Yakin melanjutkan?';
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                return new Promise((resolve) => {
                    resolver = resolve;
                });
            }

            function closeConfirm(result) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (resolver) resolver(result);
                resolver = null;
            }

            btnOk.addEventListener('click', () => closeConfirm(true));
            btnCancel.addEventListener('click', () => closeConfirm(false));
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeConfirm(false);
            });

            window.smartConfirm = openConfirm;

            // Auto-bind forms with data-confirm attribute
            document.addEventListener('submit', async (e) => {
                const form = e.target;
                if (!(form instanceof HTMLFormElement)) return;
                const message = form.dataset.confirm;
                if (!message) return;
                e.preventDefault();
                const ok = await openConfirm(message);
                if (ok) form.submit();
            });
        })();
    </script>

    @stack('scripts')
</body>

</html>
