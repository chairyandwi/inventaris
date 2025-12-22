@php
    $tipeAwal = old('tipe_peminjam', $user->tipe_peminjam);
    $isPeminjamProfileRoute = request()->routeIs('peminjam.profile.*');
    $profileUpdateRouteName = $isPeminjamProfileRoute ? 'peminjam.profile.update' : 'profile.update';
    $prodiOptions = [
        'Teknik Industri',
        'Administrasi Publik',
        'Manajemen',
        'Psikologi',
        'Hukum',
        'Teknologi Informasi',
        'Teknik Lingkungan',
        'Teknik Perminyakan',
        'Teknik Mesin',
    ];
    $tahunAngkatan = range(date('Y'), date('Y') - 10);
@endphp

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Profil Peminjam
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Lengkapi informasi diri Anda sehingga petugas mudah memverifikasi permintaan peminjaman.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route($profileUpdateRouteName) }}" class="mt-6 space-y-6" x-data="{ tipe: @js($tipeAwal) }" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="nama" value="Nama Lengkap" />
                <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" :value="old('nama', $user->nama)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('nama')" />
            </div>

            <div>
                <x-input-label for="username" value="Username" />
                <x-text-input id="username" name="username" type="text" class="mt-1 block w-full" :value="old('username', $user->username)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('username')" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="email" value="Email" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="text-sm mt-2 text-gray-800">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-600">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="nohp" value="Nomor HP" />
                <x-text-input id="nohp" name="nohp" type="text" class="mt-1 block w-full" :value="old('nohp', $user->nohp)" autocomplete="tel" placeholder="08xxxxxxxxxx" />
                <x-input-error class="mt-2" :messages="$errors->get('nohp')" />
            </div>
        </div>

        <div>
            <x-input-label for="tipe_peminjam" value="Profil Peminjam" />
            <select id="tipe_peminjam" name="tipe_peminjam" x-model="tipe" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                <option value="">-- Pilih Profil --</option>
                <option value="mahasiswa" @selected($tipeAwal === 'mahasiswa')>Mahasiswa</option>
                <option value="pegawai" @selected($tipeAwal === 'pegawai')>Pegawai Kampus</option>
            </select>
            <p class="text-xs text-gray-500 mt-1">
                Mahasiswa perlu melengkapi data studi, sedangkan pegawai kampus perlu mengisi divisi/bagian.
            </p>
            <x-input-error class="mt-2" :messages="$errors->get('tipe_peminjam')" />
        </div>

        <div x-show="tipe === 'mahasiswa'" x-cloak class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2">
                <x-input-label for="prodi" value="Program Studi" />
                <select id="prodi" name="prodi" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Pilih Prodi --</option>
                    @foreach($prodiOptions as $prodi)
                        <option value="{{ $prodi }}" @selected(old('prodi', $user->prodi) === $prodi)>{{ $prodi }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('prodi')" />
            </div>

            <div>
                <x-input-label for="angkatan" value="Angkatan" />
                <select id="angkatan" name="angkatan" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">-- Pilih Tahun --</option>
                    @foreach($tahunAngkatan as $tahun)
                        <option value="{{ $tahun }}" @selected(old('angkatan', $user->angkatan) == $tahun)>{{ $tahun }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('angkatan')" />
            </div>
        </div>

        <div x-show="tipe === 'mahasiswa'" x-cloak>
            <x-input-label for="nim" value="NIM" />
            <x-text-input id="nim" name="nim" type="text" class="mt-1 block w-full" :value="old('nim', $user->nim)" maxlength="50" />
            <p class="text-xs text-gray-500 mt-1">NIM akan membantu petugas memverifikasi identitas peminjam.</p>
            <x-input-error class="mt-2" :messages="$errors->get('nim')" />
        </div>

        <div x-show="tipe === 'pegawai'" x-cloak>
            <x-input-label for="divisi" value="Divisi / Bagian" />
            <x-text-input id="divisi" name="divisi" type="text" class="mt-1 block w-full" :value="old('divisi', $user->divisi)" maxlength="100" />
            <p class="text-xs text-gray-500 mt-1">Isi nama divisi atau unit kerja untuk mempermudah validasi.</p>
            <x-input-error class="mt-2" :messages="$errors->get('divisi')" />
        </div>

        <div x-show="tipe === 'mahasiswa'" x-cloak>
            <x-input-label for="foto_identitas_mahasiswa" value="Foto KTM" />
            <input id="foto_identitas_mahasiswa" name="foto_identitas_mahasiswa" type="file" accept="image/*"
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
            @if($user->foto_identitas_mahasiswa)
                <p class="text-xs text-gray-500 mt-1">Tersimpan: <a class="text-indigo-600 underline" href="{{ asset('storage/'.$user->foto_identitas_mahasiswa) }}" target="_blank">Lihat</a></p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('foto_identitas_mahasiswa')" />
        </div>

        <div x-show="tipe === 'pegawai'" x-cloak>
            <x-input-label for="foto_identitas_pegawai" value="Foto ID Card" />
            <input id="foto_identitas_pegawai" name="foto_identitas_pegawai" type="file" accept="image/*"
                class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
            @if($user->foto_identitas_pegawai)
                <p class="text-xs text-gray-500 mt-1">Tersimpan: <a class="text-indigo-600 underline" href="{{ asset('storage/'.$user->foto_identitas_pegawai) }}" target="_blank">Lihat</a></p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('foto_identitas_pegawai')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>Simpan</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >Tersimpan.</p>
            @endif
        </div>
    </form>
</section>
