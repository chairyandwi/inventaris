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

class InventarisLabMesinSeeder extends Seeder
{
    /**
     * Seed inventaris ruang untuk Lab Mesin (LAB06).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'LAB06')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT12' => [
                'nama' => 'Meja Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Meja Asisten Praktikum - Kayu'],
                    ['start' => 3, 'end' => 13, 'keterangan' => 'Meja Praktikum - Kayu'],
                ],
            ],
            'PBT13' => [
                'nama' => 'Kursi Lipat',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kursi Asisten Praktikum - Kursi Lipat'],
                    ['start' => 3, 'end' => 12, 'keterangan' => 'Kursi Kuliah Lipat'],
                ],
            ],
            'PBT23' => [
                'nama' => 'Meja Praktikum Panjang',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT04' => [
                'nama' => 'Lemari Besi',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT05' => [
                'nama' => 'Lemari Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Kayu'],
                ],
            ],
            'PLT14' => [
                'nama' => 'Meja Bor',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kayu'],
                ],
            ],
            'PLT15' => [
                'nama' => 'Meja Bor Duduk',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => null],
                ],
            ],
            'PLT16' => [
                'nama' => 'Ragum',
                'ranges' => [
                    ['start' => 1, 'end' => 6, 'keterangan' => 'Besi'],
                ],
            ],
            'PLT17' => [
                'nama' => 'Gerinda Duduk',
                'ranges' => [
                    ['start' => 6, 'end' => 6, 'keterangan' => null], // sesuai kode yang diberikan
                ],
            ],
            'PBT01' => [
                'nama' => 'Meja Dosen',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Dosen',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kursi Lipat'],
                ],
            ],
            'PLT01' => [
                'nama' => 'Papan Tulis (White Board)',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'White Board'],
                ],
            ],
            'PLT18' => [
                'nama' => 'Meja Gambar',
                'ranges' => [
                    ['start' => 1, 'end' => 10, 'keterangan' => 'Kayu'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu TL',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Lampu TL'],
                ],
            ],
            'ELK24' => [
                'nama' => 'Lampu LED',
                'ranges' => [
                    ['start' => 4, 'end' => 4, 'keterangan' => 'Lampu LED'],
                ],
            ],
            'ELK18' => [
                'nama' => 'Lampu Jari',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Lampu Jari'],
                ],
            ],
            'ELK26' => [
                'nama' => 'Kipas Blower', // kode master
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kipas Angin Atas'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Set Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                    ['start' => 2, 'end' => 2, 'keterangan' => null, 'tgl_masuk' => '2025-08-06'],
                ],
            ],
            'ELK05' => [
                'nama' => 'Printer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'EPSON L220'],
                ],
            ],
            'ELK13' => [
                'nama' => 'Dispenser',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Cosmos'],
                ],
            ],
            'ELK27' => [
                'nama' => 'Kompresor',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => '1/2 PK'],
                ],
            ],
            'PLT19' => [
                'nama' => 'Tabung LPG',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => '5,5 KG'],
                ],
            ],
            'PLT20' => [
                'nama' => 'Tabung Acetylene',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => '15 KG'],
                ],
            ],
            'PLT21' => [
                'nama' => 'Drum Plastik',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Plastik'],
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
                    $kodeUnit = "D/LAB06/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);
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

            BarangMasuk::updateOrCreate(
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
                    $record
                );
            }

            $barang->update(['stok' => $barang->units()->count()]);
        }
    }
}
