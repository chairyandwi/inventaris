@php
    $appConfig = $globalAppConfig ?? null;
    $useLayoutConfig = $appConfig && $appConfig->apply_layout;
    $brandName = $useLayoutConfig && $appConfig->nama_kampus ? $appConfig->nama_kampus : 'Sistem Inventaris Kampus';
@endphp
<footer class="bg-gray-800 text-white text-center py-6">
    <p>&copy; {{ date('Y') }} {{ $brandName }}. All rights reserved.</p>
    @if($useLayoutConfig && ($appConfig->alamat || $appConfig->telepon || $appConfig->email))
        <p class="text-sm text-gray-300 mt-2">
            {{ $appConfig->alamat ?? '' }}
            @if($appConfig->telepon) · Telp: {{ $appConfig->telepon }} @endif
            @if($appConfig->email) · Email: {{ $appConfig->email }} @endif
        </p>
    @endif
</footer>
