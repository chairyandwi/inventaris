<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pemakaian Barang Pinjam</title>
    <style>
        @page {
            margin: 20mm 12mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            color: #333;
            background: white;
        }

        .rangkasurat {
            margin: 0 auto;
            padding: 10mm 8mm 0;
            max-width: 210mm;
        }

        .tengah {
            text-align: center;
            line-height: 1.2;
        }

        .header-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .logo-cell {
            width: 120px;
            vertical-align: top;
            padding-right: 20px;
        }

        .logo-cell img {
            height: 100px;
            width: auto;
            padding-left: 10px;
            object-fit: contain;
        }

        .logo-placeholder {
            height: 100px;
            width: 100px;
            background: #e3f2fd;
            border: 2px solid #2196f3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #1976d2;
            font-weight: bold;
            text-align: center;
            border-radius: 8px;
            margin-left: 10px;
        }

        .header-info {
            vertical-align: top;
        }

        .header-info h2 {
            font-size: 18pt;
            font-weight: bold;
            margin-bottom: 5px;
            color: #000;
        }

        .header-info h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 8px;
            color: #000;
        }

        .header-info b {
            font-size: 12pt;
            display: block;
            margin-bottom: 5px;
        }

        .header-info p {
            font-size: 11pt;
            margin: 0;
        }

        hr {
            border: none;
            border-top: 1px solid black;
            margin: 20px 0;
        }

        .report-title {
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
        }

        .report-title b {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .signature-block {
            page-break-inside: avoid;
        }

        table.isi {
            border-collapse: collapse;
            font-size: 11pt;
            width: 100%;
            margin: 10px auto 0;
        }

        .isi th {
            border: 1px solid #000;
            padding: 8px 5px;
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .isi td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
        }

        .number-cell {
            text-align: center;
            font-weight: bold;
        }

        .code-cell {
            text-align: center;
            font-family: monospace;
        }

        .stock-cell {
            text-align: center;
            font-weight: bold;
        }

        .category-cell {
            text-align: center;
        }

        .muted {
            color: #666;
            font-style: italic;
        }

        @media screen and (max-width: 768px) {
            .rangkasurat {
                padding: 10px;
                margin: 0 5px;
            }

            .header-table {
                width: 100%;
            }

            .logo-cell,
            .header-info {
                display: block;
                width: 100%;
                text-align: center;
                margin-bottom: 15px;
            }

            .logo-cell img,
            .logo-placeholder {
                margin-left: 0;
            }

            table.isi {
                font-size: 9pt;
            }

            .isi th,
            .isi td {
                padding: 4px 2px;
            }
        }

        @media print {
            body {
                background: white;
                font-size: 11pt;
            }

            .rangkasurat {
                margin: 0 auto;
                padding: 0;
                max-width: none;
                width: 100%;
            }

            hr {
                border-top: 2px solid black;
            }

            .isi th {
                background-color: #f0f0f0 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }

            .isi tr {
                page-break-inside: avoid;
            }

            .isi thead {
                display: table-header-group;
            }
        }
    </style>
</head>

<body>
    @php
        $appConfig = $globalAppConfig ?? null;
        $usePdfConfig = $appConfig && $appConfig->apply_pdf;
        $kampusName =
            $usePdfConfig && $appConfig->nama_kampus
                ? strtoupper($appConfig->nama_kampus)
                : 'UNIVERSITAS PROKLAMASI 45';
        $departemen = $usePdfConfig && $appConfig->profil ? $appConfig->profil : 'BAGIAN UMUM';
        $alamat =
            $usePdfConfig && $appConfig->alamat ? $appConfig->alamat : 'Jl. Proklamasi No. 1, Babarsari, Yogyakarta.';
        $telepon = $usePdfConfig && $appConfig->telepon ? $appConfig->telepon : '(0274) 485535';
        $email = $usePdfConfig && $appConfig->email ? $appConfig->email : 'itsupport@up45.ac.id';
        $website = $usePdfConfig && $appConfig->website ? $appConfig->website : 'up45.ac.id';
        $logoFile =
            $usePdfConfig && $appConfig && $appConfig->logo
                ? storage_path('app/public/' . $appConfig->logo)
                : public_path('images/up.png');
        $logoBase64 = '';
        if (file_exists($logoFile)) {
            $logoBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($logoFile));
        }
        $signatureBase64 = '';
        if ($usePdfConfig && $appConfig && $appConfig->petugas_signature) {
            $signatureFile = storage_path('app/public/' . $appConfig->petugas_signature);
            if (file_exists($signatureFile)) {
                $signatureBase64 = 'data:image/png;base64,' . base64_encode(file_get_contents($signatureFile));
            }
        }
    @endphp
    <div class="rangkasurat">
        <table class="header-table">
            <tr>
                <td class="logo-cell">
                    @if ($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo Kampus">
                    @else
                        <div class="logo-placeholder">
                            LOGO<br>KAMPUS
                        </div>
                    @endif
                </td>

                <td class="tengah header-info">
                    <h2>{{ $kampusName }}</h2>
                    <h1>{{ strtoupper($departemen) }}</h1>
                    <b>{{ $alamat }} Telp : {{ $telepon }}</b>
                    <p>Email : {{ $email }} Website : {{ $website }} Kode Pos : 55281</p>
                </td>
            </tr>
        </table>

        <hr>

        <div class="report-title" style="page-break-inside: avoid;">
            <b>LAPORAN PEMAKAIAN BARANG PINJAM</b>
        </div>

        <div>
            <table class="isi">
                <thead><br>
                    <tr>
                        <th width="20">NO</th>
                        <th>Mulai</th>
                        <th>Sampai</th>
                        <th>Barang</th>
                        <th>Merk</th>
                        <th>Kegiatan</th>
                        <th>Pemakai</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php $urut = 1; @endphp
                    @forelse($riwayat as $row)
                        <tr>
                            <td class="number-cell">{{ $urut++ }}</td>
                            <td class="category-cell">{{ $row->digunakan_mulai?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td class="category-cell">{{ $row->digunakan_sampai?->format('d-m-Y H:i') ?? '-' }}</td>
                            <td>{{ $row->barang?->nama_barang ?? '-' }}</td>
                            <td class="category-cell">{{ $row->merk ?? '-' }}</td>
                            <td>{{ $row->kegiatan }}</td>
                            <td>{{ $row->creator?->nama ?? $row->creator?->username ?? '-' }}</td>
                            <td class="stock-cell">{{ $row->jumlah ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="muted" style="text-align: center; padding: 10px;">
                                Belum ada data pemakaian
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @php
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
        $petugasInventaris =
            $usePdfConfig && $appConfig && $appConfig->petugas_inventaris
                ? $appConfig->petugas_inventaris
                : 'Nama Petugas';
        $totalTransaksi = $riwayat->count();
        $totalUnit = $riwayat->sum('jumlah');
    @endphp

    <div
        style="
    display: flex;
    justify-content: flex-start;
    gap: 14px;
    margin-top: 14px;
    font-family: 'Arial', sans-serif;
    font-size: 11pt;
    page-break-inside: avoid;
    flex-wrap: wrap;
">
        <div
            style="
        background-color: #f0f4f8;
        padding: 12px 18px;
        border-radius: 10px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        min-width: 200px;
        width: 240px;
    ">
            <span style="font-size: 10pt; color: #555;">Total Transaksi</span>
            <span style="font-size: 10pt; font-weight: 700; color: #1e3a8a;">{{ $totalTransaksi }} pencatatan</span>
        </div>
        <div
            style="
        background-color: #f0f4f8;
        padding: 12px 18px;
        border-radius: 10px;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        min-width: 200px;
        width: 240px;
    ">
            <span style="font-size: 10pt; color: #555;">Total Unit Digunakan</span>
            <span style="font-size: 10pt; font-weight: 700; color: #1e3a8a;">{{ $totalUnit }} unit</span>
        </div>
    </div>

    <div class="signature-block" style="margin-top: 16px; line-height: 1.4;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 60%;"></td>
                <td style="width: 40%; text-align: center;">
                    <div style="font-size: 11pt; font-weight: bold; text-transform: uppercase;">Sleman, {{ $tanggal }}</div>
                    <div style="font-size: 11pt; font-weight: bold; margin-top: 4px;">Petugas Inventaris</div>
                    @if ($signatureBase64)
                        <div style="margin-top: 10px;">
                            <img src="{{ $signatureBase64 }}" alt="Tanda Tangan Petugas"
                                style="height: 70px; width: auto; object-fit: contain;">
                        </div>
                    @else
                        <div style="margin-top: 26px;"></div>
                    @endif
                    <div style="margin-top: 12px; font-weight: bold; text-decoration: underline; font-size: 11pt;">
                        {{ $petugasInventaris }}
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
