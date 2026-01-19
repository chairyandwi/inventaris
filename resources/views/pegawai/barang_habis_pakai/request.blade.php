@extends('layouts.app')

@section('title', 'Request Barang Habis Pakai')

@section('content')
@php
    $routePrefix = ($routePrefix ?? null) ?: (request()->routeIs('admin.*') ? 'admin' : 'pegawai');
@endphp
<div class="min-h-screen bg-slate-950 text-white">
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
        <div class="absolute -left-16 -top-24 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
        <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div>
                    <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Request Barang</p>
                    <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Persetujuan barang habis pakai</h1>
                    <p class="mt-3 text-indigo-50/90 max-w-2xl">Review request, cek detail peminjam, dan putuskan approve/reject sebelum stok berkurang.</p>
                </div>
                <a href="{{ route($routePrefix . '.barang-habis-pakai.index') }}"
                   class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white/10 text-indigo-50 font-semibold border border-white/15 hover:bg-white/20 transition">
                    Kembali ke Barang Habis Pakai
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-amber-400/30 to-orange-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Pending</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalPending'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Menunggu keputusan</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Approved</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalApproved'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Disetujui</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-rose-400/30 to-pink-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Rejected</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalRejected'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Ditolak</p>
                    </div>
                </div>
                <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400/30 to-indigo-500/40 opacity-70"></div>
                    <div class="relative">
                        <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Keluar</p>
                        <p class="text-3xl font-bold mt-2">{{ $stats['totalKeluar'] ?? 0 }}</p>
                        <p class="text-sm text-indigo-100/80 mt-1">Unit disalurkan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="relative -mt-10 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="px-6 py-5 border-b border-white/10">
                    <h2 class="text-lg font-semibold text-white">Request Menunggu</h2>
                    <p class="text-sm text-indigo-100/70 mt-1">Permintaan baru yang butuh persetujuan.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Peminjam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Divisi</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-indigo-100 uppercase tracking-wide">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Rencana Ambil</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($pending as $row)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 text-sm text-indigo-50">{{ $row->tgl_keluar }}</td>
                                    <td class="px-6 py-4 text-sm text-white">{{ $row->barang?->nama_barang ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $row->user?->nama ?? $row->user?->username ?? '-' }}</span>
                                            <a href="{{ route($routePrefix . '.barang-habis-pakai.show', $row->idbarang_keluar) }}"
                                                class="px-2 py-1 rounded-lg text-xs font-semibold bg-white/10 border border-white/10 text-indigo-100 hover:bg-white/20 transition">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $row->user?->divisi ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-right text-indigo-50">{{ $row->jumlah }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $row->tgl_pengambilan_rencana ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/70">{{ $row->keterangan ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/70 align-bottom">
                                        <div class="min-w-[180px] h-full flex flex-col items-end justify-end gap-2">
                                            <form method="POST" action="{{ route($routePrefix . '.barang-habis-pakai.approve', $row->idbarang_keluar) }}" class="w-full flex justify-end">
                                                @csrf
                                                <button type="submit" data-confirm="Setujui permintaan ini?"
                                                    class="w-full max-w-[180px] px-3 py-2 rounded-lg bg-emerald-500/20 text-emerald-100 text-xs font-semibold hover:bg-emerald-500/30 transition">
                                                    Setujui
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route($routePrefix . '.barang-habis-pakai.reject', $row->idbarang_keluar) }}" class="w-full flex flex-col items-end gap-2">
                                                @csrf
                                                <input type="text" name="alasan_penolakan" required maxlength="255"
                                                    class="w-full max-w-[180px] rounded-lg bg-slate-800/70 border border-white/10 text-white px-2 py-1 text-xs focus:ring-2 focus:ring-rose-400 focus:border-rose-400"
                                                    placeholder="Alasan penolakan">
                                                <button type="submit" data-confirm="Tolak permintaan ini?"
                                                    class="w-full max-w-[180px] px-3 py-2 rounded-lg bg-rose-500/20 text-rose-100 text-xs font-semibold hover:bg-rose-500/30 transition">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-indigo-100/80">
                                        Tidak ada request pending.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100 flex items-center justify-between">
                    <div>
                        Menampilkan {{ $pending->firstItem() ?? 0 }} - {{ $pending->lastItem() ?? 0 }} dari {{ $pending->total() }} request
                    </div>
                    <div class="text-white">
                        {{ $pending->links() }}
                    </div>
                </div>
            </div>

            <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                <div class="px-6 py-5 border-b border-white/10">
                    <h2 class="text-lg font-semibold text-white">Riwayat Request</h2>
                    <p class="text-sm text-indigo-100/70 mt-1">Request yang sudah diproses.</p>
                </div>
                <form method="GET" class="px-6 py-4 border-b border-white/10 grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="text-xs text-indigo-100/70 uppercase tracking-wide">Status</label>
                        <select name="riwayat_status" class="mt-1 w-full rounded-xl bg-slate-800/70 border border-white/10 text-white px-3 py-2">
                            <option value="all" @selected(request('riwayat_status') === 'all')>Semua</option>
                            <option value="approved" @selected(request('riwayat_status') === 'approved')>Approved</option>
                            <option value="rejected" @selected(request('riwayat_status') === 'rejected')>Rejected</option>
                        </select>
                    </div>
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
                    <div>
                        <label class="text-xs text-indigo-100/70 uppercase tracking-wide">Cari</label>
                        <input type="text" name="riwayat_search" value="{{ request('riwayat_search') }}"
                               placeholder="Barang / nama / email"
                               class="mt-1 w-full rounded-xl bg-slate-800/70 border border-white/10 text-white px-3 py-2">
                    </div>
                    <div class="md:col-span-4 flex flex-wrap gap-2">
                        <button type="submit" class="px-4 py-2 rounded-xl bg-gradient-to-r from-amber-400 to-orange-500 text-slate-900 font-semibold shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 transition">
                            Terapkan
                        </button>
                        <a href="{{ route($routePrefix . '.barang-habis-pakai.request') }}" class="px-4 py-2 rounded-xl border border-white/15 text-sm font-semibold text-indigo-50 hover:bg-white/10 transition">
                            Reset
                        </a>
                        <a href="{{ route($routePrefix . '.barang-habis-pakai.laporan', [
                            'status' => request('riwayat_status'),
                            'start_date' => request('riwayat_from'),
                            'end_date' => request('riwayat_to'),
                            'search' => request('riwayat_search'),
                        ]) }}"
                           class="px-4 py-2 rounded-xl border border-white/15 text-sm font-semibold text-indigo-50 hover:bg-white/10 transition">
                            Unduh Laporan Barang Keluar
                        </a>
                    </div>
                </form>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-white/10">
                        <thead class="bg-white/5">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Peminjam</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Divisi</th>
                                <th class="px-6 py-3 text-right text-xs font-semibold text-indigo-100 uppercase tracking-wide">Jumlah</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Rencana Ambil</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Tanggal Diterima</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-indigo-100 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($riwayat as $row)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-6 py-4 text-sm text-indigo-50">{{ $row->tgl_keluar }}</td>
                                    <td class="px-6 py-4 text-sm text-white">{{ $row->barang?->nama_barang ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $row->user?->nama ?? $row->user?->username ?? '-' }}</span>
                                            <a href="{{ route($routePrefix . '.barang-habis-pakai.show', $row->idbarang_keluar) }}"
                                                class="px-2 py-1 rounded-lg text-xs font-semibold bg-white/10 border border-white/10 text-indigo-100 hover:bg-white/20 transition">
                                                Detail
                                            </a>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $row->user?->divisi ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-right text-indigo-50">{{ $row->jumlah }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $row->tgl_pengambilan_rencana ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/80">{{ $row->tgl_diterima ? \Illuminate\Support\Carbon::parse($row->tgl_diterima)->format('d-m-Y H:i') : '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        <span @class([
                                            'px-3 py-1 rounded-full text-xs font-semibold',
                                            'bg-emerald-500/20 text-emerald-200' => $row->status === 'approved',
                                            'bg-rose-500/20 text-rose-200' => $row->status === 'rejected',
                                        ])>
                                            {{ ucfirst($row->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/70">
                                        {{ $row->keterangan ?? '-' }}
                                        @if($row->status === 'rejected' && $row->alasan_penolakan)
                                            <div class="text-xs text-rose-200 mt-1">Alasan: {{ $row->alasan_penolakan }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-indigo-100/70">
                                        @if($row->status === 'approved' && !$row->tgl_diterima)
                                            <form method="POST" action="{{ route($routePrefix . '.barang-habis-pakai.receive', $row->idbarang_keluar) }}"
                                                class="space-y-2" data-rfid-form>
                                                @csrf
                                                <input type="text" name="rfid_uid" autocomplete="off" inputmode="none"
                                                    aria-label="RFID UID"
                                                    class="sr-only rfid-input">
                                                <p class="text-[10px] text-indigo-100/70 rfid-helper">Scan kartu/tag RFID peminjam untuk menandai diterima.</p>
                                                <div class="hidden rfid-manual space-y-2">
                                                    <label class="block text-[10px] text-indigo-100/70">Password {{ auth()->user()?->role === 'admin' ? 'admin' : 'pegawai' }}</label>
                                                    <input type="password" name="manual_password" autocomplete="current-password"
                                                        class="w-full rounded-lg bg-slate-800/70 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:border-amber-400 px-2 py-1.5"
                                                        placeholder="Masukkan password">
                                                </div>
                                                <label class="inline-flex items-center gap-2 text-[10px] text-indigo-100/70">
                                                    <input type="checkbox" class="rounded border-white/20 bg-slate-800/70 text-amber-400 manual-toggle">
                                                    Konfirmasi manual
                                                </label>
                                                <div class="flex flex-wrap gap-2">
                                                    <button type="button"
                                                        class="px-3 py-2 rounded-lg bg-white/10 text-indigo-100 text-xs font-semibold hover:bg-white/20 transition rfid-scan-btn">
                                                        Scan RFID
                                                    </button>
                                                    <button type="submit" data-confirm="Tandai barang sudah diterima?"
                                                        class="px-3 py-2 rounded-lg bg-sky-500/20 text-sky-100 text-xs font-semibold hover:bg-sky-500/30 transition rfid-submit"
                                                        disabled>
                                                        Tandai Diterima
                                                    </button>
                                                </div>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-12 text-center text-sm text-indigo-100/80">
                                        Belum ada riwayat request.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100 flex items-center justify-between">
                    <div>
                        Menampilkan {{ $riwayat->firstItem() ?? 0 }} - {{ $riwayat->lastItem() ?? 0 }} dari {{ $riwayat->total() }} request
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-rfid-form]').forEach((form) => {
        const input = form.querySelector('.rfid-input');
        const scanButton = form.querySelector('.rfid-scan-btn');
        const submitButton = form.querySelector('.rfid-submit');
        const helper = form.querySelector('.rfid-helper');
        const manualToggle = form.querySelector('.manual-toggle');
        const manualWrap = form.querySelector('.rfid-manual');
        const manualInput = form.querySelector('input[name="manual_password"]');

        const syncSubmitState = () => {
            const hasValue = input && input.value.trim() !== '';
            const manualValue = manualInput && manualInput.value.trim() !== '';
            if (submitButton) {
                submitButton.disabled = !(hasValue || manualValue);
            }
        };

        scanButton?.addEventListener('click', () => {
            input?.focus();
            if (helper) {
                helper.textContent = 'Siap scan RFID...';
            }
        });

        input?.addEventListener('input', () => {
            syncSubmitState();
            if (helper && input.value.trim() !== '') {
                helper.textContent = 'RFID terbaca. Silakan konfirmasi.';
            }
        });

        manualToggle?.addEventListener('change', () => {
            if (manualWrap) {
                manualWrap.classList.toggle('hidden', !manualToggle.checked);
            }
            if (manualToggle.checked) {
                manualInput?.focus();
                if (helper) {
                    helper.textContent = 'Mode manual aktif. Masukkan password.';
                }
            } else if (helper) {
                helper.textContent = 'Scan kartu/tag RFID peminjam untuk menandai diterima.';
            }
            syncSubmitState();
        });

        manualInput?.addEventListener('input', () => {
            syncSubmitState();
        });

        form.addEventListener('submit', (event) => {
            const hasRfid = input && input.value.trim() !== '';
            const hasManual = manualInput && manualInput.value.trim() !== '';
            if (!hasRfid && !hasManual) {
                event.preventDefault();
                if (helper) {
                    helper.textContent = 'Scan RFID atau gunakan konfirmasi manual.';
                }
                (manualToggle?.checked ? manualInput : input)?.focus();
            }
        });

        syncSubmitState();
    });
});
</script>
@endpush
