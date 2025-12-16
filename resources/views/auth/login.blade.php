<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold text-indigo-600 uppercase tracking-[0.3em]">Masuk</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Selamat datang kembali 👋</h1>
            <p class="text-sm text-gray-500">Login untuk mengelola stok, ruang, dan peminjaman.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="text-sm font-semibold text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="mt-1 w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                <div class="relative">
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="mt-1 w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pr-12 text-gray-900">
                    <button type="button" data-toggle="password" data-target="password"
                        class="absolute inset-y-0 right-2 my-auto flex items-center justify-center w-8 h-8 rounded-lg text-indigo-600 hover:bg-indigo-50"
                        aria-label="Toggle password visibility">
                        <svg class="w-5 h-5" data-eye="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="w-5 h-5 hidden" data-eye="hide" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.052 10.052 0 013.187-4.56M6.18 6.18A9.964 9.964 0 0112 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center text-sm text-gray-600">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" name="remember">
                    <span class="ml-2">Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold text-indigo-600 hover:text-indigo-700" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <div class="pt-2 flex flex-col gap-3">
                <button type="submit"
                    class="w-full inline-flex justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 text-white font-semibold shadow-lg hover:shadow-xl transition">
                    Masuk ke Dashboard
                </button>
                <a href="{{ route('register') }}" class="w-full inline-flex justify-center rounded-xl border border-indigo-200 px-4 py-3 text-indigo-700 font-semibold hover:bg-indigo-50 transition">
                    Daftar akun baru
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-toggle="password"]').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.getAttribute('data-target');
                const input = document.getElementById(targetId);
                if (!input) return;
                const showIcon = btn.querySelector('[data-eye="show"]');
                const hideIcon = btn.querySelector('[data-eye="hide"]');
                const isHidden = input.type === 'password';
                input.type = isHidden ? 'text' : 'password';
                if (showIcon && hideIcon) {
                    showIcon.classList.toggle('hidden', !isHidden);
                    hideIcon.classList.toggle('hidden', isHidden);
                }
            });
        });
    });
</script>
