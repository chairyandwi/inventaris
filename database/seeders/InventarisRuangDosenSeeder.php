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

class InventarisRuangDosenSeeder extends Seeder
{
    /**
     * Seed inventaris untuk Ruang Dosen (RDSN).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'RDSN')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT01' => [
                'nama' => 'Meja Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 15, 'keterangan' => 'Kayu'],
                ],
            ],
            'PBT02' => [
                'nama' => 'Kursi Kerja',
                'ranges' => [
                    ['start' => 1, 'end' => 30, 'keterangan' => 'Kursi Kantor'],
                ],
            ],
            'PBT07' => [
                'nama' => 'Kursi Tamu',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Sofa Panjang'],
                    ['start' => 2, 'end' => 4, 'keterangan' => 'Sofa Pendek'],
                ],
            ],
            'PBT06' => [
                'nama' => 'Filing Cabinet',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT18' => [
                'nama' => 'Loker Besi',
                'ranges' => [
                    ['start' => 1, 'end' => 6, 'keterangan' => 'Besi'],
                ],
            ],
            'PBT17' => [
                'nama' => 'Rak',
                'ranges' => [
                    ['start' => 1, 'end' => 3, 'keterangan' => 'Rak Kayu'],
                ],
            ],
            'ELK01' => [
                'nama' => 'AC',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Daikin 2PK'],
                    ['start' => 2, 'end' => 2, 'keterangan' => 'Panasonic 1 PK (datang 2025-06-11)', 'tgl_masuk' => '2025-06-11'],
                ],
            ],
            'ELK09' => [
                'nama' => 'Lampu TL',
                'ranges' => [
                    ['start' => 1, 'end' => 4, 'keterangan' => 'LED'],
                ],
            ],
            'ELK13' => [
                'nama' => 'Dispenser',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Miyako'],
                ],
            ],
            'ELK12' => [
                'nama' => 'Kipas Angin',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'Kipas Berdiri - Vento'],
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
                    $kodeUnit = "D/RDSN/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);
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
