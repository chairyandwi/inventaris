<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppConfiguration extends Model
{
    protected $fillable = [
        'nama_kampus',
        'alamat',
        'telepon',
        'email',
        'website',
        'logo',
        'profil',
        'petugas_inventaris',
        'petugas_signature',
        'apply_layout',
        'apply_pdf',
        'apply_email',
    ];
}
