<?php

namespace App\Models;

use App\Models\BarangUnitKerusakan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Barang extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'idbarang';
    protected $fillable = [
        'idkategori',
        'kode_barang',
        'nama_barang',
        'stok',
        'keterangan',
        'kondisi_pinjam',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'idkategori', 'idkategori');
    }

    public function barangMasuk()
    {
        return $this->hasMany(BarangMasuk::class, 'idbarang', 'idbarang');
    }

    public function barangKeluar()
    {
        return $this->hasMany(BarangKeluar::class, 'idbarang', 'idbarang');
    }

    public function units()
    {
        return $this->hasMany(BarangUnit::class, 'idbarang', 'idbarang');
    }

    public function kerusakanUnits(): HasManyThrough
    {
        return $this->hasManyThrough(
            BarangUnitKerusakan::class,
            BarangUnit::class,
            'idbarang',           // Foreign key on barang_units
            'barang_unit_id',     // Foreign key on barang_unit_kerusakan
            'idbarang',           // Local key on barang
            'id'                  // Local key on barang_units
        );
    }

    public function rusakAktifCount(): int
    {
        return $this->kerusakanUnits()->where('status', 'rusak')->count();
    }
}
