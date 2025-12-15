<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangUnit;
use App\Models\BarangMasuk;
use App\Models\Kategori;
use App\Models\Ruang;
use App\Support\KodeInventarisGenerator;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori'); // eager load kategori

        $stats = [
            'total' => Barang::count(),
            'totalTetap' => Barang::where('jenis_barang', 'tetap')->count(),
            'totalPinjam' => Barang::where('jenis_barang', 'pinjam')->count(),
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

    public function store(Request $request)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        $validator = Validator::make($request->all(), [
            'idkategori'   => 'required|exists:kategori,idkategori',
            'kode_barang'  => 'required|string|max:20|unique:barang,kode_barang',
            'nama_barang'  => 'required|string|max:100',
            'jenis_barang' => 'required|in:pinjam,tetap',
            'keterangan'   => 'nullable|string|max:500',
            'distribusi_ruang' => 'required_if:jenis_barang,tetap|array|min:1',
            'distribusi_ruang.*' => 'required_with:distribusi_jumlah.*|exists:ruang,idruang',
            'distribusi_jumlah' => 'required_if:jenis_barang,tetap|array',
            'distribusi_jumlah.*' => 'required_with:distribusi_ruang.*|integer|min:1|max:500',
            'distribusi_catatan' => 'array|nullable',
            'distribusi_catatan.*' => 'nullable|string|max:255',
        ], [
            'idkategori.required' => 'Kategori wajib dipilih',
            'idkategori.exists'   => 'Kategori tidak valid',
            'kode_barang.required'=> 'Kode barang wajib diisi',
            'kode_barang.unique'  => 'Kode barang sudah ada',
            'nama_barang.required'=> 'Nama barang wajib diisi',
            'jenis_barang.required' => 'Jenis barang wajib dipilih',
            'distribusi_ruang.required_if' => 'Pilih minimal satu ruang inventaris',
            'distribusi_ruang.*.exists' => 'Ruang tidak valid',
            'distribusi_jumlah.*.integer' => 'Jumlah unit harus angka',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $barangData = Arr::except($validated, ['ruang_tetap', 'jumlah_tetap', 'keterangan_inventaris']);
        $barangData['stok'] = 0; // stok dikalkulasi dari transaksi (barang masuk / peminjaman)

        DB::transaction(function () use ($barangData, $request) {
            $barang = Barang::create($barangData);
            $this->syncInventarisUnits($barang, $request);
        });

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

    public function update(Request $request, Barang $barang)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        $validator = Validator::make($request->all(), [
            'idkategori'   => 'required|exists:kategori,idkategori',
            'kode_barang'  => ['required', 'string', 'max:20', Rule::unique('barang', 'kode_barang')->ignore($barang->idbarang, 'idbarang')],
            'nama_barang'  => 'required|string|max:100',
            'jenis_barang' => 'required|in:pinjam,tetap',
            'keterangan'   => 'nullable|string|max:500',
            'distribusi_ruang' => 'required_if:jenis_barang,tetap|array|min:1',
            'distribusi_ruang.*' => 'required_with:distribusi_jumlah.*|exists:ruang,idruang',
            'distribusi_jumlah' => 'required_if:jenis_barang,tetap|array',
            'distribusi_jumlah.*' => 'required_with:distribusi_ruang.*|integer|min:1|max:500',
            'distribusi_catatan' => 'array|nullable',
            'distribusi_catatan.*' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $barangData = Arr::except($validated, ['ruang_tetap', 'jumlah_tetap', 'keterangan_inventaris', 'stok']);

        DB::transaction(function () use ($barangData, $barang, $request) {
            $barang->update($barangData);
            $this->syncInventarisUnits($barang, $request);
        });

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

        return view('pegawai.barang.show', compact('barang', 'units', 'distribution'));
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
        $barangMasuk = BarangMasuk::with(['barang.kategori'])
            ->orderByDesc('tgl_masuk')
            ->orderByDesc('created_at')
            ->get();

        $pdf = Pdf::loadView('pegawai.barang.laporan', compact('barang', 'barangMasuk'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Barang.pdf');
    }

    protected function syncInventarisUnits(Barang $barang, Request $request): void
    {
        if ($barang->jenis_barang !== 'tetap') {
            $barang->units()->delete();
            return;
        }

        $barang->units()->delete();

        $ruangList = $request->input('distribusi_ruang', []);
        $jumlahList = $request->input('distribusi_jumlah', []);
        $catatanList = $request->input('distribusi_catatan', []);

        $records = [];

        foreach ($ruangList as $index => $ruangId) {
            if (!$ruangId) {
                continue;
            }

            $jumlah = (int)($jumlahList[$index] ?? 0);
            if ($jumlah <= 0) {
                continue;
            }

            $ruang = Ruang::find($ruangId);
            if (!$ruang) {
                continue;
            }

            $catatan = $catatanList[$index] ?? null;

            for ($i = 1; $i <= $jumlah; $i++) {
                $records[] = [
                    'idbarang' => $barang->idbarang,
                    'idruang' => $ruang->idruang,
                    'nomor_unit' => $i,
                    'kode_unit' => KodeInventarisGenerator::make($barang, $ruang, $i),
                    'keterangan' => $catatan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if ($records) {
            BarangUnit::insert($records);
        }
    }
}
