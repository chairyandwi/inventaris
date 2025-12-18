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

class InventarisC202Seeder extends Seeder
{
    /**
     * Seed inventaris ruang untuk C202.
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'C202')->first();
        if (!$ruang) {
            return;
        }

        // gunakan tanggal masuk eksplisit agar tidak berbagi entry barang_masuk dengan ruang lain
        $defaultTgl = '2025-12-17';
        $items = [
            'PBT10' => ['nama' => 'Kursi Kuliah', 'jumlah' => 69, 'keterangan' => 'Kursi Kuliah Lipat', 'tgl_masuk' => $defaultTgl],
            'PBT08' => ['nama' => 'Meja', 'jumlah' => 2, 'keterangan' => 'Kayu', 'tgl_masuk' => $defaultTgl],
            'PLT01' => ['nama' => 'Papan Tulis (White Board)', 'jumlah' => 1, 'keterangan' => 'White Board', 'tgl_masuk' => $defaultTgl],
            'ELK01' => ['nama' => 'AC', 'jumlah' => 1, 'keterangan' => 'Panasonic 2PK', 'tgl_masuk' => $defaultTgl],
            'ELK02' => ['nama' => 'Proyektor', 'jumlah' => 1, 'keterangan' => 'Epson EB-E500', 'tgl_masuk' => $defaultTgl],
            'ELK09' => ['nama' => 'Lampu', 'jumlah' => 6, 'keterangan' => 'Lampu TL (4 baik, 2 rusak)', 'tgl_masuk' => $defaultTgl],
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

            $jumlah = $data['jumlah'] ?? 0;
            $keterangan = $data['keterangan'] ?? null;
            $tglMasuk = $data['tgl_masuk'] ?? null;
            $timestamp = $tglMasuk ? Carbon::parse($tglMasuk) : now();

            // Catat barang masuk agar riwayat kedatangan tercatat
            $bm = BarangMasuk::updateOrCreate(
                [
                    'idbarang' => $barang->idbarang,
                    'tgl_masuk' => $tglMasuk,
                    'status_barang' => 'baru',
                ],
                [
                    'jumlah' => $jumlah,
                    'is_pc' => Str::startsWith($kodeBarang, 'ELK03'),
                    'keterangan' => 'Seed C202',
                ]
            );

            for ($i = 1; $i <= $jumlah; $i++) {
                $kodeUnit = "C/C202/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);

                BarangUnit::updateOrCreate(
                    ['kode_unit' => $kodeUnit],
                    [
                        'idbarang' => $barang->idbarang,
                        'idruang' => $ruang->idruang,
                        'barang_masuk_id' => $bm->idbarang_masuk,
                        'nomor_unit' => $i,
                        'keterangan' => $keterangan,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                    ]
                );
            }

            $barang->update(['stok' => $barang->units()->count()]);
        }
    }
}
