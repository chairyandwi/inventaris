@extends('layouts.app')

@section('title', 'Peminjaman')

@section('content')
    @php
        $homeRoute = $homeRoute ?? (auth()->check() && auth()->user()->role === 'admin' ? 'admin.index' : 'pegawai.index');
        $routePrefix = $baseRoute ?? ((auth()->check() && auth()->user()->role === 'admin') ? 'admin.peminjaman' : 'pegawai.peminjaman');
        $laporanRoute = $laporanRoute ?? $routePrefix . '.laporan';
    @endphp
    <div class="min-h-screen bg-slate-950 text-white">
        <div class="relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-700 via-violet-700 to-sky-600 opacity-80"></div>
            <div class="absolute -left-12 -top-20 h-72 w-72 bg-white/10 blur-3xl rounded-full"></div>
            <div class="absolute right-0 top-0 h-64 w-64 bg-indigo-200/30 blur-3xl rounded-full"></div>

            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 pb-12">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] text-indigo-100/80">Peminjaman</p>
                        <h1 class="text-3xl sm:text-4xl font-bold leading-tight mt-2">Proses peminjaman terstruktur</h1>
                        <p class="mt-3 text-indigo-50/90 max-w-2xl">Monitor jadwal pinjam-kembali, peminjam, dan status approval dengan tampilan rapi.</p>
                    </div>
                    <a href="{{ route($laporanRoute) }}"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-xl bg-white text-indigo-700 font-semibold shadow-lg shadow-indigo-500/30 hover:-translate-y-0.5 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659"/>
                        </svg>
                        Laporan
                    </a>
                </div>

                @php
                    $pagePending = $peminjaman->where('status', 'pending')->count();
                    $pageDipinjam = $peminjaman->where('status', 'dipinjam')->count();
                    $pageSelesai = $peminjaman->where('status', 'dikembalikan')->count();
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-8">
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-teal-500/40 opacity-70"></div>
                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">Total Peminjaman</p>
                            <p class="text-3xl font-bold mt-2">{{ $peminjaman->total() }}</p>
                            <p class="text-sm text-indigo-100/80 mt-1">Keseluruhan data</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-400/30 to-orange-500/40 opacity-70"></div>
                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">Pending (halaman)</p>
                            <p class="text-3xl font-bold mt-2">{{ $pagePending }}</p>
                            <p class="text-sm text-indigo-100/80 mt-1">Menunggu keputusan</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-sky-400/30 to-indigo-500/40 opacity-70"></div>
                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">Sedang Dipinjam (halaman)</p>
                            <p class="text-3xl font-bold mt-2">{{ $pageDipinjam }}</p>
                            <p class="text-sm text-indigo-100/80 mt-1">Aktif di halaman ini</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden rounded-2xl border border-white/10 bg-white/10 backdrop-blur p-5 shadow-lg shadow-indigo-500/20">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400/30 to-lime-500/40 opacity-70"></div>
                        <div class="relative">
                            <p class="text-xs uppercase tracking-[0.25em] text-white/70">Selesai (halaman)</p>
                            <p class="text-3xl font-bold mt-2">{{ $pageSelesai }}</p>
                            <p class="text-sm text-indigo-100/80 mt-1">Dikembalikan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="relative -mt-10 pb-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4">
                @error('alasan_penolakan')
                    <div class="bg-rose-500/10 border border-rose-400/40 text-rose-100 px-4 py-3 rounded-xl">
                        {{ $message }}
                    </div>
                @enderror

                <div class="bg-slate-900/80 border border-white/10 rounded-2xl shadow-xl shadow-indigo-500/15 overflow-hidden">
                    <div class="divide-y divide-white/10">
                        @forelse($peminjaman as $index => $p)
                            <div class="p-5 hover:bg-white/5 transition">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-white/10 border border-white/10 flex items-center justify-center text-sm font-semibold text-white">
                                            {{ $peminjaman->firstItem() + $index }}
                                        </div>
                                        <div>
                                            <p class="text-sm text-indigo-100/80">Barang</p>
                                            <p class="text-base font-semibold text-white">{{ $p->barang->nama_barang }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-1 md:self-start">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route($routePrefix . '.show', $p->idpeminjaman) }}"
                                                class="inline-flex items-center px-4 py-2 rounded-full text-xs font-semibold text-indigo-900 bg-white hover:bg-indigo-50 transition">
                                                Lihat Detail
                                                <svg class="w-3 h-3 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                            <span @class([
                                                'px-3 py-1 text-xs rounded-full font-semibold',
                                                'bg-yellow-100 text-yellow-800' => $p->status === 'pending',
                                                'bg-indigo-100 text-indigo-800' => $p->status === 'disetujui',
                                                'bg-blue-100 text-blue-800' => $p->status === 'dipinjam',
                                                'bg-green-100 text-green-800' => $p->status === 'dikembalikan',
                                                'bg-red-100 text-red-800' => $p->status === 'ditolak',
                                            ])>
                                                {{ ucfirst($p->status) }}
                                            </span>
                                        </div>
                                        @if ($p->status == 'pending')
                                            <div class="flex flex-wrap items-stretch justify-end gap-2">
                                                <form action="{{ route($routePrefix . '.approve', $p->idpeminjaman) }}"
                                                    method="POST" class="inline-flex">
                                                    @csrf
                                                    <button type="submit"
                                                        class="h-9 px-4 inline-flex items-center justify-center bg-emerald-500 text-white text-xs leading-none rounded text-center shadow hover:bg-emerald-600 transition">
                                                        Approve
                                                    </button>
                                                </form>
                                                <button type="button"
                                                    class="h-9 px-4 inline-flex items-center justify-center bg-rose-500 text-white text-xs leading-none rounded text-center shadow hover:bg-rose-600 transition open-reject-modal"
                                                    data-action="{{ route($routePrefix . '.reject', $p->idpeminjaman) }}"
                                                    data-barang="{{ $p->barang->nama_barang }}"
                                                    data-peminjam="{{ $p->user->nama ?? $p->user->username ?? $p->user->email ?? '-' }}">
                                                    Reject
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4 text-sm text-indigo-100/80">
                                    <div class="space-y-2">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-indigo-200/80">Peminjam</p>
                                            <p class="text-sm font-semibold text-white">{{ $p->user->nama ?? $p->user->username ?? $p->user->email ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-indigo-200/80">Kegiatan</p>
                                            <p class="text-sm font-semibold text-white">{{ $p->kegiatan === 'kampus' ? 'Kegiatan Kampus' : 'Kegiatan Luar Kampus' }}</p>
                                            <p class="text-xs text-indigo-100/70">{{ $p->keterangan_kegiatan ?? '-' }}</p>
                                            @if($p->kegiatan === 'kampus')
                                                <p class="text-xs text-indigo-100/70">Lokasi: {{ $p->ruang->nama_ruang ?? '-' }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <div>
                                            <p class="text-xs uppercase tracking-wide text-indigo-200/80">Jumlah</p>
                                            <p class="text-sm font-semibold text-white">{{ $p->jumlah }}</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2">
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">Rencana Pinjam</p>
                                                <p class="text-sm text-white">{{ $p->tgl_pinjam_rencana ? $p->tgl_pinjam_rencana->format('d-m-Y') : '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">Rencana Kembali</p>
                                                <p class="text-sm text-white">{{ $p->tgl_kembali_rencana ? $p->tgl_kembali_rencana->format('d-m-Y') : '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">Tgl Pinjam</p>
                                                <p class="text-sm text-white">{{ $p->tgl_pinjam ? $p->tgl_pinjam->format('d-m-Y H:i') : '-' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs uppercase tracking-wide text-indigo-200/80">Tgl Kembali Aktual</p>
                                                <p class="text-sm text-white">{{ $p->tgl_kembali ? $p->tgl_kembali->format('d-m-Y H:i') : '-' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="space-y-3">
                                        @if($p->status === 'ditolak' && $p->alasan_penolakan)
                                            <div class="p-3 rounded-xl bg-rose-500/10 border border-rose-400/30 text-rose-50 text-xs">
                                                Alasan ditolak: {{ $p->alasan_penolakan }}
                                            </div>
                                        @elseif($p->status === 'disetujui')
                                            <div class="p-3 rounded-xl bg-amber-500/10 border border-amber-400/30 text-amber-50 text-xs">
                                                @if($p->tgl_pinjam_rencana)
                                                    Jadwal: {{ $p->tgl_pinjam_rencana->format('d-m-Y') }}
                                                @else
                                                    Menunggu jadwal penjemputan.
                                                @endif
                                                @if($p->tgl_pinjam_rencana && now()->gt($p->tgl_pinjam_rencana->startOfDay()))
                                                    <br><span class="text-amber-200/90">Sudah lewat jadwal, konfirmasi pengambilan.</span>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="flex flex-wrap gap-2">
                                            @if($p->status == 'disetujui')
                                                @php
                                                    $bolehMulai = !$p->tgl_pinjam_rencana || now()->greaterThanOrEqualTo($p->tgl_pinjam_rencana->startOfDay());
                                                @endphp
                                                <form action="{{ route($routePrefix . '.pickup', $p->idpeminjaman) }}"
                                                    method="POST" class="space-y-2" data-rfid-form>
                                                    @csrf
                                                    <input type="text" name="rfid_uid" autocomplete="off" inputmode="none"
                                                        aria-label="RFID UID"
                                                        class="sr-only rfid-input">
                                                    <p class="text-[10px] text-indigo-100/70 rfid-helper">Scan kartu/tag RFID peminjam untuk melanjutkan.</p>
                                                    <div class="hidden rfid-manual space-y-2">
                                                        <label class="block text-[10px] text-indigo-100/70">Password {{ auth()->user()?->role === 'admin' ? 'admin' : 'pegawai' }}</label>
                                                        <input type="password" name="manual_password" autocomplete="current-password"
                                                            class="w-full max-w-xs rounded-lg bg-slate-800/70 border border-white/10 text-white text-xs focus:ring-2 focus:ring-amber-400 focus:border-amber-400 px-2 py-1.5"
                                                            placeholder="Masukkan password">
                                                    </div>
                                                    <label class="inline-flex items-center gap-2 text-[10px] text-indigo-100/70">
                                                        <input type="checkbox" class="rounded border-white/20 bg-slate-800/70 text-amber-400 manual-toggle">
                                                        Konfirmasi manual
                                                    </label>
                                                    <div class="flex flex-wrap gap-2">
                                                        <button type="button"
                                                            class="px-3 py-2 text-xs rounded text-center shadow transition bg-white/10 text-indigo-100 hover:bg-white/20 rfid-scan-btn">
                                                            Scan RFID
                                                        </button>
                                                        <button type="submit"
                                                            data-allow="{{ $bolehMulai ? '1' : '0' }}"
                                                            class="px-3 py-2 text-xs rounded text-center shadow transition {{ $bolehMulai ? 'bg-indigo-600 text-white hover:bg-indigo-700' : 'bg-white/10 text-indigo-100/60 cursor-not-allowed' }} rfid-submit"
                                                            disabled>
                                                            {{ $bolehMulai ? 'Mulai Peminjaman' : 'Menunggu Jadwal' }}
                                                        </button>
                                                    </div>
                                                </form>
                                            @elseif($p->status == 'dipinjam')
                                                <form action="{{ route($routePrefix . '.return', $p->idpeminjaman) }}"
                                                    method="POST" class="space-y-2 bg-white/5 border border-white/10 rounded-xl p-3 w-full max-w-xs" data-rfid-form>
                                                    @csrf
                                                    <div>
                                                        <label class="block text-xs font-medium text-indigo-100 mb-1">Tanggal pengembalian aktual</label>
                                                        <input type="datetime-local" name="tgl_kembali_real"
                                                            class="w-full rounded-lg bg-slate-800/70 border border-white/10 text-white text-xs focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400"
                                                            value="{{ now()->format('Y-m-d\TH:i') }}" required>
                                                    </div>
                                                    <label class="flex items-center text-xs text-indigo-100 space-x-2">
                                                        <input type="checkbox" name="konfirmasi_pengembalian" required class="text-indigo-600 border-white/20 rounded bg-slate-800/70">
                                                        <span>Barang sudah dikembalikan</span>
                                                    </label>
                                                    <input type="text" name="rfid_uid" autocomplete="off" inputmode="none"
                                                        aria-label="RFID UID"
                                                        class="sr-only rfid-input">
                                                    <p class="text-[10px] text-indigo-100/70 rfid-helper">Scan kartu/tag RFID peminjam untuk konfirmasi pengembalian.</p>
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
                                                            class="px-3 py-2 text-xs rounded text-center shadow transition bg-white/10 text-indigo-100 hover:bg-white/20 rfid-scan-btn">
                                                            Scan RFID
                                                        </button>
                                                        <button type="submit"
                                                            data-allow="1"
                                                            class="px-3 py-2 text-xs rounded text-center shadow transition bg-emerald-500 text-white hover:bg-emerald-600 rfid-submit"
                                                            disabled>
                                                            Konfirmasi
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-sm text-indigo-100/80">
                                <p class="text-lg font-semibold">Tidak ada data peminjaman</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="px-6 py-4 bg-white/5 border-t border-white/10 text-indigo-100 flex items-center justify-between">
                        <div>
                            Menampilkan {{ $peminjaman->firstItem() ?? 0 }} hingga {{ $peminjaman->lastItem() ?? 0 }} dari {{ $peminjaman->total() }} entri
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

<!-- Modal Reject -->
<div id="reject-modal" class="hidden fixed inset-0 z-50 items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/70 backdrop-blur-sm"></div>
    <div class="relative bg-slate-900 text-white border border-white/10 rounded-2xl shadow-2xl shadow-rose-500/20 w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-lg font-semibold">Tolak Peminjaman</h2>
                <p class="text-sm text-indigo-100/80" id="reject-meta"></p>
            </div>
            <button type="button" class="text-indigo-100 hover:text-white text-2xl leading-none" id="close-reject-modal">&times;</button>
        </div>
        <form id="reject-form" method="POST">
            @csrf
            <textarea name="alasan_penolakan" id="alasan-penolakan"
                class="w-full rounded-xl bg-slate-800/70 border border-white/10 text-white focus:border-rose-400 focus:ring-2 focus:ring-rose-400"
                rows="4" placeholder="Tuliskan alasan penolakan" required>{{ old('alasan_penolakan') }}</textarea>
            <div class="mt-4 flex items-center justify-end gap-3">
                <button type="button" id="cancel-reject"
                    class="px-4 py-2 rounded-lg border border-white/10 text-indigo-100 hover:bg-white/10 transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 rounded-lg bg-rose-600 text-white font-semibold hover:bg-rose-700 shadow shadow-rose-500/30">
                    Tolak Permintaan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('reject-modal');
    const meta = document.getElementById('reject-meta');
    const textarea = document.getElementById('alasan-penolakan');
    const form = document.getElementById('reject-form');
    const closeButtons = [document.getElementById('close-reject-modal'), document.getElementById('cancel-reject')];

    function openModal(action, barang, peminjam) {
        form.action = action;
        meta.textContent = `${peminjam ?? '-' } - ${barang ?? '-'}`;
        textarea.value = '';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        textarea.focus();
    }

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    document.querySelectorAll('.open-reject-modal').forEach(button => {
        button.addEventListener('click', () => {
            openModal(
                button.dataset.action,
                button.dataset.barang,
                button.dataset.peminjam
            );
        });
    });

    closeButtons.forEach(btn => btn.addEventListener('click', closeModal));
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    document.querySelectorAll('[data-rfid-form]').forEach((form) => {
        const input = form.querySelector('.rfid-input');
        const scanButton = form.querySelector('.rfid-scan-btn');
        const submitButton = form.querySelector('.rfid-submit');
        const helper = form.querySelector('.rfid-helper');
        const manualToggle = form.querySelector('.manual-toggle');
        const manualWrap = form.querySelector('.rfid-manual');
        const manualInput = form.querySelector('input[name="manual_password"]');
        const canSchedule = submitButton?.dataset.allow === '1';

        const syncSubmitState = () => {
            const hasValue = input && input.value.trim() !== '';
            const manualValue = manualInput && manualInput.value.trim() !== '';
            if (!submitButton) {
                return;
            }
            submitButton.disabled = !(canSchedule && (hasValue || manualValue));
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
            } else {
                if (helper) {
                    helper.textContent = 'Scan kartu/tag RFID peminjam untuk melanjutkan.';
                }
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
