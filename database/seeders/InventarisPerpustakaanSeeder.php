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

class InventarisPerpustakaanSeeder extends Seeder
{
    /**
     * Seed inventaris ruang untuk Perpustakaan (PERP).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'PERP')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT25' => [
                'nama' => 'Meja Pelayanan',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kursi Susun'],
                    ['start' => 3, 'end' => 4, 'keterangan' => 'Kursi Kantor'],
                ],
            ],
            'PBT12' => [
                'nama' => 'Meja Kayu Kecil',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT26' => [
                'nama' => 'Meja Baca Perpustakaan',
                'ranges' => [
                    ['start' => 1, 'end' => 6, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT13' => [
                'nama' => 'Kursi Lipat',
                'ranges' => [
                    ['start' => 1, 'end' => 18, 'keterangan' => 'Kursi Lipat'],
                ],
            ],
            'PBT28' => [
                'nama' => 'Kursi Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 12, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT27' => [
                'nama' => 'Kursi Bulat',
                'ranges' => [
                    ['start' => 1, 'end' => 5, 'keterangan' => 'Kayu kaki besi'],
                ],
            ],
            'PBT24' => [
                'nama' => 'Kursi Plastik',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Plastik'],
                ],
            ],
            'PBT17' => [
                'nama' => 'Rak',
                'ranges' => [
                    ['start' => 1, 'end' => 15, 'keterangan' => 'Rak Buku Kayu Besar'],
                    ['start' => 16, 'end' => 17, 'keterangan' => 'Rak Buku Kayu Kecil'],
                    ['start' => 18, 'end' => 31, 'keterangan' => 'Rak Buku Besi'],
                    ['start' => 32, 'end' => 32, 'keterangan' => 'Rak Tas Besar'],
                    ['start' => 33, 'end' => 33, 'keterangan' => 'Rak Tas Kecil'],
                    ['start' => 34, 'end' => 34, 'keterangan' => 'Rak Form Plastik - Lion Star'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi'],
                ],
            ],
            'PLT22' => [
                'nama' => 'Papan Pengumuman',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Papan Pengumuman Besar - Kayu (Kurang Baik)'],
                ],
            ],
            'PLT01' => [
                'nama' => 'Papan Tulis (White Board)',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Papan Pengumuman Kecil - White Board'],
                ],
            ],
            'PBT29' => [
                'nama' => 'Rak Koran',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu (Kurang Baik)'],
                ],
            ],
            'PBT05' => [
                'nama' => 'Lemari Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 7, 'keterangan' => 'Lemari Buku - Kayu'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Set Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'ELK05' => [
                'nama' => 'Printer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'HP 1020'],
                ],
            ],
            'ELK13' => [
                'nama' => 'Dispenser',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Miyako'],
                ],
            ],
            'ELK23' => [
                'nama' => 'Pesawat Telepon',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Panasonic (Kurang Baik)'],
                ],
            ],
            'PLT02' => [
                'nama' => 'Jam Dinding',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Quartz'],
                ],
            ],
            'ELK12' => [
                'nama' => 'Kipas Angin',
                'ranges' => [
                    ['start' => 1, 'end' => 6, 'keterangan' => 'Kipas Angin Atas'],
                    ['start' => 7, 'end' => 7, 'keterangan' => 'Kipas Angin Berdiri - Votre'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu TL',
                'ranges' => [
                    ['start' => 1, 'end' => 6, 'keterangan' => 'Lampu YL'],
                ],
            ],
            'ELK18' => [
                'nama' => 'Lampu Jari',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Lampu Jari (Baik)'],
                    ['start' => 4, 'end' => 4, 'keterangan' => 'Lampu Jari (Rusak)'],
                ],
            ],
            'ELK24' => [
                'nama' => 'Lampu LED',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Lampu LED (Baik)'],
                    ['start' => 3, 'end' => 4, 'keterangan' => 'Lampu LED (Rusak)'],
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
                    $kodeUnit = "D/PERP/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);
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
