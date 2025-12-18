<?php

namespace App\Http\Controllers;

use App\Models\Ruang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Support\ActivityLogger;

class RuangController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruang::query();
        $stats = [
            'total' => Ruang::count(),
            'totalGedung' => Ruang::distinct('nama_gedung')->count('nama_gedung'),
        ];

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('nama_ruang', 'like', "%{$searchTerm}%")
                  ->orWhere('kode_ruang', 'like', "%{$searchTerm}%")
                  ->orWhere('nama_gedung', 'like', "%{$searchTerm}%")
                  ->orWhere('nama_lantai', 'like', "%{$searchTerm}%")
                  ->orWhere('keterangan', 'like', "%{$searchTerm}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'idruang');
        $sortDirection = $request->get('sort_direction', 'asc');

        $allowedSortFields = ['idruang', 'nama_ruang', 'kode_ruang', 'nama_gedung', 'nama_lantai'];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('idruang', 'asc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $ruang = $query->paginate($perPage)->appends($request->all());

        return view('pegawai.ruang.index', compact('ruang', 'stats'));
    }

    public function create()
    {
        return view('pegawai.ruang.create');
    }

    public function store(Request $request)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        $validator = Validator::make($request->all(), [
            'nama_ruang'   => 'required|string|max:100|unique:ruang,nama_ruang',
            'kode_ruang'   => 'required|string|max:50|unique:ruang,kode_ruang',
            'nama_gedung'  => 'required|string|max:100',
            'nama_lantai'  => 'required|string|max:100',
            'keterangan'   => 'nullable|string|max:255',
        ], [
            'nama_ruang.required'  => 'Nama ruang wajib diisi',
            'nama_ruang.unique'    => 'Nama ruang sudah ada',
            'kode_ruang.required'  => 'Kode ruang wajib diisi',
            'kode_ruang.unique'    => 'Kode ruang sudah ada',
            'nama_gedung.required' => 'Nama gedung wajib diisi',
            'nama_lantai.required' => 'Nama lantai wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ruang = Ruang::create($request->only(['nama_ruang', 'kode_ruang', 'nama_gedung', 'nama_lantai', 'keterangan']));

        ActivityLogger::log('Tambah Ruang', 'Menambahkan ruang ' . $ruang->nama_ruang);

        return redirect()->route($routePrefix . '.ruang.index')
            ->with('success', 'Ruang berhasil ditambahkan');
    }

    public function show(Ruang $ruang)
    {
        return view('pegawai.ruang.show', compact('ruang'));
    }

    public function edit(Ruang $ruang)
    {
        return view('pegawai.ruang.edit', compact('ruang'));
    }

    public function update(Request $request, Ruang $ruang)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        $validator = Validator::make($request->all(), [
            'nama_ruang'   => [
                'required',
                'string',
                'max:100',
                Rule::unique('ruang', 'nama_ruang')->ignore($ruang->idruang, 'idruang'),
            ],
            'kode_ruang'   => [
                'required',
                'string',
                'max:50',
                Rule::unique('ruang', 'kode_ruang')->ignore($ruang->idruang, 'idruang'),
            ],
            'nama_gedung'  => 'required|string|max:100',
            'nama_lantai'  => 'required|string|max:100',
            'keterangan'   => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $ruang->update($request->only(['nama_ruang', 'kode_ruang', 'nama_gedung', 'nama_lantai', 'keterangan']));

            ActivityLogger::log('Perbarui Ruang', 'Memperbarui ruang ' . $ruang->nama_ruang);

            return redirect()->route($routePrefix . '.ruang.index')
                ->with('success', 'Ruang berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah ruang: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy(Ruang $ruang)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        try {
            $nama = $ruang->nama_ruang;
            $ruang->delete();

            ActivityLogger::log('Hapus Ruang', 'Menghapus ruang ' . $nama);

            return redirect()->route($routePrefix . '.ruang.index')
                ->with('success', 'Ruang berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route($routePrefix . '.ruang.index')
                ->with('error', 'Gagal menghapus ruang: ' . $e->getMessage());
        }
    }

    public function api()
    {
        $ruang = Ruang::select('idruang', 'nama_ruang', 'kode_ruang', 'nama_gedung', 'nama_lantai', 'keterangan')
            ->orderBy('nama_ruang')
            ->get();

        return response()->json($ruang);
    }
}
