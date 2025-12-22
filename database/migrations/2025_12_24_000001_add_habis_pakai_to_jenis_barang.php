<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("UPDATE barang SET jenis_barang = 'pinjam' WHERE jenis_barang IS NULL");
        DB::statement("ALTER TABLE barang MODIFY jenis_barang ENUM('pinjam','tetap','habis_pakai') NOT NULL DEFAULT 'pinjam'");
        DB::statement("ALTER TABLE barang_masuk MODIFY jenis_barang ENUM('pinjam','tetap','habis_pakai') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE barang SET jenis_barang = 'pinjam' WHERE jenis_barang IS NULL");
        DB::statement("ALTER TABLE barang MODIFY jenis_barang ENUM('pinjam','tetap') NOT NULL DEFAULT 'pinjam'");
        DB::statement("ALTER TABLE barang_masuk MODIFY jenis_barang ENUM('pinjam','tetap') NULL");
    }
};
