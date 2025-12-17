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

class InventarisLKBHSeeder extends Seeder
{
    /**
     * Seed inventaris ruang untuk LKBH.
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'LKBH')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'units' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'units' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kursi Susun'],
                    ['start' => 3, 'end' => 12, 'keterangan' => 'Kursi Susun'], // kursi rapat
                ],
            ],
            'PBT07' => [
                'nama' => 'Kursi Tamu',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Sofa isi 2'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Sofa isi 1'],
                ],
            ],
            'PBT19' => [
                'nama' => 'Bifet',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT17' => [
                'nama' => 'Rak Buku Kayu Besar',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi'],
                ],
            ],
            'ELK05' => [
                'nama' => 'Printer',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'EPSON 3210'],
                ],
            ],
            'ELK06' => [
                'nama' => 'AC',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Sharp 1PK'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Panasonic 1PK'],
                ],
            ],
            'ELK13' => [
                'nama' => 'Dispenser',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'DENPO'],
                ],
            ],
            'PBT15' => [
                'nama' => 'Meja Rapat',
                'units' => [
                    ['start' => 1, 'end' => 8, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT12' => [
                'nama' => 'Meja Kayu Kecil',
                'units' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT20' => [
                'nama' => 'Meja Praktik Pengadilan',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT21' => [
                'nama' => 'Kursi Hakim',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PLT01' => [
                'nama' => 'Papan Tulis (White Board)',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'White Board'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu',
                'units' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Lampu TL'],
                ],
            ],
            'ELK10' => [
                'nama' => 'Laptop',
                'units' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Lenovo Ideapad 3 J21D (Baru)'],
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
            $timestamp = Carbon::now();

            $unitRecords = [];
            foreach ($data['units'] as $unitSet) {
                $start = (int) ($unitSet['start'] ?? 1);
                $end = (int) ($unitSet['end'] ?? $start);
                $ket = $unitSet['keterangan'] ?? null;

                for ($i = $start; $i <= $end; $i++) {
                    $totalUnit++;
                    $kodeUnit = "C/LKBH/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);

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

            // Catat barang masuk agregat per barang
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
