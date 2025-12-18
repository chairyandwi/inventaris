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

class InventarisSekretariatSeeder extends Seeder
{
    /**
     * Seed inventaris untuk Sekretariat (RKST).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RKST')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kursi Susun'],
                ],
            ],
            'PBT07' => [
                'nama' => 'Kursi Tunggu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Sofa Panjang'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT04' => [
                'nama' => 'Lemari Besi Pintu Kaca',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Besi'],
                ],
            ],
            'ELK18' => [
                'nama' => 'Lampu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Lampu TL'],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'A/RKST');
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
