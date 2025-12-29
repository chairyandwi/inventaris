<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangUnit;
use App\Models\BarangUnitKerusakan;
use App\Models\InventarisRuangMove;
use App\Models\Ruang;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Support\KodeInventarisGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventarisRuangController extends Controller
{
    private function getRoutePrefix(): string
    {
        return auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
    }

    public function index(Request $request)
    {
        $gedungFilter = trim((string) $request->get('gedung', ''));
        $lantaiFilter = trim((string) $request->get('lantai', ''));
        $statusFilter = $request->get('status', '');

        $query = BarangUnit::with([
                'barang',
                'ruang',
                'barangMasuk',
                'kerusakanAktif',
            ])
            ->orderBy('kode_unit');

        if ($statusFilter === 'rusak') {
            $query->whereHas('kerusakanAktif');
        } elseif ($statusFilter === 'baik') {
            $query->whereDoesntHave('kerusakanAktif')
                ->where(function ($q) {
                    $q->whereNull('keterangan')
                      ->orWhereRaw("LOWER(keterangan) NOT LIKE '%kurang%'");
                });
        } elseif ($statusFilter === 'kurang_baik') {
            $query->whereDoesntHave('kerusakanAktif')
                ->where(function ($q) {
                    $q->whereRaw("LOWER(keterangan) LIKE '%kurang%'")
                      ->orWhereRaw("LOWER(keterangan) LIKE '%kb%'");
                });
        }

        if ($request->filled('idruang')) {
            $query->where('idruang', $request->idruang);
        }

        if ($request->filled('idbarang')) {
            $query->where('idbarang', $request->idbarang);
        }

        $statusFilter = $request->get('status', '');
        if ($statusFilter === 'rusak') {
            $query->whereHas('kerusakanAktif');
        } elseif ($statusFilter === 'baik') {
            $query->whereDoesntHave('kerusakanAktif')
                ->where(function ($q) {
                    $q->whereNull('keterangan')
                      ->orWhereRaw("LOWER(keterangan) NOT LIKE '%kurang%'");
                });
        } elseif ($statusFilter === 'kurang_baik') {
            $query->whereDoesntHave('kerusakanAktif')
                ->where(function ($q) {
                    $q->whereRaw("LOWER(keterangan) LIKE '%kurang%'")
                      ->orWhereRaw("LOWER(keterangan) LIKE '%kb%'");
                });
        }

        if ($request->filled('gedung')) {
            $query->whereHas('ruang', function ($q) use ($request) {
                $q->where('nama_gedung', trim($request->gedung));
            });
        }

        if ($request->filled('lantai')) {
            $query->whereHas('ruang', function ($q) use ($request) {
                $q->where('nama_lantai', trim($request->lantai));
            });
        }

        if ($gedungFilter !== '') {
            $query->whereHas('ruang', function ($q) use ($request) {
                $q->where('nama_gedung', trim($request->gedung));
            });
        }

        if ($lantaiFilter !== '') {
            $query->whereHas('ruang', function ($q) use ($request) {
                $q->where('nama_lantai', trim($request->lantai));
            });
        }

        $perPage = in_array((int)$request->get('per_page', 15), [10, 15, 25, 50, 100]) ? (int)$request->get('per_page', 15) : 15;

        $units = $query->paginate($perPage)->appends($request->only('idruang', 'idbarang', 'gedung', 'lantai', 'per_page', 'status'));
        $ruangAll = Ruang::orderBy('nama_ruang')->get();
        $ruang = Ruang::when($gedungFilter !== '', function ($q) use ($gedungFilter) {
                $q->where('nama_gedung', $gedungFilter);
            })
            ->when($lantaiFilter !== '', function ($q) use ($lantaiFilter) {
                $q->where('nama_lantai', $lantaiFilter);
            })
            ->orderBy('nama_ruang')
            ->get();
        $barang = Barang::orderBy('nama_barang')->get();
        $gedungList = Ruang::select('nama_gedung')->distinct()->orderBy('nama_gedung')->pluck('nama_gedung')->filter()->values();
        $lantaiList = Ruang::select('nama_lantai')->distinct()->orderBy('nama_lantai')->pluck('nama_lantai')->filter()->values();

        $summary = [
            'totalUnits' => BarangUnit::count(),
            'ruangTerisi' => BarangUnit::distinct('idruang')->count('idruang'),
            'barangTetap' => \App\Models\BarangMasuk::where('jenis_barang', 'tetap')->distinct('idbarang')->count('idbarang'),
        ];

        return view('pegawai.inventaris_ruang.index', compact('units', 'ruang', 'ruangAll', 'barang', 'summary', 'gedungList', 'lantaiList'));
    }

    public function create()
    {
        $barang = Barang::whereHas('barangMasuk', function ($q) {
                $q->where('jenis_barang', 'tetap');
            })
            ->orderBy('nama_barang')
            ->get();
        $ruang = Ruang::orderBy('nama_ruang')->get();

        return view('pegawai.inventaris_ruang.create', compact('barang', 'ruang'));
    }

    public function laporan(Request $request)
    {
        if (!auth('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth('web')->user();
        if (!$user || !in_array($user->role, ['pegawai', 'admin'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $gedungFilter = trim((string) $request->get('gedung', ''));
        $lantaiFilter = trim((string) $request->get('lantai', ''));
        $statusFilter = $request->get('status', '');

        $query = BarangUnit::with([
                'barang:idbarang,nama_barang,idkategori',
                'barang.kategori:idkategori,nama_kategori',
                'ruang:idruang,nama_ruang,nama_gedung',
                'kerusakanAktif',
            ])
            ->select('id', 'idbarang', 'idruang', 'kode_unit', 'keterangan', 'nomor_unit')
            ->orderBy('idruang')
            ->orderBy('idbarang')
            ->orderBy('nomor_unit');

        $selectedRuang = null;
        if ($request->filled('idruang')) {
            $query->where('idruang', $request->idruang);
            $selectedRuang = Ruang::find($request->idruang);
        }

        if ($request->filled('idbarang')) {
            $query->where('idbarang', $request->idbarang);
        }

        if ($gedungFilter !== '') {
            $query->whereHas('ruang', function ($q) use ($gedungFilter) {
                $q->where('nama_gedung', $gedungFilter);
            });
        }

        if ($lantaiFilter !== '') {
            $query->whereHas('ruang', function ($q) use ($lantaiFilter) {
                $q->where('nama_lantai', $lantaiFilter);
            });
        }

        if ($statusFilter === 'rusak') {
            $query->whereHas('kerusakanAktif');
        } elseif ($statusFilter === 'baik') {
            $query->whereDoesntHave('kerusakanAktif')
                ->where(function ($q) {
                    $q->whereNull('keterangan')
                      ->orWhereRaw("LOWER(keterangan) NOT LIKE '%kurang%'");
                });
        } elseif ($statusFilter === 'kurang_baik') {
            $query->whereDoesntHave('kerusakanAktif')
                ->where(function ($q) {
                    $q->whereRaw("LOWER(keterangan) LIKE '%kurang%'")
                      ->orWhereRaw("LOWER(keterangan) LIKE '%kb%'");
                });
        }

        $hasFilters = $request->filled('idruang')
            || $request->filled('idbarang')
            || $request->filled('gedung')
            || $request->filled('lantai')
            || $request->filled('status');
        $shouldZip = !$request->filled('idruang') && ($gedungFilter !== '' || $lantaiFilter !== '');

        if (!$hasFilters || $shouldZip) {
            $ruangList = Ruang::orderBy('nama_gedung')
                ->orderBy('nama_ruang')
                ->when($gedungFilter !== '', function ($q) use ($gedungFilter) {
                    $q->where('nama_gedung', $gedungFilter);
                })
                ->when($lantaiFilter !== '', function ($q) use ($lantaiFilter) {
                    $q->where('nama_lantai', $lantaiFilter);
                })
                ->get(['idruang', 'nama_ruang', 'nama_gedung', 'nama_lantai']);
            $dateStamp = now()->format('Ymd_His');
            $zipName = "Laporan_Inventaris_Ruang_{$dateStamp}.zip";
            $tempDir = storage_path('app/tmp/inventaris_zip_' . $dateStamp);
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0777, true);
            }
            $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipName;

            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
                return redirect()->route($this->getRoutePrefix() . '.inventaris-ruang.index')
                    ->with('error', 'Gagal membuat file ZIP laporan.');
            }

            foreach ($ruangList as $ruang) {
                $units = (clone $query)
                    ->where('idruang', $ruang->idruang)
                    ->get();
                if ($units->isEmpty()) {
                    continue;
                }
                $ruangName = $ruang->nama_ruang ?: 'Ruang';
                $gedungName = $ruang->nama_gedung ?: 'Gedung';
                $safeGedung = preg_replace('/[^A-Za-z0-9_-]+/', '_', $gedungName);
                $safeRuang = preg_replace('/[^A-Za-z0-9_-]+/', '_', $ruangName);
                $pdfFile = "{$safeGedung}_{$safeRuang}.pdf";
                $pdfPath = $tempDir . DIRECTORY_SEPARATOR . $pdfFile;

                $pdf = Pdf::loadView('pegawai.inventaris_ruang.laporan', [
                        'units' => $units,
                        'selectedRuang' => $ruang,
                        'gedungFilter' => $gedungFilter,
                        'lantaiFilter' => $lantaiFilter,
                    ])
                    ->setPaper('A4', 'portrait');
                file_put_contents($pdfPath, $pdf->output());
                $zip->addFile($pdfPath, $pdfFile);
            }

            $zip->close();

            return response()->download($zipPath)->deleteFileAfterSend(true);
        }

        $units = $query->get();
        $pdf = Pdf::loadView('pegawai.inventaris_ruang.laporan', [
                'units' => $units,
                'selectedRuang' => $selectedRuang,
                'gedungFilter' => $gedungFilter,
                'lantaiFilter' => $lantaiFilter,
            ])
            ->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Inventaris_Ruang.pdf');
    }

    /**
     * Tandai unit sebagai rusak (admin/pegawai).
     */
    public function markRusak(Request $request, BarangUnit $inventaris_ruang)
    {
        $request->validate([
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $deskripsiInput = trim((string) $request->deskripsi);
        $deskripsiFinal = $deskripsiInput !== '' ? $deskripsiInput : ($inventaris_ruang->keterangan ?? 'Rusak');

        BarangUnitKerusakan::updateOrCreate(
            [
                'barang_unit_id' => $inventaris_ruang->id,
                'status' => 'rusak',
            ],
            [
                'tgl_rusak' => now()->toDateString(),
                'deskripsi' => $deskripsiFinal,
            ]
        );

        // kosongkan keterangan agar tidak dianggap "kurang baik"
        $inventaris_ruang->update(['keterangan' => null]);

        return back()->with('success', 'Unit berhasil ditandai sebagai rusak.');
    }

    /**
     * Perbarui deskripsi kerusakan unit.
     */
    public function updateKerusakan(Request $request, BarangUnit $inventaris_ruang)
    {
        $request->validate([
            'deskripsi' => 'nullable|string|max:255',
        ]);

        $kerusakan = $inventaris_ruang->kerusakanAktif()->first();

        if (!$kerusakan) {
            return back()->with('error', 'Unit ini belum ditandai rusak.');
        }

        $kerusakan->update([
            'deskripsi' => $request->deskripsi ?: $kerusakan->deskripsi,
        ]);

        return back()->with('success', 'Deskripsi kerusakan diperbarui.');
    }

    /**
     * Pulihkan unit dari status rusak.
     */
    public function restoreKerusakan(Request $request, BarangUnit $inventaris_ruang)
    {
        $kerusakan = $inventaris_ruang->kerusakanAktif()->first();
        if ($kerusakan) {
            $kerusakan->delete();
        }
        return back()->with('success', 'Unit dipulihkan ke kondisi baik.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'idbarang' => 'required|exists:barang,idbarang',
            'idruang' => 'required|exists:ruang,idruang',
            'jumlah' => 'required|integer|min:1|max:100',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $barang = Barang::findOrFail($request->idbarang);
        $ruang = Ruang::findOrFail($request->idruang);

        $jenisBarang = $barang->barangMasuk()
            ->whereNotNull('jenis_barang')
            ->orderByDesc('tgl_masuk')
            ->orderByDesc('created_at')
            ->value('jenis_barang') ?? 'pinjam';
        if ($jenisBarang !== 'tetap') {
            return back()->withInput()->withErrors([
                'idbarang' => 'Barang ini bukan barang tetap, tidak bisa dicatat sebagai inventaris ruang.',
            ]);
        }

        $lastNomor = BarangUnit::where('idbarang', $barang->idbarang)
            ->where('idruang', $ruang->idruang)
            ->max('nomor_unit') ?? 0;

        $records = [];
        for ($i = 1; $i <= $request->jumlah; $i++) {
            $nomor = $lastNomor + $i;
            $records[] = [
                'idbarang' => $barang->idbarang,
                'idruang' => $ruang->idruang,
                'nomor_unit' => $nomor,
                'kode_unit' => KodeInventarisGenerator::make($barang, $ruang, $nomor),
                'keterangan' => $request->keterangan,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        BarangUnit::insert($records);

        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        return redirect()->route($routePrefix . '.inventaris-ruang.index')
            ->with('success', $request->jumlah . ' unit barang berhasil dicatat untuk ruang ini.');
    }

    public function destroy(BarangUnit $inventaris_ruang)
    {
        $inventaris_ruang->delete();

        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        return redirect()->route($routePrefix . '.inventaris-ruang.index')
            ->with('success', 'Unit barang berhasil dihapus.');
    }

    public function moveRuang(Request $request, BarangUnit $inventaris_ruang)
    {
        $request->validate([
            'idruang' => 'required|exists:ruang,idruang',
        ]);

        $targetRuang = Ruang::findOrFail($request->idruang);
        $asalRuangId = (int) $inventaris_ruang->idruang;
        if ($asalRuangId === (int) $targetRuang->idruang) {
            return back()->with('error', 'Ruang tujuan sama dengan ruang saat ini.');
        }

        $lastNomor = BarangUnit::where('idbarang', $inventaris_ruang->idbarang)
            ->where('idruang', $targetRuang->idruang)
            ->max('nomor_unit') ?? 0;

        $newNomor = $lastNomor + 1;
        $inventaris_ruang->update([
            'idruang' => $targetRuang->idruang,
            'nomor_unit' => $newNomor,
            'kode_unit' => KodeInventarisGenerator::make($inventaris_ruang->barang, $targetRuang, $newNomor),
        ]);

        InventarisRuangMove::create([
            'barang_unit_id' => $inventaris_ruang->id,
            'idbarang' => $inventaris_ruang->idbarang,
            'idruang_asal' => $asalRuangId,
            'idruang_tujuan' => $targetRuang->idruang,
            'moved_by' => Auth::id(),
            'moved_at' => now(),
        ]);

        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        return redirect()->route($routePrefix . '.inventaris-ruang.index')
            ->with('success', 'Unit berhasil dipindahkan ke ruang ' . ($targetRuang->nama_ruang ?? '-'));
    }

    public function moveHistory(Request $request)
    {
        $routePrefix = $this->getRoutePrefix();

        $query = InventarisRuangMove::with(['barang', 'barangUnit', 'ruangAsal', 'ruangTujuan', 'petugas'])
            ->orderByDesc('moved_at')
            ->orderByDesc('id');

        if ($request->filled('idbarang')) {
            $query->where('idbarang', $request->idbarang);
        }

        if ($request->filled('idruang_asal')) {
            $query->where('idruang_asal', $request->idruang_asal);
        }

        if ($request->filled('idruang_tujuan')) {
            $query->where('idruang_tujuan', $request->idruang_tujuan);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('moved_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('moved_at', '<=', $request->end_date);
        }

        $perPage = in_array((int) $request->get('per_page', 15), [10, 15, 25, 50, 100]) ? (int) $request->get('per_page', 15) : 15;
        $moves = $query->paginate($perPage)->appends($request->only([
            'idbarang',
            'idruang_asal',
            'idruang_tujuan',
            'start_date',
            'end_date',
            'per_page',
        ]));

        $barang = Barang::orderBy('nama_barang')->get();
        $ruang = Ruang::orderBy('nama_ruang')->get();

        return view('pegawai.inventaris_ruang.riwayat', compact('moves', 'barang', 'ruang', 'routePrefix'));
    }

    public function moveHistoryPdf(Request $request)
    {
        $query = InventarisRuangMove::with(['barang', 'barangUnit', 'ruangAsal', 'ruangTujuan', 'petugas'])
            ->orderByDesc('moved_at')
            ->orderByDesc('id');

        if ($request->filled('idbarang')) {
            $query->where('idbarang', $request->idbarang);
        }

        if ($request->filled('idruang_asal')) {
            $query->where('idruang_asal', $request->idruang_asal);
        }

        if ($request->filled('idruang_tujuan')) {
            $query->where('idruang_tujuan', $request->idruang_tujuan);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('moved_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('moved_at', '<=', $request->end_date);
        }

        $moves = $query->get();
        $filters = $request->only(['idbarang', 'idruang_asal', 'idruang_tujuan', 'start_date', 'end_date']);

        $pdf = Pdf::loadView('pegawai.inventaris_ruang.riwayat_pdf', compact('moves', 'filters'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('Riwayat_Pemindahan_Inventaris.pdf');
    }

}
