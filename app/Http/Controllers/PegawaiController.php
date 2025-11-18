<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function peminjamData(Request $request)
    {
        $search = trim($request->input('search', ''));

        $peminjamQuery = User::query()
            ->where('role', 'peminjam');

        if ($search !== '') {
            $peminjamQuery->where(function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $peminjam = $peminjamQuery
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        $tipeCounts = User::where('role', 'peminjam')
            ->select('tipe_peminjam', DB::raw('COUNT(*) as total'))
            ->groupBy('tipe_peminjam')
            ->get();

        return view('pegawai.peminjam.index', compact('peminjam', 'search', 'tipeCounts'));
    }
}
