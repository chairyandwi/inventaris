<?php

namespace Database\Seeders;

use App\Models\BarangUnit;
use App\Models\BarangUnitKerusakan;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BarangUnitKerusakanSeeder extends Seeder
{
    /**
     * Tandai unit dengan keterangan yang mengandung kata "rusak" sebagai status rusak.
     */
    public function run(): void
    {
        $now = Carbon::now()->toDateString();

        $unitsRusak = BarangUnit::whereNotNull('keterangan')
            ->whereRaw("LOWER(keterangan) LIKE '%rusak%'")
            ->get();

        foreach ($unitsRusak as $unit) {
            $deskripsi = $unit->keterangan;
            BarangUnitKerusakan::updateOrCreate(
                [
                    'barang_unit_id' => $unit->id,
                    'status' => 'rusak',
                ],
                [
                    'tgl_rusak' => ($unit->created_at?->toDateString()) ?? $now,
                    'deskripsi' => $deskripsi,
                ]
            );
            // kosongkan keterangan pada unit agar penandaan rusak berada di log kerusakan
            $unit->keterangan = null;
            $unit->save();
        }
    }
}
