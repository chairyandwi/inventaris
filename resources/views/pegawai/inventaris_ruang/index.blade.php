@extends('layouts.app')

@section('title', 'Inventaris Ruang')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-16">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Inventaris Ruang</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Mapping unit barang per ruang</h1>
                    <p class="mt-3 text-indigo-50/90 max-w-2xl">Pantau penyebaran aset tetap per ruang, unduh laporan, dan jaga keterisian ruang tetap terukur.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.riwayat') }}"
                       class="inline-flex items-center gap-2 px-4 py-3 rounded-xl border border-white/20 text-white/90 font-semibold hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        Riwayat Pemindahan
                    </a>
                    <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.create') }}"
                       class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-6-6h12" />
                        </svg>
                        Tambah Inventaris
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Unit</p>
                        <p class="text-3xl font-bold mt-2">{{ $summary['totalUnits'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Unit barang tetap tercatat</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400/30 to-orange-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Ruang Terisi</p>
                        <p class="text-3xl font-bold mt-2">{{ $summary['ruangTerisi'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Ruang yang memiliki unit</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400/30 to-indigo-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Barang Tetap</p>
                        <p class="text-3xl font-bold mt-2">{{ $summary['barangTetap'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Jenis barang siap inventaris</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/70 border border-white/10 rounded-2xl shadow-lg shadow-indigo-500/10 backdrop-blur">
                <form method="GET" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-4 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Filter Gedung</label>
                            <select name="gedung" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option class="text-slate-900" value="">Semua Gedung</option>
                                @foreach($gedungList ?? [] as $g)
                                <option class="text-slate-900" value="{{ $g }}" @selected(request('gedung') == $g)>{{ $g }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div>
                        <label class="text-sm font-semibold text-indigo-100 mb-2 block">Filter Lantai</label>
                        <select name="lantai" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            <option class="text-slate-900" value="">Semua Lantai</option>
                            @foreach($lantaiList ?? [] as $l)
                                <option class="text-slate-900" value="{{ $l }}" @selected(request('lantai') == $l)>{{ $l }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div>
                        <label class="text-sm font-semibold text-indigo-100 mb-2 block">Filter Ruang</label>
                        <select name="idruang" id="ruangSelect" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            <option class="text-slate-900" value="">Semua Ruang</option>
                            @php
                                $ruangOptions = ($ruang->count() ? $ruang : ($ruangAll ?? collect()));
                            @endphp
                            @foreach($ruangOptions as $r)
                                <option class="text-slate-900"
                                        data-gedung="{{ $r->nama_gedung }}"
                                        data-lantai="{{ $r->nama_lantai }}"
                                        value="{{ $r->idruang }}"
                                        @selected(request('idruang') == $r->idruang)>
                                    {{ $r->nama_ruang }}
                                </option>
                            @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Filter Barang</label>
                            <select name="idbarang" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option class="text-slate-900" value="">Semua Barang</option>
                                @foreach($barang as $b)
                                    <option class="text-slate-900" value="{{ $b->idbarang }}" @selected(request('idbarang') == $b->idbarang)>{{ $b->nama_barang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-indigo-100 mb-2 block">Filter Kondisi</label>
                            <select name="status" class="w-full rounded-xl bg-slate-800/60 border border-white/10 text-white focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                <option class="text-slate-900" value="">Semua Kondisi</option>
                                <option class="text-slate-900" value="baik" @selected(request('status') === 'baik')>Baik</option>
                                <option class="text-slate-900" value="kurang_baik" @selected(request('status') === 'kurang_baik')>Kurang Baik</option>
                                <option class="text-slate-900" value="rusak" @selected(request('status') === 'rusak')>Rusak</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3">
                        <div class="flex items-center gap-2 bg-slate-800/60 border border-white/10 rounded-xl px-3 py-2">
                            <label class="text-sm font-semibold text-indigo-100">Tampil</label>
                            <select name="per_page" class="select-perpage rounded-lg bg-slate-900/60 border border-white/10 text-white px-2 py-1 pr-7 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                                @foreach([10,15,25,50,100] as $pp)
                                    <option class="text-slate-900" value="{{ $pp }}" @selected(request('per_page',15)==$pp)>{{ $pp }}</option>
                                @endforeach
                            </select>
                            <span class="text-sm text-indigo-100/80">barang</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                                Terapkan
                            </button>
                            <button type="submit"
                                formaction="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.laporan') }}"
                                class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 text-slate-900 font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition">
                                Unduh
                            </button>
                            @if(request('idruang'))
                                <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.label-ruang', ['idruang' => request('idruang')]) }}"
                                   class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white/10 border border-white/20 text-indigo-50 hover:bg-white/20 transition">
                                    Cetak Label Ruang
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

                @if(request('idruang') || request('idbarang') || request('gedung') || request('lantai') || request('status'))
                    <div class="px-6 pb-4 flex flex-wrap gap-2 text-sm text-indigo-100">
                        <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10">Filter aktif:</span>
                        @if(request('gedung'))
                            <span class="px-3 py-1 rounded-full bg-purple-500/30 border border-purple-200/30">Gedung: {{ request('gedung') }}</span>
                        @endif
                        @if(request('lantai'))
                            <span class="px-3 py-1 rounded-full bg-fuchsia-500/30 border border-fuchsia-200/30">Lantai: {{ request('lantai') }}</span>
                        @endif
                        @if(request('idruang'))
                            <span class="px-3 py-1 rounded-full bg-indigo-500/30 border border-indigo-200/30">Ruang: {{ $ruang->firstWhere('idruang', request('idruang'))?->nama_ruang ?? 'Dipilih' }}</span>
                        @endif
                        @if(request('idbarang'))
                            <span class="px-3 py-1 rounded-full bg-sky-500/30 border border-sky-200/30">Barang: {{ $barang->firstWhere('idbarang', request('idbarang'))?->nama_barang ?? 'Dipilih' }}</span>
                        @endif
                        @if(request('status'))
                            @php
                                $labelStatus = [
                                    'baik' => 'Kondisi: Baik',
                                    'kurang_baik' => 'Kondisi: Kurang Baik',
                                    'rusak' => 'Kondisi: Rusak',
                                ][request('status')] ?? 'Kondisi dipilih';
                            @endphp
                            <span class="px-3 py-1 rounded-full bg-rose-500/30 border border-rose-200/30">{{ $labelStatus }}</span>
                        @endif
                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.index') }}" class="px-3 py-1 rounded-full bg-white/10 border border-white/10 hover:bg-white/20 transition">Reset</a>
                    </div>
                @endif

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="grid gap-6 p-6 md:grid-cols-2 xl:grid-cols-3">
                    @forelse($units as $unit)
                        @php
                            $barang = $unit->barang;
                            $ruangItem = $unit->ruang;
                            $unitMasuk = $unit->barangMasuk;
                            $isRusak = (bool) $unit->kerusakanAktif;
                            $isKurangBaik = !$isRusak && $unit->keterangan && str_contains(strtolower($unit->keterangan), 'kurang');
                            $statusLabel = $isRusak ? 'Rusak' : ($isKurangBaik ? 'Kurang Baik' : 'Baik');
                            $statusClass = $isRusak ? 'text-rose-300' : ($isKurangBaik ? 'text-amber-300' : 'text-emerald-300');
                            $hasSpec = $unitMasuk && (
                                $unitMasuk->processor ||
                                $unitMasuk->ram_capacity_gb ||
                                $unitMasuk->storage_capacity_gb ||
                                $unitMasuk->monitor_brand
                            );
                        @endphp
                        <div class="group relative overflow-hidden rounded-2xl border border-white/10 bg-gradient-to-br from-slate-900/80 via-slate-900/60 to-slate-800/70 backdrop-blur shadow-lg shadow-indigo-500/15">
                            <div class="absolute inset-0 opacity-60 bg-[radial-gradient(circle_at_20%_20%,rgba(99,102,241,0.25),transparent_35%),radial-gradient(circle_at_80%_0%,rgba(14,165,233,0.25),transparent_35%)]"></div>
                            <div class="relative p-5 space-y-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs uppercase tracking-[0.3em] text-indigo-100/70">Unit {{ str_pad($unit->nomor_unit, 3, '0', STR_PAD_LEFT) }}</p>
                                        <h3 class="text-xl font-bold text-white mt-1 leading-tight">{{ $barang->nama_barang ?? '-' }}</h3>
                                        <p class="text-sm text-indigo-100/80">{{ $ruangItem->nama_ruang ?? '-' }} • {{ $ruangItem->nama_gedung ?? '' }}{{ $ruangItem->nama_lantai ? ' Lt. '.$ruangItem->nama_lantai : '' }}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs font-semibold text-indigo-50">{{ $unit->kode_unit }}</span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm text-indigo-100/80">
                                    <div class="rounded-xl border border-white/5 bg-white/5 p-3">
                                        <p class="text-[11px] uppercase tracking-[0.2em] text-indigo-200/70">Kategori</p>
                                        <p class="text-sm font-semibold text-white mt-1">{{ $barang?->kategori?->nama_kategori ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-xl border border-white/5 bg-white/5 p-3">
                                        <p class="text-[11px] uppercase tracking-[0.2em] text-indigo-200/70">Status</p>
                                        <p class="text-sm font-semibold {{ $statusClass }} mt-1">{{ $statusLabel }}</p>
                                        @if($isRusak && $unit->kerusakanAktif?->deskripsi)
                                            <p class="text-xs text-rose-100/80 mt-0.5">{{ $unit->kerusakanAktif->deskripsi }}</p>
                                        @elseif($isKurangBaik)
                                            <p class="text-xs text-amber-100/80 mt-0.5">{{ $unit->keterangan }}</p>
                                        @endif
                                    </div>
                                    <div class="rounded-xl border border-white/5 bg-white/5 p-3">
                                        <p class="text-[11px] uppercase tracking-[0.2em] text-indigo-200/70">Merk</p>
                                        <p class="text-sm font-semibold text-white mt-1">{{ $unitMasuk?->merk ?? '-' }}</p>
                                    </div>
                                </div>

                                @if($hasSpec)
                                    <div class="rounded-xl border border-indigo-500/20 bg-indigo-500/5 p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs font-semibold text-indigo-100 uppercase tracking-wide">Detail Spesifikasi</p>
                                            @if($unitMasuk?->tgl_masuk)
                                                <span class="text-[11px] px-2 py-1 rounded-full bg-white/5 border border-white/10 text-indigo-100/80">Datang {{ \Carbon\Carbon::parse($unitMasuk->tgl_masuk)->format('d M Y') }}</span>
                                            @endif
                                        </div>
                                        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-indigo-100/80">
                                            <div>
                                                <dt class="text-[11px] uppercase tracking-[0.15em] text-indigo-200/70">Prosesor</dt>
                                                <dd class="font-semibold text-white">{{ $unitMasuk?->processor ?? '—' }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] uppercase tracking-[0.15em] text-indigo-200/70">RAM</dt>
                                                <dd class="font-semibold text-white">
                                                    @if($unitMasuk?->ram_capacity_gb)
                                                        {{ $unitMasuk->ram_capacity_gb }} GB {{ $unitMasuk->ram_brand ? '('.$unitMasuk->ram_brand.')' : '' }}
                                                    @else
                                                        — 
                                                    @endif
                                                </dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] uppercase tracking-[0.15em] text-indigo-200/70">Penyimpanan</dt>
                                                <dd class="font-semibold text-white">
                                                    @if($unitMasuk?->storage_capacity_gb)
                                                        {{ $unitMasuk->storage_capacity_gb }} GB {{ $unitMasuk->storage_type ?? '' }}
                                                    @else
                                                        — 
                                                    @endif
                                                </dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] uppercase tracking-[0.15em] text-indigo-200/70">Monitor</dt>
                                                <dd class="font-semibold text-white">
                                                    @if($unitMasuk?->monitor_brand)
                                                        {{ $unitMasuk->monitor_brand }} {{ $unitMasuk->monitor_model }} {{ $unitMasuk->monitor_size_inch ? $unitMasuk->monitor_size_inch . '”' : '' }}
                                                    @else
                                                        — 
                                                    @endif
                                                </dd>
                                            </div>
                                        </dl>
                                        @if($unit->keterangan)
                                            <p class="mt-3 text-sm text-indigo-100/80"><span class="font-semibold text-indigo-100">Catatan:</span> {{ $unit->keterangan }}</p>
                                        @endif
                                    </div>
                                @elseif($unit->keterangan)
                                    <div class="rounded-xl border border-white/10 bg-white/5 p-4">
                                        <p class="text-xs font-semibold text-indigo-100 uppercase tracking-wide mb-1">Catatan</p>
                                        <p class="text-sm text-indigo-100/80">{{ $unit->keterangan }}</p>
                                    </div>
                                @endif

                                <div class="flex items-start justify-between pt-1 flex-wrap gap-3">
                                    <div class="text-xs text-indigo-100/70">
        <p>{{ $barang?->kode_barang ?? '-' }} • Unit ke-{{ $unit->nomor_unit }}</p>
    </div>
    <div class="flex flex-wrap items-center gap-2">
        <form action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.move', $unit) }}" method="POST" class="flex items-center gap-2">
            @csrf
            <select name="idruang" required class="rounded-lg bg-slate-800/60 border border-white/10 text-white text-xs px-2 py-1 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                <option class="text-slate-900" value="">Pindah ke...</option>
                @foreach($ruangAll ?? $ruang as $r)
                    <option class="text-slate-900" value="{{ $r->idruang }}" @selected($r->idruang == $unit->idruang)>
                        {{ $r->nama_ruang }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-500/80 hover:bg-indigo-500 text-white shadow-md shadow-indigo-500/30 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16m-7-7 7 7-7 7" />
                </svg>
                Pindahkan
            </button>
        </form>
        <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.label', $unit) }}" target="_blank"
            class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-slate-700/80 hover:bg-slate-700 text-white shadow-md shadow-slate-700/30 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h10M7 11h10M7 15h6M5 7V5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2m0 10v2a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-2" />
            </svg>
            Label
        </a>
        <form action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.destroy', $unit) }}" method="POST" data-confirm="Hapus unit ini?">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-rose-500/80 hover:bg-rose-500 text-white shadow-md shadow-rose-500/30 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v2H5m14 0H5" />
                </svg>
                Hapus
            </button>
        </form>
        @unless($isRusak)
            <form action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.mark-rusak', $unit) }}" method="POST" data-confirm="Tandai unit ini rusak?" class="inline-flex items-center gap-2">
                @csrf
                <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-amber-500/80 hover:bg-amber-500 text-slate-900 font-semibold shadow-md shadow-amber-500/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M4.93 4.93a10 10 0 1 1 14.14 14.14A10 10 0 0 1 4.93 4.93z" />
                    </svg>
                    Tandai Rusak
                </button>
            </form>
        @else
            <form action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.update-kerusakan', $unit) }}" method="POST" class="flex flex-wrap items-center gap-2">
                @csrf
                @method('PATCH')
                <input type="text" name="deskripsi" value="{{ $unit->kerusakanAktif?->deskripsi }}" placeholder="Catatan kerusakan" class="px-2 py-1 rounded-lg bg-slate-800/60 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:border-amber-400 w-44 sm:w-56">
                <button type="submit" class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-amber-500/80 hover:bg-amber-500 text-slate-900 font-semibold shadow-md shadow-amber-500/30 transition text-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan
                </button>
            </form>
            <form action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.restore-kerusakan', $unit) }}" method="POST" data-confirm="Pulihkan unit ini ke kondisi baik?">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-500/80 hover:bg-emerald-500 text-white font-semibold shadow-md shadow-emerald-500/30 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M10 14h10M10 18h10M4 14h.01M4 18h.01" />
                    </svg>
                    Pulihkan
                </button>
            </form>
        @endunless
    </div>
</div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12 text-indigo-100/80">
                            <p class="text-lg font-semibold">Belum ada data inventaris ruang</p>
                            <p class="text-sm text-indigo-100/70 mt-1">Catat unit baru untuk mulai memetakan aset per ruang.</p>
                        </div>
                    @endforelse
                </div>
                <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100">
                    <div class="pagination-wrapper">
                        {{ $units->links() }}
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .pagination-wrapper nav {
        display: flex;
        flex-wrap: wrap;
        gap: 0.35rem;
    }
    .pagination-wrapper nav a,
    .pagination-wrapper nav span {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
    }
    .pagination-wrapper nav svg {
        width: 14px;
        height: 14px;
    }
    .select-perpage {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 2.5rem;
        background-image: url("data:image/svg+xml,%3Csvg fill='none' stroke='%23cbd5e1' stroke-width='2' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='m6 9 6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.65rem center;
        background-size: 0.9rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const gedungSelect = document.querySelector('select[name="gedung"]');
    const lantaiSelect = document.querySelector('select[name="lantai"]');
    const ruangSelect = document.getElementById('ruangSelect');
    if (!gedungSelect || !ruangSelect) return;

    @php
        $allRuangJson = ($ruangAll ?? collect())->map(function($r) {
            return [
                'value' => $r->idruang,
                'label' => $r->nama_ruang,
                'gedung' => $r->nama_gedung,
                'lantai' => $r->nama_lantai,
            ];
        })->values();
    @endphp
    const allRuang = @json($allRuangJson).map(r => ({
        ...r,
        gedung: (r.gedung || '').trim(),
        lantai: (r.lantai || '').trim(),
    }));

    const rebuildRuang = () => {
        const selectedGedung = gedungSelect.value || '';
        const selectedLantai = (lantaiSelect && lantaiSelect.value) ? lantaiSelect.value : '';
        const current = ruangSelect.value;

        ruangSelect.innerHTML = '<option class="text-slate-900" value="">Semua Ruang</option>';

        const filtered = allRuang.filter(r => {
            const matchGedung = selectedGedung ? r.gedung === selectedGedung : true;
            const matchLantai = selectedLantai ? (r.lantai === selectedLantai) : true;
            return matchGedung && matchLantai;
        });

        filtered.forEach(r => {
            const opt = document.createElement('option');
            opt.className = 'text-slate-900';
            opt.value = r.value;
            opt.textContent = r.label;
            if (r.value === current) {
                opt.selected = true;
            }
            ruangSelect.appendChild(opt);
        });

        if (ruangSelect.value === '' && current) {
            // if previous selection no longer valid, keep empty
        }
    };

    gedungSelect.addEventListener('change', rebuildRuang);
    if (lantaiSelect) {
        lantaiSelect.addEventListener('change', rebuildRuang);
    }

    rebuildRuang();
});
</script>
@endpush
