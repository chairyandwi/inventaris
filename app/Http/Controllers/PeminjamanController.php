<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Models\Ruang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan daftar semua peminjaman.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user && $user->role === 'peminjam') {
            $peminjaman = Peminjaman::with(['barang', 'ruang'])
                ->where('iduser', $user->id)
                ->latest()
                ->paginate(10);

            return view('peminjam.peminjaman.index', compact('peminjaman'));
        }

        // gunakan paginate biar bisa pakai firstItem(), lastItem(), total()
        $peminjaman = Peminjaman::with(['barang', 'user', 'ruang'])
            ->latest()
            ->paginate(10);

        return view('pegawai.peminjaman.index', compact('peminjaman'));
    }

    /**
     * Form ajukan peminjaman (role: peminjam).
     */
    public function create()
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'peminjam') {
            abort(403);
        }

        $barang = Barang::where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();
        $ruang = Ruang::orderBy('nama_ruang')->get();

        return view('peminjam.peminjaman.create', compact('barang', 'ruang'));
    }

    /**
     * Ajukan peminjaman baru (role: peminjam).
     */
    public function store(Request $request)
    {
        $request->validate([
            'idbarang' => 'required|exists:barang,idbarang',
            'idruang' => 'required|exists:ruang,idruang',
            'jumlah' => 'required|integer|min:1',
        ]);

        $barang = Barang::findOrFail($request->idbarang);

        if ($barang->stok < $request->jumlah) {
            return back()->with('error', 'Stok barang tidak mencukupi.');
        }

        Peminjaman::create([
            'idbarang' => $request->idbarang,
            'iduser' => Auth::id(),
            'idruang' => $request->idruang,
            'jumlah' => $request->jumlah,
            'status' => 'pending',
        ]);

        $redirectRoute = Auth::user() && Auth::user()->role === 'peminjam'
            ? 'peminjam.peminjaman.index'
            : 'pegawai.peminjaman.index';

        return redirect()->route($redirectRoute)
            ->with('success', 'Permintaan peminjaman berhasil diajukan.');
    }

    /**
     * Setujui peminjaman (role: pegawai/kabag/admin).
     */
    public function approve($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);
        $barang = $peminjaman->barang;

        if ($barang->stok < $peminjaman->jumlah) {
            return back()->with('error', 'Stok barang tidak cukup untuk disetujui.');
        }

        $peminjaman->update([
            'status' => 'dipinjam',
            'tgl_pinjam' => now(),
        ]);

        $barang->decrement('stok', $peminjaman->jumlah);

        return back()->with('success', 'Peminjaman disetujui.');
    }

    /**
     * Tolak peminjaman (role: pegawai/kabag/admin).
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => 'required|string|max:255',
        ]);

        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update([
            'status' => 'ditolak',
            'alasan_penolakan' => $request->alasan_penolakan,
        ]);

        return back()->with('success', 'Peminjaman ditolak.');
    }

    /**
     * Kembalikan barang (role: pegawai).
     */
    public function return($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);
        $barang = $peminjaman->barang;

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tgl_kembali' => now(),
        ]);

        $barang->increment('stok', $peminjaman->jumlah);

        return back()->with('success', 'Barang berhasil dikembalikan.');
    }
    public function laporan(Request $request)
    {
        $query = \App\Models\Peminjaman::with(['barang', 'user']);

        // contoh filter sederhana
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->start_date) {
            $query->whereDate('tgl_pinjam', '>=', $request->start_date);
        }

        if ($request->end_date) {
            $query->whereDate('tgl_pinjam', '<=', $request->end_date);
        }

        $peminjaman = $query->paginate(10);

        return view('pegawai.peminjaman.laporan', compact('peminjaman'));
    }
    public function cetak()
    {
        // Pastikan user login dan role pegawai
        if (!auth('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $user = auth('web')->user();
        
        if (!$user || $user->role !== 'pegawai') {
            abort(403, 'Anda tidak memiliki akses.');
        }
    
        // Ambil data peminjaman beserta relasinya
        $peminjaman = Peminjaman::with(['barang', 'user', 'ruang'])
                        ->orderByDesc('idpeminjaman')
                        ->get();
    
        // Generate PDF menggunakan view cetak peminjaman
        $pdf = Pdf::loadView('pegawai.peminjaman.cetak', compact('peminjaman'))
                  ->setPaper('A4', 'portrait');
    
        // Download file PDF
        return $pdf->download('Laporan_Peminjaman.pdf');
    }

    public function show($id)
    {
        $peminjaman = Peminjaman::with(['barang', 'user', 'ruang'])->findOrFail($id);
        $user = Auth::user();

        if ($user && $user->role === 'peminjam') {
            abort_unless($peminjaman->iduser === $user->id, 403);
            return view('peminjam.peminjaman.show', compact('peminjaman'));
        }

        return redirect()->route('pegawai.peminjaman.index');
    }
}
