<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaris_ruang_moves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_unit_id');
            $table->unsignedBigInteger('idbarang');
            $table->unsignedBigInteger('idruang_asal');
            $table->unsignedBigInteger('idruang_tujuan');
            $table->unsignedBigInteger('moved_by')->nullable();
            $table->timestamp('moved_at');
            $table->string('catatan', 255)->nullable();
            $table->timestamps();

            $table->index(['idbarang', 'moved_at']);
            $table->index(['idruang_asal', 'idruang_tujuan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaris_ruang_moves');
    }
};
