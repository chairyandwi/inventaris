<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PegawaiController extends Controller
{
    public function index()
    {
        // hitung jumlah user per role
        $roleCount = DB::table('users')
            ->select('role', DB::raw('count(id) as total'))
            ->groupBy('role')
            ->get();

        $labels = $roleCount->pluck('role');
        $data = $roleCount->pluck('total');

        return view('pegawai.index', compact('labels', 'data'));
    }
}
