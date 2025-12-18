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

class InventarisLabFisikaSeeder extends Seeder
{
    /**
     * Seed inventaris ruang untuk Lab Fisika (LAB02).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'LAB02')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT12' => [
                'nama' => 'Meja Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Meja Asisten Praktikum - Kayu'],
                    ['start' => 2, 'end' => 3, 'keterangan' => 'Meja Praktikum Kecil - Kayu'],
                ],
            ],
            'PBT28' => [
                'nama' => 'Kursi Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kursi Asisten Praktikum - Kayu'],
                    ['start' => 2, 'end' => 6, 'keterangan' => 'Kursi Praktikum Kayu Pendek'],
                    ['start' => 7, 'end' => 19, 'keterangan' => 'Kursi Praktikum Kayu Tinggi'],
                ],
            ],
            'PBT23' => [
                'nama' => 'Meja Praktikum Panjang',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT24' => [
                'nama' => 'Kursi Praktikum Plastik',
                'ranges' => [
                    ['start' => 1, 'end' => 27, 'keterangan' => 'Plastik'],
                ],
            ],
            'PBT05' => [
                'nama' => 'Lemari Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Lemari Kayu Pintu Kaca'],
                ],
            ],
            'PBT17' => [
                'nama' => 'Rak',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Rak Susun Plastik'],
                ],
            ],
            'ELK01' => [
                'nama' => 'AC',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'LG 1PK'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Panasonic 0.5 PK', 'tgl_masuk' => '2025-06-11'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'Panasonic 0.75 PK', 'tgl_masuk' => '2025-06-11'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu TL',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Lampu TL'],
                ],
            ],
            'PLT01' => [
                'nama' => 'Papan Tulis (White Board)',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'White Board'],
                ],
            ],
            'ELK13' => [
                'nama' => 'Dispenser',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Miyako'],
                ],
            ],
            'ELK30' => [
                'nama' => 'Kulkas',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'SANKEN'],
                ],
            ],
            'PLT13' => [
                'nama' => 'APAR',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
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
                    $kodeUnit = "C/LAB02/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);
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
