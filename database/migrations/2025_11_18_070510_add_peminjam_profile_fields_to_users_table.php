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
        Schema::table('users', function (Blueprint $table) {
            $table->string('tipe_peminjam', 50)->default('umum')->after('role');
            $table->string('prodi', 100)->nullable()->after('tipe_peminjam');
            $table->string('angkatan', 10)->nullable()->after('prodi');
            $table->string('nim', 50)->nullable()->unique()->after('angkatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['tipe_peminjam', 'prodi', 'angkatan', 'nim']);
        });
    }
};
