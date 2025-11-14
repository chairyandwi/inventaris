<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    // Nama tabel (opsional, kalau sudah sesuai konvensi bisa di-skip)
    protected $table = 'peminjaman';

    // Primary key
    protected $primaryKey = 'idpeminjaman';

    // Field yang bisa diisi
    protected $fillable = [
        'idbarang',
        'iduser',
        'idruang',
        'jumlah',
        'tgl_pinjam',
        'tgl_kembali',
        'status',
        'alasan_penolakan',
    ];

    // Relasi ke Barang
    public function barang()
    {
        return $this->belongsTo(Barang::class, 'idbarang', 'idbarang');
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'iduser', 'id');
    }

    // Relasi ke Ruang
    public function ruang()
    {
        return $this->belongsTo(Ruang::class, 'idruang', 'idruang');
    }
}
