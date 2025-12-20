<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inventaris Ruang</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 12pt; color: #333; background: white; }
        .rangkasurat { margin: 0 auto; padding: 20px; max-width: 210mm; }
        .tengah { text-align: center; line-height: 1.2; }
        .report-title { text-align: center; padding: 10px; margin-bottom: 20px; }
        .report-title b { font-size: 14pt; text-transform: uppercase; letter-spacing: 1px; }
        table.isi { border-collapse: collapse; font-size: 11pt; width: 100%; margin-top: 10px; }
        .isi th { border: 1px solid #000; padding: 8px 5px; background-color: #f5f5f5; font-weight: bold; text-align: center; }
        .isi td { border: 1px solid #000; padding: 5px; vertical-align: middle; }
        .number-cell { text-align: center; font-weight: bold; }
        .code-cell { text-align: center; font-family: monospace; }
        .category-cell { text-align: center; }
        .empty-state { text-align: center; padding: 30px; color: #666; font-style: italic; }
        .section-title { font-size: 13pt; font-weight: bold; margin-top: 25px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        @media print { .isi th { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; color-adjust: exact; } }
    </style>
</head>
<body>
    @php
        $appConfig = $globalAppConfig ?? null;
        $usePdfConfig = $appConfig && $appConfig->apply_pdf;
        $kampusName = $usePdfConfig && $appConfig->nama_kampus ? strtoupper($appConfig->nama_kampus) : 'UNIVERSITAS PROKLAMASI 45';
        $departemen = $usePdfConfig && $appConfig->profil ? $appConfig->profil : 'BAGIAN UMUM';
        $alamat = $usePdfConfig && $appConfig->alamat ? $appConfig->alamat : 'Jl. Proklamasi No. 1, Babarsari, Yogyakarta.';
        $telepon = $usePdfConfig && $appConfig->telepon ? $appConfig->telepon : '(0274) 485535';
        $email = $usePdfConfig && $appConfig->email ? $appConfig->email : 'itsupport@up45.ac.id';
        $website = $usePdfConfig && $appConfig->website ? $appConfig->website : 'up45.ac.id';
        $logoFile = $usePdfConfig && $appConfig && $appConfig->logo
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
    @endphp

    <div class="rangkasurat">
        <table class="header-table">
            <tr>
                <td class="logo-cell" style="width:120px; vertical-align: top; padding-right:20px;">
                    @if ($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="Logo" style="height: 100px; width: auto; padding-left: 10px; object-fit: contain;">
                    @endif
                </td>
                <td class="tengah header-info">
                    <h2 style="font-size: 18pt; font-weight: bold; margin-bottom: 5px; color: #000;">{{ $kampusName }}</h2>
                    <h1 style="font-size: 16pt; font-weight: bold; margin-bottom: 8px; color: #000;">{{ strtoupper($departemen) }}</h1>
                    <b style="font-size: 12pt; display: block; margin-bottom: 5px;">{{ $alamat }} Telp : {{ $telepon }}</b>
                    <p style="font-size: 11pt; margin: 0;">Email : {{ $email }} Website : {{ $website }} Kode Pos : 55281</p>
                </td>
            </tr>
        </table>

        <hr style="border: none; border-top: 1px solid black; margin: 20px 0;">

        <div class="report-title">
            <b>LAPORAN INVENTARIS PER RUANG</b>
        </div>

        <div>
            @php
                $firstUnit = $units->first();
                $ruangLabel = $firstUnit?->ruang?->nama_ruang ?? '-';
                $gedungLabel = $firstUnit?->ruang?->nama_gedung ?? '-';
            @endphp
            <div class="section-title">Nama Ruang: {{ $ruangLabel }}</div>
            <div class="section-title" style="margin-top: 4px;">Gedung: {{ $gedungLabel }}</div>
            <table class="isi">
                <tr>
                    <th width="20">NO</th>
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
                        <td class="number-cell">{{ $urut++ }}</td>
                        <td class="category-cell">{{ $unit->ruang->nama_ruang ?? '-' }}</td>
                        <td class="category-cell">{{ $unit->ruang->nama_gedung ?? '-' }}</td>
                        <td class="code-cell">{{ $unit->kode_unit ?? '-' }}</td>
                        <td class="category-cell">{{ $unit->barang->nama_barang ?? '-' }}</td>
                        <td class="category-cell">{{ $unit->barang->kategori->nama_kategori ?? '-' }}</td>
                        <td class="category-cell">{{ $unit->keterangan ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty-state">
                            Belum ada unit barang tercatat per ruang
                        </td>
                    </tr>
                @endforelse
            </table>
        </div>

        <div style="margin-top: 24px; text-align: right; line-height: 1.4;">
            <div class="section-title" style="text-transform: uppercase;">Sleman, {{ $tanggal }}</div>
            <div class="section-title" style="margin-top: 4px;">Petugas Inventaris</div>
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
