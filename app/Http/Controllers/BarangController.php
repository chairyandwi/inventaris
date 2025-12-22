<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangMasuk;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Ruang;
use App\Http\Requests\BarangRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori'); // eager load kategori

        $stats = [
            'total' => Barang::count(),
            'totalTetap' => BarangMasuk::where('jenis_barang', 'tetap')->distinct('idbarang')->count('idbarang'),
            'totalPinjam' => BarangMasuk::where('jenis_barang', 'pinjam')->distinct('idbarang')->count('idbarang'),
            'totalStok' => Barang::sum('stok'),
        ];

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('kode_barang', 'like', "%{$searchTerm}%")
                  ->orWhere('nama_barang', 'like', "%{$searchTerm}%")
                  ->orWhere('keterangan', 'like', "%{$searchTerm}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'idbarang');
        $sortDirection = $request->get('sort_direction', 'asc');
        $allowedSortFields = ['idbarang', 'kode_barang', 'nama_barang', 'stok'];
        $query->orderBy(in_array($sortBy, $allowedSortFields) ? $sortBy : 'idbarang', $sortDirection);

        // Pagination
        $perPage = in_array($request->get('per_page', 10), [10, 25, 50, 100]) ? $request->get('per_page', 10) : 10;

        $barang = $query->paginate($perPage)->appends($request->all());

        return view('pegawai.barang.index', compact('barang', 'stats'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $ruang = Ruang::orderBy('nama_ruang')->get();
        return view('pegawai.barang.create', compact('kategori', 'ruang'));
    }

    public function store(BarangRequest $request)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        $validated = $request->validated();
        $barangData = Arr::except($validated, ['ruang_tetap', 'jumlah_tetap', 'keterangan_inventaris']);
        $barangData['stok'] = 0; // stok dikalkulasi dari transaksi (barang masuk / peminjaman)

        Barang::create($barangData);

        return redirect()->route($routePrefix . '.barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $barang->load('units.ruang');
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $ruang = Ruang::orderBy('nama_ruang')->get();
        $inventarisDistribusi = $barang->units->groupBy('idruang')->map(function ($items) {
            $first = $items->first();
            return [
                'ruang' => $first->idruang,
                'jumlah' => $items->count(),
                'catatan' => $first->keterangan,
            ];
        })->values()->toArray();
        return view('pegawai.barang.edit', compact(
            'barang',
            'kategori',
            'ruang',
            'inventarisDistribusi'
        ));
    }

    public function update(BarangRequest $request, Barang $barang)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        $validated = $request->validated();
        $barangData = Arr::except($validated, ['ruang_tetap', 'jumlah_tetap', 'keterangan_inventaris', 'stok']);

        $barang->update($barangData);

        return redirect()->route($routePrefix . '.barang.index')->with('success', 'Barang berhasil diubah');
    }

    public function destroy(Barang $barang)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        try {
            $barang->delete();
            return redirect()->route($routePrefix . '.barang.index')->with('success', 'Barang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route($routePrefix . '.barang.index')->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }

    public function show(Barang $barang)
    {
        $barang->load(['kategori', 'units.ruang']);
        $units = $barang->units;
        $distribution = $units->groupBy('idruang')->map(function ($items) {
            $ruang = $items->first()->ruang;
            return [
                'ruang' => $ruang->nama_ruang ?? '-',
                'gedung' => $ruang->nama_gedung ?? '-',
                'jumlah' => $items->count(),
            ];
        })->values();

        $latestMasuk = $barang->barangMasuk()
            ->orderByDesc('tgl_masuk')
            ->orderByDesc('created_at')
            ->first();
        $effectiveJenis = $barang->barangMasuk()
            ->whereNotNull('jenis_barang')
            ->orderByDesc('tgl_masuk')
            ->orderByDesc('created_at')
            ->value('jenis_barang');
        $dipinjamQty = Peminjaman::where('idbarang', $barang->idbarang)
            ->where('status', 'dipinjam')
            ->sum('jumlah');
        $stokPinjam = $barang->barangMasuk()
            ->where('jenis_barang', 'pinjam')
            ->sum('jumlah');
        $availablePinjam = max($stokPinjam - $dipinjamQty, 0);
        $merkEntries = $barang->barangMasuk()
            ->where('jenis_barang', 'pinjam')
            ->whereNotNull('merk')
            ->where('merk', '!=', '')
            ->get(['merk', 'status_barang', 'jumlah']);
        $merkStats = $merkEntries
            ->groupBy('merk')
            ->map(function ($items, $merk) {
                $hasBaru = $items->contains('status_barang', 'baru');
                $hasBekas = $items->contains('status_barang', 'bekas');
                if ($hasBaru && $hasBekas) {
                    $kondisi = 'Campuran';
                } elseif ($hasBaru) {
                    $kondisi = 'Baru';
                } elseif ($hasBekas) {
                    $kondisi = 'Bekas';
                } else {
                    $kondisi = '-';
                }

                return [
                    'merk' => $merk,
                    'kondisi' => $kondisi,
                    'total_masuk' => $items->sum('jumlah'),
                ];
            })
            ->values();
        $kondisiList = $barang->barangMasuk()
            ->whereNotNull('status_barang')
            ->pluck('status_barang')
            ->unique()
            ->values();
        return view('pegawai.barang.show', compact(
            'barang',
            'units',
            'distribution',
            'latestMasuk',
            'effectiveJenis',
            'stokPinjam',
            'availablePinjam',
            'merkStats',
            'kondisiList',
            'dipinjamQty'
        ));
    }

    public function api()
    {
        $barang = Barang::select('idbarang', 'kode_barang', 'nama_barang', 'stok')
                        ->with('kategori:idkategori,nama_kategori')
                        ->orderBy('nama_barang')
                        ->get();

        return response()->json($barang);
    }

    public function laporan()
    {
        // Pastikan user login dan role pegawai atau admin
        if (!auth('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $user = auth('web')->user();
        
        if (!$user || !in_array($user->role, ['pegawai', 'admin'])) {
            abort(403, 'Anda tidak memiliki akses.');
        }
        

        $barang = Barang::with('kategori')->orderByDesc('idbarang')->get();

        $pdf = Pdf::loadView('pegawai.barang.laporan', compact('barang'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Barang.pdf');
    }

}
