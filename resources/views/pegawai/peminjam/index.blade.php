@extends('layouts.app')

@section('content')
<div class="pt-24 min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Data Peminjam</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau profil peminjam dan ketahui apakah mereka umum, mahasiswa, atau pegawai.</p>
            </div>
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama/email..."
                    class="rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2 w-60">
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">Cari</button>
            </form>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($tipeCounts as $count)
                <div class="bg-white p-4 rounded-2xl shadow border border-gray-100">
                    <p class="text-sm text-gray-500">Profil {{ ucfirst($count->tipe_peminjam ?? 'lainnya') }}</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $count->total }}</p>
                </div>
            @endforeach
        </div>

        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Profil</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Info Tambahan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Diperbarui</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($peminjam as $index => $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    {{ $peminjam->firstItem() + $index }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->nama }}</div>
                                    <div class="text-xs text-gray-500">Username: {{ $user->username }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    <p>{{ $user->email }}</p>
                                    <p class="text-xs text-gray-500">{{ $user->nohp ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span @class([
                                        'px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center gap-2',
                                        'bg-gray-100 text-gray-700' => $user->tipe_peminjam === 'umum',
                                        'bg-blue-100 text-blue-700' => $user->tipe_peminjam === 'mahasiswa',
                                        'bg-amber-100 text-amber-700' => $user->tipe_peminjam === 'pegawai',
                                    ])>
                                        {{ ucfirst($user->tipe_peminjam ?? 'Tidak diketahui') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-700">
                                    @if($user->tipe_peminjam === 'mahasiswa')
                                        <p>Prodi: {{ $user->prodi ?? '-' }}</p>
                                        <p>Angkatan: {{ $user->angkatan ?? '-' }}</p>
                                        <p>NIM: {{ $user->nim ?? '-' }}</p>
                                    @elseif($user->tipe_peminjam === 'pegawai')
                                        <p>Divisi: {{ $user->divisi ?? '-' }}</p>
                                    @else
                                        <p>-</p>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $user->updated_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                    <p class="font-semibold">Belum ada data peminjam.</p>
                                    <p class="text-sm mt-2">Peminjam baru akan muncul setelah melakukan registrasi.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <p class="text-sm text-gray-600">
                    Menampilkan {{ $peminjam->firstItem() ?? 0 }} - {{ $peminjam->lastItem() ?? 0 }} dari {{ $peminjam->total() }} peminjam
                </p>
                <div>
                    {{ $peminjam->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
