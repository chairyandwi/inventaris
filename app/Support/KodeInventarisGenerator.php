<?php

namespace App\Support;

use App\Models\Barang;
use App\Models\Ruang;
use Illuminate\Support\Str;

class KodeInventarisGenerator
{
    public static function make(Barang $barang, Ruang $ruang, int $nomor): string
    {
        $gedungName = trim($ruang->nama_gedung ?? 'GEDUNG');
        if (preg_match('/^gedung\s+(.*)$/i', $gedungName, $matches)) {
            $gedungName = $matches[1];
        }
        $gedung = Str::upper(Str::slug($gedungName, ''));
        $ruangKode = Str::upper(Str::slug($ruang->nama_ruang ?? 'RUANG', ''));
        $barangKode = Str::upper(Str::slug($barang->kode_barang ?? 'KODE', ''));
        $nomorFormatted = str_pad($nomor, 3, '0', STR_PAD_LEFT);

        return "{$gedung}/{$ruangKode}/{$barangKode}/{$nomorFormatted}";
    }
}
