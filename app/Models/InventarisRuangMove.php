<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarisRuangMove extends Model
{
    protected $table = 'inventaris_ruang_moves';

    protected $fillable = [
        'barang_unit_id',
        'idbarang',
        'idruang_asal',
        'idruang_tujuan',
        'moved_by',
        'moved_at',
        'catatan',
    ];

    protected $casts = [
        'moved_at' => 'datetime',
    ];

    public function barangUnit()
    {
        return $this->belongsTo(BarangUnit::class, 'barang_unit_id');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }

    public function ruangAsal()
    {
        return $this->belongsTo(Ruang::class, 'idruang_asal', 'idruang');
    }

    public function ruangTujuan()
    {
        return $this->belongsTo(Ruang::class, 'idruang_tujuan', 'idruang');
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'moved_by');
    }
}
