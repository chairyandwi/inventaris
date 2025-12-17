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

class InventarisKPMSeeder extends Seeder
{
    /**
     * Seed inventaris untuk KPM.
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'KPM')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 6, 'keterangan' => 'Kursi Kantor'],
                    ['start' => 7, 'end' => 7, 'keterangan' => 'Kursi Kayu'],
                ],
            ],
            'PBT11' => [
                'nama' => 'Meja Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT05' => [
                'nama' => 'Lemari Kayu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Besi'],
                ],
            ],
            'ELK03' => [
                'nama' => 'Komputer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'ELK05' => [
                'nama' => 'Printer',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'HP 2060'],
                ],
            ],
            'ELK12' => [
                'nama' => 'Kipas Angin Dinding',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Turbo'],
                ],
            ],
            'PLT02' => [
                'nama' => 'Jam Dinding',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => null],
                ],
            ],
            'ELK23' => [
                'nama' => 'Pesawat Telepon',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Panasonic'],
                ],
            ],
            'ELK18' => [
                'nama' => 'Lampu Jari',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Lampu Jari'],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'A/KPM');
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
