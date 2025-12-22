<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->date('tgl_pengambilan_rencana')->nullable()->after('tgl_keluar');
            $table->timestamp('tgl_diterima')->nullable()->after('tgl_pengambilan_rencana');
        });
    }

    public function down(): void
    {
        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropColumn(['tgl_pengambilan_rencana', 'tgl_diterima']);
        });
    }
};
