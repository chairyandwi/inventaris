<?php

namespace Database\Seeders;

use App\Models\BarangMasuk;
use App\Models\BarangUnit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

    /**
     * Seeding perbaikan: hubungkan barang_masuk dengan unit berdasarkan kode ruang
     * yang tertulis pada keterangan (mis. "Seed LAB01") dan prefix kode_unit.
     */
    class AssignBarangMasukToUnitsByKodeSeeder extends Seeder
    {
        public function run(): void
        {
            $barangMasukList = BarangMasuk::all();

            foreach ($barangMasukList as $bm) {
                // Jika sudah ada unit yang terhubung, lanjut.
                $linkedCount = BarangUnit::where('barang_masuk_id', $bm->idbarang_masuk)->count();
                if ($linkedCount >= $bm->jumlah) {
                continue;
            }

            // Coba temukan kode ruang dari keterangan (contoh: "Seed LAB01").
            $kodeRuang = $this->extractKodeRuang($bm->keterangan);
            $needed = max(0, $bm->jumlah - $linkedCount);
            if ($needed <= 0) {
                continue;
            }

            $unitQuery = BarangUnit::where('idbarang', $bm->idbarang)
                ->whereNull('barang_masuk_id');

            if ($kodeRuang) {
                $unitQuery->where('kode_unit', 'like', '%' . $kodeRuang . '%');
            }

            $units = $unitQuery->orderBy('id')->take($needed)->get();
            foreach ($units as $unit) {
                $unit->update(['barang_masuk_id' => $bm->idbarang_masuk]);
            }
        }
    }

    protected function extractKodeRuang(?string $text): ?string
    {
        if (!$text) {
            return null;
        }

        // Cari token seperti LAB01, B202, C202. Prioritas yang mengandung angka.
        preg_match_all('/([A-Z]{2,5}\\d{1,4}|[A-Z]{3,5})/i', $text, $matches);
        if (!empty($matches[1])) {
            $tokens = array_map(fn($t) => Str::upper($t), $matches[1]);
            foreach ($tokens as $t) {
                if (preg_match('/\\d/', $t)) {
                    return $t;
                }
            }
            return $tokens[0];
        }

        return null;
    }
}
