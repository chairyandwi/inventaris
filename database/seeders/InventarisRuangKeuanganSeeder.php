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

class InventarisRuangKeuanganSeeder extends Seeder
{
    /**
     * Seed inventaris untuk Ruang Keuangan (RKEU).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RKEU')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 5, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 5, 'keterangan' => 'Kursi Kantor'],
                    ['start' => 6, 'end' => 7, 'keterangan' => 'Kursi Susun'],
                ],
            ],
            'PBT12' => [
                'nama' => 'Meja Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT05' => [
                'nama' => 'Lemari Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Lemari Kayu Besar'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Lemari Kayu Kecil'],
                ],
            ],
            'PBT04' => [
                'nama' => 'Lemari Besi',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Lemari Besi Pintu Kaca'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'Lemari Besi Pintu Besi'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT22' => [
                'nama' => 'Brangkas',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi Baja'],
                ],
            ],
            'PLT08' => [
                'nama' => 'Container Box',
                'ranges' => [
                    ['start' => 1, 'end' => 8, 'keterangan' => 'Plastik - Besar'],
                    ['start' => 9, 'end' => 11, 'keterangan' => 'Plastik - Kecil'],
                ],
            ],
            'PBT17' => [
                'nama' => 'Rak',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Rak Besar Odner - Kayu'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Rak Buku - Kayu'],
                    ['start' => 3, 'end' => 4, 'keterangan' => 'Rak Kertas Form Kecil - Kayu'],
                ],
            ],
            'PLT01' => [
                'nama' => 'Papan Tulis (White Board)',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Papan Tulis Kecil'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Set Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => null],
                ],
            ],
            'ELK05' => [
                'nama' => 'Printer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'EPSON L360'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'EPSON L3210'],
                ],
            ],
            'ELK01' => [
                'nama' => 'AC',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Panasonic 1PK'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Panasonic 1PK'],
                ],
            ],
            'ELK12' => [
                'nama' => 'Kipas Angin',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kipas Angin Dinding - Miyako'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu TL',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Lampu TL'],
                ],
            ],
            'ELK19' => [
                'nama' => 'CCTV',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'ELK14' => [
                'nama' => 'Handphone',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Samsung'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Andromax'],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'B/RKEU');
    }

    protected function seedInventaris(Ruang $ruang, array $items, string $defaultPrefix): void
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
                $prefix = $range['prefix'] ?? $defaultPrefix;
                $tglMasuk = $range['tgl_masuk'] ?? null;
                $timestamp = $tglMasuk ? Carbon::parse($tglMasuk) : now();

                for ($i = $start; $i <= $end; $i++) {
                    $totalUnit++;
                    $kodeUnit = "{$prefix}/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);
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

