<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama',
        'email',
        'password',
        'username',
        'nohp',
        'role',
        'tipe_peminjam',
        'prodi',
        'angkatan',
        'nim',
        'divisi',
        'foto_identitas_mahasiswa',
        'foto_identitas_pegawai',
        'rfid_uid',
    ];

    public function missingProfileFields(): array
    {
        $missing = [];

        if (!$this->nama) {
            $missing[] = 'nama lengkap';
        }
        if (!$this->username) {
            $missing[] = 'username';
        }
        if (!$this->email) {
            $missing[] = 'email';
        }
        if (!$this->nohp) {
            $missing[] = 'nomor HP';
        }
        if (!$this->tipe_peminjam || !in_array($this->tipe_peminjam, ['mahasiswa', 'pegawai'], true)) {
            $missing[] = 'tipe peminjam';
        }

        if ($this->tipe_peminjam === 'mahasiswa') {
            if (!$this->prodi) {
                $missing[] = 'program studi';
            }
            if (!$this->angkatan) {
                $missing[] = 'angkatan';
            }
            if (!$this->nim) {
                $missing[] = 'NIM';
            }
            if (!$this->foto_identitas_mahasiswa) {
                $missing[] = 'foto KTM';
            }
        }

        if ($this->tipe_peminjam === 'pegawai') {
            if (!$this->divisi) {
                $missing[] = 'divisi/bagian';
            }
            if (!$this->foto_identitas_pegawai) {
                $missing[] = 'foto ID card';
            }
        }

        return $missing;
    }

    public function isProfileComplete(): bool
    {
        return count($this->missingProfileFields()) === 0;
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
