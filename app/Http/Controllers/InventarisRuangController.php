<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangUnit;
use App\Models\Ruang;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Support\KodeInventarisGenerator;
use Illuminate\Http\Request;

class InventarisRuangController extends Controller
{
    public function index(Request $request)
    {
        $query = BarangUnit::with(['barang', 'ruang'])
            ->orderBy('kode_unit');

        if ($request->filled('idruang')) {
            $query->where('idruang', $request->idruang);
        }

        if ($request->filled('idbarang')) {
            $query->where('idbarang', $request->idbarang);
        }

        $units = $query->paginate(15)->appends($request->only('idruang', 'idbarang'));
        $ruang = Ruang::orderBy('nama_ruang')->get();
        $barang = Barang::orderBy('nama_barang')->get();

        return view('pegawai.inventaris_ruang.index', compact('units', 'ruang', 'barang'));
    }

    public function create()
    {
        $barang = Barang::where('jenis_barang', 'tetap')->orderBy('nama_barang')->get();
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

        $query = BarangUnit::with(['barang.kategori', 'ruang'])
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

        $units = $query->get();

        $pdf = Pdf::loadView('pegawai.inventaris_ruang.laporan', compact('units'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Inventaris_Ruang.pdf');
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

        if ($barang->jenis_barang !== 'tetap') {
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

        return redirect()->route('pegawai.inventaris-ruang.index')
            ->with('success', $request->jumlah . ' unit barang berhasil dicatat untuk ruang ini.');
    }

    public function destroy(BarangUnit $inventaris_ruang)
    {
        $inventaris_ruang->delete();

        return redirect()->route('pegawai.inventaris-ruang.index')
            ->with('success', 'Unit barang berhasil dihapus.');
    }

}
