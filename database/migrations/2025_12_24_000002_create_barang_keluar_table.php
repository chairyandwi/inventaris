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
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->id('idbarang_keluar');
            $table->foreignId('idbarang')->constrained('barang', 'idbarang')->cascadeOnDelete();
            $table->foreignId('iduser')->constrained('users', 'id')->cascadeOnDelete();
            $table->date('tgl_keluar');
            $table->integer('jumlah');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluar');
    }
};
