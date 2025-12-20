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
        date_default_timezone_set('Asia/Jakarta');
        $currentTime = date('d/m/Y H:i:s');
    @endphp

    <div class="page">
        <div class="title">
            <h1>Laporan Inventaris Per Ruang</h1>
        </div>
        <div class="meta">Dicetak: {{ $currentTime }}</div>

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
    </div>
</body>
</html>
