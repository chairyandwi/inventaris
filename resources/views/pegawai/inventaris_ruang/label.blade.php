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

.sheet td {
    width: 33.33%;
}

/* ========== LABEL ========== */
.label {
    width: 6cm;
    height: 2.5cm;
    border: 0.35mm solid #111;
}

/* ========== STRUKTUR LABEL ========== */
.label-table {
    width: 100%;
    height: 100%;
    border-collapse: collapse;
    table-layout: fixed;
}

.label-table td {
    vertical-align: middle;
    text-align: center;
    border-right: 0.25mm solid #111;
}

.label-table td:last-child {
    border-right: none;
}

/* ========== KOLOM (FINAL) ========== */
.col-logo {
    width: 1.1cm;
}

.col-info {
    width: 4.1cm;
    padding: 0.8mm 1.2mm;
}

.col-cond {
    width: 0.8cm;
    font-weight: bold;
}

/* ========== LOGO ========== */
.logo img {
    width: 9mm;
    height: 9mm;
    object-fit: contain;
}

/* ========== INFO INTERNAL ========== */
.info-block {
    border-bottom: 0.25mm solid #111;
    padding: 0.6mm 0;
}

.info-block:last-child {
    border-bottom: none;
}

/* ========== TEKS ========== */
.title {
    font-weight: bold;
    font-size: 6.8pt;
}

.value {
    font-weight: bold;
    font-size: 6.8pt;
    white-space: nowrap;
}

.small {
    font-size: 6pt;
    color: #444;
}

/* ========== KONDISI ========== */
.cond-title {
    font-size: 6pt;
}

.cond-value {
    font-size: 6.6pt;
    font-weight: bold;
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
<table class="label-table">
<tr>

    <!-- LOGO -->
    <td class="col-logo">
        @if ($logoBase64)
            <div class="logo">
                <img src="{{ $logoBase64 }}">
            </div>
        @endif
    </td>

    <!-- INFO -->
    <td class="col-info">

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

    </td>

    <!-- KONDISI -->
    <td class="col-cond">
        <div class="cond-title">KONDISI</div>
        <div class="cond-value">{{ $status }}</div>
    </td>

</tr>
</table>
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
