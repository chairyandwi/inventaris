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
        Schema::create('barang_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('idbarang')->constrained('barang', 'idbarang')->cascadeOnDelete();
            $table->foreignId('idruang')->constrained('ruang', 'idruang')->cascadeOnDelete();
            $table->unsignedInteger('nomor_unit');
            $table->string('kode_unit')->unique();
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->unique(['idbarang', 'idruang', 'nomor_unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_units');
    }
};
