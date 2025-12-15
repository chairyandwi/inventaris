<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with('barang'); // eager load relasi barang
        $stats = [
            'totalEntry' => BarangMasuk::count(),
            'totalQty' => BarangMasuk::sum('jumlah'),
            'totalBaru' => BarangMasuk::where('status_barang', 'baru')->sum('jumlah'),
            'totalBekas' => BarangMasuk::where('status_barang', 'bekas')->sum('jumlah'),
            'totalPc' => BarangMasuk::where('is_pc', true)->sum('jumlah'),
        ];

        // Search
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('barang', function ($q) use ($searchTerm) {
                $q->where('kode_barang', 'like', "%{$searchTerm}%")
                  ->orWhere('nama_barang', 'like', "%{$searchTerm}%");
            })
            ->orWhere('keterangan', 'like', "%{$searchTerm}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'idbarang_masuk');
        $sortDirection = $request->get('sort_direction', 'asc');
        $allowedSortFields = ['idbarang_masuk', 'idbarang', 'jumlah', 'tgl_masuk'];
        $query->orderBy(in_array($sortBy, $allowedSortFields) ? $sortBy : 'idbarang_masuk', $sortDirection);

        // Pagination
        $perPage = in_array($request->get('per_page', 10), [10, 25, 50, 100]) ? $request->get('per_page', 10) : 10;

        $barangMasuk = $query->paginate($perPage)->appends($request->all());

        return view('pegawai.barang_masuk.index', compact('barangMasuk', 'stats'));
    }

    public function create()
    {
        $barang = Barang::with('kategori')->orderBy('nama_barang')->get();
        return view('pegawai.barang_masuk.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        $validator = Validator::make($request->all(), [
            'idbarang'    => 'required|exists:barang,idbarang',
            'tgl_masuk'   => 'required|date',
            'jumlah'      => 'required|integer|min:1',
            'status_barang' => 'required|in:baru,bekas',
            'keterangan'  => 'nullable|string|max:500',
            'is_pc'       => 'nullable|boolean',
            'ram_brand'   => 'nullable|string|max:100',
            'ram_capacity_gb' => 'nullable|integer|min:1|max:1024',
            'storage_type' => 'nullable|in:SSD,HDD',
            'storage_capacity_gb' => 'nullable|integer|min:1|max:10000',
            'processor'   => 'nullable|string|max:150',
        ], [
            'idbarang.required'   => 'Barang wajib dipilih',
            'idbarang.exists'     => 'Barang tidak valid',
            'jumlah.required'     => 'Jumlah wajib diisi',
            'jumlah.integer'      => 'Jumlah harus berupa angka',
            'status_barang.required' => 'Status barang wajib dipilih',
        ]);

        $barang = Barang::with('kategori')->find($request->idbarang);
        $kategoriNama = strtolower(optional($barang?->kategori)->nama_kategori ?? '');
        $requiresPcSpec = $request->boolean('is_pc') || str_contains($kategoriNama, 'pc');

        $validator->after(function ($validator) use ($request, $requiresPcSpec) {
            if ($requiresPcSpec) {
                $missingSpec = ! $request->filled('ram_capacity_gb')
                    || ! $request->filled('storage_type')
                    || ! $request->filled('storage_capacity_gb')
                    || ! $request->filled('processor');

                if ($missingSpec) {
                    $validator->errors()->add('spesifikasi', 'Spesifikasi PC (RAM, storage, dan prosesor) wajib diisi untuk perangkat PC.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated['is_pc'] = $requiresPcSpec;
        $validated['tgl_masuk'] = $validated['tgl_masuk'] ?? now()->toDateString();

        DB::transaction(function () use ($validated) {
            $barangLocked = Barang::lockForUpdate()->findOrFail($validated['idbarang']);
            BarangMasuk::create($validated);
            $barangLocked->increment('stok', $validated['jumlah']);
        });

        return redirect()->route($routePrefix . '.barang_masuk.index')->with('success', 'Data barang masuk berhasil ditambahkan');
    }

    public function edit(BarangMasuk $barangMasuk)
    {
        $barang = Barang::orderBy('nama_barang')->get();
        return view('pegawai.barang_masuk.edit', compact('barangMasuk', 'barang'));
    }

    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        $validator = Validator::make($request->all(), [
            'idbarang'    => 'required|exists:barang,idbarang',
            'tgl_masuk'   => 'required|date',
            'jumlah'      => 'required|integer|min:1',
            'status_barang' => 'required|in:baru,bekas',
            'keterangan'  => 'nullable|string|max:500',
            'is_pc'       => 'nullable|boolean',
            'ram_brand'   => 'nullable|string|max:100',
            'ram_capacity_gb' => 'nullable|integer|min:1|max:1024',
            'storage_type' => 'nullable|in:SSD,HDD',
            'storage_capacity_gb' => 'nullable|integer|min:1|max:10000',
            'processor'   => 'nullable|string|max:150',
        ]);

        $barang = Barang::with('kategori')->find($request->idbarang);
        $kategoriNama = strtolower(optional($barang?->kategori)->nama_kategori ?? '');
        $requiresPcSpec = $request->boolean('is_pc') || str_contains($kategoriNama, 'pc');

        $validator->after(function ($validator) use ($request, $requiresPcSpec) {
            if ($requiresPcSpec) {
                $missingSpec = ! $request->filled('ram_capacity_gb')
                    || ! $request->filled('storage_type')
                    || ! $request->filled('storage_capacity_gb')
                    || ! $request->filled('processor');

                if ($missingSpec) {
                    $validator->errors()->add('spesifikasi', 'Spesifikasi PC (RAM, storage, dan prosesor) wajib diisi untuk perangkat PC.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated['is_pc'] = $requiresPcSpec;
        $validated['tgl_masuk'] = $validated['tgl_masuk'] ?? ($barangMasuk->tgl_masuk ?? now()->toDateString());

        DB::transaction(function () use ($barangMasuk, $validated) {
            $originalBarangId = $barangMasuk->idbarang;
            $originalJumlah = $barangMasuk->jumlah;

            $barangMasuk->update($validated);

            $newBarang = Barang::lockForUpdate()->findOrFail($validated['idbarang']);

            if ($originalBarangId !== $validated['idbarang']) {
                $oldBarang = Barang::lockForUpdate()->find($originalBarangId);
                if ($oldBarang) {
                    $oldBarang->update(['stok' => max(0, $oldBarang->stok - $originalJumlah)]);
                }
                $newBarang?->increment('stok', $validated['jumlah']);
            } else {
                $delta = $validated['jumlah'] - $originalJumlah;
                if ($delta !== 0 && $newBarang) {
                    $newBarang->update(['stok' => max(0, $newBarang->stok + $delta)]);
                }
            }
        });

        return redirect()->route($routePrefix . '.barang_masuk.index')->with('success', 'Data barang masuk berhasil diubah');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        try {
            DB::transaction(function () use ($barangMasuk) {
                $barang = Barang::lockForUpdate()->find($barangMasuk->idbarang);
                if ($barang) {
                    $barang->update(['stok' => max(0, $barang->stok - $barangMasuk->jumlah)]);
                }

                $barangMasuk->delete();
            });
            return redirect()->route($routePrefix . '.barang_masuk.index')->with('success', 'Data barang masuk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route($routePrefix . '.barang_masuk.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function show(BarangMasuk $barangMasuk)
    {
        return view('pegawai.barang_masuk.show', compact('barangMasuk'));
    }

    public function laporan()
    {
        if (!auth('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $user = auth('web')->user();

        if (!$user || $user->role !== 'pegawai') {
            abort(403, 'Anda tidak memiliki akses.');
        }

        $barangMasuk = BarangMasuk::with('barang')->orderByDesc('idbarang_masuk')->get();

        $pdf = Pdf::loadView('pegawai.barang_masuk.laporan', compact('barangMasuk'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Barang_Masuk.pdf');
    }
}
