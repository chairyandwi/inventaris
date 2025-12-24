<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_pinjam_usages', function (Blueprint $table) {
            $table->string('merk', 120)->nullable()->after('idbarang');
        });
    }

    public function down(): void
    {
        Schema::table('barang_pinjam_usages', function (Blueprint $table) {
            $table->dropColumn('merk');
        });
    }
};
