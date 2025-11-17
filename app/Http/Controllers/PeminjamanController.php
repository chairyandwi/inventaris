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

        $hasActiveLoan = Peminjaman::where('iduser', $user->id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('peminjam.peminjaman.index')
                ->with('error', 'Anda belum dapat mengajukan peminjaman baru sebelum permintaan sebelumnya selesai dikembalikan.');
        }

        $barang = Barang::withCount('units')->get()->filter(function ($item) {
            return $item->stok > $item->units_count;
        })->map(function ($item) {
            $item->available_stok = $item->stok - $item->units_count;
            return $item;
        })->values();
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
            'kegiatan' => 'required|in:kampus,luar',
            'keterangan_kegiatan' => 'required|string|max:255',
            'idruang' => 'required_if:kegiatan,kampus|nullable|exists:ruang,idruang',
            'jumlah' => 'required|integer|min:1',
            'tgl_pinjam_rencana' => 'required|date|after_or_equal:today',
            'tgl_kembali_rencana' => 'required|date|after_or_equal:tgl_pinjam_rencana',
            'foto_identitas' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $barang = Barang::findOrFail($request->idbarang);

        $hasActiveLoan = Peminjaman::where('iduser', Auth::id())
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('peminjam.peminjaman.index')
                ->with('error', 'Anda belum dapat mengajukan peminjaman baru sebelum pengajuan sebelumnya selesai.');
        }

        $assignedUnits = $barang->units()->count();
        $availableStok = max($barang->stok - $assignedUnits, 0);

        if ($availableStok <= 0) {
            return back()->with('error', 'Barang ini sedang tidak tersedia untuk dipinjam.');
        }

        if ($request->jumlah > $availableStok) {
            return back()->with('error', 'Stok barang tidak mencukupi.');
        }

        $fotoPath = $request->file('foto_identitas')->store('identitas', 'public');

        Peminjaman::create([
            'idbarang' => $request->idbarang,
            'iduser' => Auth::id(),
            'idruang' => $request->kegiatan === 'kampus' ? $request->idruang : null,
            'jumlah' => $request->jumlah,
            'foto_identitas' => $fotoPath,
            'tgl_pinjam_rencana' => $request->tgl_pinjam_rencana,
            'tgl_kembali_rencana' => $request->tgl_kembali_rencana,
            'kegiatan' => $request->kegiatan,
            'keterangan_kegiatan' => $request->keterangan_kegiatan,
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

        if ($peminjaman->status !== 'pending') {
            return back()->with('error', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $barang = $peminjaman->barang;

        $sudahDibooking = Peminjaman::where('idbarang', $peminjaman->idbarang)
            ->whereIn('status', ['disetujui', 'dipinjam'])
            ->where('idpeminjaman', '!=', $peminjaman->idpeminjaman)
            ->sum('jumlah');

        $tersedia = max($barang->stok - $sudahDibooking, 0);

        if ($peminjaman->jumlah > $tersedia) {
            return back()->with('error', 'Stok barang tidak mencukupi untuk disetujui pada jadwal tersebut.');
        }

        $peminjaman->update([
            'status' => 'disetujui',
            'tgl_pinjam' => null,
        ]);

        return back()->with('success', 'Peminjaman disetujui dan menunggu pengambilan sesuai jadwal.');
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
     * Tandai barang sudah diambil sesuai jadwal (role: pegawai).
     */
    public function pickup($id)
    {
        $peminjaman = Peminjaman::with('barang')->findOrFail($id);

        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Pengajuan ini belum disetujui atau sudah diproses.');
        }

        if ($peminjaman->tgl_pinjam_rencana && now()->lt($peminjaman->tgl_pinjam_rencana->startOfDay())) {
            return back()->with('error', 'Belum memasuki jadwal pengambilan barang.');
        }

        $barang = $peminjaman->barang;

        if ($barang->stok < $peminjaman->jumlah) {
            return back()->with('error', 'Stok barang habis, tidak bisa memulai peminjaman.');
        }

        $peminjaman->update([
            'status' => 'dipinjam',
            'tgl_pinjam' => now(),
        ]);

        $barang->decrement('stok', $peminjaman->jumlah);

        return back()->with('success', 'Barang sudah ditandai diambil.');
    }

    /**
     * Kembalikan barang (role: pegawai).
     */
    public function return(Request $request, $id)
    {
        $request->validate([
            'tgl_kembali_real' => 'required|date',
            'konfirmasi_pengembalian' => 'accepted',
        ]);

        $peminjaman = Peminjaman::with('barang')->findOrFail($id);
        $barang = $peminjaman->barang;

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Barang belum dalam status dipinjam.');
        }

        $peminjaman->update([
            'status' => 'dikembalikan',
            'tgl_kembali' => $request->tgl_kembali_real,
        ]);

        $barang->increment('stok', $peminjaman->jumlah);

        return back()->with('success', 'Barang berhasil dikembalikan.');
    }
    public function laporan(Request $request)
    {
        $peminjaman = $this->filteredPeminjaman($request)
            ->paginate(10)
            ->appends($request->only('status', 'start_date', 'end_date'));

        return view('pegawai.peminjaman.laporan', compact('peminjaman'));
    }
    public function cetak(Request $request)
    {
        // Pastikan user login dan role pegawai
        if (!auth('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $user = auth('web')->user();
        
        if (!$user || $user->role !== 'pegawai') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $peminjaman = $this->filteredPeminjaman($request, includeRuang: true)
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

    private function filteredPeminjaman(Request $request, bool $includeRuang = false)
    {
        $relations = ['barang', 'user'];
        if ($includeRuang) {
            $relations[] = 'ruang';
        }

        $query = Peminjaman::with($relations);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tgl_pinjam', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tgl_pinjam', '<=', $request->end_date);
        }

        return $query;
    }
}
