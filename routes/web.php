<?php

use App\Models\User;
use App\Models\Ruang;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use App\Models\Peminjaman;
use App\Models\BarangKeluar;
use App\Models\BarangPinjamUsage;
use App\Models\BarangUnitKerusakan;
use App\Models\AppConfiguration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangPinjamController;
use App\Http\Controllers\BarangHabisPakaiController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\InventarisRuangController;
use App\Http\Controllers\Admin\AppConfigController;
use App\Http\Controllers\Admin\LogAktivitasController;
use App\Models\LogAktivitas;

Route::get('/', function () {
    if (Auth::check()) {
        switch (Auth::user()->role) {
            case 'admin':
                return redirect()->route('admin.index');
            case 'kabag':
                return redirect()->route('kabag.index');
            case 'pegawai':
                return redirect()->route('pegawai.index');
            case 'peminjam':
                return redirect()->route('peminjam.index');
        }
    }
    return view('home');
});

Route::get('/dashboard', function () {
    return redirect('/');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profile user
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Route untuk Pegawai
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:pegawai'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/', function () {
        $barangMasuk = BarangMasuk::count();
        $peminjamanPending = Peminjaman::where('status', 'pending')->count();
        $peminjamanDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();
        $requestBarangPending = \App\Models\BarangKeluar::where('status', 'pending')->count();
        $user = User::count();
        $barang = Barang::count();
        $ruang = Ruang::count();
        $kategori = Kategori::count();
        return view('pegawai.index', compact(
            'barangMasuk', 'peminjamanPending', 'peminjamanDipinjam', 'peminjamanDikembalikan',
            'requestBarangPending',
            'user', 'barang', 'ruang', 'kategori'
        ));
    })->name('index');

    // Laporan
    Route::get('barang/laporan', [BarangController::class, 'laporan'])->name('barang.laporan');
    Route::get('barang_masuk/laporan', [BarangMasukController::class, 'laporan'])->name('barang_masuk.laporan');
    Route::get('peminjaman/laporan', [PeminjamanController::class, 'laporan'])->name('peminjaman.laporan');
    Route::get('peminjaman/cetak', [PeminjamanController::class, 'cetak'])->name('peminjaman.cetak');
    Route::get('inventaris-ruang/laporan', [InventarisRuangController::class, 'laporan'])->name('inventaris-ruang.laporan');

    Route::get('barang-habis-pakai', [BarangHabisPakaiController::class, 'index'])->name('barang-habis-pakai.index');
    Route::get('barang-habis-pakai/request', [BarangHabisPakaiController::class, 'requestIndex'])->name('barang-habis-pakai.request');
    Route::get('barang-habis-pakai/riwayat/{id}', [BarangHabisPakaiController::class, 'show'])->name('barang-habis-pakai.show');
    Route::get('barang-habis-pakai/laporan', [BarangHabisPakaiController::class, 'laporan'])->name('barang-habis-pakai.laporan');
    Route::post('barang-habis-pakai/{id}/approve', [BarangHabisPakaiController::class, 'approve'])->name('barang-habis-pakai.approve');
    Route::post('barang-habis-pakai/{id}/receive', [BarangHabisPakaiController::class, 'receive'])->name('barang-habis-pakai.receive');
    Route::post('barang-habis-pakai/{id}/reject', [BarangHabisPakaiController::class, 'reject'])->name('barang-habis-pakai.reject');
    Route::get('barang-pinjam', [BarangPinjamController::class, 'index'])->name('barang-pinjam.index');
    Route::post('barang-pinjam/{barang}/usage', [BarangPinjamController::class, 'storeUsage'])->name('barang-pinjam.usage');
    Route::get('barang-pinjam/laporan', [BarangPinjamController::class, 'laporan'])->name('barang-pinjam.laporan');

    // Master Data
    Route::resource('kategori', KategoriController::class);
    Route::resource('ruang', RuangController::class);
    Route::resource('user', UserController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('barang_masuk', BarangMasukController::class);
    Route::resource('inventaris-ruang', InventarisRuangController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::get('inventaris-ruang/riwayat', [InventarisRuangController::class, 'moveHistory'])->name('inventaris-ruang.riwayat');
    Route::get('inventaris-ruang/riwayat/cetak', [InventarisRuangController::class, 'moveHistoryPdf'])->name('inventaris-ruang.riwayat.cetak');
    Route::post('inventaris-ruang/{inventaris_ruang}/mark-rusak', [InventarisRuangController::class, 'markRusak'])->name('inventaris-ruang.mark-rusak');
    Route::post('inventaris-ruang/{inventaris_ruang}/move', [InventarisRuangController::class, 'moveRuang'])->name('inventaris-ruang.move');
    Route::patch('inventaris-ruang/{inventaris_ruang}/kerusakan', [InventarisRuangController::class, 'updateKerusakan'])->name('inventaris-ruang.update-kerusakan');
    Route::delete('inventaris-ruang/{inventaris_ruang}/kerusakan', [InventarisRuangController::class, 'restoreKerusakan'])->name('inventaris-ruang.restore-kerusakan');

    // Manajemen peminjaman (pegawai approve/reject/return)
    Route::get('peminjaman/laporan', [PeminjamanController::class, 'laporan'])->name('peminjaman.laporan');
    Route::get('peminjaman/cetak', [PeminjamanController::class, 'cetak'])->name('peminjaman.cetak');
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('peminjaman/{id}/pickup', [PeminjamanController::class, 'pickup'])->name('peminjaman.pickup');
    Route::post('peminjaman/{id}/return', [PeminjamanController::class, 'return'])->name('peminjaman.return');

    // Data Peminjam
    Route::get('peminjam/data', [PegawaiController::class, 'peminjamData'])->name('peminjam.data');
});

/*
|--------------------------------------------------------------------------
| Route untuk Peminjam
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/', function () {
        $userId = Auth::id();
        $user = Auth::user();
        $missingFields = $user ? $user->missingProfileFields() : [];
        $profilLengkap = $user ? $user->isProfileComplete() : false;

        $totalPeminjaman = Peminjaman::where('iduser', $userId)->count();
        $pendingPeminjaman = Peminjaman::where('iduser', $userId)->where('status', 'pending')->count();
        $dipinjamPeminjaman = Peminjaman::where('iduser', $userId)->where('status', 'dipinjam')->count();
        $selesaiPeminjaman = Peminjaman::where('iduser', $userId)->where('status', 'dikembalikan')->count();
        $ditolakPeminjaman = Peminjaman::where('iduser', $userId)->where('status', 'ditolak')->count();
        $rusakCounts = BarangUnitKerusakan::where('status', 'rusak')
            ->join('barang_units', 'barang_unit_kerusakan.barang_unit_id', '=', 'barang_units.id')
            ->select('barang_units.idbarang', DB::raw('COUNT(*) as jumlah'))
            ->groupBy('barang_units.idbarang')
            ->pluck('jumlah', 'idbarang');
        $pinjamMasuk = BarangMasuk::where('jenis_barang', 'pinjam')
            ->select('idbarang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('idbarang')
            ->pluck('total', 'idbarang');
        $pinjamTerpakai = Peminjaman::whereIn('status', ['pending', 'disetujui', 'dipinjam'])
            ->select('idbarang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('idbarang')
            ->pluck('total', 'idbarang');
        $pinjamDigunakan = BarangPinjamUsage::where('digunakan_sampai', '>', now())
            ->select('idbarang', DB::raw('SUM(jumlah) as total'))
            ->groupBy('idbarang')
            ->pluck('total', 'idbarang');
        $barangPinjamAvailable = Barang::whereHas('barangMasuk', function ($q) {
            $q->where('jenis_barang', 'pinjam');
        })
            ->where('kondisi_pinjam', 'tersedia')
            ->with('kategori')
            ->get()
            ->filter(function ($item) use ($rusakCounts, $pinjamMasuk, $pinjamTerpakai, $pinjamDigunakan) {
                $rusak = $rusakCounts[$item->idbarang] ?? 0;
                $totalPinjam = $pinjamMasuk[$item->idbarang] ?? 0;
                $terpakai = $pinjamTerpakai[$item->idbarang] ?? 0;
                $digunakan = $pinjamDigunakan[$item->idbarang] ?? 0;
                return ($totalPinjam - $terpakai - $rusak - $digunakan) > 0;
            })
            ->map(function ($item) use ($rusakCounts, $pinjamMasuk, $pinjamTerpakai, $pinjamDigunakan) {
                $rusak = $rusakCounts[$item->idbarang] ?? 0;
                $totalPinjam = $pinjamMasuk[$item->idbarang] ?? 0;
                $terpakai = $pinjamTerpakai[$item->idbarang] ?? 0;
                $digunakan = $pinjamDigunakan[$item->idbarang] ?? 0;
                $item->available_stok = max(($totalPinjam - $terpakai - $rusak - $digunakan), 0);
                return $item;
            })
            ->sortByDesc('available_stok')
            ->values();
        $barangTersedia = $barangPinjamAvailable->count();
        $barangPinjamList = $barangPinjamAvailable->take(6);
        $barangHabisPakaiList = Barang::whereHas('barangMasuk', function ($q) {
            $q->where('jenis_barang', 'habis_pakai');
        })
            ->where('stok', '>', 0)
            ->with('kategori')
            ->orderByDesc('stok')
            ->orderBy('nama_barang')
            ->take(6)
            ->get();
        $requestHabisPakaiTotal = BarangKeluar::where('iduser', $userId)->count();
        $requestHabisPakaiUnit = BarangKeluar::where('iduser', $userId)->sum('jumlah');
        $requestHabisPakaiBulan = BarangKeluar::where('iduser', $userId)
            ->whereYear('tgl_keluar', now()->year)
            ->whereMonth('tgl_keluar', now()->month)
            ->count();

        return view('peminjam.index', compact(
            'totalPeminjaman',
            'pendingPeminjaman',
            'dipinjamPeminjaman',
            'selesaiPeminjaman',
            'ditolakPeminjaman',
            'barangTersedia',
            'barangPinjamList',
            'barangHabisPakaiList',
            'requestHabisPakaiTotal',
            'requestHabisPakaiUnit',
            'requestHabisPakaiBulan',
            'profilLengkap',
            'missingFields'
        ));
    })->name('index');

    // Ajukan & lihat status peminjaman
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store', 'show']);
    Route::get('barang-habis-pakai', [BarangHabisPakaiController::class, 'peminjamIndex'])->name('barang-habis-pakai.index');
    Route::post('barang-habis-pakai', [BarangHabisPakaiController::class, 'store'])->name('barang-habis-pakai.store');

    // Profil peminjam (akses melalui prefix peminjam)
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Route untuk Admin & Kabag (nanti bisa diisi)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        $barangPinjam = Peminjaman::where('status', 'dipinjam')->count();
        $user = User::count();
        $barang = Barang::count();
        $ruang = Ruang::count();
        $kategori = Kategori::count();
        $peminjamanPending = Peminjaman::where('status', 'pending')->count();
        $peminjamanDipinjam = Peminjaman::where('status', 'dipinjam')->count();
        $peminjamanDikembalikan = Peminjaman::where('status', 'dikembalikan')->count();
        $peminjamanDitolak = Peminjaman::where('status', 'ditolak')->count();
        $barangMasuk = BarangMasuk::count();
        $requestBarangPending = \App\Models\BarangKeluar::where('status', 'pending')->count();

        $pesanAktivitas = \App\Models\LogAktivitas::latest()->take(5)->get();

        return view('admin.index', compact(
            'barangPinjam', 'barangMasuk',
            'user', 'barang', 'ruang', 'kategori', 'pesanAktivitas',
            'peminjamanPending', 'peminjamanDipinjam', 'peminjamanDikembalikan', 'peminjamanDitolak',
            'requestBarangPending'
        ));
    })->name('index');

    Route::resource('kategori', KategoriController::class);
    Route::resource('ruang', RuangController::class);
    Route::resource('user', UserController::class);
    Route::get('barang/laporan', [BarangController::class, 'laporan'])->name('barang.laporan');
    Route::get('barang_masuk/laporan', [BarangMasukController::class, 'laporan'])->name('barang_masuk.laporan');
    Route::get('barang-habis-pakai', [BarangHabisPakaiController::class, 'index'])->name('barang-habis-pakai.index');
    Route::get('barang-habis-pakai/request', [BarangHabisPakaiController::class, 'requestIndex'])->name('barang-habis-pakai.request');
    Route::get('barang-habis-pakai/riwayat/{id}', [BarangHabisPakaiController::class, 'show'])->name('barang-habis-pakai.show');
    Route::get('barang-habis-pakai/laporan', [BarangHabisPakaiController::class, 'laporan'])->name('barang-habis-pakai.laporan');
    Route::post('barang-habis-pakai/{id}/approve', [BarangHabisPakaiController::class, 'approve'])->name('barang-habis-pakai.approve');
    Route::post('barang-habis-pakai/{id}/receive', [BarangHabisPakaiController::class, 'receive'])->name('barang-habis-pakai.receive');
    Route::post('barang-habis-pakai/{id}/reject', [BarangHabisPakaiController::class, 'reject'])->name('barang-habis-pakai.reject');
    Route::get('barang-pinjam', [BarangPinjamController::class, 'index'])->name('barang-pinjam.index');
    Route::post('barang-pinjam/{barang}/usage', [BarangPinjamController::class, 'storeUsage'])->name('barang-pinjam.usage');
    Route::get('barang-pinjam/laporan', [BarangPinjamController::class, 'laporan'])->name('barang-pinjam.laporan');
    Route::resource('barang', BarangController::class);
    Route::resource('barang_masuk', BarangMasukController::class);
    Route::resource('inventaris-ruang', InventarisRuangController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::get('inventaris-ruang/riwayat', [InventarisRuangController::class, 'moveHistory'])->name('inventaris-ruang.riwayat');
    Route::get('inventaris-ruang/riwayat/cetak', [InventarisRuangController::class, 'moveHistoryPdf'])->name('inventaris-ruang.riwayat.cetak');
    Route::post('inventaris-ruang/{inventaris_ruang}/mark-rusak', [InventarisRuangController::class, 'markRusak'])->name('inventaris-ruang.mark-rusak');
    Route::post('inventaris-ruang/{inventaris_ruang}/move', [InventarisRuangController::class, 'moveRuang'])->name('inventaris-ruang.move');
    Route::patch('inventaris-ruang/{inventaris_ruang}/kerusakan', [InventarisRuangController::class, 'updateKerusakan'])->name('inventaris-ruang.update-kerusakan');
    Route::delete('inventaris-ruang/{inventaris_ruang}/kerusakan', [InventarisRuangController::class, 'restoreKerusakan'])->name('inventaris-ruang.restore-kerusakan');
    Route::get('peminjaman/laporan', [PeminjamanController::class, 'laporan'])->name('peminjaman.laporan');
    Route::get('peminjaman/cetak', [PeminjamanController::class, 'cetak'])->name('peminjaman.cetak');
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('peminjaman/{id}/pickup', [PeminjamanController::class, 'pickup'])->name('peminjaman.pickup');
    Route::post('peminjaman/{id}/return', [PeminjamanController::class, 'return'])->name('peminjaman.return');

    Route::get('aplikasi', [AppConfigController::class, 'index'])->name('aplikasi.index');
    Route::put('aplikasi', [AppConfigController::class, 'update'])->name('aplikasi.update');
    Route::get('logs', [LogAktivitasController::class, 'index'])->name('logs.index');
    Route::get('inventaris-ruang/laporan', [InventarisRuangController::class, 'laporan'])->name('inventaris-ruang.laporan');
});

Route::middleware(['auth', 'role:kabag'])->prefix('kabag')->name('kabag.')->group(function () {
    Route::get('/', function () {
        return view('kabag.index');
    })->name('index');
});

require __DIR__ . '/auth.php';
