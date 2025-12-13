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
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->enum('status_barang', ['baru', 'bekas'])->default('baru')->after('jumlah');
            $table->boolean('is_pc')->default(false)->after('status_barang');
            $table->date('tgl_masuk')->nullable()->after('idbarang');

            $table->string('ram_brand', 100)->nullable()->after('keterangan');
            $table->unsignedSmallInteger('ram_capacity_gb')->nullable()->after('ram_brand');
            $table->enum('storage_type', ['SSD', 'HDD'])->nullable()->after('ram_capacity_gb');
            $table->unsignedInteger('storage_capacity_gb')->nullable()->after('storage_type');
            $table->string('processor', 150)->nullable()->after('storage_capacity_gb');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn([
                'status_barang',
                'is_pc',
                'tgl_masuk',
                'ram_brand',
                'ram_capacity_gb',
                'storage_type',
                'storage_capacity_gb',
                'processor',
            ]);
        });
    }
};
