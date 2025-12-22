<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangUnit;
use App\Models\Kategori;
use App\Models\Ruang;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InventarisBPKSeeder extends Seeder
{
    /**
    * Seed inventaris untuk BPK / Front Office (RFO).
    */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RFO')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Meja Kerja Besar - Kayu'],
                    ['start' => 2, 'end' => 8, 'keterangan' => 'Meja Kerja Kecil - Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 11, 'keterangan' => 'Kursi Kantor'],
                ],
            ],
            'PBT07' => [
                'nama' => 'Kursi Tamu',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Kursi Susun'],
                ],
            ],
            'PLT01' => [
                'nama' => 'Papan Tulis',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'White Board'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Set Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 7, 'keterangan' => null],
                    ['start' => 8, 'end' => 8, 'keterangan' => 'Kurang baik'],
                ],
            ],
            'ELK04' => [
                'nama' => 'Printer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'EPSON L220'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'HP 315'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'Canon MP287'],
                ],
            ],
            'ELK20' => [
                'nama' => 'Laptop',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Lenovo'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Laptop PMB - Lenovo V14 NKID13', 'tgl_masuk' => '2025-08-07'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'Laptop Marcom - Lenovo IP3GAID N512', 'tgl_masuk' => '2025-10-10'],
                ],
            ],
            'ELK21' => [
                'nama' => 'Handycam',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Sony'],
                ],
            ],
            'ELK22' => [
                'nama' => 'Camera',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Canon'],
                ],
            ],
            'ELK23' => [
                'nama' => 'Pesawat Telepon',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Panasonic'],
                ],
            ],
            'ELK01' => [
                'nama' => 'AC',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'TCL 1PK'],
                ],
            ],
            'ELK13' => [
                'nama' => 'Dispenser',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Miyako'],
                ],
            ],
            'ELK14' => [
                'nama' => 'Handphone',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Samsung A03'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'REDMI A2 (FO)'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'REDMI A2 (FO)'],
                    ['start' => 4, 'end' => 4, 'keterangan' => 'REDMI 13X (KS) - 2025-06-13', 'tgl_masuk' => '2025-06-13'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu TL',
                'ranges' => [
                    ['start' => 2, 'end' => 6, 'keterangan' => 'Lampu TL'],
                ],
            ],
            'ELK24' => [
                'nama' => 'Lampu Atas PMB',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'LED'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'LED (rusak)'],
                ],
            ],
            'ELK25' => [
                'nama' => 'Stabilizer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kasugawa (rusak)'],
                ],
            ],
            'PBT04' => [
                'nama' => 'Lemari Besi',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT17' => [
                'nama' => 'Rak',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Rak File Besar'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Rak Kayu'],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'A/RFO');
    }

    protected function seedInventaris(Ruang $ruang, array $items, string $kodePrefix): void
    {
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
                    'stok' => 0,
                ]
            );

            $totalUnit = 0;
            $unitRecords = [];

            foreach ($data['ranges'] as $range) {
                $start = (int) ($range['start'] ?? 1);
                $end = (int) ($range['end'] ?? $start);
                $ket = $range['keterangan'] ?? null;
                $tglMasuk = $range['tgl_masuk'] ?? null;
                $timestamp = $tglMasuk ? Carbon::parse($tglMasuk) : now();

                for ($i = $start; $i <= $end; $i++) {
                    $totalUnit++;
                    $kodeUnit = "{$kodePrefix}/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);
                    $unitRecords[] = [
                        'kode_unit' => $kodeUnit,
                        'idbarang' => $barang->idbarang,
                        'idruang' => $ruang->idruang,
                        'nomor_unit' => $i,
                        'keterangan' => $ket,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                }
            }

            $bm = BarangMasuk::updateOrCreate(
                [
                    'idbarang' => $barang->idbarang,
                    'tgl_masuk' => null,
                    'status_barang' => 'baru',
                ],
                [
                    'jumlah' => $totalUnit,
                    'is_pc' => Str::startsWith($kodeBarang, 'ELK03'),
                    'keterangan' => null,
                ]
            );

            foreach ($unitRecords as $record) {
                BarangUnit::updateOrCreate(
                    ['kode_unit' => $record['kode_unit']],
                    array_merge($record, [
                        'barang_masuk_id' => $bm->idbarang_masuk,
                    ])
                );
            }

            $barang->update(['stok' => $barang->units()->count()]);
        }
    }
}

