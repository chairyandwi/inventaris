<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'idbarang';
    protected $fillable = [
        'idkategori',
        'kode_barang',
        'nama_barang',
        'jenis_barang',
        'stok',
        'keterangan',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'idkategori', 'idkategori');
    }

    public function units()
    {
        return $this->hasMany(BarangUnit::class, 'idbarang', 'idbarang');
    }
}
