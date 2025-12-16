<x-guest-layout>
    <div class="space-y-6">
        <div>
            <p class="text-xs font-semibold text-indigo-600 uppercase tracking-[0.3em]">Registrasi</p>
            <h1 class="text-2xl font-bold text-gray-900 mt-1">Buat akun baru 🔐</h1>
            <p class="text-sm text-gray-500">Isi data Anda untuk mengakses dashboard futuristik inventaris.</p>
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
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                        class="mt-1 w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div>
                    <label for="password_confirmation" class="text-sm font-semibold text-gray-700">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                        class="mt-1 w-full rounded-xl border border-gray-200 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
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
