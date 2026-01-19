<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\BarangPinjamUsage;
use App\Models\BarangUnitKerusakan;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Support\ActivityLogger;

class BarangPinjamController extends Controller
{
    public function index(Request $request)
    {
        $routePrefix = Auth::check() && Auth::user()->role === 'admin' ? 'admin' : 'pegawai';

        $pinjamBarangIds = $this->getPinjamBarangIds();
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
        $rusakCounts = $this->getRusakCounts($pinjamBarangIds);

        $latestMerkId = BarangMasuk::select('idbarang', DB::raw('MAX(idbarang_masuk) as latest_id'))
            ->where('jenis_barang', 'pinjam')
            ->whereNotNull('merk')
            ->where('merk', '!=', '')
            ->groupBy('idbarang');
        $latestMerkMap = BarangMasuk::joinSub($latestMerkId, 'latest_merk', function ($join) {
            $join->on('barang_masuk.idbarang', '=', 'latest_merk.idbarang')
                ->on('barang_masuk.idbarang_masuk', '=', 'latest_merk.latest_id');
        })
            ->pluck('barang_masuk.merk', 'barang_masuk.idbarang');

        $merkTotals = BarangMasuk::where('jenis_barang', 'pinjam')
            ->whereIn('idbarang', $pinjamBarangIds)
            ->select('idbarang', 'merk', DB::raw('SUM(jumlah) as total'))
            ->groupBy('idbarang', 'merk')
            ->get()
            ->groupBy('idbarang');
        $usageByMerk = BarangPinjamUsage::where('digunakan_sampai', '>', now())
            ->select('idbarang', 'merk', DB::raw('SUM(jumlah) as total'))
            ->groupBy('idbarang', 'merk')
            ->get()
            ->groupBy('idbarang');

        $activeUsage = BarangPinjamUsage::where('digunakan_sampai', '>', now())
            ->select('idbarang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('idbarang')
            ->pluck('total', 'idbarang');
        $activeUsageList = BarangPinjamUsage::with('creator')
            ->where('digunakan_sampai', '>', now())
            ->orderBy('digunakan_sampai')
            ->get()
            ->groupBy('idbarang');

        $query = Barang::whereIn('idbarang', $pinjamBarangIds);

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($sub) use ($term) {
                $sub->where('kode_barang', 'like', "%{$term}%")
                    ->orWhere('nama_barang', 'like', "%{$term}%");
            });
        }

        $barang = $query->orderBy('nama_barang')
            ->get()
            ->map(function ($item) use ($pinjamMasuk, $pinjamTerpakai, $rusakCounts, $latestMerkMap, $activeUsage, $activeUsageList, $merkTotals, $usageByMerk) {
                $rusak = $rusakCounts[$item->idbarang] ?? 0;
                $totalPinjam = $pinjamMasuk[$item->idbarang] ?? 0;
                $terpakai = $pinjamTerpakai[$item->idbarang] ?? 0;
                $digunakan = $activeUsage[$item->idbarang] ?? 0;
                $available = max(($totalPinjam - $terpakai - $rusak - $digunakan), 0);

                $item->available_stok = $available;
                $item->digunakan_stok = $digunakan;
                $item->merk_pinjam = $latestMerkMap[$item->idbarang] ?? '-';
                $item->usage_active = $activeUsageList[$item->idbarang] ?? collect();
                $merkRows = $merkTotals[$item->idbarang] ?? collect();
                $usageRows = $usageByMerk[$item->idbarang] ?? collect();
                $usageMap = $usageRows->keyBy(function ($row) {
                    return $row->merk ?? '-';
                });
                $item->merk_groups = $merkRows->map(function ($row) use ($usageMap) {
                    $key = $row->merk ?? '-';
                    $used = $usageMap[$key]->total ?? 0;
                    $total = (int) $row->total;
                    return [
                        'merk' => $row->merk ?? '-',
                        'total' => $total,
                        'used' => (int) $used,
                        'available' => max($total - $used, 0),
                    ];
                })->values();
                return $item;
            });

        $stats = [
            'pending' => Peminjaman::where('status', 'pending')->count(),
            'disetujui' => Peminjaman::where('status', 'disetujui')->count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
            'ditolak' => Peminjaman::where('status', 'ditolak')->count(),
        ];

