<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Seed barang master data grouped by kategori.
     */
    public function run(): void
    {
        $categories = [
            'PBT' => 'Perabot',
            'PLT' => 'Peralatan',
            'ELK' => 'Elektronik',
            'PKP' => 'Perlengkapan',
            'ALT' => 'Alat',
        ];

        $items = [
            'PBT' => [
                ['kode' => '01', 'nama' => 'Meja Kerja'],
                ['kode' => '02', 'nama' => 'Kursi Kerja/Rapat'],
                ['kode' => '03', 'nama' => 'Kursi Meja'],
                ['kode' => '04', 'nama' => 'Lemari Besi'],
                ['kode' => '05', 'nama' => 'Lemari Kayu'],
                ['kode' => '06', 'nama' => 'Filling Cabinet'],
                ['kode' => '07', 'nama' => 'Sofa/Kursi Tamu'],
                ['kode' => '08', 'nama' => 'Meja Dosen'],
                ['kode' => '09', 'nama' => 'Kursi Dosen'],
                ['kode' => '10', 'nama' => 'Kursi Kuliah'],
                ['kode' => '11', 'nama' => 'Meja Komputer'],
                ['kode' => '12', 'nama' => 'Meja Kayu'],
                ['kode' => '13', 'nama' => 'Kursi Lipat'],
                ['kode' => '14', 'nama' => 'Meja Tamu Sofa'],
                ['kode' => '15', 'nama' => 'Meja Rapat/Panjang'],
                ['kode' => '16', 'nama' => 'Loker Kayu'],
                ['kode' => '17', 'nama' => 'Rak'],
                ['kode' => '18', 'nama' => 'Loker Besi'],
                ['kode' => '19', 'nama' => 'Bifet'],
                ['kode' => '20', 'nama' => 'Meja Praktik Peradilan'],
                ['kode' => '21', 'nama' => 'Kursi Hakim'],
                ['kode' => '22', 'nama' => 'Brangkas'],
                ['kode' => '23', 'nama' => 'Meja Praktikum (Panjang)'],
                ['kode' => '24', 'nama' => 'Kursi Plastik'],
                ['kode' => '25', 'nama' => 'Meja Pelayanan'],
                ['kode' => '26', 'nama' => 'Meja Baca'],
                ['kode' => '27', 'nama' => 'Kursi Bulat'],
                ['kode' => '28', 'nama' => 'Kursi Kayu'],
                ['kode' => '29', 'nama' => 'Rak Koran'],
                ['kode' => '30', 'nama' => 'Panggung'],
                ['kode' => '31', 'nama' => 'Mimbar'],
                ['kode' => '32', 'nama' => 'Meja Bulat'],
            ],
            'PLT' => [
                ['kode' => '01', 'nama' => 'Papan Tulis (White Board)'],
                ['kode' => '02', 'nama' => 'Jam Dinding'],
                ['kode' => '03', 'nama' => 'Gambar Pancasila'],
                ['kode' => '04', 'nama' => 'Foto Presiden'],
                ['kode' => '05', 'nama' => 'Foto Wakil Presiden'],
                ['kode' => '06', 'nama' => 'Tiang Bendera'],
                ['kode' => '07', 'nama' => 'Layar Proyektor'],
                ['kode' => '08', 'nama' => 'Container Box'],
                ['kode' => '09', 'nama' => 'Tabung Nitrogen'],
                ['kode' => '10', 'nama' => 'Tabung Oksigen'],
                ['kode' => '11', 'nama' => 'Kotak P3K'],
                ['kode' => '13', 'nama' => 'APAR'],
                ['kode' => '14', 'nama' => 'Meja Bor'],
                ['kode' => '15', 'nama' => 'Meja Bor Duduk'],
                ['kode' => '16', 'nama' => 'Ragum'],
                ['kode' => '17', 'nama' => 'Gerinda Duduk'],
                ['kode' => '18', 'nama' => 'Meja Gambar'],
                ['kode' => '19', 'nama' => 'Tabung LPG'],
                ['kode' => '20', 'nama' => 'Tabung Acetylene'],
                ['kode' => '21', 'nama' => 'Drum Plastik'],
                ['kode' => '22', 'nama' => 'Papan Pengumuman'],
                ['kode' => '23', 'nama' => 'Gong'],
                ['kode' => '24', 'nama' => 'Flipchart'],
                ['kode' => '25', 'nama' => 'Stan Speaker'],
                ['kode' => '26', 'nama' => 'Stan Mic'],
            ],
            'ELK' => [
                ['kode' => '01', 'nama' => 'AC'],
                ['kode' => '02', 'nama' => 'Proyektor'],
                ['kode' => '03', 'nama' => 'Set Komputer'],
                ['kode' => '04', 'nama' => 'PC'],
                ['kode' => '05', 'nama' => 'Printer'],
                ['kode' => '06', 'nama' => 'Remote AC'],
                ['kode' => '07', 'nama' => 'Remote Proyektor'],
                ['kode' => '08', 'nama' => 'Terminal Listrik'],
                ['kode' => '09', 'nama' => 'Lampu TL'],
                ['kode' => '10', 'nama' => 'Monitor'],
                ['kode' => '11', 'nama' => 'HAP'],
                ['kode' => '12', 'nama' => 'Kipas Angin'],
                ['kode' => '13', 'nama' => 'Dispanser'],
                ['kode' => '14', 'nama' => 'Handphone'],
                ['kode' => '15', 'nama' => 'TV'],
                ['kode' => '16', 'nama' => 'Speaker Portabel'],
                ['kode' => '17', 'nama' => 'Finger Print'],
                ['kode' => '18', 'nama' => 'Lampu Jari'],
                ['kode' => '19', 'nama' => 'CCTV'],
                ['kode' => '20', 'nama' => 'Laptop'],
                ['kode' => '21', 'nama' => 'Handycam'],
                ['kode' => '22', 'nama' => 'Kamera'],
                ['kode' => '23', 'nama' => 'Pesawat Telepon'],
                ['kode' => '24', 'nama' => 'Lampu LED'],
                ['kode' => '25', 'nama' => 'Stabilizer'],
                ['kode' => '26', 'nama' => 'Kipas Blower'],
                ['kode' => '27', 'nama' => 'Kompresor'],
                ['kode' => '28', 'nama' => 'Mixer (Pengaduk)'],
                ['kode' => '29', 'nama' => 'Router'],
                ['kode' => '30', 'nama' => 'Kulkas'],
                ['kode' => '31', 'nama' => 'Mixer Sound'],
                ['kode' => '32', 'nama' => 'Wireless'],
                ['kode' => '33', 'nama' => 'Mic Wireless'],
                ['kode' => '34', 'nama' => 'Mic Kabel'],
                ['kode' => '35', 'nama' => 'Speaker Aktif'],
                ['kode' => '36', 'nama' => 'Spliter'],
                ['kode' => '37', 'nama' => 'Scanner'],
                ['kode' => '38', 'nama' => 'CPU'],
                ['kode' => '39', 'nama' => 'Mouse'],
                ['kode' => '40', 'nama' => 'Key Board'],
                ['kode' => '41', 'nama' => 'LED'],
                ['kode' => '42', 'nama' => 'HT'],
                ['kode' => '43', 'nama' => 'Conventer HDMI to VGA'],
            ],
            'PKP' => [
                ['kode' => '01', 'nama' => 'Penghapus'],
            ],
            'ALT' => [
                ['kode' => '01', 'nama' => 'Treadmill'],
                ['kode' => '02', 'nama' => 'Sepeda Statis'],
                ['kode' => '03', 'nama' => 'Tensimeter'],
                ['kode' => '04', 'nama' => 'Oximeter'],
                ['kode' => '05', 'nama' => 'Goniometer'],
            ],
        ];

        $kategoriMap = [];

        foreach ($categories as $code => $name) {
            $kategori = Kategori::updateOrCreate(
                ['keterangan' => $code],
                ['nama_kategori' => $name]
            );

            $kategoriMap[$code] = $kategori->idkategori;
        }

        foreach ($items as $kategoriCode => $barangList) {
            $kategoriId = $kategoriMap[$kategoriCode] ?? null;
            if (!$kategoriId) {
                continue;
            }

            foreach ($barangList as $barang) {
                $kodeBarang = $kategoriCode . str_pad($barang['kode'], 2, '0', STR_PAD_LEFT);

                Barang::updateOrCreate(
                    ['kode_barang' => $kodeBarang],
                    [
                        'idkategori' => $kategoriId,
                        'nama_barang' => $barang['nama'],
                        'keterangan' => $barang['keterangan'] ?? null,
                    ]
                );
            }
        }
    }
}
