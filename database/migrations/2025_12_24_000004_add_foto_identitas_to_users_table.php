<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto_identitas_mahasiswa')->nullable()->after('nim');
            $table->string('foto_identitas_pegawai')->nullable()->after('divisi');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['foto_identitas_mahasiswa', 'foto_identitas_pegawai']);
        });
    }
};
