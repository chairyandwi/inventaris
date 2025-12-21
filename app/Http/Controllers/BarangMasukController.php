<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Ruang;
use App\Models\BarangUnit;
use Illuminate\Http\Request;
use App\Http\Requests\BarangMasukRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Support\KodeInventarisGenerator;

class BarangMasukController extends Controller
{
    private function getRoutePrefix(): string
    {
        return auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
    }

    public function index(Request $request)
    {
        $query = BarangMasuk::with('barang'); // eager load relasi barang
        $barangList = Barang::orderBy('nama_barang')->get();
        $ruangList = Ruang::orderBy('nama_ruang')->get();
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
            $query->where(function ($q) use ($searchTerm) {
                $q->whereHas('barang', function ($q) use ($searchTerm) {
                    $q->where('kode_barang', 'like', "%{$searchTerm}%")
                        ->orWhere('nama_barang', 'like', "%{$searchTerm}%");
                })
                ->orWhere('keterangan', 'like', "%{$searchTerm}%")
                ->orWhereIn('idbarang_masuk', function ($sub) use ($searchTerm) {
                    $sub->from('barang_units')
                        ->join('ruang', 'ruang.idruang', '=', 'barang_units.idruang')
                        ->select('barang_units.barang_masuk_id')
                        ->whereNotNull('barang_units.barang_masuk_id')
                        ->where(function ($q) use ($searchTerm) {
                            $q->where('ruang.kode_ruang', 'like', "%{$searchTerm}%")
                                ->orWhere('ruang.nama_ruang', 'like', "%{$searchTerm}%");
                        });
                });
            });
        }

        // Filters
        if ($request->filled('idbarang')) {
            $query->where('idbarang', $request->idbarang);
        }

        if ($request->filled('status_barang')) {
            $query->where('status_barang', $request->status_barang);
        }

        if ($request->has('is_pc') && $request->is_pc !== '') {
            $query->where('is_pc', (bool)$request->is_pc);
        }

        if ($request->filled('idruang')) {
            $query->whereIn('idbarang_masuk', function ($sub) use ($request) {
                $sub->from('barang_units')
                    ->select('barang_masuk_id')
                    ->whereNotNull('barang_masuk_id')
                    ->where('idruang', $request->idruang);
            });
        }

        if ($request->filled('tgl_masuk_from')) {
            $query->whereDate('tgl_masuk', '>=', $request->tgl_masuk_from);
        }

        if ($request->filled('tgl_masuk_to')) {
            $query->whereDate('tgl_masuk', '<=', $request->tgl_masuk_to);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'tgl_masuk');
        $sortDirection = strtolower($request->get('sort_direction', 'desc')) === 'asc' ? 'asc' : 'desc';
        $allowedSortFields = ['idbarang_masuk', 'idbarang', 'jumlah', 'tgl_masuk'];
        $query->orderBy(in_array($sortBy, $allowedSortFields) ? $sortBy : 'idbarang_masuk', $sortDirection);

        // Pagination
        $perPage = in_array($request->get('per_page', 10), [10, 25, 50, 100]) ? $request->get('per_page', 10) : 10;

        $baseQuery = clone $query;
        $allIds = $baseQuery->pluck('idbarang_masuk');

        $barangMasuk = $query->paginate($perPage)->appends($request->all());
        $ruangAggregates = collect();
        $unitCounts = collect();
        $unitTotalsByBarangRuang = collect();
        if ($allIds->count()) {
            $ruangAggregates = DB::table('barang_units')
                ->join('ruang', 'ruang.idruang', '=', 'barang_units.idruang')
                ->whereIn('barang_units.barang_masuk_id', $allIds)
                ->select(
                    'barang_units.barang_masuk_id',
                    DB::raw("GROUP_CONCAT(DISTINCT ruang.kode_ruang ORDER BY ruang.kode_ruang SEPARATOR ', ') as kode_list"),
                    DB::raw("GROUP_CONCAT(DISTINCT ruang.nama_ruang ORDER BY ruang.nama_ruang SEPARATOR ', ') as ruang_list")
                )
                ->groupBy('barang_units.barang_masuk_id')
                ->get()
                ->keyBy('barang_masuk_id');
            $unitCounts = DB::table('barang_units')
                ->whereIn('barang_units.barang_masuk_id', $allIds)
                ->select('barang_units.barang_masuk_id', DB::raw('COUNT(*) as total'))
                ->groupBy('barang_units.barang_masuk_id')
                ->get()
                ->keyBy('barang_masuk_id');
        }

