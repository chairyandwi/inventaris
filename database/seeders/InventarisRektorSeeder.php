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

class InventarisRektorSeeder extends Seeder
{
    /**
     * Seed inventaris untuk ruang Rektor (RREK).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RREK')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja / Kursi Rapat',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kursi Rektor'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Kursi Susun'],
                    ['start' => 3, 'end' => 10, 'keterangan' => 'Kursi Susun (Rapat)'],
                ],
            ],
            'PBT14' => [
                'nama' => 'Meja Tamu',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT07' => [
                'nama' => 'Kursi Tamu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Sofa Besar'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Sofa Kecil'],
                ],
            ],
            'PBT15' => [
                'nama' => 'Meja Rapat Bundar',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT05' => [
                'nama' => 'Lemari Kayu Pintu Kaca',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PLT01' => [
                'nama' => 'Papan Tulis',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'White Board'],
                ],
            ],
            'PLT06' => [
                'nama' => 'Tiang Bendera',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'ELK01' => [
                'nama' => 'AC',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'LG 1PK'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Lampu TL'],
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
        ];

        $this->seedInventaris($ruang, $items, 'A/RREK');
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