        $riwayatQuery = BarangPinjamUsage::with(['barang', 'creator'])->orderByDesc('digunakan_mulai');
        if ($request->filled('riwayat_from')) {
            $riwayatQuery->whereDate('digunakan_mulai', '>=', $request->riwayat_from);
        }
        if ($request->filled('riwayat_to')) {
            $riwayatQuery->whereDate('digunakan_mulai', '<=', $request->riwayat_to);
        }
        if ($request->filled('riwayat_search')) {
            $term = $request->riwayat_search;
            $riwayatQuery->where(function ($query) use ($term) {
                $query->whereHas('barang', function ($q) use ($term) {
                    $q->where('kode_barang', 'like', "%{$term}%")
                        ->orWhere('nama_barang', 'like', "%{$term}%");
                })->orWhereHas('creator', function ($q) use ($term) {
                    $q->where('nama', 'like', "%{$term}%")
                        ->orWhere('username', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            });
        }

        $riwayat = $riwayatQuery
            ->paginate(10, ['*'], 'riwayat_page')
            ->appends($request->only(['riwayat_from', 'riwayat_to', 'riwayat_search']));

        return view('pegawai.barang_pinjam.index', compact('barang', 'stats', 'routePrefix', 'riwayat'));
    }

    public function storeUsage(Request $request, Barang $barang)
    {
        $validated = $request->validate([
            'kegiatan' => ['required', 'string', 'max:255'],
            'digunakan_sampai' => ['required', 'date', 'after:now'],
            'merk' => ['nullable', 'string', 'max:120'],
        ]);

        $pinjamBarangIds = $this->getPinjamBarangIds();
        if (!$pinjamBarangIds->contains($barang->idbarang)) {
            return back()->with('error', 'Barang ini bukan kategori pinjam.');
        }

        $merk = $validated['merk'] ?? '-';
        $merkExists = BarangMasuk::where('idbarang', $barang->idbarang)
            ->where('jenis_barang', 'pinjam')
            ->where(function ($query) use ($merk) {
                if ($merk === '-' || $merk === '') {
                    $query->whereNull('merk')->orWhere('merk', '=', '');
                } else {
                    $query->where('merk', $merk);
                }
            })
            ->exists();

        if (!$merkExists) {
            return back()->with('error', 'Merk tidak ditemukan untuk barang ini.');
        }

        $totalByMerk = BarangMasuk::where('idbarang', $barang->idbarang)
            ->where('jenis_barang', 'pinjam')
            ->where(function ($query) use ($merk) {
                if ($merk === '-' || $merk === '') {
                    $query->whereNull('merk')->orWhere('merk', '=', '');
                } else {
                    $query->where('merk', $merk);
                }
            })
            ->sum('jumlah');
        $usedByMerk = BarangPinjamUsage::where('idbarang', $barang->idbarang)
            ->where('digunakan_sampai', '>', now())
            ->where(function ($query) use ($merk) {
                if ($merk === '-' || $merk === '') {
                    $query->whereNull('merk')->orWhere('merk', '=', '');
                } else {
                    $query->where('merk', $merk);
                }
            })
            ->sum('jumlah');
        $availableByMerk = max($totalByMerk - $usedByMerk, 0);

        $jumlah = 1;
        if ($jumlah > $availableByMerk) {
            return back()->with('error', 'Stok merk ini sedang habis.');
        }

        $totalPinjam = BarangMasuk::where('jenis_barang', 'pinjam')
            ->where('idbarang', $barang->idbarang)
            ->sum('jumlah');
        $terpakai = Peminjaman::whereIn('status', ['pending', 'disetujui', 'dipinjam'])
            ->where('idbarang', $barang->idbarang)
            ->sum('jumlah');
        $rusak = $this->getRusakCounts(collect([$barang->idbarang]))[$barang->idbarang] ?? 0;
        $digunakan = BarangPinjamUsage::where('idbarang', $barang->idbarang)
            ->where('digunakan_sampai', '>', now())
            ->sum('jumlah');
        $available = max(($totalPinjam - $terpakai - $rusak - $digunakan), 0);

        if ($jumlah > $available) {
            return back()->with('error', 'Stok barang sedang habis.');
        }

        BarangPinjamUsage::create([
            'idbarang' => $barang->idbarang,
            'merk' => ($merk === '' || $merk === '-') ? null : $merk,
            'created_by' => Auth::id(),
            'jumlah' => $jumlah,
            'kegiatan' => $validated['kegiatan'],
            'digunakan_mulai' => now(),
            'digunakan_sampai' => $validated['digunakan_sampai'],
        ]);

        ActivityLogger::log(
            'Pemakaian Barang Pinjam',
            'Mencatat pemakaian barang: ' . ($barang->nama_barang ?? '-') . ' (Merk: ' . $merk . ').'
        );

        $routePrefix = Auth::check() && Auth::user()->role === 'admin' ? 'admin' : 'pegawai';

        return redirect()->route($routePrefix . '.barang-pinjam.index')
            ->with('success', 'Pemakaian barang dicatat.');
    }

    public function laporan(Request $request)
    {
        if (!auth('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth('web')->user();
        if (!$user || !in_array($user->role, ['pegawai', 'admin', 'kabag'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $query = BarangPinjamUsage::with(['barang', 'creator'])->orderByDesc('digunakan_mulai');

        if ($request->filled('start_date')) {
            $query->whereDate('digunakan_mulai', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('digunakan_mulai', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($sub) use ($term) {
                $sub->whereHas('barang', function ($q) use ($term) {
                    $q->where('kode_barang', 'like', "%{$term}%")
                        ->orWhere('nama_barang', 'like', "%{$term}%");
                })->orWhereHas('creator', function ($q) use ($term) {
                    $q->where('nama', 'like', "%{$term}%")
                        ->orWhere('username', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            });
        }

        $riwayat = $query->get();

        $pdf = Pdf::loadView('pegawai.barang_pinjam.laporan', compact('riwayat'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Pemakaian_Barang_Pinjam.pdf');
    }

    protected function getPinjamBarangIds()
    {
        $latestNonNullMasuk = BarangMasuk::select('idbarang', DB::raw('MAX(idbarang_masuk) as latest_id'))
            ->whereNotNull('jenis_barang')
            ->groupBy('idbarang');

        return BarangMasuk::joinSub($latestNonNullMasuk, 'latest_non_null', function ($join) {
            $join->on('barang_masuk.idbarang', '=', 'latest_non_null.idbarang')
                ->on('barang_masuk.idbarang_masuk', '=', 'latest_non_null.latest_id');
        })
            ->where('barang_masuk.jenis_barang', 'pinjam')
            ->pluck('barang_masuk.idbarang')
            ->unique()
            ->values();
    }

    protected function getRusakCounts($barangIds)
    {
        if ($barangIds->isEmpty()) {
            return collect();
        }

        return BarangUnitKerusakan::where('status', 'rusak')
            ->join('barang_units', 'barang_unit_kerusakan.barang_unit_id', '=', 'barang_units.id')
            ->whereIn('barang_units.idbarang', $barangIds)
            ->select('barang_units.idbarang', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('barang_units.idbarang')
            ->pluck('jumlah', 'idbarang');
    }
}
