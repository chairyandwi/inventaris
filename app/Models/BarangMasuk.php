<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    protected $primaryKey = 'idbarang_masuk';

    protected $fillable = [
        'idbarang',
        'tgl_masuk',
        'jumlah',
        'status_barang',
        'is_pc',
        'keterangan',
        'ram_brand',
        'ram_capacity_gb',
        'storage_type',
        'storage_capacity_gb',
        'processor',
        'monitor_brand',
        'monitor_model',
        'monitor_size_inch',
    ];

    // Relasi ke tabel Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }
}