        if ($barangMasuk->count()) {
            $barangIds = $barangMasuk->pluck('idbarang')->filter()->unique()->values();
            if ($barangIds->isNotEmpty()) {
                $unitTotalsByBarangRuang = DB::table('barang_units')
                    ->join('ruang', 'ruang.idruang', '=', 'barang_units.idruang')
                    ->whereIn('barang_units.idbarang', $barangIds)
                    ->select('barang_units.idbarang', 'ruang.kode_ruang', DB::raw('COUNT(*) as total'))
                    ->groupBy('barang_units.idbarang', 'ruang.kode_ruang')
                    ->get()
                    ->groupBy('idbarang')
                    ->map(function ($items) {
                        return $items->keyBy('kode_ruang')->map(fn ($row) => (int) $row->total);
                    });
            }
        }

        return view('pegawai.barang_masuk.index', compact('barangMasuk', 'stats', 'barangList', 'ruangList', 'ruangAggregates', 'unitCounts', 'unitTotalsByBarangRuang'));
    }

    public function create()
    {
        $barang = Barang::with('kategori')->orderBy('nama_barang')->get();
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $ruang = Ruang::orderBy('nama_ruang')->get();
        $routePrefix = $this->getRoutePrefix();

        return view('pegawai.barang_masuk.create', compact('barang', 'kategori', 'ruang', 'routePrefix'));
    }

    public function store(BarangMasukRequest $request)
    {
        $routePrefix = $this->getRoutePrefix();
        $validator = Validator::make($request->all(), [
            'idbarang'    => 'required|exists:barang,idbarang',
            'tgl_masuk'   => 'required|date',
            'jumlah'      => 'required|integer|min:1',
            'status_barang' => 'required|in:baru,bekas',
            'jenis_barang' => 'nullable|in:pinjam,tetap',
            'keterangan'  => 'nullable|string|max:500',
            'merk'        => 'nullable|string|max:120',
            'is_pc'       => 'nullable|boolean',
            'ram_brand'   => 'nullable|string|max:100',
            'ram_capacity_gb' => 'nullable|integer|min:1|max:1024',
            'storage_type' => 'nullable|in:SSD,HDD',
            'storage_capacity_gb' => 'nullable|integer|min:1|max:10000',
            'processor'   => 'nullable|string|max:150',
            'monitor_brand' => 'nullable|string|max:120',
            'monitor_model' => 'nullable|string|max:150',
            'monitor_size_inch' => 'nullable|numeric|min:10|max:60',
            'distribusi_ruang' => 'array',
            'distribusi_ruang.*' => 'nullable|integer',
            'distribusi_jumlah' => 'array',
            'distribusi_jumlah.*' => 'nullable|integer|min:1|max:500',
            'distribusi_catatan' => 'array',
            'distribusi_catatan.*' => 'nullable|string|max:255',
        ], [
            'idbarang.required'   => 'Barang wajib dipilih',
            'idbarang.exists'     => 'Barang tidak valid',
            'jumlah.required'     => 'Jumlah wajib diisi',
            'jumlah.integer'      => 'Jumlah harus berupa angka',
            'status_barang.required' => 'Status barang wajib dipilih',
        ]);

        $barang = Barang::with('kategori')->find($request->idbarang);
        $barangJenis = $request->input('jenis_barang', $barang?->jenis_barang ?? 'pinjam');
        $kategoriNama = strtolower(optional($barang?->kategori)->nama_kategori ?? '');
        $requiresPcSpec = $request->boolean('is_pc') || str_contains($kategoriNama, 'pc');
        $distribusiRuang = $request->input('distribusi_ruang', []);
        $distribusiJumlah = $request->input('distribusi_jumlah', []);
        $distribusiCatatan = $request->input('distribusi_catatan', []);

        $validator->after(function ($validator) use ($request, $requiresPcSpec, $barangJenis, $distribusiRuang, $distribusiJumlah) {
            if ($requiresPcSpec) {
                $missingSpec = ! $request->filled('ram_capacity_gb')
                    || ! $request->filled('storage_type')
                    || ! $request->filled('storage_capacity_gb')
                    || ! $request->filled('processor');

                if ($missingSpec) {
                    $validator->errors()->add('spesifikasi', 'Spesifikasi PC (RAM, storage, dan prosesor) wajib diisi untuk perangkat PC.');
                }

                if (! $request->filled('monitor_size_inch')) {
                    $validator->errors()->add('monitor_size_inch', 'Ukuran monitor (inci) wajib diisi untuk perangkat PC.');
                }
            }

            if ($barangJenis === 'tetap') {
                $rows = collect($distribusiRuang)->filter()->keys();
                if ($rows->isEmpty()) {
                    $validator->errors()->add('distribusi_ruang', 'Distribusi ruang wajib diisi untuk barang tetap.');
                    return;
                }

                $totalDistribusi = 0;
                foreach ($rows as $idx) {
                    $jumlah = (int) ($distribusiJumlah[$idx] ?? 0);
                    if ($jumlah < 1) {
                        $validator->errors()->add('distribusi_jumlah.' . $idx, 'Jumlah unit per ruang minimal 1.');
                    }
                    $totalDistribusi += $jumlah;
                }

                $jumlahMasuk = (int) $request->input('jumlah', 0);
                if ($jumlahMasuk > 0 && $totalDistribusi !== $jumlahMasuk) {
                    $validator->errors()->add('distribusi_jumlah', 'Total jumlah distribusi harus sama dengan jumlah masuk.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated['is_pc'] = $requiresPcSpec;
        $validated['tgl_masuk'] = $validated['tgl_masuk'] ?? now()->toDateString();

        $distribusiData = [];
        if ($barangJenis === 'tetap') {
            $ruangIds = collect($distribusiRuang)->filter()->map(fn($id) => (int) $id)->all();
            $ruangMap = Ruang::whereIn('idruang', $ruangIds)->get()->keyBy('idruang');

            foreach ($distribusiRuang as $idx => $ruangId) {
                if (!$ruangId) {
                    continue;
                }
                $ruangModel = $ruangMap[$ruangId] ?? null;
                if (!$ruangModel) {
                    continue;
                }

                $jumlahUnit = (int) ($distribusiJumlah[$idx] ?? 0);
                $catatan = trim((string) ($distribusiCatatan[$idx] ?? ''));
                $distribusiData[] = [
                    'ruang' => $ruangModel,
                    'jumlah' => max(0, $jumlahUnit),
                    'catatan' => $catatan !== '' ? $catatan : null,
                ];
            }
        }

        DB::transaction(function () use ($validated, $distribusiData, $barangJenis) {
            $barangLocked = Barang::lockForUpdate()->findOrFail($validated['idbarang']);
            $barangMasuk = BarangMasuk::create($validated);
            $barangLocked->increment('stok', $validated['jumlah']);

            if ($barangJenis === 'tetap' && !empty($distribusiData)) {
                $records = [];

                foreach ($distribusiData as $row) {
                    $lastNomor = BarangUnit::where('idbarang', $barangLocked->idbarang)
                        ->where('idruang', $row['ruang']->idruang)
                        ->lockForUpdate()
                        ->max('nomor_unit') ?? 0;

                        for ($i = 1; $i <= $row['jumlah']; $i++) {
                            $nomor = $lastNomor + $i;
                            $records[] = [
                                'idbarang' => $barangLocked->idbarang,
                                'idruang' => $row['ruang']->idruang,
                                'barang_masuk_id' => $barangMasuk->idbarang_masuk,
                                'nomor_unit' => $nomor,
                                'kode_unit' => KodeInventarisGenerator::make($barangLocked, $row['ruang'], $nomor),
                                'keterangan' => $row['catatan'],
                                'created_at' => now(),
                                'updated_at' => now(),
                        ];
                    }
                }

                if (!empty($records)) {
                    BarangUnit::insert($records);
                }
            }
        });

        return redirect()->route($routePrefix . '.barang_masuk.index')->with('success', 'Data barang masuk berhasil ditambahkan');
    }

    public function edit(BarangMasuk $barangMasuk)
    {
        $barang = Barang::with('kategori')->orderBy('nama_barang')->get();
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $ruang = Ruang::orderBy('nama_ruang')->get();

        $distribusi = collect();
        $barangJenis = $barangMasuk->barang->jenis_barang ?? $barangMasuk->jenis_barang;
        if ($barangJenis === 'tetap') {
            // Ambil distribusi hanya untuk entry barang_masuk ini
            $distribusi = BarangUnit::select('idruang', DB::raw('COUNT(*) as jumlah'), DB::raw('MIN(keterangan) as catatan'))
                ->where('idbarang', $barangMasuk->idbarang)
                ->where(function ($q) use ($barangMasuk) {
                    $q->where('barang_masuk_id', $barangMasuk->idbarang_masuk);
                })
                ->groupBy('idruang')
                ->get()
                ->map(function ($row) {
                    return [
                        'idruang' => $row->idruang,
                        'jumlah' => (int)$row->jumlah,
                        'catatan' => $row->catatan,
                    ];
                });
        }

        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';

        return view('pegawai.barang_masuk.edit', compact('barangMasuk', 'barang', 'kategori', 'ruang', 'distribusi', 'routePrefix'));
    }

    public function update(BarangMasukRequest $request, BarangMasuk $barangMasuk)
    {
        $routePrefix = auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
        $validator = Validator::make($request->all(), [
            'idbarang'    => 'required|exists:barang,idbarang',
            'tgl_masuk'   => 'required|date',
            'jumlah'      => 'required|integer|min:1',
            'status_barang' => 'required|in:baru,bekas',
            'jenis_barang' => 'nullable|in:pinjam,tetap',
            'keterangan'  => 'nullable|string|max:500',
            'merk'        => 'nullable|string|max:120',
            'is_pc'       => 'nullable|boolean',
            'ram_brand'   => 'nullable|string|max:100',
            'ram_capacity_gb' => 'nullable|integer|min:1|max:1024',
            'storage_type' => 'nullable|in:SSD,HDD',
            'storage_capacity_gb' => 'nullable|integer|min:1|max:10000',
            'processor'   => 'nullable|string|max:150',
            'monitor_brand' => 'nullable|string|max:120',
            'monitor_model' => 'nullable|string|max:150',
            'monitor_size_inch' => 'nullable|numeric|min:10|max:60',
            'distribusi_ruang' => 'array',
            'distribusi_ruang.*' => 'nullable|integer|exists:ruang,idruang',
            'distribusi_jumlah' => 'array',
            'distribusi_jumlah.*' => 'nullable|integer|min:1|max:500',
            'distribusi_catatan' => 'array',
            'distribusi_catatan.*' => 'nullable|string|max:255',
        ]);

        $barang = Barang::with('kategori')->find($request->idbarang);
        $kategoriNama = strtolower(optional($barang?->kategori)->nama_kategori ?? '');
        $requiresPcSpec = $request->boolean('is_pc') || str_contains($kategoriNama, 'pc');
        $barangJenis = $request->input('jenis_barang', $barang->jenis_barang ?? 'pinjam');
        $distribusiRuang = $request->input('distribusi_ruang', []);
        $distribusiJumlah = $request->input('distribusi_jumlah', []);
        $distribusiCatatan = $request->input('distribusi_catatan', []);

        $validator->after(function ($validator) use ($request, $requiresPcSpec, $barangJenis, $distribusiRuang, $distribusiJumlah) {
            if ($requiresPcSpec) {
                $missingSpec = ! $request->filled('ram_capacity_gb')
                    || ! $request->filled('storage_type')
                    || ! $request->filled('storage_capacity_gb')
                    || ! $request->filled('processor');

                if ($missingSpec) {
                    $validator->errors()->add('spesifikasi', 'Spesifikasi PC (RAM, storage, dan prosesor) wajib diisi untuk perangkat PC.');
                }

                if (! $request->filled('monitor_size_inch')) {
                    $validator->errors()->add('monitor_size_inch', 'Ukuran monitor (inci) wajib diisi untuk perangkat PC.');
                }
            }

            if ($barangJenis === 'tetap') {
                $rows = collect($distribusiRuang)->filter()->keys();
                if ($rows->isEmpty()) {
                    $validator->errors()->add('distribusi_ruang', 'Distribusi ruang wajib diisi untuk barang tetap.');
                    return;
                }

                $totalDistribusi = 0;
                foreach ($rows as $idx) {
                    $jumlah = (int) ($distribusiJumlah[$idx] ?? 0);
                    if ($jumlah < 1) {
                        $validator->errors()->add('distribusi_jumlah.' . $idx, 'Jumlah unit per ruang minimal 1.');
                    }
                    $totalDistribusi += $jumlah;
                }

                $jumlahMasuk = (int) $request->input('jumlah', 0);
                if ($jumlahMasuk > 0 && $totalDistribusi !== $jumlahMasuk) {
                    $validator->errors()->add('distribusi_jumlah', 'Total jumlah distribusi harus sama dengan jumlah masuk.');
                }
            }
        });

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $validated['is_pc'] = $requiresPcSpec;
        $validated['tgl_masuk'] = $validated['tgl_masuk'] ?? ($barangMasuk->tgl_masuk ?? now()->toDateString());

        $distribusiData = [];
        if ($barangJenis === 'tetap') {
            $ruangIds = collect($distribusiRuang)->filter()->map(fn($id) => (int) $id)->all();
            $ruangMap = Ruang::whereIn('idruang', $ruangIds)->get()->keyBy('idruang');

            foreach ($distribusiRuang as $idx => $ruangId) {
                if (!$ruangId) {
                    continue;
                }
                $ruangModel = $ruangMap[$ruangId] ?? null;
                if (!$ruangModel) {
                    continue;
                }

                $jumlahUnit = (int) ($distribusiJumlah[$idx] ?? 0);
                $catatan = trim((string) ($distribusiCatatan[$idx] ?? ''));
                $distribusiData[] = [
                    'ruang' => $ruangModel,
                    'jumlah' => max(0, $jumlahUnit),
                    'catatan' => $catatan !== '' ? $catatan : null,
                ];
            }
        }

        DB::transaction(function () use ($barangMasuk, $validated, $barangJenis, $distribusiData) {
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

            if ($barangJenis === 'tetap' && !empty($distribusiData) && $originalBarangId === $validated['idbarang']) {
                $currentCounts = BarangUnit::where('idbarang', $barangMasuk->idbarang)
                    ->where(function ($q) use ($barangMasuk) {
                        $q->where('barang_masuk_id', $barangMasuk->idbarang_masuk);
                    })
                    ->select('idruang', DB::raw('COUNT(*) as jumlah'))
                    ->groupBy('idruang')
                    ->get()
                    ->keyBy('idruang');

                // Hapus unit yang berlebih atau ruang yang dihilangkan
                foreach ($currentCounts as $idruang => $row) {
                    $desired = collect($distribusiData)->firstWhere('ruang.idruang', $idruang);
                    $desiredCount = $desired['jumlah'] ?? 0;
                    if ($row->jumlah > $desiredCount) {
                        $toDelete = $row->jumlah - $desiredCount;
                        $deleteIds = BarangUnit::where('idbarang', $barangMasuk->idbarang)
                            ->where(function ($q) use ($barangMasuk) {
                                $q->where('barang_masuk_id', $barangMasuk->idbarang_masuk);
                            })
                            ->where('idruang', $idruang)
                            ->orderByDesc('id')
                            ->limit($toDelete)
                            ->pluck('id');
                        if ($deleteIds->isNotEmpty()) {
                            BarangUnit::whereIn('id', $deleteIds)->delete();
                        }
                    }
                }

                // Tambah atau perbarui unit sesuai distribusi baru
                foreach ($distribusiData as $row) {
                    $idruang = $row['ruang']->idruang;
                    $desiredCount = $row['jumlah'];
                    $currentCount = (int) ($currentCounts[$idruang]->jumlah ?? 0);

                    if ($desiredCount > $currentCount) {
                        $toAdd = $desiredCount - $currentCount;
                        $lastNomor = BarangUnit::where('idbarang', $barangMasuk->idbarang)
                            ->where('idruang', $idruang)
                            ->lockForUpdate()
                            ->max('nomor_unit') ?? 0;

                        $records = [];
                        for ($i = 1; $i <= $toAdd; $i++) {
                            $nomor = $lastNomor + $i;
                            $records[] = [
                                'idbarang' => $barangMasuk->idbarang,
                                'idruang' => $idruang,
                                'barang_masuk_id' => $barangMasuk->idbarang_masuk,
                                'nomor_unit' => $nomor,
                                'kode_unit' => KodeInventarisGenerator::make($newBarang, $row['ruang'], $nomor),
                                'keterangan' => $row['catatan'],
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                        BarangUnit::insert($records);
                    } elseif ($row['catatan']) {
                        BarangUnit::where('idbarang', $barangMasuk->idbarang)
                            ->where('idruang', $idruang)
                            ->update(['keterangan' => $row['catatan']]);
                    }
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
        try {
            if (!auth('web')->check()) {
                return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
            }

            // Antisipasi pemrosesan PDF dengan dataset besar
            ini_set('memory_limit', '512M');
            set_time_limit(60);

            $user = auth('web')->user();

            if (!$user || !in_array($user->role, ['pegawai', 'admin'])) {
                abort(403, 'Anda tidak memiliki akses.');
            }

            $barangMasukQuery = BarangMasuk::with(['barang.kategori', 'units.ruang'])->orderByDesc('idbarang_masuk');
            $start = request()->query('tgl_masuk_from') ?? request()->query('start_date');
            $end = request()->query('tgl_masuk_to') ?? request()->query('end_date');
            if ($start) {
                $barangMasukQuery->whereDate('tgl_masuk', '>=', $start);
            }
            if ($end) {
                $barangMasukQuery->whereDate('tgl_masuk', '<=', $end);
            }
            $barangMasuk = $barangMasukQuery->get();

            $pdf = Pdf::loadView('pegawai.barang_masuk.laporan', compact('barangMasuk'))
                      ->setPaper('A4', 'portrait');

            return $pdf->download('Laporan_Barang_Masuk.pdf');
        } catch (\Throwable $e) {
            Log::error('Gagal membuat laporan barang masuk', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return redirect()->back()->with('error', 'Gagal membuat laporan: ' . $e->getMessage());
        }
    }
}
