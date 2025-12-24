<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_pinjam_usages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idbarang');
            $table->unsignedBigInteger('created_by');
            $table->unsignedInteger('jumlah');
            $table->string('kegiatan', 255);
            $table->dateTime('digunakan_mulai');
            $table->dateTime('digunakan_sampai');
            $table->timestamps();

            $table->foreign('idbarang')->references('idbarang')->on('barang')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_pinjam_usages');
    }
};
