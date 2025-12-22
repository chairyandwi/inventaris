<?php

namespace Database\Seeders;

use App\Models\BarangMasuk;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CleanupOrphanBarangMasukSeeder extends Seeder
{
    /**
     * Hapus barang_masuk barang tetap yang tidak punya unit (ruang kosong),
     * agar data tidak duplikat/ambigu.
     */
    public function run(): void
    {
        $orphanIds = DB::table('barang_masuk as bm')
            ->leftJoin('barang_units as bu', 'bu.barang_masuk_id', '=', 'bm.idbarang_masuk')
            ->whereNull('bu.barang_masuk_id')
            ->where(function ($q) {
                $q->where('bm.jenis_barang', 'tetap');
            })
            ->pluck('bm.idbarang_masuk')
            ->all();

        if (empty($orphanIds)) {
            return;
        }

        BarangMasuk::whereIn('idbarang_masuk', $orphanIds)->delete();
    }
}
