<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Label Inventaris</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #111;
            background: #fff;
        }

        .sheet {
            padding: 10mm 8mm;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, 5cm);
            grid-auto-rows: 2.5cm;
            gap: 4mm;
        }

        .label {
            width: 5cm;
            height: 2.5cm;
            border: 0.35mm solid #111;
            padding: 1mm;
        }

        .label-inner {
            width: 100%;
            height: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .label-inner tr {
            height: 8.3mm;
        }

        .logo-col {
            width: 22%;
            text-align: center;
            vertical-align: middle;
            border-right: 0.25mm solid #111;
        }

        .info-col {
            width: 58%;
            vertical-align: middle;
            text-align: center;
            border-right: 0.25mm solid #111;
        }

        .cond-col {
            width: 20%;
            text-align: center;
            vertical-align: middle;
            font-size: 7pt;
            font-weight: bold;
        }

        .label-inner td {
            line-height: 1.15;
            overflow: hidden;
        }

        .row {
            border-bottom: 0.25mm solid #111;
        }

        .row:last-child {
            border-bottom: none;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-height: 20mm;
        }

        .title {
            font-weight: bold;
            font-size: 7pt;
        }

        .value {
            font-size: 7.4pt;
            font-weight: bold;
        }

        .small {
            font-size: 6.4pt;
        }

        .muted {
            color: #444;
        }

        .cond-label {
            font-size: 6.2pt;
            text-transform: uppercase;
            letter-spacing: 0.2px;
        }
    </style>
</head>
<body>
    @php
        $appConfig = $globalAppConfig ?? null;
        $usePdfConfig = $appConfig && $appConfig->apply_pdf;
        $logoFile =
            $usePdfConfig && $appConfig && $appConfig->logo
                ? storage_path('app/public/' . $appConfig->logo)
                : public_path('images/up.png');
        $logoBase64 = '';
        if (file_exists($logoFile)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoFile));
        }

        $isRusak = (bool) $unit->kerusakanAktif;
        $isKurangBaik = !$isRusak && $unit->keterangan && str_contains(strtolower($unit->keterangan), 'kurang');
        $statusLabel = $isRusak ? 'Rusak' : ($isKurangBaik ? 'Kurang Baik' : 'Baik');

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
        $cekTanggal = now()->format('d') . ' ' . $monthNames[(int) now()->format('n')] . ' ' . now()->format('Y');
    @endphp

    <div class="sheet">
        <div class="grid">
            @for ($i = 0; $i < 9; $i++)
                <div class="label">
                    <table class="label-inner">
                        <tr class="row">
                            <td class="logo-col" rowspan="3">
                                <div class="logo">
                                    @if ($logoBase64)
                                        <img src="{{ $logoBase64 }}" alt="Logo" style="width: 9mm; height: 9mm; object-fit: contain;">
                                    @else
                                        <span class="small">LOGO</span>
                                    @endif
                                </div>
                            </td>
                            <td class="info-col">
                                <div class="title">No Reg:</div>
                                <div class="value">{{ $unit->kode_unit ?? '-' }}</div>
                            </td>
                            <td class="cond-col" rowspan="3">
                                <div class="cond-label">Kondisi</div>
                                <div>{{ $statusLabel }}</div>
                            </td>
                        </tr>
                        <tr class="row">
                            <td class="info-col">
                                <div class="title">Barang:</div>
                                <div class="value">
                                    {{ $unit->barang?->nama_barang ?? '-' }}
                                    <span class="small muted">({{ $unit->ruang?->nama_ruang ?? '-' }})</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="info-col">
                                <div class="small muted">Cek: {{ $cekTanggal }}</div>
                            </td>
                        </tr>
                    </table>
                </div>
            @endfor
        </div>
    </div>
</body>
</html>
