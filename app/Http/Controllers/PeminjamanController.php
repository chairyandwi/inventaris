<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use App\Http\Requests\PeminjamanRequest;
use App\Models\Ruang;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\BarangUnitKerusakan;
use Illuminate\Support\Facades\DB;

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

        $rolePrefix = ($user && $user->role === 'admin') ? 'admin' : 'pegawai';
        $homeRoute = $rolePrefix . '.index';
        $baseRoute = $rolePrefix . '.peminjaman';
        $laporanRoute = $baseRoute . '.laporan';

        // gunakan paginate biar bisa pakai firstItem(), lastItem(), total()
        $peminjaman = Peminjaman::with(['barang', 'user', 'ruang'])
            ->latest()
            ->paginate(10);

        return view('pegawai.peminjaman.index', compact('peminjaman', 'homeRoute', 'baseRoute', 'laporanRoute'));
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

        if (!$user->isProfileComplete()) {
            return redirect()->route('peminjam.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu sebelum mengajukan peminjaman.');
        }

        $hasActiveLoan = Peminjaman::where('iduser', $user->id)
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('peminjam.peminjaman.index')
                ->with('error', 'Anda belum dapat mengajukan peminjaman baru sebelum permintaan sebelumnya selesai dikembalikan.');
        }

        $rusakCounts = $this->getRusakCounts();

        $latestNonNullMasuk = BarangMasuk::select('idbarang', DB::raw('MAX(idbarang_masuk) as latest_id'))
            ->whereNotNull('jenis_barang')
            ->groupBy('idbarang');

        $pinjamBarangIds = BarangMasuk::joinSub($latestNonNullMasuk, 'latest_non_null', function ($join) {
            $join->on('barang_masuk.idbarang', '=', 'latest_non_null.idbarang')
                ->on('barang_masuk.idbarang_masuk', '=', 'latest_non_null.latest_id');
        })
            ->where('barang_masuk.jenis_barang', 'pinjam')
            ->pluck('barang_masuk.idbarang')
            ->unique()
            ->values();

        $pinjamMasuk = BarangMasuk::where('jenis_barang', 'pinjam')
            ->whereIn('idbarang', $pinjamBarangIds)
            ->select('idbarang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('idbarang')
            ->pluck('total', 'idbarang');
        $pinjamTerpakai = Peminjaman::whereIn('status', ['pending', 'disetujui', 'dipinjam'])
            ->whereIn('idbarang', $pinjamBarangIds)
            ->select('idbarang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('idbarang')
            ->pluck('total', 'idbarang');

        $barang = Barang::whereIn('idbarang', $pinjamBarangIds)
            ->get()
            ->filter(function ($item) use ($rusakCounts, $pinjamMasuk, $pinjamTerpakai) {
                $rusak = $rusakCounts[$item->idbarang] ?? 0;
                $totalPinjam = $pinjamMasuk[$item->idbarang] ?? 0;
                $terpakai = $pinjamTerpakai[$item->idbarang] ?? 0;
                return ($totalPinjam - $terpakai - $rusak) > 0;
            })
            ->map(function ($item) use ($rusakCounts, $pinjamMasuk, $pinjamTerpakai) {
                $rusak = $rusakCounts[$item->idbarang] ?? 0;
                $totalPinjam = $pinjamMasuk[$item->idbarang] ?? 0;
                $terpakai = $pinjamTerpakai[$item->idbarang] ?? 0;
                $item->available_stok = max(($totalPinjam - $terpakai - $rusak), 0);
                return $item;
            })->values();
        $ruang = Ruang::orderBy('nama_ruang')->get();

        return view('peminjam.peminjaman.create', compact('barang', 'ruang'));
    }

    /**
     * Ajukan peminjaman baru (role: peminjam).
     */
    public function store(\App\Http\Requests\PeminjamanRequest $request)
    {
        $user = $request->user();
        if ($user && $user->role === 'peminjam' && !$user->isProfileComplete()) {
            return redirect()->route('peminjam.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu sebelum mengajukan peminjaman.');
        }

        $validated = $request->validated();
        $barang = Barang::findOrFail($validated['idbarang']);
        $jenisBarang = $barang->barangMasuk()
            ->whereNotNull('jenis_barang')
            ->orderByDesc('idbarang_masuk')
            ->value('jenis_barang');
        if ($jenisBarang !== 'pinjam') {
            return back()->with('error', 'Barang ini tidak tersedia untuk peminjaman.');
        }

        $hasActiveLoan = Peminjaman::where('iduser', Auth::id())
            ->whereIn('status', ['pending', 'dipinjam'])
            ->exists();

        if ($hasActiveLoan) {
            return redirect()->route('peminjam.peminjaman.index')
                ->with('error', 'Anda belum dapat mengajukan peminjaman baru sebelum pengajuan sebelumnya selesai.');
        }

        $totalPinjam = BarangMasuk::where('idbarang', $barang->idbarang)
            ->where('jenis_barang', 'pinjam')
            ->sum('jumlah');
        $terpakai = Peminjaman::where('idbarang', $barang->idbarang)
            ->whereIn('status', ['pending', 'disetujui', 'dipinjam'])
            ->sum('jumlah');
        $rusak = $this->getRusakCounts([$barang->idbarang])[$barang->idbarang] ?? 0;
        $availableStok = max(($totalPinjam - $terpakai - $rusak), 0);

        if ($availableStok <= 0) {
            return back()->with('error', 'Barang ini sedang tidak tersedia untuk dipinjam.');
        }

        if ($validated['jumlah'] > $availableStok) {
            return back()->with('error', 'Stok barang tidak mencukupi.');
        }

        $fotoPath = $user->tipe_peminjam === 'mahasiswa'
            ? $user->foto_identitas_mahasiswa
            : $user->foto_identitas_pegawai;

        if (!$fotoPath) {
            return redirect()->route('peminjam.profile.edit')
                ->with('error', 'Lengkapi foto identitas di profil sebelum mengajukan peminjaman.');
        }

        Peminjaman::create([
            'idbarang' => $validated['idbarang'],
            'iduser' => Auth::id(),
            'idruang' => $validated['kegiatan'] === 'kampus' ? $validated['idruang'] : null,
            'jumlah' => $validated['jumlah'],
            'foto_identitas' => $fotoPath,
            'tgl_pinjam_rencana' => $validated['tgl_pinjam_rencana'],
            'tgl_kembali_rencana' => $validated['tgl_kembali_rencana'],
            'kegiatan' => $validated['kegiatan'],
            'keterangan_kegiatan' => $validated['keterangan_kegiatan'],
            'status' => 'pending',
        ]);

        $redirectRoute = 'peminjam.peminjaman.index';
        if (Auth::user() && Auth::user()->role !== 'peminjam') {
            $redirectPrefix = Auth::user()->role === 'admin' ? 'admin' : 'pegawai';
            $redirectRoute = $redirectPrefix . '.peminjaman.index';
        }

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

        $totalPinjam = BarangMasuk::where('idbarang', $barang->idbarang)
            ->where('jenis_barang', 'pinjam')
            ->sum('jumlah');
        $rusak = $this->getRusakCounts([$barang->idbarang])[$barang->idbarang] ?? 0;

        $tersedia = max(($totalPinjam - $rusak) - $sudahDibooking, 0);

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

    /**
     * Ambil jumlah unit rusak aktif per barang.
     *
     * @param  array<int>|null  $barangIds
     * @return \Illuminate\Support\Collection keyed by idbarang
     */
    protected function getRusakCounts(array $barangIds = null)
    {
        $query = BarangUnitKerusakan::where('status', 'rusak')
            ->join('barang_units', 'barang_unit_kerusakan.barang_unit_id', '=', 'barang_units.id');

        if ($barangIds) {
            $query->whereIn('barang_units.idbarang', $barangIds);
        }

        return $query
            ->select('barang_units.idbarang', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('barang_units.idbarang')
            ->pluck('jumlah', 'idbarang');
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
        
        if (!$user || !in_array($user->role, ['pegawai', 'admin'])) {
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

        return view('pegawai.peminjaman.show', compact('peminjaman'));
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
