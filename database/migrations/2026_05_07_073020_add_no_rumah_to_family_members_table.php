<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('family_members', function (Blueprint $table) {
        // Menambahkan kolom no_rumah setelah status_hubungan
        $table->string('no_rumah')->nullable()->after('status_hubungan');
    });
}

public function down(): void
{
    Schema::table('family_members', function (Blueprint $table) {
        $table->dropColumn('no_rumah');
    });
}
};
