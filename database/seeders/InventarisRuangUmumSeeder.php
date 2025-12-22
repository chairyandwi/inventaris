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

class InventarisRuangUmumSeeder extends Seeder
{
    /**
     * Seed inventaris untuk Ruang Umum (RUMM).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RUMM')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Kursi Kantor'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT04' => [
                'nama' => 'Lemari Besi',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi Pintu Kaca'],
                ],
            ],
            'PBT05' => [
                'nama' => 'Lemari Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Set Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Lemot, sering blank'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Kabag Umum - rakitan (datang 2025-02-03)', 'tgl_masuk' => '2025-02-03'],
                ],
            ],
            'ELK05' => [
                'nama' => 'Printer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'HP 1020'],
                ],
            ],
            'ELK12' => [
                'nama' => 'Kipas Angin',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kipas Berdiri - Miyako'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Kipas Dinding - Miyako (KB)'],
                ],
            ],
            'ELK16' => [
                'nama' => 'Speaker Aktif',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'TOA WA-641C'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu TL',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'LED'],
                ],
            ],
            'PLT02' => [
                'nama' => 'Jam Dinding',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Quartz'],
                ],
            ],
            'ELK37' => [
                'nama' => 'Scanner',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Canon LiDE 120'],
                ],
            ],
            'ELK02' => [
                'nama' => 'Proyektor',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'HITACHI ED-32X'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'HITACHI EX301N (Gambar blur)'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'EPSON (dari B307)'],
                ],
            ],
            'PLT08' => [
                'nama' => 'Container Box',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besar Putih - Plastik'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Besar Hitam - Plastik'],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'D/RUMM');
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

