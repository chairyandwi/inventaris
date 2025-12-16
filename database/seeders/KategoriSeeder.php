<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Seed kategori with default groups.
     */
    public function run(): void
    {
        $kategoriList = [
            ['nama' => 'Perabot', 'kode' => 'PBT'],
            ['nama' => 'Peralatan', 'kode' => 'PLT'],
            ['nama' => 'Elektronik', 'kode' => 'ELK'],
            ['nama' => 'Perlengkapan', 'kode' => 'PKP'],
            ['nama' => 'Alat', 'kode' => 'ALT'],
        ];

        foreach ($kategoriList as $kategori) {
            Kategori::updateOrCreate(
                ['nama_kategori' => $kategori['nama']],
                ['keterangan' => $kategori['kode']]
            );
        }
    }
}
