<?php

namespace Database\Seeders;

use App\Models\BarangMasuk;
use App\Models\BarangUnit;
use App\Models\Barang;
use App\Models\Ruang;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Normalisasi: pastikan setiap BarangUnit memiliki barang_masuk_id
 * dengan membuat entri BarangMasuk per kombinasi barang+ruang untuk data yang belum terhubung.
 */
class NormalizeUnitsBarangMasukSeeder extends Seeder
{
    public function run(): void
    {
        $groups = BarangUnit::whereNull('barang_masuk_id')
            ->select('idbarang', 'idruang', DB::raw('COUNT(*) as jumlah'), DB::raw('MIN(created_at) as pertama'), DB::raw('MIN(keterangan) as catatan'))
            ->groupBy('idbarang', 'idruang')
            ->get();

        foreach ($groups as $group) {
            $barang = Barang::find($group->idbarang);
            $ruang = Ruang::find($group->idruang);
            if (!$barang || !$ruang) {
                continue;
            }

            $bm = BarangMasuk::create([
                'idbarang' => $barang->idbarang,
                'tgl_masuk' => $group->pertama ?: now(),
                'jumlah' => (int) $group->jumlah,
                'jenis_barang' => 'tetap',
                'status_barang' => 'baru',
                'is_pc' => str_contains(strtolower($barang->kategori->nama_kategori ?? ''), 'pc'),
                'keterangan' => $group->catatan,
            ]);

            BarangUnit::whereNull('barang_masuk_id')
                ->where('idbarang', $group->idbarang)
                ->where('idruang', $group->idruang)
                ->update(['barang_masuk_id' => $bm->idbarang_masuk]);
        }
    }
}
