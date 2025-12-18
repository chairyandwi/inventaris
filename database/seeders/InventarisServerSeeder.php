<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangUnit;
use App\Models\Kategori;
use App\Models\Ruang;
use App\Models\BarangMasuk;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InventarisServerSeeder extends Seeder
{
    /**
     * Seed inventaris barang untuk ruang Server (RSV1).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RSV1')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'ELK03' => [
                'nama' => 'Set Komputer',
                'specs' => [
                    'ram_brand' => 'Kingston',
                    'ram_capacity_gb' => 16,
                    'storage_type' => 'SSD',
                    'storage_capacity_gb' => 512,
                    'processor' => 'Intel Core i5-10400',
                    'monitor_brand' => 'Dell',
                    'monitor_model' => 'P2419H',
                    'monitor_size_inch' => 24.00,
                ],
                'units' => [
                    ['kode' => 'C/RSV1/ELK03/001', 'keterangan' => 'Komputer Server (B)'],
                    ['kode' => 'C/RSV1/ELK03/002', 'keterangan' => 'Komputer Operasional (B)'],
                    ['kode' => 'C/RSV1/ELK03/003', 'keterangan' => 'Komputer Operasional (B)'],
                    ['kode' => 'C/RSV1/ELK03/004', 'keterangan' => 'Komputer ke SDM'],
                    ['kode' => 'C/RSV1/ELK03/005', 'keterangan' => 'Komputer', 'tgl_masuk' => '2025-11-29'],
                ],
            ],
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'units' => [
                    ['kode' => 'C/RSV1/PBT01/001', 'keterangan' => 'Meja Kerja Kayu (B)'],
                    ['kode' => 'C/RSV1/PBT01/002', 'keterangan' => 'Meja Kerja Kayu (B)'],
                    ['kode' => 'C/RSV1/PBT01/003', 'keterangan' => 'Meja Kerja Kayu (B)'],
                    ['kode' => 'C/RSV1/PBT01/004', 'keterangan' => 'Meja Kerja Kayu (B)'],
                    ['kode' => 'C/RSV1/PBT01/005', 'keterangan' => 'Meja Kerja Kayu (B)'],
                    // Normalized agar tidak duplikat: lanjut ke 006-007 untuk meja server
                    ['kode' => 'C/RSV1/PBT01/006', 'keterangan' => 'Meja Server Kayu (B)'],
                    ['kode' => 'C/RSV1/PBT01/007', 'keterangan' => 'Meja Server Kayu (B)'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi',
                'units' => [
                    ['kode' => 'C/RSV1/PBT02/001', 'keterangan' => 'Kursi Kayu (B)'],
                    ['kode' => 'C/RSV1/PBT02/002', 'keterangan' => 'Kursi Kayu (B)'],
                    ['kode' => 'C/RSV1/PBT02/003', 'keterangan' => 'Kursi Kayu (B)'],
                    ['kode' => 'C/RSV1/PBT02/004', 'keterangan' => 'Kursi Kayu (B)'],
                    ['kode' => 'C/RSV1/PBT02/005', 'keterangan' => 'Kursi Kayu (B)'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filling Cabinet',
                'units' => [
                    ['kode' => 'C/RSV1/PBT06/001', 'keterangan' => 'Lemari Arsip - Filing Kabinet (B)'],
                ],
            ],
            'ELK14' => [
                'nama' => 'Handphone',
                'units' => [
                    ['kode' => 'C/RSV1/ELK14/001', 'keterangan' => 'iPhone 13', 'tgl_masuk' => '2025-08-06'],
                ],
            ],
        ];

        foreach ($items as $kodeBarang => $data) {
            $kategoriCode = substr($kodeBarang, 0, 3);
            $kategori = Kategori::where('keterangan', $kategoriCode)->first();
            if (!$kategori) {
                continue;
            }

            $barang = Barang::firstOrCreate(
                ['kode_barang' => $kodeBarang],
                [
                    'idkategori' => $kategori->idkategori,
                    'nama_barang' => $data['nama'],
                    'jenis_barang' => 'tetap',
                    'stok' => 0,
                ]
            );

            $unitCount = 0;
            $barangMasuk = [];
            $unitRecords = [];

            foreach ($data['units'] as $unit) {
                $nomor = $this->extractNomor($unit['kode']);
                $tglMasuk = $unit['tgl_masuk'] ?? null;
                $timestamp = $tglMasuk ? Carbon::parse($tglMasuk) : now();
                $specs = $unit['specs'] ?? ($data['specs'] ?? []);

                $key = $barang->idbarang . '|' . ($tglMasuk ?? 'null');
                if (!isset($barangMasuk[$key])) {
                    $barangMasuk[$key] = [
                        'idbarang' => $barang->idbarang,
                        'tgl_masuk' => $tglMasuk,
                        'jumlah' => 0,
                        'status_barang' => 'baru',
                        'is_pc' => Str::startsWith($kodeBarang, 'ELK03'),
                        'keterangan' => 'Seed RSV1',
                        'specs' => $specs,
                    ];
                }
                $barangMasuk[$key]['jumlah']++;

                $unitRecords[] = [
                    'bm_key' => $key,
                    'data' => [
                        'kode_unit' => $unit['kode'],
                        'idbarang' => $barang->idbarang,
                        'idruang' => $ruang->idruang,
                        'nomor_unit' => $nomor,
                        'keterangan' => $unit['keterangan'] ?? null,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ],
                ];

                $unitCount++;
            }

            $bmMap = [];
            foreach ($barangMasuk as $key => $entry) {
                $bm = BarangMasuk::updateOrCreate(
                    [
                        'idbarang' => $entry['idbarang'],
                        'tgl_masuk' => $entry['tgl_masuk'],
                        'status_barang' => $entry['status_barang'],
                    ],
                    [
                        'jumlah' => $entry['jumlah'],
                        'is_pc' => $entry['is_pc'],
                        'keterangan' => $entry['keterangan'],
                        'ram_brand' => $entry['specs']['ram_brand'] ?? null,
                        'ram_capacity_gb' => $entry['specs']['ram_capacity_gb'] ?? null,
                        'storage_type' => $entry['specs']['storage_type'] ?? null,
                        'storage_capacity_gb' => $entry['specs']['storage_capacity_gb'] ?? null,
                        'processor' => $entry['specs']['processor'] ?? null,
                        'monitor_brand' => $entry['specs']['monitor_brand'] ?? null,
                        'monitor_model' => $entry['specs']['monitor_model'] ?? null,
                        'monitor_size_inch' => $entry['specs']['monitor_size_inch'] ?? null,
                    ]
                );
                $bmMap[$key] = $bm->idbarang_masuk;
            }

            foreach ($unitRecords as $record) {
                BarangUnit::updateOrCreate(
                    ['kode_unit' => $record['data']['kode_unit']],
                    array_merge($record['data'], [
                        'barang_masuk_id' => $bmMap[$record['bm_key']] ?? null,
                    ])
                );
            }

            $barang->update(['stok' => $unitCount]);
        }
    }

    protected function extractNomor(string $kode): int
    {
        if (preg_match('/(\\d{3})$/', $kode, $matches)) {
            return (int)$matches[1];
        }

        return 1;
    }
}
