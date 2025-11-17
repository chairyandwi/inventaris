@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <section class="pt-24 pb-14 bg-gradient-to-r from-indigo-700 to-purple-500 text-white text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl font-extrabold mb-4">Halo {{ auth()->user()->nama }}!</h2>
            <p class="text-lg">Panel administrator untuk mengelola seluruh aset, peminjaman, dan konfigurasi aplikasi.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $cards = [
                    ['label' => 'Kategori', 'value' => $kategori, 'text' => 'text-lime-600', 'pill' => 'bg-lime-50 hover:bg-lime-100', 'route' => route('admin.kategori.index')],
                    ['label' => 'Ruang', 'value' => $ruang, 'text' => 'text-violet-600', 'pill' => 'bg-violet-50 hover:bg-violet-100', 'route' => route('admin.ruang.index')],
                    ['label' => 'Barang', 'value' => $barang, 'text' => 'text-pink-600', 'pill' => 'bg-pink-50 hover:bg-pink-100', 'route' => route('admin.barang.index')],
                    ['label' => 'User', 'value' => $user, 'text' => 'text-amber-600', 'pill' => 'bg-amber-50 hover:bg-amber-100', 'route' => route('admin.user.index')],
                ];
            @endphp
            @foreach($cards as $card)
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium uppercase tracking-wide {{ $card['text'] }}">{{ $card['label'] }}</p>
                            <p class="text-3xl font-bold text-gray-900 mt-2">{{ $card['value'] }}</p>
                        </div>
                        <a href="{{ $card['route'] }}" class="p-3 rounded-lg transition {{ $card['pill'] }}">
                            <svg class="w-6 h-6 {{ $card['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm font-semibold text-gray-500 uppercase">Barang Masuk</p>
                <p class="text-3xl font-bold text-gray-900">{{ $barangMasuk }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm font-semibold text-gray-500 uppercase">Barang Dipinjam</p>
                <p class="text-3xl font-bold text-gray-900">{{ $barangPinjam }}</p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-sm font-semibold text-gray-500 uppercase">Barang Keluar</p>
                <p class="text-3xl font-bold text-gray-900">{{ $barangKeluar }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Log Aktivitas Terbaru</h3>
                <a href="{{ route('admin.logs.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700">Lihat Semua</a>
            </div>
            @if($pesanAktivitas->isEmpty())
                <p class="text-sm text-gray-500">Belum ada log aktivitas.</p>
            @else
                <ul class="space-y-3">
                    @foreach($pesanAktivitas as $log)
                        <li class="flex items-start space-x-3">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-semibold">
                                {{ strtoupper(substr($log->user->nama ?? $log->user->email, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $log->aksi }}</p>
                                <p class="text-xs text-gray-500">{{ $log->deskripsi }}</p>
                                <p class="text-xs text-gray-400">{{ $log->created_at?->format('d M Y H:i') }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
@endsection
