@extends('layouts.app')

@section('content')
<div class="pt-24 min-h-screen bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="bg-white rounded-2xl shadow overflow-hidden">
            <div class="px-6 py-4 border-b flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Log Aktivitas</h1>
                    <p class="text-sm text-gray-500">Catatan perubahan dan aktivitas penting sistem.</p>
                </div>
                <a href="{{ route('admin.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-semibold">&larr; Dashboard</a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-sm text-gray-500">{{ $log->created_at?->format('d M Y H:i') }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ optional($log->user)->nama ?? optional($log->user)->username ?? optional($log->user)->email ?? '-' }}</td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $log->aksi }}</td>
                                <td class="px-6 py-4 text-sm text-gray-700">{{ $log->deskripsi ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">Belum ada log aktivitas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
