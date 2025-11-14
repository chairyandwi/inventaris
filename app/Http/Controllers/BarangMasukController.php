<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class BarangMasukController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangMasuk::with('barang'); // eager load relasi barang

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
        $allowedSortFields = ['idbarang_masuk', 'idbarang', 'jumlah', 'tgl_pengembalian'];
        $query->orderBy(in_array($sortBy, $allowedSortFields) ? $sortBy : 'idbarang_masuk', $sortDirection);

        // Pagination
        $perPage = in_array($request->get('per_page', 10), [10, 25, 50, 100]) ? $request->get('per_page', 10) : 10;

        $barangMasuk = $query->paginate($perPage)->appends($request->all());

        return view('pegawai.barang_masuk.index', compact('barangMasuk'));
    }

    public function create()
    {
        $barang = Barang::orderBy('nama_barang')->get();
        return view('pegawai.barang_masuk.create', compact('barang'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idbarang'    => 'required|exists:barang,idbarang',
            'jumlah'      => 'required|integer|min:1',
            'keterangan'  => 'nullable|string|max:500',
            'tgl_pengembalian'   => 'required|date',
        ], [
            'idbarang.required'   => 'Barang wajib dipilih',
            'idbarang.exists'     => 'Barang tidak valid',
            'jumlah.required'     => 'Jumlah wajib diisi',
            'jumlah.integer'      => 'Jumlah harus berupa angka',
            'tgl_pengembalian.required'  => 'Tanggal masuk wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        BarangMasuk::create($validator->validated());

        return redirect()->route('pegawai.barang_masuk.index')->with('success', 'Data barang masuk berhasil ditambahkan');
    }

    public function edit(BarangMasuk $barangMasuk)
    {
        $barang = Barang::orderBy('nama_barang')->get();
        return view('pegawai.barang_masuk.edit', compact('barangMasuk', 'barang'));
    }

    public function update(Request $request, BarangMasuk $barangMasuk)
    {
        $validator = Validator::make($request->all(), [
            'idbarang'    => 'required|exists:barang,idbarang',
            'jumlah'      => 'required|integer|min:1',
            'keterangan'  => 'nullable|string|max:500',
            'tgl_pengembalian'   => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $barangMasuk->update($validator->validated());

        return redirect()->route('pegawai.barang_masuk.index')->with('success', 'Data barang masuk berhasil diubah');
    }

    public function destroy(BarangMasuk $barangMasuk)
    {
        try {
            $barangMasuk->delete();
            return redirect()->route('pegawai.barang_masuk.index')->with('success', 'Data barang masuk berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('pegawai.barang_masuk.index')->with('error', 'Gagal menghapus data: ' . $e->getMessage());
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
