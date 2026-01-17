<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->string('pickup_konfirmasi_metode', 20)->nullable()->after('tgl_pinjam');
            $table->string('pickup_rfid_uid', 50)->nullable()->after('pickup_konfirmasi_metode');
            $table->string('return_konfirmasi_metode', 20)->nullable()->after('tgl_kembali');
            $table->string('return_rfid_uid', 50)->nullable()->after('return_konfirmasi_metode');
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->string('receive_konfirmasi_metode', 20)->nullable()->after('tgl_diterima');
            $table->string('receive_rfid_uid', 50)->nullable()->after('receive_konfirmasi_metode');
        });
    }

    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn([
                'pickup_konfirmasi_metode',
                'pickup_rfid_uid',
                'return_konfirmasi_metode',
                'return_rfid_uid',
            ]);
        });

        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropColumn([
                'receive_konfirmasi_metode',
                'receive_rfid_uid',
            ]);
        });
    }
};
