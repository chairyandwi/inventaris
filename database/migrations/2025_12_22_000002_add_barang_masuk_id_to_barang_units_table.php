<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barang_units', function (Blueprint $table) {
            $table->foreignId('barang_masuk_id')->nullable()->after('idruang')->constrained('barang_masuk', 'idbarang_masuk')->nullOnDelete();
            $table->index(['barang_masuk_id'], 'barang_units_barang_masuk_id_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_units', function (Blueprint $table) {
            $table->dropForeign(['barang_masuk_id']);
            $table->dropIndex('barang_units_barang_masuk_id_index');
            $table->dropColumn('barang_masuk_id');
        });
    }
};
