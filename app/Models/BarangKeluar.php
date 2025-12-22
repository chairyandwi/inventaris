<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangKeluar extends Model
{
    protected $table = 'barang_keluar';
    protected $primaryKey = 'idbarang_keluar';

    protected $fillable = [
        'idbarang',
        'iduser',
        'tgl_keluar',
        'tgl_pengambilan_rencana',
        'tgl_diterima',
        'jumlah',
        'keterangan',
        'status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'alasan_penolakan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }
}
