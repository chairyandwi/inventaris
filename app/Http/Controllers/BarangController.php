<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    public function index(Request $request)
    {
        $query = Barang::with('kategori'); // eager load kategori

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

        return view('pegawai.barang.index', compact('barang'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('pegawai.barang.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idkategori'   => 'required|exists:kategori,idkategori',
            'kode_barang'  => 'required|string|max:20|unique:barang,kode_barang',
            'nama_barang'  => 'required|string|max:100',
            'stok'         => 'nullable|integer|min:0',
            'keterangan'   => 'nullable|string|max:500',
        ], [
            'idkategori.required' => 'Kategori wajib dipilih',
            'idkategori.exists'   => 'Kategori tidak valid',
            'kode_barang.required'=> 'Kode barang wajib diisi',
            'kode_barang.unique'  => 'Kode barang sudah ada',
            'nama_barang.required'=> 'Nama barang wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Barang::create($validator->validated());

        return redirect()->route('pegawai.barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function edit(Barang $barang)
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('pegawai.barang.edit', compact('barang', 'kategori'));
    }

    public function update(Request $request, Barang $barang)
    {
        $validator = Validator::make($request->all(), [
            'idkategori'   => 'required|exists:kategori,idkategori',
            'kode_barang'  => ['required', 'string', 'max:20', Rule::unique('barang', 'kode_barang')->ignore($barang->idbarang, 'idbarang')],
            'nama_barang'  => 'required|string|max:100',
            'stok'         => 'nullable|integer|min:0',
            'keterangan'   => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $barang->update($validator->validated());

        return redirect()->route('pegawai.barang.index')->with('success', 'Barang berhasil diubah');
    }

    public function destroy(Barang $barang)
    {
        try {
            $barang->delete();
            return redirect()->route('pegawai.barang.index')->with('success', 'Barang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('pegawai.barang.index')->with('error', 'Gagal menghapus barang: ' . $e->getMessage());
        }
    }

    public function show(Barang $barang)
    {
        return view('pegawai.barang.show', compact('barang'));
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
        // Pastikan user login dan role pegawai
        if (!auth('web')->check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        $user = auth('web')->user();
        
        if (!$user || $user->role !== 'pegawai') {
            abort(403, 'Anda tidak memiliki akses.');
        }
        

        $barang = Barang::with('kategori')->orderByDesc('idbarang')->get();

        $pdf = Pdf::loadView('pegawai.barang.laporan', compact('barang'))
                  ->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Barang.pdf');
    }
}
