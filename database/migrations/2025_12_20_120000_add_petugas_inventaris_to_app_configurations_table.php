<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_configurations', function (Blueprint $table) {
            $table->string('petugas_inventaris')->nullable()->after('profil');
        });
    }

    public function down(): void
    {
        Schema::table('app_configurations', function (Blueprint $table) {
            $table->dropColumn('petugas_inventaris');
        });
    }
};
