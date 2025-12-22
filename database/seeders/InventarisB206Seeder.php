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

class InventarisB206Seeder extends Seeder
{
    /**
     * Seed inventaris untuk ruang B206.
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'B206')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT08' => ['nama' => 'Meja Dosen', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => 'Kayu']]],
            'PBT09' => ['nama' => 'Kursi Dosen', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => 'Kayu']]],
            'PBT03' => [
                'nama' => 'Kursi Kuliah',
                'ranges' => [
                    ['start' => 1, 'end' => 29, 'keterangan' => 'Kursi Kuliah Lipat'],
                    ['start' => 30, 'end' => 30, 'keterangan' => 'Kursi Kuliah Lipat (Rusak)'],
                ],
            ],
            'PLT01' => ['nama' => 'Papan Tulis (White Board)', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => 'White Board']]],
            'ELK01' => ['nama' => 'AC', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => 'DAIKIN 2PK']]],
            'ELK09' => ['nama' => 'Lampu TL', 'ranges' => [['start' => 1, 'end' => 2, 'keterangan' => 'Lampu TL']]],
            'ELK02' => ['nama' => 'Proyektor', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => 'EPSON EB-E500']]],
            'ELK08' => ['nama' => 'Terminal Listrik', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => 'Broco']]],
        ];

        $this->seedInventaris($ruang, $items, 'B/B206');
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

