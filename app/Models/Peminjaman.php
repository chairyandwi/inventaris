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
        'foto_identitas',
        'tgl_pinjam',
        'tgl_kembali_rencana',
        'tgl_kembali',
        'status',
        'alasan_penolakan',
        'kegiatan',
        'keterangan_kegiatan',
    ];

    protected $casts = [
        'tgl_pinjam' => 'datetime',
        'tgl_kembali_rencana' => 'date',
        'tgl_kembali' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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
