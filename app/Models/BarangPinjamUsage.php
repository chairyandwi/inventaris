<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangPinjamUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'idbarang',
        'merk',
        'created_by',
        'jumlah',
        'kegiatan',
        'digunakan_mulai',
        'digunakan_sampai',
    ];

    protected $casts = [
        'digunakan_mulai' => 'datetime',
        'digunakan_sampai' => 'datetime',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
