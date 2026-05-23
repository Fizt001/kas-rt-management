<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan index untuk query yang paling sering dijalankan.
     */
    public function up(): void
    {
        // Index pada billings: query user + bulan + tahun sangat sering dipakai
        Schema::table('billings', function (Blueprint $table) {
            $table->index(['user_id', 'tahun', 'bulan'], 'billings_user_tahun_bulan_idx');
            $table->index('status', 'billings_status_idx');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropIndex('billings_user_tahun_bulan_idx');
            $table->dropIndex('billings_status_idx');
        });

    }
};
