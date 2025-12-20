<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Ruang</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11pt; color: #111; background: #fff; }
        .page { padding: 18px; }
        .title { text-align: center; margin-bottom: 12px; }
        .title h1 { font-size: 14pt; letter-spacing: 0.5px; text-transform: uppercase; }
        .meta { font-size: 9pt; color: #333; text-align: right; margin-bottom: 8px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 4px 5px; font-size: 9.5pt; }
        th { background: #f2f2f2; text-align: center; }
        td { vertical-align: middle; }
        .center { text-align: center; }
        .mono { font-family: monospace; }
    </style>
</head>
<body>
    @php
        $appConfig = $globalAppConfig ?? null;
        $usePdfConfig = $appConfig && $appConfig->apply_pdf;
        date_default_timezone_set('Asia/Jakarta');
        $monthNames = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        $tanggal = date('d') . ' ' . $monthNames[(int) date('n')] . ' ' . date('Y');
        $petugasInventaris = $usePdfConfig && $appConfig && $appConfig->petugas_inventaris
            ? $appConfig->petugas_inventaris
            : 'Nama Petugas';
        $signatureBase64 = '';
        if ($usePdfConfig && $appConfig && $appConfig->petugas_signature) {
            $signatureFile = storage_path('app/public/' . $appConfig->petugas_signature);
            if (file_exists($signatureFile)) {
                $signatureBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($signatureFile));
            }
        }
    @endphp

    <div class="page">
        <div class="title">
            <h1>Laporan Inventaris Per Ruang</h1>
        </div>

        <table>
            <tr>
                <th width="22">NO</th>
                <th>Ruang</th>
                <th>Gedung</th>
                <th>Kode Unit</th>
                <th>Barang</th>
                <th>Kategori</th>
                <th>Keterangan</th>
            </tr>
            @php $urut = 1; @endphp
            @forelse($units as $unit)
                <tr>
                    <td class="center">{{ $urut++ }}</td>
                    <td class="center">{{ $unit->ruang->nama_ruang ?? '-' }}</td>
                    <td class="center">{{ $unit->ruang->nama_gedung ?? '-' }}</td>
                    <td class="center mono">{{ $unit->kode_unit ?? '-' }}</td>
                    <td class="center">{{ $unit->barang->nama_barang ?? '-' }}</td>
                    <td class="center">{{ $unit->barang->kategori->nama_kategori ?? '-' }}</td>
                    <td class="center">{{ $unit->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="center">Belum ada unit barang tercatat per ruang</td>
                </tr>
            @endforelse
        </table>

        <div style="margin-top: 22px; text-align: right; line-height: 1.4;">
            <div style="font-size: 11pt; font-weight: bold; text-transform: uppercase;">Sleman, {{ $tanggal }}</div>
            <div style="font-size: 11pt; font-weight: bold; margin-top: 4px;">Petugas Inventaris</div>
            @if ($signatureBase64)
                <div style="margin-top: 10px;">
                    <img src="{{ $signatureBase64 }}" alt="Tanda Tangan Petugas" style="height: 70px; width: auto; object-fit: contain;">
                </div>
            @else
                <div style="margin-top: 26px;"></div>
            @endif
            <div style="margin-top: 12px; font-weight: bold; text-decoration: underline; font-size: 11pt;">
                {{ $petugasInventaris }}
            </div>
        </div>
    </div>
</body>
</html>
