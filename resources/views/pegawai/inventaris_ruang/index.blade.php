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
                <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.create') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m-6-6h12" />
                    </svg>
                    Tambah Inventaris
                </a>
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
                <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 p-6">
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
                    <div class="flex items-end gap-3">
                        <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center px-4 py-3 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                            Terapkan Filter
                        </button>
                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.laporan', request()->only(['idruang','idbarang','gedung','lantai'])) }}"
                           class="w-full md:w-auto inline-flex items-center justify-center px-4 py-3 rounded-xl bg-gradient-to-r from-emerald-400 to-teal-500 text-slate-900 font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/50 transition">
                            Unduh Laporan
                        </a>
                    </div>
                </form>

                @if(request('idruang') || request('idbarang') || request('gedung') || request('lantai'))
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
                        <a href="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.index') }}" class="px-3 py-1 rounded-full bg-white/10 border border-white/10 hover:bg-white/20 transition">Reset</a>
                    </div>
                @endif
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="grid gap-6 p-6 md:grid-cols-2 xl:grid-cols-3">
                    @forelse($units as $unit)
                        @php
                            $barang = $unit->barang;
                            $ruangItem = $unit->ruang;
                            $latestMasuk = $barang?->barangMasuk->first();
                            $hasSpec = $latestMasuk && (
                                $latestMasuk->processor ||
                                $latestMasuk->ram_capacity_gb ||
                                $latestMasuk->storage_capacity_gb ||
                                $latestMasuk->monitor_brand
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

                                <div class="grid grid-cols-2 gap-3 text-sm text-indigo-100/80">
                                    <div class="rounded-xl border border-white/5 bg-white/5 p-3">
                                        <p class="text-[11px] uppercase tracking-[0.2em] text-indigo-200/70">Kategori</p>
                                        <p class="text-sm font-semibold text-white mt-1">{{ $barang?->kategori?->nama_kategori ?? '-' }}</p>
                                    </div>
                                    <div class="rounded-xl border border-white/5 bg-white/5 p-3">
                                        <p class="text-[11px] uppercase tracking-[0.2em] text-indigo-200/70">Status</p>
                                        <p class="text-sm font-semibold text-emerald-300 mt-1">Aktif</p>
                                    </div>
                                </div>

                                @if($hasSpec)
                                    <div class="rounded-xl border border-indigo-500/20 bg-indigo-500/5 p-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <p class="text-xs font-semibold text-indigo-100 uppercase tracking-wide">Detail Spesifikasi</p>
                                            @if($latestMasuk?->tgl_masuk)
                                                <span class="text-[11px] px-2 py-1 rounded-full bg-white/5 border border-white/10 text-indigo-100/80">Datang {{ \Carbon\Carbon::parse($latestMasuk->tgl_masuk)->format('d M Y') }}</span>
                                            @endif
                                        </div>
                                        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-indigo-100/80">
                                            <div>
                                                <dt class="text-[11px] uppercase tracking-[0.15em] text-indigo-200/70">Prosesor</dt>
                                                <dd class="font-semibold text-white">{{ $latestMasuk?->processor ?? '—' }}</dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] uppercase tracking-[0.15em] text-indigo-200/70">RAM</dt>
                                                <dd class="font-semibold text-white">
                                                    @if($latestMasuk?->ram_capacity_gb)
                                                        {{ $latestMasuk->ram_capacity_gb }} GB {{ $latestMasuk->ram_brand ? '('.$latestMasuk->ram_brand.')' : '' }}
                                                    @else
                                                        —
                                                    @endif
                                                </dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] uppercase tracking-[0.15em] text-indigo-200/70">Penyimpanan</dt>
                                                <dd class="font-semibold text-white">
                                                    @if($latestMasuk?->storage_capacity_gb)
                                                        {{ $latestMasuk->storage_capacity_gb }} GB {{ $latestMasuk->storage_type ?? '' }}
                                                    @else
                                                        —
                                                    @endif
                                                </dd>
                                            </div>
                                            <div>
                                                <dt class="text-[11px] uppercase tracking-[0.15em] text-indigo-200/70">Monitor</dt>
                                                <dd class="font-semibold text-white">
                                                    @if($latestMasuk?->monitor_brand)
                                                        {{ $latestMasuk->monitor_brand }} {{ $latestMasuk->monitor_model }} {{ $latestMasuk->monitor_size_inch ? $latestMasuk->monitor_size_inch . '”' : '' }}
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

                                <div class="flex items-center justify-between pt-1">
                                    <div class="text-xs text-indigo-100/70">
                                        <p>{{ $barang?->kode_barang ?? '-' }} • Unit ke-{{ $unit->nomor_unit }}</p>
                                    </div>
                                    <form action="{{ route(($routePrefix ?? 'pegawai') . '.inventaris-ruang.destroy', $unit) }}" method="POST" onsubmit="return confirm('Hapus unit ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-rose-500/80 hover:bg-rose-500 text-white shadow-md shadow-rose-500/30 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0 1 16.138 21H7.862a2 2 0 0 1-1.995-1.858L5 7m5 4v6m4-6v6m1-10V5a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v2H5m14 0H5" />
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
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
                    {{ $units->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
