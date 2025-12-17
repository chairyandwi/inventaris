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

    public function kerusakan()
    {
        return $this->hasMany(BarangUnitKerusakan::class, 'barang_unit_id');
    }

    public function kerusakanAktif()
    {
        return $this->hasOne(BarangUnitKerusakan::class, 'barang_unit_id')->where('status', 'rusak');
    }

    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'idruang', 'idruang');
    }
}
