@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-12 -top-20 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12 space-y-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Data Peminjam</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Profil peminjam lebih informatif</h1>
                    <p class="text-indigo-50/90 mt-3 max-w-2xl">Lihat tipe peminjam (umum, mahasiswa, pegawai), kontak, dan riwayat pembaruan dengan gaya futuristik.</p>
                </div>
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama / email..."
                        class="rounded-xl bg-white/90 text-slate-900 focus:ring-2 focus:ring-indigo-400 px-4 py-2 w-60">
                    <button type="submit"
                        class="px-4 py-2 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">Cari</button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($tipeCounts as $count)
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                        <div class="relative">
                            <p class="text-sm text-indigo-100/80">Profil {{ ucfirst($count->tipe_peminjam ?? 'lainnya') }}</p>
                            <p class="text-3xl font-bold text-white mt-1">{{ $count->total }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="divide-y divide-white/10">
                    @forelse($peminjam as $index => $user)
                        <div class="p-5 hover:bg-white/5 transition">
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-white/10 border border-white/10 flex items-center justify-center text-sm font-semibold text-white">
                                        {{ $peminjam->firstItem() + $index }}
                                    </div>
                                    <div>
                                        <p class="text-sm text-indigo-100/80">Nama</p>
                                        <p class="text-base font-semibold text-white">{{ $user->nama }}</p>
                                        <p class="text-xs text-indigo-100/70">Username: {{ $user->username }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span @class([
                                        'px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-2',
                                        'bg-white/15 text-white border border-white/20' => $user->tipe_peminjam === 'umum',
                                        'bg-blue-100 text-blue-800' => $user->tipe_peminjam === 'mahasiswa',
                                        'bg-amber-100 text-amber-800' => $user->tipe_peminjam === 'pegawai',
                                    ])>
                                        {{ ucfirst($user->tipe_peminjam ?? 'Tidak diketahui') }}
                                    </span>
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs text-indigo-100/80">Updated {{ $user->updated_at?->format('d M Y H:i') ?? '-' }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm text-indigo-100/80">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-indigo-200/80">Kontak</p>
                                    <p class="text-white">{{ $user->email }}</p>
                                    <p class="text-xs text-indigo-100/70">{{ $user->nohp ? 'No HP: '.$user->nohp : 'No HP belum diisi' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-indigo-200/80">Info Tambahan</p>
                                    @if($user->tipe_peminjam === 'mahasiswa')
                                        <p>Prodi: {{ $user->prodi ?? '-' }}</p>
                                        <p>Angkatan: {{ $user->angkatan ?? '-' }}</p>
                                        <p>NIM: {{ $user->nim ?? '-' }}</p>
                                    @elseif($user->tipe_peminjam === 'pegawai')
                                        <p>Divisi: {{ $user->divisi ?? '-' }}</p>
                                    @else
                                        <p>-</p>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-indigo-200/80">Catatan</p>
                                    <p class="text-xs text-indigo-100/70">Pastikan kontak aktif untuk notifikasi status peminjaman.</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-indigo-100/80">
                            <p class="font-semibold">Belum ada data peminjam.</p>
                            <p class="text-sm mt-2 text-indigo-100/70">Peminjam baru akan muncul setelah melakukan registrasi.</p>
                        </div>
                    @endforelse
                </div>
                <div class="px-6 py-4 bg-white/5 border-t border-white/10 flex flex-col md:flex-row md:items-center md:justify-between gap-4 text-indigo-100">
                    <p class="text-sm">
                        Menampilkan {{ $peminjam->firstItem() ?? 0 }} - {{ $peminjam->lastItem() ?? 0 }} dari {{ $peminjam->total() }} peminjam
                    </p>
                    <div class="text-white">
                        {{ $peminjam->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
