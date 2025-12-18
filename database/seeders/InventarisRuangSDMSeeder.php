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

class InventarisRuangSDMSeeder extends Seeder
{
    /**
     * Seed inventaris untuk Ruang SDM (RSDM).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RSDM')->first();
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
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Kursi Kantor'],
                    ['start' => 4, 'end' => 4, 'keterangan' => 'Kursi Susun'],
                    ['start' => 5, 'end' => 5, 'keterangan' => 'Kursi Lipat'],
                ],
            ],
            'PBT04' => [
                'nama' => 'Lemari Besi',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT05' => [
                'nama' => 'Lemari Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu (Sekretariat)'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT17' => [
                'nama' => 'Rak Buku',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kayu'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Set Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'SDM'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'SDM, ke Sekre'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'Sekretariat'],
                    ['start' => 4, 'end' => 4, 'keterangan' => 'Dari Server (Atika)'],
                ],
            ],
            'ELK05' => [
                'nama' => 'Printer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'EPSON L3210'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'EPSON L3110'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'Canon MG2570s (Sekretariat)'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu TL',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'LED'],
                ],
            ],
            'ELK01' => [
                'nama' => 'AC',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'TCL 1PK'],
                ],
            ],
            'PBT14' => [
                'nama' => 'Meja Tamu Sofa',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu Kaca'],
                ],
            ],
            'PBT07' => [
                'nama' => 'Kursi Tamu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Sofa satu set'],
                ],
            ],
            'ELK17' => [
                'nama' => 'Finger Print',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'ELK14' => [
                'nama' => 'Handphone',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Redmi A2 (Sekretariat)'],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'D/RSDM');
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
