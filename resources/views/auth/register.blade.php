<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold text-indigo-600 uppercase tracking-[0.3em]">Registrasi</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Buat akun baru 🔐</h1>
            <p class="text-sm text-gray-500">Isi data Anda untuk mengakses dashboard inventaris.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="nama" class="text-sm font-semibold text-gray-700">Nama</label>
                <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required
                    class="mt-1 w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                <x-input-error :messages="$errors->get('nama')" class="mt-2" />
            </div>

            <div>
                <label for="username" class="text-sm font-semibold text-gray-700">Username</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required
                    class="mt-1 w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                <x-input-error :messages="$errors->get('username')" class="mt-2" />
            </div>

            <div>
                <label for="nohp" class="text-sm font-semibold text-gray-700">No HP</label>
                <input id="nohp" type="text" name="nohp" value="{{ old('nohp') }}"
                    class="mt-1 w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                <x-input-error :messages="$errors->get('nohp')" class="mt-2" />
            </div>

            <div>
                <label for="email" class="text-sm font-semibold text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                    class="mt-1 w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" required autocomplete="new-password"
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
                <div>
                    <label for="password_confirmation" class="text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                            class="mt-1 w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pr-12 text-gray-900">
                        <button type="button" data-toggle="password" data-target="password_confirmation"
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
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="pt-2 flex flex-col gap-3">
                <button type="submit"
                    class="w-full inline-flex justify-center rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 text-white font-semibold shadow-lg hover:shadow-xl transition">
                    Daftar & Masuk
                </button>
                <a href="{{ route('login') }}" class="w-full inline-flex justify-center rounded-xl border border-indigo-200 px-4 py-3 text-indigo-700 font-semibold hover:bg-indigo-50 transition">
                    Sudah punya akun? Login
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
