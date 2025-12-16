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
            $table->string('monitor_brand', 120)->nullable()->after('processor');
            $table->string('monitor_model', 150)->nullable()->after('monitor_brand');
            $table->decimal('monitor_size_inch', 5, 2)->nullable()->after('monitor_model');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn(['monitor_brand', 'monitor_model', 'monitor_size_inch']);
        });
    }
};
