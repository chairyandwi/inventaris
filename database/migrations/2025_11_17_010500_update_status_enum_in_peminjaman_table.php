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
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending','disetujui','dipinjam','dikembalikan','ditolak') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE peminjaman MODIFY COLUMN status ENUM('pending','dipinjam','dikembalikan','ditolak') DEFAULT 'pending'");
    }
};
