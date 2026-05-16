<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambahkan no_rumah ke tabel users jika belum ada
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'no_rumah')) {
                $table->string('no_rumah')->nullable()->after('name');
            }
        });

        // 2. SELAMATKAN DATA: Pindahkan data dari family_members ke users
        if (Schema::hasColumn('family_members', 'no_rumah')) {
            $data = DB::table('family_members')->whereNotNull('no_rumah')->get();
            foreach ($data as $row) {
                DB::table('users')->where('id', $row->user_id)->update([
                    'no_rumah' => $row->no_rumah
                ]);
            }

            // 3. BUANG kolom no_rumah yang lama di family_members
            Schema::table('family_members', function (Blueprint $table) {
                $table->dropColumn('no_rumah');
            });
        }
    }

    public function down(): void
    {
        // Jika rollback, kembalikan kolom ke family_members
        Schema::table('family_members', function (Blueprint $table) {
            $table->string('no_rumah')->nullable();
        });
    }
};