<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class KategoriController extends Controller
{
    private function getRoutePrefix(): string
    {
        return auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $routePrefix = $this->getRoutePrefix();
        $query = Kategori::query();
        $stats = [
            'total' => Kategori::count(),
        ];

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where('nama_kategori', 'like', "%{$searchTerm}%")
                ->orWhere('keterangan', 'like', "%{$searchTerm}%");
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'idkategori');
        $sortDirection = $request->get('sort_direction', 'asc');

        $allowedSortFields = ['idkategori',];
        if (in_array($sortBy, $allowedSortFields)) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('idkategori', 'asc');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $allowedPerPage = [10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }

        $kategori = $query->paginate($perPage)->appends($request->all());

        return view('pegawai.kategori.index', compact('kategori', 'stats', 'routePrefix'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $routePrefix = $this->getRoutePrefix();

        return view('pegawai.kategori.create', compact('routePrefix'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $routePrefix = $this->getRoutePrefix();
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:255|unique:kategori,nama_kategori',
            'keterangan' => 'nullable|string|max:500'
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.unique' => 'Nama kategori sudah ada',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter',
            'keterangan.max' => 'Keterangan maksimal 500 karakter'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route($routePrefix . '.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kategori $kategori)
    {
        $routePrefix = $this->getRoutePrefix();

        return view('pegawai.kategori.show', compact('kategori', 'routePrefix'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kategori $kategori)
    {
        $routePrefix = $this->getRoutePrefix();

        return view('pegawai.kategori.edit', compact('kategori', 'routePrefix'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kategori $kategori)
    {
        $routePrefix = $this->getRoutePrefix();
        $validator = Validator::make($request->all(), [
            'nama_kategori' => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategori', 'nama_kategori')->ignore($kategori->idkategori, 'idkategori'),
            ],
            'keterangan' => 'nullable|string|max:500'
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi',
            'nama_kategori.unique' => 'Nama kategori sudah ada',
            'nama_kategori.max' => 'Nama kategori maksimal 255 karakter',
            'keterangan.max' => 'Keterangan maksimal 500 karakter'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $kategori->update([
                'nama_kategori' => $request->nama_kategori,
                'keterangan'    => $request->keterangan
            ]);

            return redirect()->route($routePrefix . '.kategori.index')
                ->with('success', 'Kategori berhasil diubah');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengubah kategori: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kategori $kategori)
    {
        $routePrefix = $this->getRoutePrefix();
        try {
            $kategori->delete();
    
            return redirect()->route($routePrefix . '.kategori.index')
                ->with('success', 'Kategori berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route($routePrefix . '.kategori.index')
                ->with('error', 'Gagal menghapus kategori: ' . $e->getMessage());
        }
    }
    

    /**
     * Get all kategoris for API or select options
     */
    public function api()
    {
        $kategori = Kategori::select('idkategori', 'nama_kategori', 'keterangan')
            ->orderBy('nama_kategori')
            ->get();

        return response()->json($kategori);
    }
}
