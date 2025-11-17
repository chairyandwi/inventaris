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
        Schema::table('app_configurations', function (Blueprint $table) {
            $table->boolean('apply_layout')->default(false)->after('profil');
            $table->boolean('apply_pdf')->default(false)->after('apply_layout');
            $table->boolean('apply_email')->default(false)->after('apply_pdf');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('app_configurations', function (Blueprint $table) {
            $table->dropColumn(['apply_layout', 'apply_pdf', 'apply_email']);
        });
    }
};
