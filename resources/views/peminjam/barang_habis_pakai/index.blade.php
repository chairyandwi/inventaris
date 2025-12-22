@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12 space-y-8">
            <div class="bg-slate-900/80 rounded-2xl border border-white/10 shadow-xl shadow-indigo-500/10 p-6 sm:p-8">
                <div class="flex items-center justify-between gap-4 flex-wrap mb-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/70">Barang Habis Pakai</p>
                        <h1 class="text-2xl font-bold mt-2">Request Barang Habis Pakai</h1>
                        <p class="text-sm text-indigo-100/80 mt-2">Pilih barang habis pakai yang tersedia, permintaan akan diproses petugas sebelum stok berkurang.</p>
                    </div>
                    <a href="{{ route('peminjam.peminjaman.create') }}" class="text-indigo-200 hover:text-white text-sm font-semibold">
                        Ajukan Peminjaman
                    </a>
                </div>

                @if(session('success'))
                    <div class="mb-4 rounded-xl bg-emerald-500/10 border border-emerald-300/30 px-4 py-3 text-emerald-100">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-4 rounded-xl bg-rose-500/10 border border-rose-300/30 px-4 py-3 text-rose-100">
                        {{ session('error') }}
                    </div>
                @endif

                @if(isset($profilLengkap) && !$profilLengkap)
                    <div class="mb-4 rounded-xl bg-amber-500/10 border border-amber-300/30 px-4 py-3 text-amber-100">
                        Lengkapi profil Anda terlebih dahulu untuk melakukan request barang habis pakai.
                        <a href="{{ route('peminjam.profile.edit') }}" class="underline font-semibold">Lengkapi sekarang</a>.
                    </div>
                @endif

                <form method="POST" action="{{ route('peminjam.barang-habis-pakai.store') }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Barang</label>
                            <select name="idbarang" @disabled(isset($profilLengkap) && !$profilLengkap)
                                class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent disabled:opacity-60">
                                <option value="">Pilih barang</option>
                                @foreach($barang as $item)
                                    <option value="{{ $item->idbarang }}" @selected(old('idbarang') == $item->idbarang)>
                                        {{ $item->nama_barang }} (Stok: {{ $item->stok }})
                                    </option>
                                @endforeach
                            </select>
                            @error('idbarang')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-indigo-100 mb-2">Jumlah</label>
                            <input type="number" name="jumlah" min="1" value="{{ old('jumlah', 1) }}"
                                   @disabled(isset($profilLengkap) && !$profilLengkap)
                                   class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent disabled:opacity-60">
                            @error('jumlah')
                                <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-indigo-100 mb-2">Rencana Tanggal Pengambilan</label>
                        <input type="date" name="tgl_pengambilan_rencana"
                               value="{{ old('tgl_pengambilan_rencana', now()->format('Y-m-d')) }}"
                               @disabled(isset($profilLengkap) && !$profilLengkap)
                               class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent disabled:opacity-60">
                        @error('tgl_pengambilan_rencana')
                            <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-indigo-100 mb-2">Keterangan (opsional)</label>
                        <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                               @disabled(isset($profilLengkap) && !$profilLengkap)
                               class="w-full px-3 py-2 rounded-xl bg-slate-800/70 border border-white/10 text-white focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent disabled:opacity-60"
                               placeholder="Contoh: untuk kebutuhan rapat">
                        @error('keterangan')
                            <p class="mt-1 text-sm text-rose-300">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center justify-end gap-3">
                        <button type="submit" @disabled(isset($profilLengkap) && !$profilLengkap)
                            class="inline-flex items-center px-5 py-2.5 rounded-xl bg-white text-indigo-700 font-semibold shadow hover:shadow-indigo-500/40 transition disabled:opacity-60">
                            Kirim Request
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-slate-900/80 rounded-2xl border border-white/10 shadow-xl shadow-indigo-500/10 p-6 sm:p-8">
                <div class="flex items-center justify-between gap-4 flex-wrap mb-4">
                    <div>
                        <h2 class="text-lg font-semibold">Request Menunggu</h2>
                        <p class="text-sm text-indigo-100/70">Permintaan yang sedang menunggu persetujuan petugas.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr class="text-left text-xs uppercase tracking-wider text-indigo-100/70">
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Barang</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                                <th class="px-4 py-3">Rencana Ambil</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($requests as $row)
                                <tr class="hover:bg-white/5">
                                    <td class="px-4 py-3 text-sm text-slate-200">{{ $row->tgl_keluar }}</td>
                                    <td class="px-4 py-3 text-sm text-white">{{ $row->barang?->nama_barang ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-white text-right">{{ $row->jumlah }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-200">{{ $row->tgl_pengambilan_rencana ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span @class([
                                            'px-3 py-1 rounded-full text-xs font-semibold',
                                            'bg-amber-500/20 text-amber-200' => $row->status === 'pending',
                                            'bg-emerald-500/20 text-emerald-200' => $row->status === 'approved',
                                            'bg-rose-500/20 text-rose-200' => $row->status === 'rejected',
                                        ])>
                                            {{ ucfirst($row->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-300">
                                        {{ $row->keterangan ?? '-' }}
                                        @if(($row->status ?? 'pending') === 'rejected' && $row->alasan_penolakan)
                                            <div class="text-xs text-rose-200 mt-1">Alasan: {{ $row->alasan_penolakan }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-300">Belum ada request yang menunggu.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-slate-200">
                    {{ $requests->links() }}
                </div>
            </div>

            <div class="bg-slate-900/80 rounded-2xl border border-white/10 shadow-xl shadow-indigo-500/10 p-6 sm:p-8">
                <div class="flex items-center justify-between gap-4 flex-wrap mb-4">
                    <div>
                        <h2 class="text-lg font-semibold">Riwayat Request</h2>
                        <p class="text-sm text-indigo-100/70">Daftar request yang sudah diproses.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr class="text-left text-xs uppercase tracking-wider text-indigo-100/70">
                                <th class="px-4 py-3">Tanggal</th>
                                <th class="px-4 py-3">Barang</th>
                                <th class="px-4 py-3 text-right">Jumlah</th>
                                <th class="px-4 py-3">Rencana Ambil</th>
                                <th class="px-4 py-3">Tanggal Diterima</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($riwayat as $row)
                                <tr class="hover:bg-white/5">
                                    <td class="px-4 py-3 text-sm text-slate-200">{{ $row->tgl_keluar }}</td>
                                    <td class="px-4 py-3 text-sm text-white">{{ $row->barang?->nama_barang ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-white text-right">{{ $row->jumlah }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-200">{{ $row->tgl_pengambilan_rencana ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-slate-200">{{ $row->tgl_diterima ? \Illuminate\Support\Carbon::parse($row->tgl_diterima)->format('d-m-Y H:i') : '-' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span @class([
                                            'px-3 py-1 rounded-full text-xs font-semibold',
                                            'bg-emerald-500/20 text-emerald-200' => $row->status === 'approved',
                                            'bg-rose-500/20 text-rose-200' => $row->status === 'rejected',
                                        ])>
                                            {{ ucfirst($row->status ?? 'approved') }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-slate-300">
                                        {{ $row->keterangan ?? '-' }}
                                        @if(($row->status ?? 'approved') === 'rejected' && $row->alasan_penolakan)
                                            <div class="text-xs text-rose-200 mt-1">Alasan: {{ $row->alasan_penolakan }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-300">Belum ada riwayat request.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 text-slate-200">
                    {{ $riwayat->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
