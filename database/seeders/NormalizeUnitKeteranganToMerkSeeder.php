<?php

namespace Database\Seeders;

use App\Models\BarangMasuk;
use App\Models\BarangUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NormalizeUnitKeteranganToMerkSeeder extends Seeder
{
    /**
     * Pindahkan keterangan unit menjadi merk pada barang_masuk,
     * lalu kosongkan keterangan unit.
     */
    public function run(): void
    {
        $map = [];

        BarangUnit::with('barangMasuk')
            ->whereNotNull('keterangan')
            ->where('keterangan', '!=', '')
            ->chunkById(200, function ($units) use (&$map) {
                foreach ($units as $unit) {
                    $merk = trim((string) $unit->keterangan);
                    if ($merk === '') {
                        continue;
                    }

                    $bm = $unit->barangMasuk;
                    $bmKey = $bm ? $bm->idbarang_masuk : 'none';
                    $key = $bmKey . '|' . md5($merk);

                    if (!isset($map[$key])) {
                        $payload = [
                            'idbarang' => $unit->idbarang,
                            'tgl_masuk' => $bm?->tgl_masuk,
                            'jumlah' => 0,
                            'status_barang' => $bm?->status_barang ?? 'baru',
                            'is_pc' => $bm?->is_pc ?? false,
                            'keterangan' => $bm?->keterangan,
                            'merk' => $merk,
                            'ram_brand' => $bm?->ram_brand,
                            'ram_capacity_gb' => $bm?->ram_capacity_gb,
                            'storage_type' => $bm?->storage_type,
                            'storage_capacity_gb' => $bm?->storage_capacity_gb,
                            'processor' => $bm?->processor,
                            'monitor_brand' => $bm?->monitor_brand,
                            'monitor_model' => $bm?->monitor_model,
                            'monitor_size_inch' => $bm?->monitor_size_inch,
                        ];

                        $newBm = BarangMasuk::create($payload);
                        $map[$key] = $newBm->idbarang_masuk;
                    }

                    $unit->update([
                        'barang_masuk_id' => $map[$key],
                        'keterangan' => null,
                    ]);
                }
            });

        $counts = BarangUnit::select('barang_masuk_id', DB::raw('COUNT(*) as total'))
            ->whereNotNull('barang_masuk_id')
            ->groupBy('barang_masuk_id')
            ->get()
            ->keyBy('barang_masuk_id');

        foreach ($counts as $bmId => $row) {
            BarangMasuk::where('idbarang_masuk', $bmId)->update(['jumlah' => (int) $row->total]);
        }
    }
}
