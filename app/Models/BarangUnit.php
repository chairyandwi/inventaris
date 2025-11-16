<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'idbarang',
        'idruang',
        'nomor_unit',
        'kode_unit',
        'keterangan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'idruang', 'idruang');
    }
}
