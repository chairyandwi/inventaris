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

class InventarisRuangKaprodiSeeder extends Seeder
{
    /**
     * Seed inventaris untuk Ruang Kaprodi (RKPD).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RKPD')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 10, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja/Rapat',
                'ranges' => [
                    ['start' => 1, 'end' => 12, 'keterangan' => 'Kursi Kantor'],
                    ['start' => 13, 'end' => 15, 'keterangan' => 'Kursi Susun'],
                    ['start' => 16, 'end' => 24, 'keterangan' => 'Kursi Susun (Rapat)'],
                ],
            ],
            'PBT14' => [
                'nama' => 'Meja Tamu Sofa',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu Alas Kaca'],
                ],
            ],
            'PBT07' => [
                'nama' => 'Kursi Tamu',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'Sofa'],
                ],
            ],
            'PBT15' => [
                'nama' => 'Meja Rapat/Panjang',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT04' => [
                'nama' => 'Lemari Besi',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Lemari Besi Pintu Kaca'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Besi'],
                ],
            ],
            'ELK01' => [
                'nama' => 'AC',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'LG 2PK (Rusak)'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'LG 1,5PK (Rusak)'],
                    ['start' => 3, 'end' => 3, 'keterangan' => 'Panasonic 1PK (dipasang 2025-02-25)', 'tgl_masuk' => '2025-02-25'],
                    ['start' => 4, 'end' => 4, 'keterangan' => 'Panasonic 1PK (dipasang 2026-02-25)', 'tgl_masuk' => '2026-02-25'],
                    ['start' => 5, 'end' => 5, 'keterangan' => 'Panasonic 0,5PK (dipasang 2027-02-25)', 'tgl_masuk' => '2027-02-25'],
                ],
            ],
            'ELK12' => [
                'nama' => 'Kipas Angin',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kipas Angin Atas - National'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu',
                'ranges' => [
                    ['start' => 1, 'end' => 6, 'keterangan' => 'LED'],
                ],
            ],
            'ELK13' => [
                'nama' => 'Dispenser',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Miyako'],
                ],
            ],
        ];

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
                    $kodeUnit = "D/RKPD/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);
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
