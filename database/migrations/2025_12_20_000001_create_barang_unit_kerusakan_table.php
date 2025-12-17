<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_unit_kerusakan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_unit_id')->constrained('barang_units')->cascadeOnDelete();
            $table->enum('status', ['rusak', 'diperbaiki'])->default('rusak');
            $table->date('tgl_rusak')->nullable();
            $table->date('tgl_perbaikan')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->index(['barang_unit_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang_unit_kerusakan');
    }
};
