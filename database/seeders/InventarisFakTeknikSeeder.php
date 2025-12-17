<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangUnit;
use App\Models\BarangUnitKerusakan;
use App\Models\Kategori;
use App\Models\Ruang;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InventarisFakTeknikSeeder extends Seeder
{
    /**
     * Seed inventaris untuk Fakultas Teknik (RFT).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RFT')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 31, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 34, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT14' => [
                'nama' => 'Meja Tamu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Meja Kaca'],
                ],
            ],
            'PBT07' => [
                'nama' => 'Kursi Tamu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Sofa Besar'],
                    ['start' => 2, 'end' => 3, 'keterangan' => 'Sofa Kecil'],
                ],
            ],
            'PBT15' => [
                'nama' => 'Meja Besar',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi atas kaca'],
                ],
            ],
            'PBT05' => [
                'nama' => 'Lemari Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Besar pintu kaca'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'Kecil pintu kaca'],
                ],
            ],
            'PBT04' => [
                'nama' => 'Lemari Besi',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Pintu besi'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Pintu kaca'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 9, 'keterangan' => 'Besi'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Set Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => null],
                    ['start' => 4, 'end' => 4, 'keterangan' => 'Datang 2025-03-22, monitor Samsung 2025-04-11', 'tgl_masuk' => '2025-03-22'],
                ],
            ],
            'ELK05' => [
                'nama' => 'Printer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'HP MFP M176n'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'EPSON L220'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'HP P1102 (rusak)'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu',
                'ranges' => [
                    ['start' => 1, 'end' => 12, 'keterangan' => 'Lampu TL'],
                ],
            ],
            'ELK12' => [
                'nama' => 'Kipas Angin',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Kipas Angin Atas - National'],
                    ['start' => 5, 'end' => 6, 'keterangan' => 'Kipas Berdiri - National'],
                ],
            ],
            'PLT02' => [
                'nama' => 'Jam Dinding',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'QSTAR'],
                ],
            ],
            'ELK13' => [
                'nama' => 'Dispenser',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Miyako'],
                ],
            ],
            'ELK15' => [
                'nama' => 'TV',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Polytron (rusak)'],
                ],
            ],
            'PLT03' => [
                'nama' => 'Gambar Pancasila',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'PLT04' => [
                'nama' => 'Foto Presiden',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'PLT05' => [
                'nama' => 'Foto Wakil Presiden',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'ELK16' => [
                'nama' => 'LCD Projektor',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'EPSON (Teknik Perminyakan)'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'EPSON (Teknik Mesin)'],
                ],
            ],
            'ELK17' => [
                'nama' => 'AC 1PK',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Panasonic (rusak)'],
                    ['start' => 3, 'end' => 4, 'keterangan' => 'Panasonic, dipasang 2025-01-15', 'tgl_masuk' => '2025-01-15'],
                    ['start' => 5, 'end' => 6, 'keterangan' => 'Panasonic, dipasang 2025-02-03', 'tgl_masuk' => '2025-02-03'],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'A/RFT');
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
            $rusakRecords = [];

            foreach ($data['ranges'] as $range) {
                $start = (int) ($range['start'] ?? 1);
                $end = (int) ($range['end'] ?? $start);
                $ket = $range['keterangan'] ?? null;
                $tglMasuk = $range['tgl_masuk'] ?? null;
                $timestamp = $tglMasuk ? Carbon::parse($tglMasuk) : now();
                $ketRusak = $ket && stripos($ket, 'rusak') !== false;

                for ($i = $start; $i <= $end; $i++) {
                    $totalUnit++;
                    $kodeUnit = "{$kodePrefix}/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);
                    $record = [
                        'kode_unit' => $kodeUnit,
                        'idbarang' => $barang->idbarang,
                        'idruang' => $ruang->idruang,
                        'nomor_unit' => $i,
                        'keterangan' => $ket,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ];
                    $unitRecords[] = $record;
                    if ($ketRusak) {
                        $rusakRecords[] = [
                            'kode_unit' => $kodeUnit,
                            'deskripsi' => $ket,
                            'tgl_rusak' => $timestamp->toDateString(),
                        ];
                    }
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
                $unit = BarangUnit::updateOrCreate(
                    ['kode_unit' => $record['kode_unit']],
                    $record
                );
                // log kerusakan jika ada
                foreach ($rusakRecords as $rusak) {
                    if ($rusak['kode_unit'] === $unit->kode_unit) {
                        BarangUnitKerusakan::updateOrCreate(
                            [
                                'barang_unit_id' => $unit->id,
                                'status' => 'rusak',
                            ],
                            [
                                'tgl_rusak' => $rusak['tgl_rusak'],
                                'deskripsi' => $rusak['deskripsi'],
                            ]
                        );
                    }
                }
            }

            $barang->update(['stok' => $barang->units()->count()]);
        }
    }
}
