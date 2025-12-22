@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
    @php
        $routePrefix = (auth()->check() && auth()->user()->role === 'admin') ? 'admin.peminjaman' : 'pegawai.peminjaman';
        $homeRoute = (auth()->check() && auth()->user()->role === 'admin') ? 'admin.index' : 'pegawai.index';
        $statusOptions = [
            '' => 'Semua Status',
            'pending' => 'Menunggu',
            'dipinjam' => 'Dipinjam',
            'dikembalikan' => 'Dikembalikan',
            'ditolak' => 'Ditolak',
        ];
        $activeStatus = request('status', '');
    @endphp

    <div class="min-h-screen bg-slate-950 text-white">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
            <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
            <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                    <div>
                        <div class="flex items-center gap-3 text-indigo-100/80 text-xs uppercase tracking-[0.25em]">
                            <a href="{{ route($homeRoute) }}" class="inline-flex items-center gap-2 text-indigo-100 hover:text-white transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                                Kembali
                            </a>
                            <span>Pelaporan</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-3">Laporan Peminjaman</h1>
                        <p class="mt-3 text-indigo-50/90 max-w-2xl">
                            Pantau riwayat peminjaman, status terkini, serta detail peminjam untuk keperluan evaluasi.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="rounded-2xl border border-white/10 bg-white/10 px-5 py-4 backdrop-blur shadow-lg shadow-indigo-500/20">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Entri</p>
                            <p class="text-2xl font-bold mt-2">{{ $peminjaman->total() }}</p>
                            <p class="text-xs text-indigo-100/80 mt-1">Data tersaring</p>
                        </div>
                        <button type="submit"
                            form="peminjaman-filter"
                            formaction="{{ route($routePrefix . '.cetak') }}"
                            formtarget="_blank"
                            class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                            </svg>
                            Cetak PDF
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative -mt-10 pb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="bg-slate-900/70 border border-white/10 rounded-2xl shadow-lg shadow-indigo-500/10 backdrop-blur">
                    <form id="peminjaman-filter" method="GET" action="{{ route($routePrefix . '.laporan') }}" class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="text-sm font-semibold text-indigo-100 mb-2 block">Status</label>
                                <select name="status" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                    @foreach($statusOptions as $value => $label)
                                        <option class="text-slate-900" value="{{ $value }}" @selected($activeStatus === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-indigo-100 mb-2 block">Tanggal Awal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}"
                                    class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            </div>
                            <div>
                                <label class="text-sm font-semibold text-indigo-100 mb-2 block">Tanggal Akhir</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}"
                                    class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            </div>
                            <div class="flex items-end gap-2">
                                <button type="submit"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z" />
                                    </svg>
                                    Terapkan
                                </button>
                                <a href="{{ route($routePrefix . '.laporan') }}"
                                    class="inline-flex items-center justify-center px-4 py-2 rounded-xl border border-white/15 text-sm font-semibold text-indigo-50 hover:bg-white/10 transition">
                                    Reset
                                </a>
                            </div>
                        </div>

                        @if(request('status') || request('start_date') || request('end_date'))
                            <div class="flex flex-wrap gap-2 text-sm text-indigo-100">
                                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10">Filter aktif:</span>
                                @if(request('status'))
                                    <span class="px-3 py-1 rounded-full bg-indigo-500/30 border border-indigo-200/30">Status: {{ $statusOptions[request('status')] ?? 'Semua' }}</span>
                                @endif
                                @if(request('start_date'))
                                    <span class="px-3 py-1 rounded-full bg-sky-500/30 border border-sky-200/30">Mulai: {{ request('start_date') }}</span>
                                @endif
                                @if(request('end_date'))
                                    <span class="px-3 py-1 rounded-full bg-emerald-500/30 border border-emerald-200/30">Selesai: {{ request('end_date') }}</span>
                                @endif
                            </div>
                        @endif
                    </form>
                </div>

                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-[1200px] w-full divide-y divide-white/10">
                            <thead class="bg-white/5">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Barang</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Peminjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Detail Peminjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Kegiatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Jumlah</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Tgl Pinjam</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Tgl Kembali</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($peminjaman as $index => $p)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="px-6 py-4 text-sm text-indigo-50">{{ $peminjaman->firstItem() + $index }}</td>
                                        <td class="px-6 py-4 text-sm text-white">{{ $p->barang->nama_barang ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-white">{{ $p->user->nama ?? $p->user->username ?? $p->user->email ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-indigo-100/80">
                                            <div class="space-y-1">
                                                <p><span class="font-semibold text-white">Profil:</span> {{ ucfirst($p->user->tipe_peminjam ?? 'Tidak diketahui') }}</p>
                                                @if($p->user?->tipe_peminjam === 'mahasiswa')
                                                    <p><span class="font-semibold text-white">Prodi:</span> {{ $p->user->prodi ?? '-' }}</p>
                                                    <p><span class="font-semibold text-white">Angkatan:</span> {{ $p->user->angkatan ?? '-' }}</p>
                                                    <p><span class="font-semibold text-white">NIM:</span> {{ $p->user->nim ?? '-' }}</p>
                                                @elseif($p->user?->tipe_peminjam === 'pegawai')
                                                    <p><span class="font-semibold text-white">Divisi:</span> {{ $p->user->divisi ?? '-' }}</p>
                                                @endif
                                                <p>
                                                    <span class="font-semibold text-white">Foto:</span>
                                                    @if($p->foto_identitas)
                                                        <a href="{{ asset('storage/'.$p->foto_identitas) }}" target="_blank" class="text-indigo-200 hover:text-white transition">Lihat</a>
                                                    @else
                                                        <span class="text-indigo-100/60">Belum diunggah</span>
                                                    @endif
                                                </p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-indigo-100/80">
                                            <p class="font-semibold text-white">{{ $p->kegiatan === 'kampus' ? 'Kampus' : 'Luar Kampus' }}</p>
                                            <p class="text-xs text-indigo-100/60">{{ $p->keterangan_kegiatan ?? '-' }}</p>
                                            @if($p->kegiatan === 'kampus')
                                                <p class="text-xs text-indigo-100/60">Ruang: {{ $p->ruang->nama_ruang ?? '-' }}</p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-white">{{ $p->jumlah }}</td>
                                        <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $p->tgl_pinjam ? $p->tgl_pinjam->format('d-m-Y H:i') : '-' }}</td>
                                        <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $p->tgl_kembali ? $p->tgl_kembali->format('d-m-Y H:i') : '-' }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span @class([
                                                'px-2.5 py-1 rounded-full text-xs font-semibold',
                                                'bg-amber-100 text-amber-700' => $p->status === 'pending',
                                                'bg-sky-100 text-sky-700' => $p->status === 'dipinjam',
                                                'bg-emerald-100 text-emerald-700' => $p->status === 'dikembalikan',
                                                'bg-rose-100 text-rose-700' => $p->status === 'ditolak',
                                            ])>
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-16 text-center text-sm text-indigo-100/70">
                                            <div class="flex flex-col items-center gap-3">
                                                <div class="h-12 w-12 rounded-full bg-white/10 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-indigo-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7h18M3 12h18M3 17h9" />
                                                    </svg>
                                                </div>
                                                <p class="text-base font-semibold text-white">Belum ada data peminjaman</p>
                                                <p>Sesuaikan filter untuk menemukan data yang dibutuhkan.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100 flex items-center justify-between">
                        <div>
                            Menampilkan {{ $peminjaman->firstItem() ?? 0 }} - {{ $peminjaman->lastItem() ?? 0 }} dari {{ $peminjaman->total() }} entri
                        </div>
                        <div class="text-white">
                            {{ $peminjaman->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
