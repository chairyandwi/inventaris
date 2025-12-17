<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangUnitKerusakan extends Model
{
    use HasFactory;

    protected $table = 'barang_unit_kerusakan';

    protected $fillable = [
        'barang_unit_id',
        'status',
        'tgl_rusak',
        'tgl_perbaikan',
        'deskripsi',
    ];

    protected $casts = [
        'tgl_rusak' => 'date',
        'tgl_perbaikan' => 'date',
    ];

    public function unit()
    {
        return $this->belongsTo(BarangUnit::class, 'barang_unit_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'rusak');
    }
}
