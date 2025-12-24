<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKeluar;
use App\Models\BarangMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Support\ActivityLogger;

class BarangHabisPakaiController extends Controller
{
    private function getRoutePrefix(): string
    {
        return auth()->check() && auth()->user()->role === 'admin' ? 'admin' : 'pegawai';
    }

    public function index(Request $request)
    {
        $query = Barang::whereHas('barangMasuk', function ($q) {
                $q->where('jenis_barang', 'habis_pakai');
            })
            ->with('kategori');
        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($q) use ($term) {
                $q->where('kode_barang', 'like', "%{$term}%")
                    ->orWhere('nama_barang', 'like', "%{$term}%")
                    ->orWhere('keterangan', 'like', "%{$term}%");
            });
        }

        $barang = $query->orderBy('nama_barang')
            ->paginate(12, ['*'], 'barang_page')
            ->appends($request->only('search'));
        $stats = [
            'totalJenis' => BarangMasuk::where('jenis_barang', 'habis_pakai')->distinct('idbarang')->count('idbarang'),
            'totalStok' => Barang::whereHas('barangMasuk', function ($q) {
                $q->where('jenis_barang', 'habis_pakai');
            })->sum('stok'),
        ];

        $routePrefix = $this->getRoutePrefix();

        return view('pegawai.barang_habis_pakai.index', compact('barang', 'stats', 'routePrefix'));
    }

    public function requestIndex(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && in_array($user->role, ['admin', 'pegawai']), 403);

        $pending = BarangKeluar::with(['barang', 'user'])
            ->where('status', 'pending')
            ->orderByDesc('tgl_keluar')
            ->orderByDesc('idbarang_keluar')
            ->paginate(10, ['*'], 'pending_page');

        $riwayatQuery = BarangKeluar::with(['barang', 'user'])
            ->whereIn('status', ['approved', 'rejected']);

        if ($request->filled('riwayat_status') && $request->riwayat_status !== 'all') {
            $riwayatQuery->where('status', $request->riwayat_status);
        }

        if ($request->filled('riwayat_from')) {
            $riwayatQuery->whereDate('tgl_keluar', '>=', $request->riwayat_from);
        }

        if ($request->filled('riwayat_to')) {
            $riwayatQuery->whereDate('tgl_keluar', '<=', $request->riwayat_to);
        }

        if ($request->filled('riwayat_search')) {
            $term = $request->riwayat_search;
            $riwayatQuery->where(function ($query) use ($term) {
                $query->whereHas('barang', function ($q) use ($term) {
                    $q->where('kode_barang', 'like', "%{$term}%")
                        ->orWhere('nama_barang', 'like', "%{$term}%");
                })->orWhereHas('user', function ($q) use ($term) {
                    $q->where('nama', 'like', "%{$term}%")
                        ->orWhere('username', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            });
        }

        $riwayat = $riwayatQuery
            ->orderByDesc('tgl_keluar')
            ->orderByDesc('idbarang_keluar')
            ->paginate(10, ['*'], 'riwayat_page')
            ->appends($request->only(['riwayat_status', 'riwayat_from', 'riwayat_to', 'riwayat_search']));

        $stats = [
            'totalPending' => BarangKeluar::where('status', 'pending')->count(),
            'totalApproved' => BarangKeluar::where('status', 'approved')->count(),
            'totalRejected' => BarangKeluar::where('status', 'rejected')->count(),
            'totalKeluar' => BarangKeluar::where('status', 'approved')->sum('jumlah'),
        ];

        $routePrefix = $this->getRoutePrefix();

        return view('pegawai.barang_habis_pakai.request', compact('pending', 'riwayat', 'stats', 'routePrefix'));
    }

    public function show($id)
    {
        $user = Auth::user();
        abort_unless($user && in_array($user->role, ['admin', 'pegawai']), 403);

        $barangKeluar = BarangKeluar::with(['barang', 'user'])->findOrFail($id);
        $routePrefix = $this->getRoutePrefix();

        $fotoIdentitas = null;
        if ($barangKeluar->user) {
            $fotoIdentitas = $barangKeluar->user->tipe_peminjam === 'mahasiswa'
                ? $barangKeluar->user->foto_identitas_mahasiswa
                : $barangKeluar->user->foto_identitas_pegawai;
        }

        return view('pegawai.barang_habis_pakai.show', compact('barangKeluar', 'routePrefix', 'fotoIdentitas'));
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

        $query = BarangKeluar::with(['barang', 'user']);

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tgl_keluar', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('tgl_keluar', '<=', $request->end_date);
        }

        if ($request->filled('search')) {
            $term = $request->search;
            $query->where(function ($sub) use ($term) {
                $sub->whereHas('barang', function ($q) use ($term) {
                    $q->where('kode_barang', 'like', "%{$term}%")
                        ->orWhere('nama_barang', 'like', "%{$term}%");
                })->orWhereHas('user', function ($q) use ($term) {
                    $q->where('nama', 'like', "%{$term}%")
                        ->orWhere('username', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%");
                });
            });
        }

        $riwayat = $query->orderByDesc('tgl_keluar')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pegawai.barang_habis_pakai.laporan', compact('riwayat'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Barang_Keluar.pdf');
    }

    public function peminjamIndex()
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'peminjam', 403);

        $profilLengkap = $user->isProfileComplete();

        $barang = Barang::whereHas('barangMasuk', function ($q) {
                $q->where('jenis_barang', 'habis_pakai');
            })
            ->where('stok', '>', 0)
            ->orderBy('nama_barang')
            ->get();

        $requests = BarangKeluar::with('barang')
            ->where('iduser', $user->id)
            ->where('status', 'pending')
            ->orderByDesc('tgl_keluar')
            ->orderByDesc('idbarang_keluar')
            ->paginate(10, ['*'], 'request_page');

        $riwayat = BarangKeluar::with('barang')
            ->where('iduser', $user->id)
            ->whereIn('status', ['approved', 'rejected'])
            ->orderByDesc('tgl_keluar')
            ->orderByDesc('idbarang_keluar')
            ->paginate(10, ['*'], 'riwayat_page');

        return view('peminjam.barang_habis_pakai.index', compact('barang', 'requests', 'riwayat', 'profilLengkap'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        abort_unless($user && $user->role === 'peminjam', 403);

        if (!$user->isProfileComplete()) {
            return redirect()->route('peminjam.profile.edit')
                ->with('error', 'Lengkapi profil terlebih dahulu sebelum melakukan request barang.');
        }

        $validated = $request->validate([
            'idbarang' => ['required', 'exists:barang,idbarang'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'tgl_pengambilan_rencana' => ['required', 'date'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $result = DB::transaction(function () use ($validated, $user) {
            $barang = Barang::where('idbarang', $validated['idbarang'])
                ->whereHas('barangMasuk', function ($q) {
                    $q->where('jenis_barang', 'habis_pakai');
                })
                ->lockForUpdate()
                ->first();

            if (!$barang) {
                return ['error' => 'Barang tidak tersedia untuk habis pakai.'];
            }

            if ($barang->stok < $validated['jumlah']) {
                return ['error' => 'Stok barang tidak mencukupi.'];
            }

            $keluar = BarangKeluar::create([
                'idbarang' => $barang->idbarang,
                'iduser' => $user->id,
                'tgl_keluar' => now()->toDateString(),
                'tgl_pengambilan_rencana' => $validated['tgl_pengambilan_rencana'],
                'jumlah' => $validated['jumlah'],
                'keterangan' => $validated['keterangan'] ?? null,
                'status' => 'pending',
            ]);

            return [
                'success' => true,
                'barang_nama' => $barang->nama_barang ?? '-',
                'jumlah' => $keluar->jumlah,
            ];
        });

        if (!empty($result['error'])) {
            return back()->with('error', $result['error'])->withInput();
        }

        ActivityLogger::log(
            'Request Barang Habis Pakai',
            'Mengajukan request barang: ' . ($result['barang_nama'] ?? '-') . ' (' . ($result['jumlah'] ?? 0) . ' unit).'
        );

        return back()->with('success', 'Permintaan barang habis pakai berhasil dikirim dan menunggu persetujuan.');
    }

    public function approve($id)
    {
        $user = Auth::user();
        abort_unless($user && in_array($user->role, ['admin', 'pegawai']), 403);

        $result = DB::transaction(function () use ($id, $user) {
            $keluar = BarangKeluar::with('barang')->lockForUpdate()->findOrFail($id);

            if ($keluar->status !== 'pending') {
                return ['error' => 'Permintaan ini sudah diproses sebelumnya.'];
            }

            $barang = Barang::where('idbarang', $keluar->idbarang)
                ->whereHas('barangMasuk', function ($q) {
                    $q->where('jenis_barang', 'habis_pakai');
                })
                ->lockForUpdate()
                ->first();

            if (!$barang) {
                return ['error' => 'Barang habis pakai tidak ditemukan.'];
            }

            if ($barang->stok < $keluar->jumlah) {
                return ['error' => 'Stok barang tidak mencukupi untuk menyetujui permintaan ini.'];
            }

            $keluar->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'approved_at' => now(),
                'rejected_by' => null,
                'rejected_at' => null,
                'alasan_penolakan' => null,
            ]);

            $barang->decrement('stok', $keluar->jumlah);

            return [
                'success' => true,
                'nama_barang' => $keluar->barang?->nama_barang,
                'jumlah' => $keluar->jumlah,
            ];
        });

        if (!empty($result['error'])) {
            return back()->with('error', $result['error']);
        }

        ActivityLogger::log(
            'Setujui Request Barang',
            'Menyetujui request barang: ' . ($result['nama_barang'] ?? '-') . ' (' . ($result['jumlah'] ?? 0) . ' unit).'
        );

        return back()->with('success', 'Permintaan barang habis pakai disetujui.');
    }

    public function receive($id)
    {
        $user = Auth::user();
        abort_unless($user && in_array($user->role, ['admin', 'pegawai']), 403);

        $keluar = BarangKeluar::findOrFail($id);

        if ($keluar->status !== 'approved') {
            return back()->with('error', 'Permintaan belum disetujui atau sudah ditolak.');
        }

        if ($keluar->tgl_diterima) {
            return back()->with('error', 'Permintaan ini sudah ditandai diterima.');
        }

        $keluar->update([
            'tgl_diterima' => now(),
        ]);

        ActivityLogger::log(
            'Barang Diterima',
            'Menandai barang diterima untuk request ID ' . $keluar->idbarang_keluar . '.'
        );

        return back()->with('success', 'Permintaan barang habis pakai ditandai sudah diterima.');
    }

    public function reject(Request $request, $id)
    {
        $user = Auth::user();
        abort_unless($user && in_array($user->role, ['admin', 'pegawai']), 403);

        $validated = $request->validate([
            'alasan_penolakan' => ['required', 'string', 'max:255'],
        ]);

        $keluar = BarangKeluar::findOrFail($id);

        if ($keluar->status !== 'pending') {
            return back()->with('error', 'Permintaan ini sudah diproses sebelumnya.');
        }

        $keluar->update([
            'status' => 'rejected',
            'rejected_by' => $user->id,
            'rejected_at' => now(),
            'alasan_penolakan' => $validated['alasan_penolakan'],
        ]);

        ActivityLogger::log(
            'Tolak Request Barang',
            'Menolak request barang ID ' . $keluar->idbarang_keluar . '.'
        );

        return back()->with('success', 'Permintaan barang habis pakai ditolak.');
    }
}
