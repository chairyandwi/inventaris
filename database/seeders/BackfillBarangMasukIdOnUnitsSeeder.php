<?php

namespace Database\Seeders;

use App\Models\BarangMasuk;
use App\Models\BarangUnit;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

/**
 * Isi barang_masuk_id pada barang_units yang belum terisi (data lama) dengan membuat
 * entri barang_masuk baru per kombinasi barang+ruang.
 */
class BackfillBarangMasukIdOnUnitsSeeder extends Seeder
{
    public function run(): void
    {
        // Group unit tanpa barang_masuk_id per barang & ruang untuk dibuatkan entry sendiri.
        $groups = BarangUnit::whereNull('barang_masuk_id')
            ->select('idbarang', 'idruang', DB::raw('COUNT(*) as jumlah'), DB::raw('MIN(created_at) as pertama'), DB::raw('MIN(keterangan) as catatan'))
            ->groupBy('idbarang', 'idruang')
            ->get();

        foreach ($groups as $group) {
            $barang = Barang::find($group->idbarang);
            if (! $barang) {
                continue;
            }

            $isPc = $barang->jenis_barang === 'pc'
                || str_contains(strtolower($barang->kategori->nama_kategori ?? ''), 'pc');

            $bm = BarangMasuk::create([
                'idbarang' => $barang->idbarang,
                'tgl_masuk' => $group->pertama ?: now(),
                'jumlah' => (int) $group->jumlah,
                'status_barang' => 'baru',
                'is_pc' => $isPc,
                'keterangan' => $group->catatan,
            ]);

            BarangUnit::whereNull('barang_masuk_id')
                ->where('idbarang', $group->idbarang)
                ->where('idruang', $group->idruang)
                ->update(['barang_masuk_id' => $bm->idbarang_masuk]);
        }
    }
}
