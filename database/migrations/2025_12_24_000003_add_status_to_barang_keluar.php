<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->after('keterangan');
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->unsignedBigInteger('rejected_by')->nullable()->after('approved_at');
            $table->timestamp('rejected_at')->nullable()->after('rejected_by');
            $table->string('alasan_penolakan', 255)->nullable()->after('rejected_at');
        });

        DB::table('barang_keluar')->update([
            'status' => 'approved',
        ]);
    }

    public function down(): void
    {
        Schema::table('barang_keluar', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'approved_by',
                'approved_at',
                'rejected_by',
                'rejected_at',
                'alasan_penolakan',
            ]);
        });
    }
};
