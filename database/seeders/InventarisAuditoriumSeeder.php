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

class InventarisAuditoriumSeeder extends Seeder
{
    /**
    * Seed inventaris untuk Auditorium (RAD).
    */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RAD')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT30' => [
                'nama' => 'Panggung',
                'ranges' => [
                    ['start' => 1, 'end' => 12, 'keterangan' => 'Papan Triplek'],
                ],
            ],
            'PLT04' => [
                'nama' => 'Tiang Bendera',
                'ranges' => [
                    ['start' => 1, 'end' => 6, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT31' => [
                'nama' => 'Mimbar',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PLT23' => [
                'nama' => 'Gong',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kuningan'],
                ],
            ],
            'PBT15' => [
                'nama' => 'Meja Panjang',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT12' => [
                'nama' => 'Meja Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 5, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT07' => [
                'nama' => 'Sofa Set',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu Busa'],
                ],
            ],
            'PBT11' => [
                'nama' => 'Meja Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Biru',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Kursi Kantor'],
                    ['start' => 5, 'end' => 11, 'keterangan' => 'Kursi Susun'],
                ],
            ],
            'PBT13' => [
                'nama' => 'Kursi Kuliah Lipat',
                'ranges' => [
                    ['start' => 1, 'end' => 100, 'keterangan' => 'Kursi Lipat'],
                ],
            ],
            'ELK01' => [
                'nama' => 'AC',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'LG 2PK'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'LG 1PK (KB)'],
                    ['start' => 4, 'end' => 4, 'keterangan' => 'LG 1PK (rusak)'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu',
                'ranges' => [
                    ['start' => 1, 'end' => 12, 'keterangan' => 'Lampu TL'],
                ],
            ],
            'PLT34' => [
                'nama' => 'Papan Tulis Kecil',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Flipchart'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Set Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Datang 2025-09-16', 'tgl_masuk' => '2025-09-16'],
                ],
            ],
            'ELK31' => [
                'nama' => 'Mixer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'ELK32' => [
                'nama' => 'Wireless',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'ELK35' => [
                'nama' => 'Speaker Aktif',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => null],
                    ['start' => 3, 'end' => 4, 'keterangan' => 'Dibeli Maret 2025', 'tgl_masuk' => '2025-03-01'],
                ],
            ],
            'ELK33' => [
                'nama' => 'Mic Wireless',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => null],
                ],
            ],
            'ELK34' => [
                'nama' => 'Mic Kabel',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'ELK25' => [
                'nama' => 'Stan Speaker',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Besi'],
                ],
            ],
            'ELK26' => [
                'nama' => 'Stan Mic',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi'],
                ],
            ],
            'ELK36' => [
                'nama' => 'Spliter',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'ELK02' => [
                'nama' => 'Proyektor',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'HITACHI ED-27X (rusak)'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'EPSON (dari B307)'],
                ],
            ],
            'PLT07' => [
                'nama' => 'Layar Screen Stan',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'A/RAD');
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
