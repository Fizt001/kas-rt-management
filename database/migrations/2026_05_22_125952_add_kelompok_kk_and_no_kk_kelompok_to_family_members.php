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
        Schema::table('family_members', function (Blueprint $table) {
            $table->string('kelompok_kk')->nullable()->after('status_hubungan');
            $table->text('no_kk_kelompok')->nullable()->after('kelompok_kk'); // using text for encryption
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn(['kelompok_kk', 'no_kk_kelompok']);
        });
    }
};
