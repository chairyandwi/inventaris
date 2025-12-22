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

class InventarisLabPsikologiSeeder extends Seeder
{
    /**
     * Seed inventaris untuk Lab Psikologi (LAB05).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'LAB05')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Dosen',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kursi Susun (dosen)'],
                    ['start' => 2, 'end' => 15, 'keterangan' => 'Kursi Kantor (pertemuan)'],
                    ['start' => 16, 'end' => 17, 'keterangan' => 'Kursi Susun (pertemuan)'],
                ],
            ],
            'PBT12' => [
                'nama' => 'Meja Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Meja kayu besar atas kaca'],
                    ['start' => 2, 'end' => 3, 'keterangan' => 'Meja pertemuan - Kayu'],
                ],
            ],
            'PBT13' => [
                'nama' => 'Kursi Kuliah Lipat',
                'ranges' => [
                    ['start' => 1, 'end' => 30, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT04' => [
                'nama' => 'Lemari Besi',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Lemari Besi Pintu Kaca'],
                    ['start' => 4, 'end' => 4, 'keterangan' => 'Lemari Rak Besi'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Besi'],
                ],
            ],
            'PLT01' => [
                'nama' => 'Papan Tulis',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'White Board'],
                ],
            ],
            'ELK02' => [
                'nama' => 'Proyektor',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'HITACHI ED-32X'],
                ],
            ],
            'PLT07' => [
                'nama' => 'Layar LCD',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Merk G-LITE'],
                ],
            ],
            'ELK01' => [
                'nama' => 'AC',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Panasonic 2PK'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Lampu TL'],
                    ['start' => 5, 'end' => 6, 'keterangan' => 'Lampu TL (KB)'],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'A/LAB05');
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

