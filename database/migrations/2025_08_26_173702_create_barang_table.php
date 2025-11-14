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
        Schema::create('barang', function (Blueprint $table) {
            $table->id('idbarang');
            $table->foreignId('idkategori')->constrained('kategori', 'idkategori')->onDelete('cascade');

            $table->string('kode_barang', 20)->unique();
            $table->string('nama_barang', 100);
            $table->integer('stok')->default(0);
            $table->string('keterangan')->nullable();
            $table->timestamps();

        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
