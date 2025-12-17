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

class InventarisRKMHSeeder extends Seeder
{
    /**
     * Seed inventaris ruang untuk Kemahasiswaan (RKMH).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RKMH')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 6, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kursi Kantor'],
                    ['start' => 3, 'end' => 5, 'keterangan' => 'Kursi Susun'],
                    ['start' => 6, 'end' => 9, 'keterangan' => 'Kursi Lipat'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Baik'],
                    ['start' => 3, 'end' => 4, 'keterangan' => 'Rusak'],
                ],
            ],
            'ELK05' => [
                'nama' => 'Printer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'HP Tank 315'],
                ],
            ],
            'PLT01' => [
                'nama' => 'Papan Tulis (White Board)',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'White Board'],
                ],
            ],
            'ELK12' => [
                'nama' => 'Kipas Angin',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Maspion (Berdiri)'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Cosmos (Dinding)'],
                ],
            ],
            'PBT05' => [
                'nama' => 'Lemari Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT17' => [
                'nama' => 'Rak Kayu Besar',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi'],
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
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Samsung'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Lampu TL'],
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

            $timestamp = Carbon::now();
            $totalUnit = 0;
            $unitRecords = [];

            foreach ($data['ranges'] as $set) {
                $start = (int) ($set['start'] ?? 1);
                $end = (int) ($set['end'] ?? $start);
                $ket = $set['keterangan'] ?? null;

                for ($i = $start; $i <= $end; $i++) {
                    $totalUnit++;
                    $kodeUnit = "C/RKMH/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);

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
