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
        'jumlah',
        'keterangan',
        'tgl_pengembalian',
    ];

    // Relasi ke tabel Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }
}
