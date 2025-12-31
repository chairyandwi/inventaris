<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Label Inventaris</title>

<style>
/* ========== RESET ========== */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* ========== KERTAS ========== */
@page {
    size: A4;
    margin: 12mm;
}

body {
    font-family: Arial, sans-serif;
    font-size: 7pt;
    color: #111;
}

/* ========== GRID KERTAS ========== */
.sheet {
    width: 100%;
    border-collapse: separate;
    border-spacing: 4mm 4mm;
}

.sheet > tbody > tr > td {
    width: 33.33%;
}

/* ========== LABEL ========== */
.label {
    width: 60mm;
    height: 25mm;
    border: 0.35mm solid #111;
}

/* ========== STRUKTUR LABEL ========== */
.label-cols {
    width: 100%;
    height: 100%;
    position: relative;
}

.label-cols::before,
.label-cols::after {
    content: "";
    position: absolute;
    top: 0;
    bottom: 0;
    width: 0.25mm;
    background: #111;
}

.label-cols::before {
    left: calc(12mm - 0.125mm);
}

.label-cols::after {
    left: calc(42mm - 0.125mm);
}

.label-cols > .col-logo,
.label-cols > .col-info,
.label-cols > .col-cond {
    height: 100%;
    text-align: center;
    box-sizing: border-box;
    position: absolute;
    top: 0;
    bottom: 0;
}

/* ========== KOLOM (FINAL) ========== */
.col-logo {
    width: 12mm;
    left: 0;
    display: block;
    position: relative;
}

.col-info {
    width: 30mm;
    left: 12mm;
    padding: 0;
    overflow: hidden;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
}

.col-cond {
    width: 18mm;
    left: 42mm;
    font-weight: bold;
    display: block;
    position: relative;
    padding: 0;
    box-sizing: border-box;
    text-align: center;
}

.logo-wrap,
.cond-wrap {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 100%;
    text-align: center;
}

/* ========== LOGO ========== */
.logo img {
    width: 8mm;
    height: 8mm;
    object-fit: contain;
}

/* ========== INFO INTERNAL ========== */
.info-wrap {
    height: 100%;
    display: flex;
    flex-direction: column;
    width: 100%;
}

.info-block {
    border-bottom: 0.25mm solid #111;
    padding: 0.4mm 0;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    height: 33.33%;
    text-align: center;
    align-items: center;
}

.info-block:last-child {
    border-bottom: none;
    padding-bottom: 0.2mm;
}

/* ========== TEKS ========== */
.title {
    font-weight: bold;
    font-size: 6.8pt;
    width: 100%;
    text-align: center;
}

.value {
    font-weight: bold;
    font-size: 6.8pt;
    white-space: normal;
    overflow-wrap: break-word;
    width: 100%;
    text-align: center;
}

.small {
    font-size: 6pt;
    color: #444;
    line-height: 1.1;
    width: 100%;
    text-align: center;
}

/* ========== KONDISI ========== */
.cond-title {
    font-size: 5.8pt;
    line-height: 1.1;
    width: 100%;
    text-align: center;
}

.cond-value {
    font-size: 6.2pt;
    font-weight: bold;
    line-height: 1.1;
    white-space: normal;
    word-break: break-word;
    width: 100%;
    text-align: center;
}
</style>
</head>

<body>

@php
$units = collect($units ?? []);

$logoPath = $globalAppConfig?->logo
    ? storage_path('app/public/' . $globalAppConfig->logo)
    : public_path('images/up.png');

$logoBase64 = file_exists($logoPath)
    ? 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath))
    : '';

$bulan = [
    1=>'Januari','Februari','Maret','April','Mei','Juni',
    'Juli','Agustus','September','Oktober','November','Desember'
];

$cekTanggal = now()->format('d').' '.$bulan[now()->format('n')].' '.now()->format('Y');
@endphp

<table class="sheet">
@foreach ($units->chunk(3) as $row)
<tr>
@foreach ($row as $unit)
@php
$status = (bool)$unit->kerusakanAktif ? 'Rusak' : 'Baik';
@endphp

<td>
<div class="label">
<div class="label-cols">

    <!-- LOGO -->
    <div class="col-logo">
        <div class="logo-wrap">
            @if ($logoBase64)
                <div class="logo">
                    <img src="{{ $logoBase64 }}">
                </div>
            @endif
        </div>
    </div>

    <!-- INFO -->
    <div class="col-info">
        <div class="info-wrap">
            <div class="info-block">
                <div class="title">No Reg:</div>
                <div class="value">{{ $unit->kode_unit }}</div>
            </div>

            <div class="info-block">
                <div class="title">Barang:</div>
                <div class="value">{{ $unit->barang?->nama_barang }}</div>
                <div class="small">({{ $unit->ruang?->nama_ruang }})</div>
            </div>

            <div class="info-block">
                <div class="small">Cek: {{ $cekTanggal }}</div>
            </div>
        </div>
    </div>

    <!-- KONDISI -->
    <div class="col-cond">
        <div class="cond-wrap">
            <div class="cond-title">KONDISI</div>
            <div class="cond-value">{{ $status }}</div>
        </div>
    </div>

</div>
</div>
</td>

@endforeach
@for ($i = $row->count(); $i < 3; $i++)
<td></td>
@endfor
</tr>
@endforeach
</table>

</body>
</html>
