<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangUnit;
use App\Models\Kategori;
use App\Models\Ruang;
use App\Models\BarangMasuk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InventarisLabKomputerSeeder extends Seeder
{
    /**
     * Seed inventaris ruang untuk Lab Komputer (LAB01).
     */
    public function run(): void
    {
        $ruang = Ruang::where('kode_ruang', 'LAB01')->first();
        if (!$ruang) {
            return;
        }

        $items = [
            'ELK03' => [
                'nama' => 'Komputer',
                'jumlah' => 20,
                'specs' => [
                    'ram_brand' => 'Kingston',
                    'ram_capacity_gb' => 8,
                    'storage_type' => 'SSD',
                    'storage_capacity_gb' => 256,
                    'processor' => 'Intel Core i5',
                    'monitor_brand' => 'Dell',
                    'monitor_model' => 'P2419H',
                    'monitor_size_inch' => 24.00,
                    'tgl_masuk' => '2025-01-15',
                ],
            ],
            'PBT11' => ['nama' => 'Meja Komputer', 'jumlah' => 30, 'keterangan' => 'Partikel Board'],
            'PBT10' => ['nama' => 'Kursi Kuliah', 'jumlah' => 30, 'keterangan' => 'Kursi Susun'],
            'PBT08' => ['nama' => 'Meja Dosen', 'jumlah' => 1, 'keterangan' => 'Kayu'],
            'PBT09' => ['nama' => 'Kursi Dosen', 'jumlah' => 1, 'keterangan' => 'Kursi Susun'],
            'ELK11' => ['nama' => 'HAP', 'jumlah' => 2],
            'ELK02' => ['nama' => 'Proyektor', 'jumlah' => 1, 'keterangan' => 'EPSON EB-E500'],
            'ELK01' => ['nama' => 'AC', 'jumlah' => 1, 'keterangan' => 'Panasonic 2PK'],
            'PLT01' => ['nama' => 'Papan Tulis (White Board)', 'jumlah' => 1, 'keterangan' => 'White Board'],
            'ELK09' => ['nama' => 'Lampu', 'jumlah' => 6, 'keterangan' => 'Lampu TL'],
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
                    'stok' => 0,
                ]
            );

            $jumlah = $data['jumlah'] ?? 0;
            $keterangan = $data['keterangan'] ?? null;
            $specs = $data['specs'] ?? [];
            // berikan default tanggal masuk khusus lab komputer agar tidak bentrok dengan ruang lain (mis. C202)
            $tglMasuk = $data['tgl_masuk'] ?? ($specs['tgl_masuk'] ?? '2025-01-15');
            $timestamp = $tglMasuk ? Carbon::parse($tglMasuk) : now();
            $bm = BarangMasuk::updateOrCreate(
                [
                    'idbarang' => $barang->idbarang,
                    'tgl_masuk' => $tglMasuk,
                    'status_barang' => 'baru',
                ],
                [
                    'jumlah' => $jumlah,
                    'is_pc' => Str::startsWith($kodeBarang, 'ELK03'),
                    'ram_brand' => $specs['ram_brand'] ?? null,
                    'ram_capacity_gb' => $specs['ram_capacity_gb'] ?? null,
                    'storage_type' => $specs['storage_type'] ?? null,
                    'storage_capacity_gb' => $specs['storage_capacity_gb'] ?? null,
                    'processor' => $specs['processor'] ?? null,
                    'monitor_brand' => $specs['monitor_brand'] ?? null,
                    'monitor_model' => $specs['monitor_model'] ?? null,
                    'monitor_size_inch' => $specs['monitor_size_inch'] ?? null,
                    'keterangan' => 'Seed LAB01',
                ]
            );

            for ($i = 1; $i <= $jumlah; $i++) {
                $kodeUnit = "C/LAB01/{$kodeBarang}/" . str_pad($i, 3, '0', STR_PAD_LEFT);

                BarangUnit::updateOrCreate(
                    ['kode_unit' => $kodeUnit],
                    [
                        'idbarang' => $barang->idbarang,
                        'idruang' => $ruang->idruang,
                        'nomor_unit' => $i,
                        'keterangan' => $keterangan,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp,
                        'barang_masuk_id' => $bm->idbarang_masuk,
                    ]
                );
            }

            $barang->update(['stok' => $barang->units()->count()]);
        }
    }
}

