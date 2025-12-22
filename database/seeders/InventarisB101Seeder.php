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

class InventarisB101Seeder extends Seeder
{
    /**
     * Seed inventaris untuk ruang B101.
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'B101')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT08' => [
                'nama' => 'Meja Dosen',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT09' => [
                'nama' => 'Kursi Dosen',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT12' => [
                'nama' => 'Meja Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Meja Panjang 250x65 cm - Kayu'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'Meja Persegi 170x107 cm - Kayu kaki besi'],
                    ['start' => 4, 'end' => 7, 'keterangan' => 'Meja Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 27, 'keterangan' => 'Kursi Kantor - CHAIRMAN'],
                ],
            ],
            'PBT13' => [
                'nama' => 'Kursi Lipat',
                'ranges' => [
                    ['start' => 1, 'end' => 10, 'keterangan' => 'Stainless'],
                ],
            ],
            'PLT01' => [
                'nama' => 'Papan Tulis (White Board)',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'White Board'],
                ],
            ],
            'ELK02' => [
                'nama' => 'Proyektor',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'EPSON EB-E500'],
                ],
            ],
            'ELK01' => [
                'nama' => 'AC',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'LG 1PK'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu TL',
                'ranges' => [
                    ['start' => 1, 'end' => 2, 'keterangan' => 'Lampu TL'],
                ],
            ],
            'ELK08' => [
                'nama' => 'Terminal Listrik',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Broco'],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'B/B101');
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

