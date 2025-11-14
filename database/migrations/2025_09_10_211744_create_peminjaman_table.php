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
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id('idpeminjaman');
            $table->foreignId('idbarang')->constrained('barang', 'idbarang')->onDelete('cascade');
            $table->foreignId('iduser')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('idruang')->constrained('ruang', 'idruang')->onDelete('cascade');
            $table->integer('jumlah');
            $table->dateTime('tgl_pinjam')->nullable();
            $table->dateTime('tgl_kembali')->nullable();
        
            // status approval & proses
            $table->enum('status', ['pending', 'dipinjam', 'dikembalikan', 'ditolak'])->default('pending');
            $table->string('alasan_penolakan')->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
