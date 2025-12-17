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

class InventarisRuangKuliahMHSeeder extends Seeder
{
    /**
     * Seed inventaris untuk Ruang Kuliah MH (KMH1).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'KMH1')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'PBT08' => ['nama' => 'Meja Dosen', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => null]]],
            'PBT09' => ['nama' => 'Kursi Dosen', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => null]]],
            'PBT03' => ['nama' => 'Kursi Kuliah', 'ranges' => [['start' => 1, 'end' => 40, 'keterangan' => 'Kursi Kuliah Oregon']]],
            'PLT01' => ['nama' => 'Papan Tulis (White Board)', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => 'Papan Tulis Beroda']]],
            'ELK01' => ['nama' => 'AC', 'ranges' => [['start' => 1, 'end' => 2, 'keterangan' => 'Panasonic 1PK']]],
            'ELK03' => ['nama' => 'Set Komputer', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => null]]],
            'ELK16' => ['nama' => 'Speaker Aktif', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => null]]],
            'ELK22' => ['nama' => 'Kamera', 'ranges' => [['start' => 1, 'end' => 1, 'keterangan' => null]]],
            'ELK02' => [
                'nama' => 'Proyektor',
                'ranges' => [
                    ['start' => 1, 'end' => 1, 'keterangan' => 'EPSON EB-E500', 'prefix' => 'B/B204'],
                ],
            ],
        ];

        $this->seedInventaris($ruang, $items, 'B/KMH1');
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
