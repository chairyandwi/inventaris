<?php

namespace App\Support;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $aksi, ?string $deskripsi = null): void
    {
        if (!Auth::check()) {
            return;
        }

        LogAktivitas::create([
            'iduser' => Auth::id(),
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
        ]);
    }
}
