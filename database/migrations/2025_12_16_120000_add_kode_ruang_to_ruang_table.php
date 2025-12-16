<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ruang', function (Blueprint $table) {
            // Tambahkan kolom nullable dulu agar tidak gagal karena nilai lama belum unik
            $table->string('kode_ruang', 50)->after('nama_ruang')->nullable();
        });

        // Isi kode_ruang untuk data yang sudah ada agar unik
        $existing = DB::table('ruang')->select('idruang', 'nama_ruang')->get();
        foreach ($existing as $row) {
            $generated = Str::upper(Str::slug($row->nama_ruang ?: 'RUANG-'.$row->idruang, '-'));
            // Jika hasil kosong, fallback ke ID
            if (! $generated) {
                $generated = 'RUANG-'.$row->idruang;
            }
            // Tambahkan ID di belakang untuk memastikan unik
            $generated = $generated.'-'.$row->idruang;
            DB::table('ruang')
                ->where('idruang', $row->idruang)
                ->update(['kode_ruang' => $generated]);
        }

        // Setelah terisi unik, baru set unique constraint
        Schema::table('ruang', function (Blueprint $table) {
            $table->unique('kode_ruang', 'ruang_kode_ruang_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ruang', function (Blueprint $table) {
            $table->dropColumn('kode_ruang');
        });
    }
};
