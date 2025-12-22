<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BarangHabisPakaiController extends Controller
{
    private function getRoutePrefix(): string
    {
        return auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
    }

    public function index(Request $request)
    {
        $query = Barang::whereHas('barangMasuk', function ($q) {
                $q->where('jenis_barang', 'habis_pakai');
            })
            ->with('kategori');
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('kode_barang', 'like', "%{$term}%")
                    ->orWhere('nama_barang', 'like', "%{$term}%")
                    ->orWhere('keterangan', 'like', "%{$term}%");
            });
        }

        $barang = $query->orderBy('nama_barang')
            ->paginate(12, ['*'], 'barang_page')
            ->appends($request->only('search'));
        $keluar = BarangKeluar::with(['barang', 'user'])
            ->orderByDesc('tgl_keluar')
            ->orderByDesc('idbarang_keluar')
            ->paginate(10, ['*'], 'keluar_page');

        $stats = [
            'totalJenis' => \App\Models\BarangMasuk::where('jenis_barang', 'habis_pakai')->distinct('idbarang')->count('idbarang'),
            'totalStok' => Barang::whereHas('barangMasuk', function ($q) {
                $q->where('jenis_barang', 'habis_pakai');
            })->sum('stok'),
            'totalKeluar' => BarangKeluar::sum('jumlah'),
        ];

        $routePrefix = $this->getRoutePrefix();

        return view('pegawai.barang_habis_pakai.index', compact('barang', 'keluar', 'stats', 'routePrefix'));
    }

    public function peminjamIndex()
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'peminjam', 403);

        $barang = Barang::whereHas('barangMasuk', function ($q) {
                $q->where('jenis_barang', 'habis_pakai');
            })
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $riwayat = BarangKeluar::with('barang')
            ->where('iduser', $user->id)
            ->orderByDesc('tgl_keluar')
            ->orderByDesc('idbarang_keluar')
            ->paginate(10);

        return view('peminjam.barang_habis_pakai.index', compact('barang', 'riwayat'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'peminjam', 403);

        $validated = $request->validate([
            'idbarang' => ['required', 'exists:barang,idbarang'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $result = DB::transaction(function () use ($validated, $user) {
            $barang = Barang::where('idbarang', $validated['idbarang'])
                ->whereHas('barangMasuk', function ($q) {
                    $q->where('jenis_barang', 'habis_pakai');
                })
                ->lockForUpdate()
                ->first();

            if (!$barang) {
                return ['error' => 'Barang tidak tersedia untuk habis pakai.'];
            }

            if ($barang->stok < $validated['jumlah']) {
                return ['error' => 'Stok barang tidak mencukupi.'];
            }

            BarangKeluar::create([
                'idbarang' => $barang->idbarang,
                'iduser' => $user->id,
                'tgl_keluar' => now()->toDateString(),
                'jumlah' => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            $barang->decrement('stok', $validated['jumlah']);

            return ['success' => true];
        });

        if (!empty($result['error'])) {
            return back()->with('error', $result['error'])->withInput();
        }

        return back()->with('success', 'Permintaan barang habis pakai berhasil dicatat.');
    }
}
