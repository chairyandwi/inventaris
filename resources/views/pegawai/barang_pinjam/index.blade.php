@extends('layouts.app')

@section('title', 'Barang Pinjam')

@section('content')
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Barang Pinjam</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Dashboard barang dapat dipinjam</h1>
                    <p class="mt-3 text-indigo-50/90 max-w-2xl">Pantau stok tersedia, catat pemakaian sementara, dan lihat statistik transaksi.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mt-8">
                @php
                    $cards = [
                        ['label' => 'Pending', 'value' => $stats['pending'] ?? 0, 'gradient' => 'from-amber-400/30 to-orange-500/40'],
                        ['label' => 'Disetujui', 'value' => $stats['disetujui'] ?? 0, 'gradient' => 'from-emerald-400/30 to-teal-500/40'],
                        ['label' => 'Dipinjam', 'value' => $stats['dipinjam'] ?? 0, 'gradient' => 'from-sky-400/30 to-indigo-500/40'],
                        ['label' => 'Dikembalikan', 'value' => $stats['dikembalikan'] ?? 0, 'gradient' => 'from-indigo-300/30 to-violet-400/40'],
                        ['label' => 'Ditolak', 'value' => $stats['ditolak'] ?? 0, 'gradient' => 'from-rose-400/30 to-pink-500/40'],
                    ];
                @endphp
                @foreach($cards as $card)
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br {{ $card['gradient'] }} opacity-70"></div>
                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">{{ $card['label'] }}</p>
                            <p class="text-3xl font-bold mt-2">{{ $card['value'] }}</p>
                            <p class="text-sm text-indigo-100/80 mt-1">Transaksi</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="px-6 py-5 border-b border-white/10">
                    <h2 class="text-lg font-semibold text-white">Barang Pinjam</h2>
                    <p class="text-sm text-indigo-100/70 mt-1">Kelola unit yang sedang digunakan sementara.</p>
                </div>
                @if(session('success'))
                    <div class="px-6 py-4 border-b border-emerald-500/20 bg-emerald-500/10 text-emerald-100 text-sm">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="px-6 py-4 border-b border-rose-500/20 bg-rose-500/10 text-rose-100 text-sm">
                        {{ session('error') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="px-6 py-4 border-b border-rose-500/20 bg-rose-500/10 text-rose-100 text-sm space-y-1">
                        @foreach($errors->all() as $message)
                            <div>{{ $message }}</div>
                        @endforeach
                    </div>
                @endif
                <form method="GET" class="px-6 py-4 border-b border-white/10 flex flex-col md:flex-row md:items-end gap-3">
                    <div class="flex-1">
                        <label class="text-xs text-indigo-100/70 uppercase tracking-wide">Cari</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Kode / nama barang"
                               class="mt-1 w-full rounded-xl bg-slate-800/70 border border-white/10 text-white px-3 py-2">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                            Terapkan
                        </button>
                        <a href="{{ route($routePrefix . '.barang-pinjam.index') }}" class="px-4 py-2 rounded-xl border border-white/15 text-sm font-semibold text-indigo-50 hover:bg-white/10 transition">
                            Reset
                        </a>
                    </div>
                </form>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 p-6">
                    @forelse($barang as $item)
                        @php
                            $available = $item->available_stok ?? 0;
                            $digunakan = $item->digunakan_stok ?? 0;
                            $statusLabel = $available > 0 ? 'Dapat Dipinjam' : 'Stok Kosong';
                            $statusClass = $available > 0
                                ? 'bg-emerald-500/20 text-emerald-200 border-emerald-400/40'
                                : 'bg-amber-500/20 text-amber-200 border-amber-400/40';
                        @endphp
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-5 space-y-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/70">Barang</p>
                                    <p class="text-lg font-semibold text-white mt-1">{{ $item->nama_barang }}</p>
                                    <p class="text-sm text-indigo-100/70 mt-1">Kode: {{ $item->kode_barang }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 text-sm text-indigo-100/80">
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-indigo-200/70">Stok Tersedia</p>
                                    <p class="text-white mt-1">{{ $available }} unit</p>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wide text-indigo-200/70">Sedang Digunakan</p>
                                    <p class="text-white mt-1">{{ $digunakan }} unit</p>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @foreach($item->merk_groups ?? [] as $group)
                                    <div class="rounded-xl border border-white/10 bg-white/5 p-4 space-y-3">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-white">Merk: {{ $group['merk'] }}</p>
                                            <span class="text-xs text-indigo-100/70">Total: {{ $group['total'] }} unit</span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 text-sm text-indigo-100/80">
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-indigo-200/70">Tersedia</p>
                                                <p class="text-white mt-1">{{ $group['available'] }} unit</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-indigo-200/70">Digunakan</p>
                                                <p class="text-white mt-1">{{ $group['used'] }} unit</p>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route($routePrefix . '.barang-pinjam.usage', $item->idbarang) }}" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="merk" value="{{ $group['merk'] }}">
                                            <label class="text-xs uppercase tracking-wide text-indigo-200/70">Catat Pemakaian</label>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                <div class="space-y-1">
                                                    <label class="text-[11px] uppercase tracking-wide text-indigo-200/70">Kegiatan</label>
                                                    <input type="text" name="kegiatan"
                                                        class="w-full rounded-xl bg-slate-800/70 border border-white/10 text-white px-3 py-2 text-sm"
                                                        placeholder="Contoh: Operasional, rapat, dinas" required>
                                                </div>
                                                <div class="space-y-1">
                                                    <label class="text-[11px] uppercase tracking-wide text-indigo-200/70">Digunakan sampai</label>
                                                    <input type="datetime-local" name="digunakan_sampai"
                                                        class="w-full rounded-xl bg-slate-800/70 border border-white/10 text-white px-3 py-2 text-sm" required>
                                                </div>
                                            </div>
                                            <div class="flex items-center justify-between">
                                                <p class="text-xs text-indigo-100/70">Unit otomatis kembali tersedia setelah waktu selesai.</p>
                                                <button type="submit"
                                                    class="px-4 py-2 rounded-xl bg-white/10 text-indigo-50 text-sm font-semibold border border-white/10 hover:bg-white/20 transition">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                            @if($item->usage_active && $item->usage_active->count())
                                <div class="pt-3 border-t border-white/10">
                                    <p class="text-xs uppercase tracking-wide text-indigo-200/70">Sedang Digunakan</p>
                                    <div class="mt-2 space-y-2 text-sm text-indigo-100/80">
                                        @foreach($item->usage_active as $usage)
                                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 rounded-xl bg-white/5 border border-white/10 px-3 py-2">
                                                <div>
                                                    <p class="text-white font-semibold">{{ $usage->kegiatan }}</p>
                                                    <p class="text-xs text-indigo-100/70">{{ $usage->jumlah }} unit • {{ $usage->creator?->nama ?? '-' }}</p>
                                                    <p class="text-xs text-indigo-100/70">Merk: {{ $usage->merk ?? '-' }}</p>
                                                </div>
                                                <div class="text-xs text-indigo-100/70">
                                                    Sampai: {{ $usage->digunakan_sampai?->format('d M Y H:i') ?? '-' }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-full text-center text-indigo-100/70 py-10">
                            Belum ada barang pinjam yang tersedia.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="px-6 py-5 border-b border-white/10">
                    <h2 class="text-lg font-semibold text-white">Riwayat Pemakaian</h2>
                    <p class="text-sm text-indigo-100/70 mt-1">Catatan pemakaian sementara yang sudah disimpan.</p>
                </div>
                <form method="GET" class="px-6 py-4 border-b border-white/10 grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="text-xs text-indigo-100/70 uppercase tracking-wide">Dari</label>
                        <input type="date" name="riwayat_from" value="{{ request('riwayat_from') }}"
                               class="mt-1 w-full rounded-xl bg-slate-800/70 border border-white/10 text-white px-3 py-2">
                    </div>
                    <div>
                        <label class="text-xs text-indigo-100/70 uppercase tracking-wide">Sampai</label>
                        <input type="date" name="riwayat_to" value="{{ request('riwayat_to') }}"
                               class="mt-1 w-full rounded-xl bg-slate-800/70 border border-white/10 text-white px-3 py-2">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-xs text-indigo-100/70 uppercase tracking-wide">Cari</label>
                        <input type="text" name="riwayat_search" value="{{ request('riwayat_search') }}"
                               placeholder="Barang / pemakai / email"
                               class="mt-1 w-full rounded-xl bg-slate-800/70 border border-white/10 text-white px-3 py-2">
                    </div>
                    <div class="md:col-span-4 flex flex-wrap gap-2">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                            Terapkan
                        </button>
                        <a href="{{ route($routePrefix . '.barang-pinjam.index') }}" class="px-4 py-2 rounded-xl border border-white/15 text-sm font-semibold text-indigo-50 hover:bg-white/10 transition">
                            Reset
                        </a>
                        <a href="{{ route($routePrefix . '.barang-pinjam.laporan', [
                            'start_date' => request('riwayat_from'),
                            'end_date' => request('riwayat_to'),
                            'search' => request('riwayat_search'),
                        ]) }}" class="px-4 py-2 rounded-xl border border-white/15 text-sm font-semibold text-indigo-50 hover:bg-white/10 transition">
                            Unduh Laporan Pemakaian
                        </a>
                    </div>
                </form>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Mulai</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Sampai</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Merk</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Kegiatan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Pemakai</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-indigo-100 uppercase tracking-wide">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($riwayat as $row)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 text-sm text-indigo-50">{{ $row->digunakan_mulai?->format('d-m-Y H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-50">{{ $row->digunakan_sampai?->format('d-m-Y H:i') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-white">{{ $row->barang?->nama_barang ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $row->merk ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $row->kegiatan }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $row->creator?->nama ?? $row->creator?->username ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-right text-indigo-50">{{ $row->jumlah }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-indigo-100/80">
                                        Belum ada riwayat pemakaian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100 flex items-center justify-between">
                    <div>
                        Menampilkan {{ $riwayat->firstItem() ?? 0 }} - {{ $riwayat->lastItem() ?? 0 }} dari {{ $riwayat->total() }} catatan
                    </div>
                    <div class="text-white">
                        {{ $riwayat->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
