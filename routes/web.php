<?php

use App\Models\User;
use App\Models\Ruang;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\BarangMasuk;
use App\Models\Peminjaman;
use App\Models\AppConfiguration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RuangController;
use App\Http\Controllers\BarangController;
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
Route::middleware(['auth', 'role:pegawai,admin'])->prefix('pegawai')->name('pegawai.')->group(function () {
    Route::get('/', function () {
        $barangKeluar = 10;
        $barangMasuk = BarangMasuk::count();
        $barangPinjam = 6;
        $user = User::count();
        $barang = Barang::count();
        $ruang = Ruang::count();
        $kategori = Kategori::count();
        return view('pegawai.index', compact(
            'barangKeluar', 'barangMasuk', 'barangPinjam',
            'user', 'barang', 'ruang', 'kategori'
        ));
    })->name('index');

    // Laporan
    Route::get('barang/laporan', [BarangController::class, 'laporan'])->name('barang.laporan');
    Route::get('barang_masuk/laporan', [BarangMasukController::class, 'laporan'])->name('barang_masuk.laporan');
    Route::get('peminjaman/laporan', [PeminjamanController::class, 'laporan'])->name('peminjaman.laporan');
    Route::get('peminjaman/cetak', [PeminjamanController::class, 'cetak'])->name('peminjaman.cetak');


    // Master Data
    Route::resource('kategori', KategoriController::class);
    Route::resource('ruang', RuangController::class);
    Route::resource('user', UserController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('barang_masuk', BarangMasukController::class);
    Route::resource('inventaris-ruang', InventarisRuangController::class)->only(['index', 'create', 'store', 'destroy']);

    // Manajemen peminjaman (pegawai approve/reject/return)
    Route::resource('peminjaman', PeminjamanController::class);
    Route::post('peminjaman/{id}/approve', [PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('peminjaman/{id}/reject', [PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('peminjaman/{id}/pickup', [PeminjamanController::class, 'pickup'])->name('peminjaman.pickup');
    Route::post('peminjaman/{id}/return', [PeminjamanController::class, 'return'])->name('peminjaman.return');
});

/*
|--------------------------------------------------------------------------
| Route untuk Peminjam
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/', function () {
        $userId = Auth::id();

        $totalPeminjaman = Peminjaman::where('iduser', $userId)->count();
        $pendingPeminjaman = Peminjaman::where('iduser', $userId)->where('status', 'pending')->count();
        $dipinjamPeminjaman = Peminjaman::where('iduser', $userId)->where('status', 'dipinjam')->count();
        $selesaiPeminjaman = Peminjaman::where('iduser', $userId)->where('status', 'dikembalikan')->count();
        $ditolakPeminjaman = Peminjaman::where('iduser', $userId)->where('status', 'ditolak')->count();
        $barangTersedia = Barang::count();
        $ruangTersedia = Ruang::count();

        return view('peminjam.index', compact(
            'totalPeminjaman',
            'pendingPeminjaman',
            'dipinjamPeminjaman',
            'selesaiPeminjaman',
            'ditolakPeminjaman',
            'barangTersedia',
            'ruangTersedia'
        ));
    })->name('index');

    // Ajukan & lihat status peminjaman
    Route::resource('peminjaman', PeminjamanController::class)->only(['index', 'create', 'store', 'show']);
});

/*
|--------------------------------------------------------------------------
| Route untuk Admin & Kabag (nanti bisa diisi)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        $barangKeluar = 10;
        $barangMasuk = BarangMasuk::count();
        $barangPinjam = Peminjaman::where('status', 'dipinjam')->count();
        $user = User::count();
        $barang = Barang::count();
        $ruang = Ruang::count();
        $kategori = Kategori::count();

        $pesanAktivitas = \App\Models\LogAktivitas::latest()->take(5)->get();

        return view('admin.index', compact(
            'barangKeluar', 'barangMasuk', 'barangPinjam',
            'user', 'barang', 'ruang', 'kategori', 'pesanAktivitas'
        ));
    })->name('index');

    Route::resource('kategori', KategoriController::class);
    Route::resource('ruang', RuangController::class);
    Route::resource('user', UserController::class);
    Route::resource('barang', BarangController::class);
    Route::resource('barang_masuk', BarangMasukController::class);
    Route::resource('inventaris-ruang', InventarisRuangController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::resource('peminjaman', PeminjamanController::class);
    Route::get('peminjaman/laporan', [PeminjamanController::class, 'laporan'])->name('peminjaman.laporan');
    Route::get('peminjaman/cetak', [PeminjamanController::class, 'cetak'])->name('peminjaman.cetak');

    Route::get('aplikasi', [AppConfigController::class, 'index'])->name('aplikasi.index');
    Route::put('aplikasi', [AppConfigController::class, 'update'])->name('aplikasi.update');
    Route::get('logs', [LogAktivitasController::class, 'index'])->name('logs.index');
});

Route::middleware(['auth', 'role:kabag'])->prefix('kabag')->name('kabag.')->group(function () {
    Route::get('/', function () {
        return view('kabag.index');
    })->name('index');
});

require __DIR__ . '/auth.php';
